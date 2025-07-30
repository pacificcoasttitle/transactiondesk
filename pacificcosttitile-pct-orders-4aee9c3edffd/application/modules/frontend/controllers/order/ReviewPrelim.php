<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class ReviewPrelim extends MX_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('order/apiLogs');
        $this->load->model('order/reviewPrelimData');
        $this->load->library('order/order');
        $this->load->model('order/document');
        $this->load->library('order/resware');
        $this->load->model('order/home_model');
        $this->load->helper('sendemail');
    }

    
    public function fetchData()
    {
    	ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
		$json = file_get_contents('php://input');

		if ($_SERVER['SERVER_NAME'] == 'sandbox.pacificcoasttitle.com') {
			$logSyncId = $this->apiLogs->syncLogs(0, 'local', 'sync_prelim_data','https://mypctrep.com/ReceiveSearchDataService.svc?wsdl', array('ReceiveSearchDataService'=>true), array());
			$url = "https://app.pacificcoasttitle.com/resware-fetch-data";    
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER,
					array("Content-type: application/json"));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			$json_response = curl_exec($curl);
			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$this->apiLogs->syncLogs(0, 'local', 'sync_prelim_data', 'https://mypctrep.com/ReceiveSearchDataService.svc?wsdl', array(), $json_response, 0, $logSyncId);
			curl_close($curl);
		}
		
		if ($json) {
			$logId = $this->apiLogs->syncLogs(0,'resware WCF', 'get_prelim','https://mypctrep.com/ReceiveSearchDataService.svc?wsdl', array('ReceiveSearchDataService'=>true), array());
			$this->apiLogs->syncLogs(0, 'resware WCF', 'get_prelim', 'https://mypctrep.com/ReceiveSearchDataService.svc?wsdl', array(), $json, 0, $logId);
			$dir_name = APPPATH.'logs/prelim';

			if (!is_dir($dir_name)) {
				mkdir($dir_name);
			}

			$file = APPPATH.'logs/prelim/response.log';

			if (file_exists($file)) {
				$fh = fopen($file, 'a');
			} else {
				$fh = fopen($file, 'w');
			}

			fwrite($fh, $json."\n");
			fclose($fh);
			$data = json_decode($json,TRUE);
			$this->db->select('*');
			$this->db->from('pct_order_code_book');
			$query = $this->db->get();
			$codeBooks = $query->result_array();
			
			if (isset($data) && !empty($data))  {
				$file_number = isset($data['FileNumber']) && !empty($data['FileNumber']) ? $data['FileNumber'] : '';
				$orderCondition = array(
					'where' => array(
						'file_number' => $file_number,
					)
				);
				
				$order_details = $this->order->get_rows($orderCondition);
				if(empty($order_details)) {
					$retOrderId = $this->importOrder($file_number);
					if(!empty($retOrderId)) {
						//update
						
						$order_details = $this->order->get_rows($orderCondition);
					}
				}

				$update_prelim_flag_data = [
					'prelim_flag'=>0
				];
				$update_prelim_flag_condition = ['file_number' => $file_number,];
				$this->order->update($update_prelim_flag_data,$update_prelim_flag_condition);

				if(isset($order_details) && !empty($order_details)) {
					$file_id = isset($order_details['file_id']) && !empty($order_details['file_id']) ? $order_details['file_id'] : '';
					$orderDetails = $this->order->get_order_details($file_id,1);
					
					$parcelID = isset($data['ParcelID']) && !empty($data['ParcelID']) ? $data['ParcelID'] : '';
					$vesting = isset($data['Vesting']) && !empty($data['Vesting']) ? $data['Vesting'] : '';
					$generated_date = isset($data['CommitmentEffectiveDate']) && !empty($data['CommitmentEffectiveDate']) ? date('Y-m-d H:i:s', strtotime($data['CommitmentEffectiveDate'])) : '';
					$tax = $liens = $email_data = array();
					$documentIds = array();
					$arr_find = array("_BOOKONLY_", "_DATE_", "_DOCUMENTNAME_", "_GRANTEE_", "_GRANTOR_", "_INSTRUMENTONLY_", "_RECORDEDDATE_", "_RECORDDATE_", "_PURPOSE_", "_PAGEONLY_", "_LIBERONLY_", "_VOLUMEONLY_", "_AMOUNT_", "_TRUSTEE_", "_AGAINST_", "_ASSIGNOR_", "_ASSIGNEE_", "_ASSIGNEEBOOK_", "_ASSIGNEEBOOKONLY_", "_ASSIGNEEPAGE_", "_ASSIGNEEPAGEONLY_", "_ASSIGNEELIBER_", "_ASSIGNEELIBERONLY_", "_ASSIGNEEVOLUME_", "_ASSIGNEEVOLUMEONLY_", "_ASSIGNEEINSTRUMENT_", "_ASSIGNEEINSTRUMENTONLY_", "_BOOK_", "_CASENUMBER_", "_COUNTY_", "_COURTDISTRICT_", "_COURTTYPE_", "_ENDORSEMENTS_", "_HOLDER_", "_INFAVOROF_", "_INSTALLMENTNUMBER_", "_INSTRUMENT_", "_INSTALLMENTAMOUNT_", "_LIBER_", "_MATURITYDATE_", "_PAGE_", "_STATE_", "_STATEDISTRICT_", "_TAXYEARS_", "_VOLUME_", "_PARCELID1_");

					if (isset($data['Liens']) && !empty($data['Liens'])) {
						$liensCount = 1;
						$easementCheck = 0;
						$lienFlag =  1;

						foreach ($data['Liens'] as $key => $lien) {
							$lienKey = array_search($lien['LienTypeID'], array_column($codeBooks, 'type_id'));

							if (isset($lienKey) && !empty($lienKey)) {
								
								if ($codeBooks[$lienKey]['required_number'] == 1) {
									$language = '';
									$preLanguage = '';
								} else {
									$preLanguage = $language;
									$language = '';
									$liensCount--;
								}

								$language = $codeBooks[$lienKey]['language'];
								if(strpos($codeBooks[$lienKey]['language'], '___') !== false) { 
									$lienLanguage = isset($lien['Language']) && !empty($lien['Language']) ? $lien['Language'] : '';

									if (strpos($lienLanguage, '_PARCELID1_') !== false) {
										$lienLanguage = preg_replace('/ <a.*a>/', '_PARCELID1_', $lienLanguage);
									} else if(strpos($lienLanguage, '_INSTRUMENTONLY_') !== false) {
										$lienLanguage = preg_replace('/ <a.*a>/', '_INSTRUMENTONLY_', $lienLanguage);
									}

									$opcodes = FineDiff::getDiffOpcodes($language, $lienLanguage, [$granularityStack = null] );
									$replace = explode("^^",$opcodes);

									if (isset($replace) && !empty($replace)) {
										foreach ($replace as $key => $value) {
											$pos = strpos($language,'___');
											if(isset($pos) && $pos > 0) {
												$language = substr_replace($language, $value, $pos,3);
											}
										} 
									} 
									
									if (trim($codeBooks[$lienKey]['language']) == '___') {
										$language = '';
										$language = isset($lien['Language']) && !empty($lien['Language']) ? $lien['Language'] : '';
									}

									if (strpos($language, '_PROPERTYADDRESS_') !== false) {
										$language = '';
										$language = isset($lien['Language']) && !empty($lien['Language']) ? $lien['Language'] : '';
									}
								} else {
									$lienLanguage = isset($lien['Language']) && !empty($lien['Language']) ? $lien['Language'] : '';
								}
							} else {
								$language = '';
								$language = isset($lien['Language']) && !empty($lien['Language']) ? $lien['Language'] : '';
							}
						
							$Book = isset($lien['Book']) && !empty($lien['Book']) ? $lien['Book'] : '';
							$Date = isset($lien['Date']) && !empty($lien['Date']) ? $lien['Date'] : '';
							$DocumentName = isset($lien['DocumentName']) && !empty($lien['DocumentName']) ? $lien['DocumentName'] : '';
							$Grantor = isset($lien['Grantor']) && !empty($lien['Grantor']) ? $lien['Grantor'] : '';
							$Grantee = isset($lien['Grantee']) && !empty($lien['Grantee']) ? $lien['Grantee'] : '';
							$Instrument = isset($lien['Instrument']) && !empty($lien['Instrument']) ? $lien['Instrument'] : '';
							$RecordedDate = isset($lien['RecordedDate']) && !empty($lien['RecordedDate']) ? $lien['RecordedDate'] : '';
							$Purpose = isset($lien['Purpose']) && !empty($lien['Purpose']) ? $lien['Purpose'] : '';
							$Page = isset($lien['Page']) && !empty($lien['Page']) ? $lien['Page'] : '';
							$Liber = isset($lien['Liber']) && !empty($lien['Liber']) ? $lien['Liber'] : '';
							$Volume = isset($lien['Volume']) && !empty($lien['Volume']) ? $lien['Volume'] : '';
							$Amount = isset($lien['Amount']) && !empty($lien['Amount']) ? $lien['Amount'] : '';
							$Trustee = isset($lien['Trustee']) && !empty($lien['Trustee']) ? $lien['Trustee'] : '';
							$Against = isset($lien['Against']) && !empty($lien['Against']) ? $lien['Against'] : '';
							$Assignor = isset($lien['Assignor']) && !empty($lien['Assignor']) ? $lien['Assignor'] : '';
							$Assignee = isset($lien['Assignee']) && !empty($lien['Assignee']) ? $lien['Assignee'] : '';
							$AssigneeBook = isset($lien['AssigneeBook']) && !empty($lien['AssigneeBook']) ? $lien['AssigneeBook'] : '';
							$AssigneePage = isset($lien['AssigneePage']) && !empty($lien['AssigneePage']) ? $lien['AssigneePage'] : '';
							$AssigneeLiber = isset($lien['AssigneeLiber']) && !empty($lien['AssigneeLiber']) ? $lien['AssigneeLiber'] : '';
							$AssigneeVolume = isset($lien['AssigneeVolume']) && !empty($lien['AssigneeVolume']) ? $lien['AssigneeVolume'] : '';
							$AssigneeInstrument = isset($lien['AssigneeInstrument']) && !empty($lien['AssigneeInstrument']) ? $lien['AssigneeInstrument'] : '';
							$CaseNumber = isset($lien['CaseNumber']) && !empty($lien['CaseNumber']) ? $lien['CaseNumber'] : '';
							$County = isset($lien['County']) && !empty($lien['County']) ? $lien['County'] : '';
							$CourtDistrict = isset($lien['CourtDistrict']) && !empty($lien['CourtDistrict']) ? $lien['CourtDistrict'] : '';
							$CourtType = isset($lien['CourtType']) && !empty($lien['CourtType']) ? $lien['CourtType'] : '';
							$Endorsements = isset($lien['Endorsements']) && !empty($lien['Endorsements']) ? $lien['Endorsements'] : '';
							$Holder = isset($lien['Holder']) && !empty($lien['Holder']) ? $lien['Holder'] : '';
							$InFavorOf = isset($lien['InFavorOf']) && !empty($lien['InFavorOf']) ? $lien['InFavorOf'] : '';
							$InstallmentNumber = isset($lien['InstallmentNumber']) && !empty($lien['InstallmentNumber']) ? $lien['InstallmentNumber'] : '';
							$InstallmentAmount = isset($lien['InstallmentAmount']) && !empty($lien['InstallmentAmount']) ? $lien['InstallmentAmount'] : '';
							$MaturityDate = isset($lien['MaturityDate']) && !empty($lien['MaturityDate']) ? $lien['MaturityDate'] : '';
							$State = isset($lien['State']) && !empty($lien['State']) ? $lien['State'] : '';
							$StateDistrict = isset($lien['StateDistrict']) && !empty($lien['StateDistrict']) ? $lien['StateDistrict'] : '';
							$TaxYears = isset($lien['TaxYears']) && !empty($lien['TaxYears']) ? $lien['TaxYears'] : '';

							
							$result = array();
							$language1 = '';
							$language1 = isset($lien['Language']) && !empty($lien['Language']) ? $lien['Language'] : '';
							preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $language1, $result);

							if (!empty($result) && !empty($result['href'][0])) {
								$link = $result['href'][0];
								$documentId = str_replace('http://clients.pacificcoasttitle.com/DownloadDocument.aspx?DocumentID=', '', $link);
								$documentIds[$documentId] = $liensCount;
								$sync = 1;
								$order_id = $order_details['id'];
								$onclick = "href='javascript:void(0)' style='cusror:pointer !important;' onclick='load_doc($sync, $documentId, $order_id)'";
								if (strpos($lienLanguage, '_PARCELID1_') !== false) {
									$parcelID = "<a $onclick>".$parcelID."</a>";
								} else if(strpos($lienLanguage, '_INSTRUMENTONLY_') !== false) {
									$Instrument = "<a $onclick>".$Instrument."</a>";
								}
								
							}	

							$arr_rep = array($Book, $Date, $DocumentName, $Grantee, $Grantor, $Instrument, $RecordedDate, $RecordedDate, $Purpose, $Page, $Liber, $Volume, $Amount, $Trustee, $Against, $Assignor, $Assignee, $AssigneeBook, $AssigneeBook, $AssigneePage, $AssigneePage, $AssigneeLiber, $AssigneeLiber, $AssigneeVolume, $AssigneeVolume, $AssigneeInstrument, $AssigneeInstrument, $Book, $CaseNumber, $County, $CourtDistrict, $CourtType, $Endorsements, $Holder, $InFavorOf, $InstallmentNumber, $Instrument, $InstallmentAmount, $Liber, $MaturityDate, $Page, $State, $StateDistrict, $TaxYears, $Volume, $parcelID);
							$language = str_replace($arr_find, $arr_rep, $language);

							if(strpos(strtolower($language), 'any liens or other assessments') !== false || strpos(strtolower($language), 'the lien of supplemental') !== false || strpos(strtolower($language), 'property taxes') !== false ) {
								if (!empty($preLanguage)) {
									$preLanguage .= "\n\n".$language;
									$language = $preLanguage;
									$preLanguage = '';
								}
								$tax[$liensCount] = $language;
								$email_data['tax'][] = $language;
							} else {
								if($easementCheck == 0) {
									$easements = array();
									if (isset($data['Easements']) && !empty($data['Easements']))  {

										foreach ($data['Easements'] as $key => $easement) {
											$easementKey = array_search($easement['EasementTypeID'], array_column($codeBooks, 'type_id'));
											
											if (isset($easementKey) && !empty($easementKey)) {
												if ($codeBooks[$easementKey]['required_number'] == 1) {
													$easementLanguage = '';
													$preEasementLanguage = '';
												} else {
													$preEasementLanguage = $easementLanguage;
													$easementLanguage = '';
													$liensCount--;
												}
												$easementLanguage = $codeBooks[$easementKey]['language']; 
												if(strpos($codeBooks[$easementKey]['language'], '___') !== false) {
													$easementToLanguage = isset($easement['Language']) && !empty($easement['Language']) ? $easement['Language'] : '';

													if (strpos($easementToLanguage, '_PARCELID1_') !== false) {
														$easementToLanguage = preg_replace('/ <a.*a>/', '_PARCELID1_', $easementToLanguage);
													} else if(strpos($easementToLanguage, '_INSTRUMENTONLY_') !== false) {
														$easementToLanguage = preg_replace('/ <a.*a>/', '_INSTRUMENTONLY_', $easementToLanguage);
													}

													$opcodes = FineDiff::getDiffOpcodes($easementLanguage, $easementToLanguage, [$granularityStack = null] );
													$replace = explode("^^",$opcodes);

													if (isset($replace) && !empty($replace)) {
														foreach ($replace as $key => $value) {
															$pos = strpos($easementLanguage,'___');
															if(isset($pos) && $pos > 0) {
																$easementLanguage = substr_replace($easementLanguage, $value, $pos,3);
															}
														} 
													} 
													
													if(trim($codeBooks[$easementKey]['language']) == '___') {
														$easementLanguage = '';
														$easementLanguage = isset($easement['Language']) && !empty($easement['Language']) ? $easement['Language'] : '';
													}

													if (strpos($language, '_PROPERTYADDRESS_') !== false) {
														$easementLanguage = '';
														$easementLanguage = isset($easement['Language']) && !empty($easement['Language']) ? $easement['Language'] : '';
													}
												}
											} else {
												$easementLanguage = '';
												$easementLanguage = isset($easement['Language']) && !empty($easement['Language']) ? $easement['Language'] : '';
											}

											$Book = isset($easement['Book']) && !empty($easement['Book']) ? $easement['Book'] : '';
											$Date = isset($easement['Date']) && !empty($easement['Date']) ? $easement['Date'] : '';
											$DocumentName = isset($easement['DocumentName']) && !empty($easement['DocumentName']) ? $easement['DocumentName'] : '';
											$Grantor = isset($easement['Grantor']) && !empty($easement['Grantor']) ? $easement['Grantor'] : '';
											$Grantee = isset($easement['Grantee']) && !empty($easement['Grantee']) ? $easement['Grantee'] : '';
											$Instrument = isset($easement['Instrument']) && !empty($easement['Instrument']) ? $easement['Instrument'] : '';
											$RecordedDate = isset($easement['RecordedDate']) && !empty($easement['RecordedDate']) ? $easement['RecordedDate'] : '';
											$Purpose = isset($easement['Purpose']) && !empty($easement['Purpose']) ? $easement['Purpose'] : '';
											$Page = isset($easement['Page']) && !empty($easement['Page']) ? $easement['Page'] : '';
											$Liber = isset($easement['Liber']) && !empty($easement['Liber']) ? $easement['Liber'] : '';
											$Volume = isset($easement['Volume']) && !empty($easement['Volume']) ? $easement['Volume'] : '';
											$Amount = isset($easement['Amount']) && !empty($easement['Amount']) ? $easement['Amount'] : '';
											$Trustee = isset($easement['Trustee']) && !empty($easement['Trustee']) ? $easement['Trustee'] : '';
											$Against = isset($easement['Against']) && !empty($easement['Against']) ? $easement['Against'] : '';
											$Assignor = isset($easement['Assignor']) && !empty($easement['Assignor']) ? $easement['Assignor'] : '';
											$Assignee = isset($easement['Assignee']) && !empty($easement['Assignee']) ? $easement['Assignee'] : '';
											$AssigneeBook = isset($easement['AssigneeBook']) && !empty($easement['AssigneeBook']) ? $easement['AssigneeBook'] : '';
											$AssigneePage = isset($easement['AssigneePage']) && !empty($easement['AssigneePage']) ? $easement['AssigneePage'] : '';
											$AssigneeLiber = isset($easement['AssigneeLiber']) && !empty($easement['AssigneeLiber']) ? $easement['AssigneeLiber'] : '';
											$AssigneeVolume = isset($easement['AssigneeVolume']) && !empty($easement['AssigneeVolume']) ? $easement['AssigneeVolume'] : '';
											$AssigneeInstrument = isset($easement['AssigneeInstrument']) && !empty($easement['AssigneeInstrument']) ? $easement['AssigneeInstrument'] : '';
											$CaseNumber = isset($easement['CaseNumber']) && !empty($easement['CaseNumber']) ? $easement['CaseNumber'] : '';
											$County = isset($easement['County']) && !empty($easement['County']) ? $easement['County'] : '';
											$CourtDistrict = isset($easement['CourtDistrict']) && !empty($easement['CourtDistrict']) ? $easement['CourtDistrict'] : '';
											$CourtType = isset($easement['CourtType']) && !empty($easement['CourtType']) ? $easement['CourtType'] : '';
											$Endorsements = isset($easement['Endorsements']) && !empty($easement['Endorsements']) ? $easement['Endorsements'] : '';
											$Holder = isset($easement['Holder']) && !empty($easement['Holder']) ? $easement['Holder'] : '';
											$InFavorOf = isset($easement['InFavorOf']) && !empty($easement['InFavorOf']) ? $easement['InFavorOf'] : '';
											$InstallmentNumber = isset($easement['InstallmentNumber']) && !empty($easement['InstallmentNumber']) ? $easement['InstallmentNumber'] : '';
											$InstallmentAmount = isset($easement['InstallmentAmount']) && !empty($easement['InstallmentAmount']) ? $easement['InstallmentAmount'] : '';
											$MaturityDate = isset($easement['MaturityDate']) && !empty($easement['MaturityDate']) ? $easement['MaturityDate'] : '';
											$State = isset($easement['State']) && !empty($easement['State']) ? $easement['State'] : '';
											$StateDistrict = isset($easement['StateDistrict']) && !empty($easement['StateDistrict']) ? $easement['StateDistrict'] : '';
											$TaxYears = isset($easement['TaxYears']) && !empty($easement['TaxYears']) ? $easement['TaxYears'] : '';

											
											$easementResult = array();
											$easementLanguage1 = '';
											$easementLanguage1 = isset($easement['Language']) && !empty($easement['Language']) ? $easement['Language'] : '';
											preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $easementLanguage1, $easementResult);

											if (!empty($easementResult) && !empty($easementResult['href'][0])) {
												$link = $easementResult['href'][0];
												$documentId = str_replace('http://clients.pacificcoasttitle.com/DownloadDocument.aspx?DocumentID=', '', $link);
												$documentIds[$documentId] = $liensCount;
												if(!empty($Instrument)) {
													$sync = 1;
													$order_id = $order_details['id'];
													$onclick = "href='javascript:void(0)' style='cusror:pointer !important;' onclick='load_doc($sync, $documentId, $order_id)'";
													$Instrument = "<a $onclick>".$Instrument."</a>";
												}
											}
							
											$arr_rep = array($Book, $Date, $DocumentName, $Grantee, $Grantor, $Instrument, $RecordedDate, $RecordedDate, $Purpose, $Page, $Liber, $Volume, $Amount, $Trustee, $Against, $Assignor, $Assignee, $AssigneeBook, $AssigneeBook, $AssigneePage, $AssigneePage, $AssigneeLiber, $AssigneeLiber, $AssigneeVolume, $AssigneeVolume, $AssigneeInstrument, $AssigneeInstrument, $Book, $CaseNumber, $County, $CourtDistrict, $CourtType, $Endorsements, $Holder, $InFavorOf, $InstallmentNumber, $Instrument, $InstallmentAmount, $Liber, $MaturityDate, $Page, $State, $StateDistrict, $TaxYears, $Volume, $parcelID);
											$easementLanguage = str_replace($arr_find, $arr_rep, $easementLanguage); 
											if(!empty($easementLanguage)) {
												if (!empty($preEasementLanguage)) {
													$preEasementLanguage .= "\n\n".$easementLanguage;
													$easementLanguage = $preEasementLanguage;
													$preEasementLanguage = '';
												}
												$easements[$liensCount] = $easementLanguage;
											}
											$liensCount++;
										}
										$easementCheck++;
									}
								}
								if ($lienFlag == 1) {
									
									if (!empty($result) && !empty($result['href'][0])) {
										$link = $result['href'][0];
										$documentId = str_replace('http://clients.pacificcoasttitle.com/DownloadDocument.aspx?DocumentID=', '', $link);
										$documentIds[$documentId] = $liensCount;
									}
								}
								$lienFlag++;
								if (!empty($preLanguage)) {
									$preLanguage .= "\n\n".$language;
									$language = $preLanguage;
									$preLanguage = '';
								}
								$liens[$liensCount] = $language;
								$email_data['liens'][] = $language;
							}
							$liensCount++;	
						}
					}

					$requirements = array();
					$requirementCount = 1;
					if(isset($data['Requirements']) && !empty($data['Requirements'])) {

						foreach ($data['Requirements'] as $key => $requirement) {
							
							$requirementKey = array_search($requirement['RequirementTypeID'], array_column($codeBooks, 'type_id'));
						
							if (isset($requirementKey) && !empty($requirementKey)) {

								if ($codeBooks[$requirementKey]['required_number'] == 1) {
									$language = '';
									$preLanguage = '';
								} else {
									$preLanguage = $language;
									$language = '';
									$liensCount--;
								}

								$language = $codeBooks[$requirementKey]['language'];

								if(strpos($codeBooks[$requirementKey]['language'], '___') !== false) {
									$requirementToLanguage = isset($requirement['Language']) && !empty($requirement['Language']) ? $requirement['Language'] : '';

									if (strpos($requirementToLanguage, '_PARCELID1_') !== false) {
										$requirementToLanguage = preg_replace('/ <a.*a>/', '_PARCELID1_', $requirementToLanguage);
									} else if(strpos($requirementToLanguage, '_INSTRUMENTONLY_') !== false) {
										$requirementToLanguage = preg_replace('/ <a.*a>/', '_INSTRUMENTONLY_', $requirementToLanguage);
									}

									$opcodes = FineDiff::getDiffOpcodes($language, $requirementToLanguage, [$granularityStack = null] );
									$replace = explode("^^",$opcodes);

									if (isset($replace) && !empty($replace)) {
										foreach ($replace as $key => $value) {
											$pos = strpos($language,'___');
											if(isset($pos) && $pos > 0) {
												$language = substr_replace($language, $value, $pos,3);
											}
										} 
									} 
									
									if (trim($codeBooks[$requirementKey]['language']) == '___') {
										$language = '';
										$language = isset($requirement['Language']) && !empty($requirement['Language']) ? $requirement['Language'] : '';
									}

									if (strpos($language, '_PROPERTYADDRESS_') !== false) {
										$language = '';
										$language = isset($requirement['Language']) && !empty($requirement['Language']) ? $requirement['Language'] : '';
									}
								}
							} else {
								$language = '';
								$language = isset($requirement['Language']) && !empty($requirement['Language']) ? $requirement['Language'] : '';
							}

							$Book = isset($requirement['Book']) && !empty($requirement['Book']) ? $requirement['Book'] : '';
							$Date = isset($requirement['Date']) && !empty($requirement['Date']) ? $requirement['Date'] : '';
							$DocumentName = isset($requirement['DocumentName']) && !empty($requirement['DocumentName']) ? $requirement['DocumentName'] : '';
							$Grantee = isset($requirement['Grantee']) && !empty($requirement['Grantee']) ? $requirement['Grantee'] : '';
							$Grantor = isset($requirement['Grantor']) && !empty($requirement['Grantor']) ? $requirement['Grantor'] : '';
							$Instrument = isset($requirement['Instrument']) && !empty($requirement['Instrument']) ? $requirement['Instrument'] : '';
							$RecordedDate = isset($requirement['RecordedDate']) && !empty($requirement['RecordedDate']) ? $requirement['RecordedDate'] : '';
							$Purpose = isset($requirement['Purpose']) && !empty($requirement['Purpose']) ? $requirement['Purpose'] : '';
							$Page = isset($requirement['Page']) && !empty($requirement['Page']) ? $requirement['Page'] : '';
							$Liber = isset($requirement['Liber']) && !empty($requirement['Liber']) ? $requirement['Liber'] : '';
							$Volume = isset($requirement['Volume']) && !empty($requirement['Volume']) ? $requirement['Volume'] : '';
							$Amount = isset($requirement['Amount']) && !empty($requirement['Amount']) ? $requirement['Amount'] : '';
							$Trustee = isset($requirement['Trustee']) && !empty($requirement['Trustee']) ? $requirement['Trustee'] : '';
							$Against = isset($requirement['Against']) && !empty($requirement['Against']) ? $requirement['Against'] : '';
							$Assignor = isset($requirement['Assignor']) && !empty($requirement['Assignor']) ? $requirement['Assignor'] : '';
							$Assignee = isset($requirement['Assignee']) && !empty($requirement['Assignee']) ? $requirement['Assignee'] : '';
							$AssigneeBook = isset($requirement['AssigneeBook']) && !empty($requirement['AssigneeBook']) ? $requirement['AssigneeBook'] : '';
							$AssigneePage = isset($requirement['AssigneePage']) && !empty($requirement['AssigneePage']) ? $requirement['AssigneePage'] : '';
							$AssigneeLiber = isset($requirement['AssigneeLiber']) && !empty($requirement['AssigneeLiber']) ? $requirement['AssigneeLiber'] : '';
							$AssigneeVolume = isset($requirement['AssigneeVolume']) && !empty($requirement['AssigneeVolume']) ? $requirement['AssigneeVolume'] : '';
							$AssigneeInstrument = isset($requirement['AssigneeInstrument']) && !empty($requirement['AssigneeInstrument']) ? $requirement['AssigneeInstrument'] : '';
							$CaseNumber = isset($requirement['CaseNumber']) && !empty($requirement['CaseNumber']) ? $requirement['CaseNumber'] : '';
							$County = isset($requirement['County']) && !empty($requirement['County']) ? $requirement['County'] : '';
							$CourtDistrict = isset($requirement['CourtDistrict']) && !empty($requirement['CourtDistrict']) ? $requirement['CourtDistrict'] : '';
							$CourtType = isset($requirement['CourtType']) && !empty($requirement['CourtType']) ? $requirement['CourtType'] : '';
							$Endorsements = isset($requirement['Endorsements']) && !empty($requirement['Endorsements']) ? $requirement['Endorsements'] : '';
							$Holder = isset($requirement['Holder']) && !empty($requirement['Holder']) ? $requirement['Holder'] : '';
							$InFavorOf = isset($requirement['InFavorOf']) && !empty($requirement['InFavorOf']) ? $requirement['InFavorOf'] : '';
							$InstallmentNumber = isset($requirement['InstallmentNumber']) && !empty($requirement['InstallmentNumber']) ? $requirement['InstallmentNumber'] : '';
							$InstallmentAmount = isset($requirement['InstallmentAmount']) && !empty($requirement['InstallmentAmount']) ? $requirement['InstallmentAmount'] : '';
							$MaturityDate = isset($requirement['MaturityDate']) && !empty($requirement['MaturityDate']) ? $requirement['MaturityDate'] : '';
							$State = isset($requirement['State']) && !empty($requirement['State']) ? $requirement['State'] : '';
							$StateDistrict = isset($requirement['StateDistrict']) && !empty($requirement['StateDistrict']) ? $requirement['StateDistrict'] : '';
							$TaxYears = isset($requirement['TaxYears']) && !empty($requirement['TaxYears']) ? $requirement['TaxYears'] : '';
							$result = array();
							$requirementLanguage = '';
							$requirementLanguage = isset($requirement['Language']) && !empty($requirement['Language']) ? $requirement['Language'] : '';
							preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $requirementLanguage, $result);

							if (!empty($result) && !empty($result['href'][0])) {
								$link = $result['href'][0];
								$documentId = str_replace('http://clients.pacificcoasttitle.com/DownloadDocument.aspx?DocumentID=', '', $link);
								$documentIds[$documentId] = $liensCount;
								if(!empty($Instrument)) {
									$sync = 1;
									$order_id = $order_details['id'];
									$onclick = "href='javascript:void(0)' style='cusror:pointer !important;' onclick='load_doc($sync, $documentId, $order_id)'";
									$Instrument = "<a $onclick>".$Instrument."</a>";
								}
							}	
							$arr_rep = array($Book, $Date, $DocumentName, $Grantee, $Grantor, $Instrument, $RecordedDate, $RecordedDate, $Purpose, $Page, $Liber, $Volume, $Amount, $Trustee, $Against, $Assignor, $Assignee, $AssigneeBook, $AssigneeBook, $AssigneePage, $AssigneePage, $AssigneeLiber, $AssigneeLiber, $AssigneeVolume, $AssigneeVolume, $AssigneeInstrument, $AssigneeInstrument, $Book, $CaseNumber, $County, $CourtDistrict, $CourtType, $Endorsements, $Holder, $InFavorOf, $InstallmentNumber, $Instrument, $InstallmentAmount, $Liber, $MaturityDate, $Page, $State, $StateDistrict, $TaxYears, $Volume, $parcelID);
							
							$language = str_replace($arr_find, $arr_rep, $language); 
							if (!empty($preLanguage)) {
								$preLanguage .= "\n\n".$language;
								$language = $preLanguage;
								$preLanguage = '';
							}
							if (!empty($language)) {
								$requirements[$liensCount] = $language;
							}
							$liensCount++;
						}
					}
				
					$restrictions = array();
					$restrictionsCount = 0;
					if (isset($data['Restrictions']) && !empty($data['Restrictions'])) {
						foreach ($data['Restrictions'] as $key => $restriction) {
							$language = '';
							$restrictionKey = array_search($restriction['RestrictionTypeID'], array_column($codeBooks, 'type_id'));
						
							if (isset($restrictionKey) && !empty($restrictionKey)) {
								$language = $codeBooks[$restrictionKey]['language'];

								if(strpos($codeBooks[$restrictionKey]['language'], '___') !== false) {
									$restrictionToLanguage = isset($restriction['Language']) && !empty($restriction['Language']) ? $restriction['Language'] : '';

									if (strpos($restrictionToLanguage, '_PARCELID1_') !== false) {
										$restrictionToLanguage = preg_replace('/ <a.*a>/', '_PARCELID1_', $restrictionToLanguage);
									} else if(strpos($restrictionToLanguage, '_INSTRUMENTONLY_') !== false) {
										$restrictionToLanguage = preg_replace('/ <a.*a>/', '_INSTRUMENTONLY_', $restrictionToLanguage);
									}

									$opcodes = FineDiff::getDiffOpcodes($language, $restrictionToLanguage, [$granularityStack = null] );
									$replace = explode("^^",$opcodes);

									if (isset($replace) && !empty($replace)) {
										foreach ($replace as $key => $value) {
											$pos = strpos($language,'___');
											if(isset($pos) && $pos > 0) {
												$language = substr_replace($language, $value, $pos,3);
											}
										} 
									} 
									
									if (trim($codeBooks[$restrictionKey]['language']) == '___') {
										$language = '';
										$language = isset($restriction['Language']) && !empty($restriction['Language']) ? $restriction['Language'] : '';
									}
								}
							} else {
								$language = isset($restriction['Language']) && !empty($restriction['Language']) ? $restriction['Language'] : '';
							}

							$Book = isset($restriction['Book']) && !empty($restriction['Book']) ? $restriction['Book'] : '';
							$Date = isset($restriction['Date']) && !empty($restriction['Date']) ? $restriction['Date'] : '';
							$DocumentName = isset($restriction['DocumentName']) && !empty($restriction['DocumentName']) ? $restriction['DocumentName'] : '';
							$Grantee = isset($restriction['Grantee']) && !empty($restriction['Grantee']) ? $restriction['Grantee'] : '';
							$Instrument = isset($restriction['Instrument']) && !empty($restriction['Instrument']) ? $restriction['Instrument'] : '';
							$RecordedDate = isset($restriction['RecordedDate']) && !empty($restriction['RecordedDate']) ? $restriction['RecordedDate'] : '';
							$Purpose = isset($restriction['Purpose']) && !empty($restriction['Purpose']) ? $restriction['Purpose'] : '';
							$Page = isset($restriction['Page']) && !empty($restriction['Page']) ? $restriction['Page'] : '';
							$Liber = isset($restriction['Liber']) && !empty($restriction['Liber']) ? $restriction['Liber'] : '';
							$Volume = isset($restriction['Volume']) && !empty($restriction['Volume']) ? $restriction['Volume'] : '';
							$Amount = isset($restriction['Amount']) && !empty($restriction['Amount']) ? $restriction['Amount'] : '';
							$Trustee = isset($restriction['Trustee']) && !empty($restriction['Trustee']) ? $restriction['Trustee'] : '';
							$Against = isset($restriction['Against']) && !empty($restriction['Against']) ? $restriction['Against'] : '';
							$Assignor = isset($restriction['Assignor']) && !empty($restriction['Assignor']) ? $restriction['Assignor'] : '';
							$Assignee = isset($restriction['Assignee']) && !empty($restriction['Assignee']) ? $restriction['Assignee'] : '';
							$AssigneeBook = isset($restriction['AssigneeBook']) && !empty($restriction['AssigneeBook']) ? $restriction['AssigneeBook'] : '';
							$AssigneePage = isset($restriction['AssigneePage']) && !empty($restriction['AssigneePage']) ? $restriction['AssigneePage'] : '';
							$AssigneeLiber = isset($restriction['AssigneeLiber']) && !empty($restriction['AssigneeLiber']) ? $restriction['AssigneeLiber'] : '';
							$AssigneeVolume = isset($restriction['AssigneeVolume']) && !empty($restriction['AssigneeVolume']) ? $restriction['AssigneeVolume'] : '';
							$AssigneeInstrument = isset($restriction['AssigneeInstrument']) && !empty($restriction['AssigneeInstrument']) ? $restriction['AssigneeInstrument'] : '';
							$CaseNumber = isset($restriction['CaseNumber']) && !empty($restriction['CaseNumber']) ? $restriction['CaseNumber'] : '';
							$County = isset($restriction['County']) && !empty($restriction['County']) ? $restriction['County'] : '';
							$CourtDistrict = isset($restriction['CourtDistrict']) && !empty($restriction['CourtDistrict']) ? $restriction['CourtDistrict'] : '';
							$CourtType = isset($restriction['CourtType']) && !empty($restriction['CourtType']) ? $restriction['CourtType'] : '';
							$Endorsements = isset($restriction['Endorsements']) && !empty($restriction['Endorsements']) ? $restriction['Endorsements'] : '';
							$Holder = isset($restriction['Holder']) && !empty($restriction['Holder']) ? $restriction['Holder'] : '';
							$InFavorOf = isset($restriction['InFavorOf']) && !empty($restriction['InFavorOf']) ? $restriction['InFavorOf'] : '';
							$InstallmentNumber = isset($restriction['InstallmentNumber']) && !empty($restriction['InstallmentNumber']) ? $restriction['InstallmentNumber'] : '';
							$InstallmentAmount = isset($restriction['InstallmentAmount']) && !empty($restriction['InstallmentAmount']) ? $restriction['InstallmentAmount'] : '';
							$MaturityDate = isset($restriction['MaturityDate']) && !empty($restriction['MaturityDate']) ? $restriction['MaturityDate'] : '';
							$State = isset($restriction['State']) && !empty($restriction['State']) ? $restriction['State'] : '';
							$StateDistrict = isset($restriction['StateDistrict']) && !empty($restriction['StateDistrict']) ? $restriction['StateDistrict'] : '';
							$TaxYears = isset($restriction['TaxYears']) && !empty($restriction['TaxYears']) ? $restriction['TaxYears'] : '';
							$result = array();
							$restrictionLanguage = '';
							$restrictionLanguage = isset($restriction['Language']) && !empty($restriction['Language']) ? $restriction['Language'] : '';
							preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $restrictionLanguage, $result);

							if (!empty($result)) {
								$link = $result['href'][0];
								$documentId = str_replace('http://clients.pacificcoasttitle.com/DownloadDocument.aspx?DocumentID=', '', $link);
								$documentIds[$documentId] = $liensCount;
								if(!empty($Instrument)) {
									$sync = 1;
									$order_id = $order_details['id'];
									$onclick = "href='javascript:void(0)' style='cusror:pointer !important;' onclick='load_doc($sync, $documentId, $order_id)'";
									$Instrument = "<a $onclick>".$Instrument."</a>";
								}
							}	

							$arr_rep = array($Book, $Date, $DocumentName, $Grantee, $Grantor, $Instrument, $RecordedDate, $RecordedDate, $Purpose, $Page, $Liber, $Volume, $Amount, $Trustee, $Against, $Assignor, $Assignee, $AssigneeBook, $AssigneeBook, $AssigneePage, $AssigneePage, $AssigneeLiber, $AssigneeLiber, $AssigneeVolume, $AssigneeVolume, $AssigneeInstrument, $AssigneeInstrument, $Book, $CaseNumber, $County, $CourtDistrict, $CourtType, $Endorsements, $Holder, $InFavorOf, $InstallmentNumber, $Instrument, $InstallmentAmount, $Liber, $MaturityDate, $Page, $State, $StateDistrict, $TaxYears, $Volume, $parcelID);
							$language = str_replace($arr_find, $arr_rep, $language); 

							if(!empty($language)) {
								$restrictions[] = $language;
							}
							$liensCount++;
						}
					}
				
					$summaryData = array(
						'file_number'=> $file_number,
						'vesting'=> $vesting,
						'generated_date'=> $generated_date,
						'tax'=> json_encode($tax),
						'lien'=> json_encode($liens),
						'easement'=> json_encode($easements),
						'requirements'=> json_encode($requirements),
						'restrictions'=> json_encode($restrictions),
						'resware_json' => $json,
						'parcel_id' => $parcelID
					);

					$con = array(
						'where' => array(
							'file_number' => $file_number,
						),
						'returnType' => 'count'
					);
					$prevCount = $this->reviewPrelimData->get_rows($con);
					
					if($prevCount > 0)
					{
						$condition = array('file_number' => $file_number);
						$summaryData['is_updated'] = 1;
						$summaryData['is_visited'] = 0;
						$update = $this->reviewPrelimData->update($summaryData, $condition);
					}
					else
					{
						$id = $this->reviewPrelimData->insert($summaryData);
						$condition = array(
								'file_number' => $file_number
						);
						$data = array(
							'prelim_summary_id'	=> $id
						);

						$this->order->update($data,$condition);
					}

					$update_prelim_flag_condition = ['file_number' => $file_number];
					$update_prelim_flag_data = [
						'prelim_flag'=>1
					];
					$this->order->update($update_prelim_flag_data,$update_prelim_flag_condition);
			

					/* Send email to customer */

					$order_id = isset($order_details['id']) && !empty($order_details['id']) ? $order_details['id'] : '';
					

					$this->db->delete('pct_order_documents', array('order_id' => $order_id, 'is_prelim_document' => 1));
					$this->db->delete('pct_order_documents', array('order_id' => $order_id, 'is_linked_doc' => 1));

					/* Generate Docs */
					$documents = $this->order->get_order_documents($file_id,1);

					$customer_id = isset($order_details['customer_id']) && !empty($order_details['customer_id']) ? $order_details['customer_id'] : '';

					$orderUser =  $this->home_model->get_user(array('id' => $customer_id));
					$user_data = array();
					$user_data = array(
						'admin_api' => 1
					);
					$user_data['from_mail'] = 1;

					$endPoint = 'files/'. $file_id .'/documents';
					$logid = $this->apiLogs->syncLogs($customer_id, 'resware', 'get_documents', env('RESWARE_ORDER_API').$endPoint, array(), array(), $orderDetails['order_id'], 0);
					$resultDocuments = $this->resware->make_request('GET', $endPoint, '', $user_data);
					$this->apiLogs->syncLogs($customer_id, 'resware', 'get_documents', env('RESWARE_ORDER_API').$endPoint, array(), $resultDocuments, $orderDetails['order_id'], $logid);
					$resDocuments = json_decode($resultDocuments, true);
					$documentCount  = count($documents);
					$apiDocumentIds = array_column($documents, 'api_document_id');

					if (!empty($documents)) {
						if (!empty($resDocuments['Documents'])) {
							$linkDocArray = array();
							foreach($resDocuments['Documents'] as $resDocument) {
								$ext = end(explode('.', $resDocument['DocumentName']));
								if (!in_array($resDocument['DocumentID'], $apiDocumentIds)) {
									$time = round((int)(str_replace("-0000)/", "", str_replace("/Date(", "", $resDocument['CreateDate'])))/1000);
									$created_date = date('Y-m-d H:i:s', $time);
									$document_name = date('YmdHis')."_".$resDocument['DocumentName'];
									if (($resDocument['DocumentType']['DocumentTypeID'] == 1032 || $resDocument['DocumentType']['DocumentTypeID'] == '1032' || strpos($resDocument['DocumentName'], 'Prelim') !== false) && (strtolower($ext) == 'doc' || strtolower($ext) == 'docx')) {
										$is_prelim_document = 1;
										$document_name = str_replace($ext, 'pdf', $document_name);
									} else {
										$is_prelim_document = 0;
										if(strtolower($ext) == 'doc' || strtolower($ext) == 'docx') {
											$document_name = str_replace($ext, 'pdf', $document_name);
										}
									}
									$documentData = array(
										'document_name' => $document_name,
										'original_document_name' => $resDocument['DocumentName'],
										'document_type_id' => $resDocument['DocumentType']['DocumentTypeID'],
										'api_document_id' => $resDocument['DocumentID'],
										'document_size' => $resDocument['Size'],
										'user_id' => $customer_id,
										'order_id' => $orderDetails['order_id'],
										'description' => $resDocument['DocumentName'],
										'created' => $created_date,
										'is_sync' => 0,
										'is_prelim_document' => $is_prelim_document
									);
									$documentId = $this->document->insert($documentData);

									if($is_prelim_document == 1) {
										$prelimDocument['original_document_name'] = $resDocument['DocumentName'];
										$prelimDocument['document_name'] = $document_name;
										$prelimDocument['api_document_id'] = $resDocument['DocumentID'];
										$prelimDocument['is_sync'] = 0;
										$prelimDocument['order_id'] = $orderDetails['order_id'];
										$prelimDocument['is_prelim_document'] =  $is_prelim_document;

										$endPoint = 'documents/'.$resDocument['DocumentID'].'?format=json';
										$logid = $this->apiLogs->syncLogs($customer_id, 'resware', 'get_document', env('RESWARE_ORDER_API').$endPoint, array(), array(), $orderDetails['order_id'], 0);
										$resultDocument = $this->resware->make_request('GET', $endPoint, '', $user_data);
										$this->apiLogs->syncLogs($customer_id, 'resware', 'get_document', env('RESWARE_ORDER_API').$endPoint, array(), $resultDocument, $orderDetails['order_id'], $logid);
										$resDocument = json_decode($resultDocument, true);

										if (isset($resDocument['Document']) && !empty($resDocument['Document'])) { 
											$documentContent = base64_decode($resDocument['Document']['DocumentBody'], true);
											if (!is_dir('uploads/documents')) {
												mkdir(FCPATH.'/uploads/documents', 0777, TRUE);
											}
											file_put_contents(FCPATH.'/uploads/documents/'.$document_name, $documentContent);
											$prelimDocumentName = $document_name;
											$this->document->update(array('is_sync' => 1), array('api_document_id' => $resDocument['DocumentID']));
											$source_pdf = FCPATH.'/uploads/documents/'.$document_name;
									
											// $wordsApi = new \Aspose\Words\WordsApi(getenv('PDF_TO_DOC_CLIENT_ID'), getenv('PDF_TO_DOC_SECRET_KEY'));
											// $format = "docx";
											// $file = ($source_pdf);
											// $doc_file_name =  str_replace('pdf', 'docx', $document_name);
											// $dest_doc = FCPATH.'/uploads/documents/'.$doc_file_name;
											
											// $request = new Aspose\Words\Model\Requests\ConvertDocumentRequest($file, $format, null);
											// $result = $wordsApi->ConvertDocument($request); 
											// copy($result->getPathName(), $dest_doc);
											// $this->order->uploadDocumentOnAwsS3($doc_file_name, 'documents');
													
											\Gufy\PdfToHtml\Config::set('pdftohtml.bin', getenv('PDFTOHTML_PATH'));
											\Gufy\PdfToHtml\Config::set('pdfinfo.bin', getenv('PDFTOINFO_PATH'));
											$pdf = new \Gufy\PdfToHtml\Pdf($source_pdf);
											$pages = array(4, 5, 6, 7, 8, 9);
											$linkedDocCount = 0;

											foreach ($pages as $page) {
												$html = $pdf->html($page);
												$total_pages = $pdf->getPages();
												$htmlDom = new DOMDocument;
												@$htmlDom->loadHTML($html);
												$links = $htmlDom->getElementsByTagName('a');
												$extractedLinks = array();
												
												foreach($links as $link) {
													$linkText = $link->nodeValue;
													$linkHref = $link->getAttribute('href');
													if(strlen(trim($linkHref)) == 0){
														continue;
													}

													if($linkHref[0] == '#'){
														continue;
													}
												
													if(strpos($linkHref, 'clients.pacificcoasttitle.com') !== false){
														$linkText = str_replace(' ', '-', $linkText); 
														$linkText = preg_replace('/[^A-Za-z0-9\-]/', '', $linkText).'.pdf';
														if($linkText == '.pdf' || strtolower($linkText) == 'no-.pdf'){
															continue;
														}
														$document_name = date('YmdHis')."_".$linkText;
														$documentId = explode('=', $linkHref);
														if (!in_array($documentId[1], $linkDocArray))  {
															file_put_contents(FCPATH.'/uploads/documents/'.$document_name, file_get_contents($linkHref));
															$fileSize = filesize(FCPATH.'/uploads/documents/'.$document_name);
															$this->order->uploadDocumentOnAwsS3($document_name, 'documents');
															$documentData = array(
																'document_name' => $document_name,
																'original_document_name' => $linkText,
																'document_type_id' => 0,
																'api_document_id' => $documentId[1],
																'document_size' => $fileSize,
																'user_id' => $customer_id,
																'order_id' => $orderDetails['order_id'],
																'description' => "",
																'created' => date('Y-m-d H:i:s'),
																'is_sync' => 1,
																'is_prelim_document' => 0,
																'is_linked_doc' => 1,
																'index_number' => $documentIds[$documentId[1]]
															);
															$document_id = $this->document->insert($documentData);
															$linked_doc[$linkedDocCount]['original_document_name'] = $linkText;
															$linked_doc[$linkedDocCount]['document_name'] = $document_name;
															$linked_doc[$linkedDocCount]['api_document_id'] = $documentId[1];
															$linked_doc[$linkedDocCount]['is_sync'] = 1;
															$linked_doc[$linkedDocCount]['is_prelim_document'] = 0;
															$linked_doc[$linkedDocCount]['order_id'] = $orderDetails['order_id'];
															$apiDocumentIds[] = $documentId[1];
															$linkDocArray[] = $documentId[1];
														} 
														$linkedDocCount++;
													} else{
														continue;
													}
												}
												$dir = "./vendor/gufy/pdftohtml-php/output/";
												if(file_exists($dir)){
													$di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
													$ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
													foreach ( $ri as $file ) {
														$file->isDir() ?  rmdir($file) : unlink($file);
													}
												}
											}
											
											$this->order->uploadDocumentOnAwsS3($prelimDocumentName, 'documents');
										}	
									} else {
										$documents[$documentCount]['original_document_name'] = $resDocument['DocumentName'];
										$documents[$documentCount]['document_name'] = $document_name;
										$documents[$documentCount]['api_document_id'] = $resDocument['DocumentID'];
										$documents[$documentCount]['is_sync'] = 0;
										$documents[$documentCount]['is_prelim_document'] = $is_prelim_document;
										$documents[$documentCount]['order_id'] = $orderDetails['order_id'];
										$documentCount++;
									}
								} else if (($resDocument['DocumentType']['DocumentTypeID'] == 1032 || $resDocument['DocumentType']['DocumentTypeID'] == '1032' || strpos($resDocument['DocumentName'], 'Prelim') !== false) && (strtolower($ext) == 'doc' || strtolower($ext) == 'docx')) {
									$key = array_search(1, array_column($documents, 'is_prelim_document'));
									$prelimDocument['original_document_name'] = $documents[$key]['original_document_name'];
									$prelimDocument['document_name'] = $documents[$key]['document_name'];
									$prelimDocument['api_document_id'] = $documents[$key]['api_document_id'];
									$prelimDocument['is_sync'] = $documents[$key]['is_sync'];
									$prelimDocument['order_id'] = $orderDetails['order_id'];
									array_splice($documents, $key, 1);
									$keys = array_keys(array_column($documents, 'is_linked_doc'), 1);
									$linkedDocCount = 0;
									foreach($keys as $key) {
										$linked_doc[$linkedDocCount]['original_document_name'] = $documents[$key]['original_document_name'];
										$linked_doc[$linkedDocCount]['document_name'] = $documents[$key]['document_name'];
										$linked_doc[$linkedDocCount]['api_document_id'] = $documents[$key]['api_document_id'];
										$linked_doc[$linkedDocCount]['is_sync'] = $documents[$key]['is_sync'];
										$linked_doc[$linkedDocCount]['is_prelim_document'] = 0;
										$linked_doc[$linkedDocCount]['order_id'] = $orderDetails['order_id'];
										$linkedDocCount++;
									}
								}
							}	
						}
					} else {
						if (!empty($resDocuments['Documents'])) {
							$linkDocArray = array();
							foreach($resDocuments['Documents'] as $resDocument) {
								if (!in_array($resDocument['DocumentID'], $apiDocumentIds)) {
									$time = round((str_replace("-0000)/", "", str_replace("/Date(", "", $resDocument['CreateDate'])))/1000);
									$created_date = date('Y-m-d H:i:s', $time);
									$document_name = date('YmdHis')."_".$resDocument['DocumentName'];
									$ext = end(explode('.', $resDocument['DocumentName']));
									if (($resDocument['DocumentType']['DocumentTypeID'] == 1032 || $resDocument['DocumentType']['DocumentTypeID'] == '1032' || strpos($resDocument['DocumentName'], 'Prelim') !== false) && (strtolower($ext) == 'doc' || strtolower($ext) == 'docx')) {
										$is_prelim_document = 1;
										$document_name = str_replace($ext, 'pdf', $document_name);
									} else {
										$is_prelim_document = 0;
										if(strtolower($ext) == 'doc' || strtolower($ext) == 'docx') {
											$document_name = str_replace($ext, 'pdf', $document_name);
										}
									}
									$documentData = array(
										'document_name' => $document_name,
										'original_document_name' => $resDocument['DocumentName'],
										'document_type_id' => $resDocument['DocumentType']['DocumentTypeID'],
										'api_document_id' => $resDocument['DocumentID'],
										'document_size' => $resDocument['Size'],
										'user_id' => $customer_id,
										'order_id' => $orderDetails['order_id'],
										'description' => $resDocument['DocumentName'],
										'created' => $created_date,
										'is_sync' => 0,
										'is_prelim_document' => $is_prelim_document
									);   
									$documentId = $this->document->insert($documentData);
									if($is_prelim_document == 1) {
										$prelimDocument['original_document_name'] = $resDocument['DocumentName'];
										$prelimDocument['document_name'] = $document_name;
										$prelimDocument['api_document_id'] = $resDocument['DocumentID'];
										$prelimDocument['is_sync'] = 0;
										$prelimDocument['is_prelim_document'] =  $is_prelim_document;
										$prelimDocument['order_id'] = $orderDetails['order_id'];

										$endPoint = 'documents/'.$resDocument['DocumentID'].'?format=json';
										$logid = $this->apiLogs->syncLogs($customer_id, 'resware', 'get_document', env('RESWARE_ORDER_API').$endPoint, array(), array(), $orderDetails['order_id'], 0);
										$resultDocument = $this->resware->make_request('GET', $endPoint, '', $user_data);
										$this->apiLogs->syncLogs($customer_id, 'resware', 'get_document', env('RESWARE_ORDER_API').$endPoint, array(), $resultDocument, $orderDetails['order_id'], $logid);
										$resDocument = json_decode($resultDocument, true);

										if (isset($resDocument['Document']) && !empty($resDocument['Document'])) { 
											$documentContent = base64_decode($resDocument['Document']['DocumentBody'], true);
											if (!is_dir('uploads/documents')) {
												mkdir(FCPATH.'/uploads/documents', 0777, TRUE);
											}
											file_put_contents(FCPATH.'/uploads/documents/'.$document_name, $documentContent);
											$prelimDocumentName = $document_name;
											$this->document->update(array('is_sync' => 1), array('api_document_id' => $resDocument['DocumentID']));
											$source_pdf = FCPATH.'/uploads/documents/'.$document_name;

											// $wordsApi = new \Aspose\Words\WordsApi(getenv('PDF_TO_DOC_CLIENT_ID'), getenv('PDF_TO_DOC_SECRET_KEY'));
											// $format = "docx";
											// $file = ($source_pdf);
											// $doc_file_name =  str_replace('pdf', 'docx', $document_name);
											// $dest_doc = FCPATH.'/uploads/documents/'.$doc_file_name;
											
											// $request = new Aspose\Words\Model\Requests\ConvertDocumentRequest($file, $format, null);
											// $result = $wordsApi->ConvertDocument($request); 
											// copy($result->getPathName(), $dest_doc);
											// $this->order->uploadDocumentOnAwsS3($doc_file_name, 'documents');
											
											chmod($source_pdf, 0755);
											\Gufy\PdfToHtml\Config::set('pdftohtml.bin', getenv('PDFTOHTML_PATH'));
											\Gufy\PdfToHtml\Config::set('pdfinfo.bin', getenv('PDFTOINFO_PATH'));
											$pdf = new \Gufy\PdfToHtml\Pdf($source_pdf);
											$pages = array(4, 5, 6, 7, 8, 9);
											$linkedDocCount = 0;
											foreach ($pages as $page) {
												$html = $pdf->html($page);
												$total_pages = $pdf->getPages();
												$htmlDom = new DOMDocument();
												@$htmlDom->loadHTML($html);
												if(!$htmlDom) {
													echo 'failed to load DOM';
													exit;
												} else {
													$links = $htmlDom->getElementsByTagName('a');
													$extractedLinks = array();
													foreach($links as $link) {
														$linkText = $link->nodeValue;
														$linkHref = $link->getAttribute('href');
														if(strlen(trim($linkHref)) == 0){
															continue;
														}

														if($linkHref[0] == '#'){
															continue;
														}
													
														if(strpos($linkHref, 'clients.pacificcoasttitle.com') !== false){
															$linkText = str_replace(' ', '-', $linkText); 
															$linkText = preg_replace('/[^A-Za-z0-9\-]/', '', $linkText).'.pdf';
															if($linkText == '.pdf' || strtolower($linkText) == 'no-.pdf'){
																continue;
															}
															$document_name = date('YmdHis')."_".$linkText;
															$documentId = explode('=', $linkHref);
															if (!in_array($documentId[1], $linkDocArray))  {
																file_put_contents(FCPATH.'/uploads/documents/'.$document_name, file_get_contents($linkHref));
																$fileSize = filesize(FCPATH.'/uploads/documents/'.$document_name);
																$this->order->uploadDocumentOnAwsS3($document_name, 'documents');
																$documentData = array(
																	'document_name' => $document_name,
																	'original_document_name' => $linkText,
																	'document_type_id' => 0,
																	'api_document_id' => $documentId[1],
																	'document_size' => $fileSize,
																	'user_id' => $customer_id,
																	'order_id' => $orderDetails['order_id'],
																	'description' => "",
																	'created' => date('Y-m-d H:i:s'),
																	'is_sync' => 1,
																	'is_prelim_document' => 0,
																	'is_linked_doc' => 1,
																	'index_number' => $documentIds[$documentId[1]]
																);
																$document_id = $this->document->insert($documentData);
																$linked_doc[$linkedDocCount]['original_document_name'] = $linkText;
																$linked_doc[$linkedDocCount]['document_name'] = $document_name;
																$linked_doc[$linkedDocCount]['api_document_id'] = $documentId[1];
																$linked_doc[$linkedDocCount]['is_sync'] = 1;
																$linked_doc[$linkedDocCount]['is_prelim_document'] = 0;
																$linked_doc[$linkedDocCount]['order_id'] = $orderDetails['order_id'];
																$apiDocumentIds[] = $documentId[1];
																$linkDocArray[] = $documentId[1];
															} 
															$linkedDocCount++;
														} else{
															continue;
														}
													}
													$dir = "./vendor/gufy/pdftohtml-php/output/";
													if(file_exists($dir)){
														$di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
														$ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
														foreach ( $ri as $file ) {
															$file->isDir() ?  rmdir($file) : unlink($file);
														}
													}
													//array_map('unlink', array_filter((array) glob("./vendor/gufy/pdftohtml-php/output/*") ) );
												}
												
											}
											$this->order->uploadDocumentOnAwsS3($prelimDocumentName, 'documents');
										}
									} else {
										$documents[$documentCount]['original_document_name'] = $resDocument['DocumentName'];
										$documents[$documentCount]['document_name'] = $document_name;
										$documents[$documentCount]['api_document_id'] = $resDocument['DocumentID'];
										$documents[$documentCount]['is_sync'] = 0;
										$documents[$documentCount]['is_prelim_document'] = $is_prelim_document;
										$documents[$documentCount]['order_id'] = $orderDetails['order_id'];
										$documentCount++;
									}
								}
							}	
						}
					}
					
					$file = array();
					$prelimfilename = $prelimDocument['document_name'];
					if (env('AWS_ENABLE_FLAG') == 1) {
						if ($this->order->fileExistOrNotOnS3('documents/'.$prelimfilename)) {
							$file[] = env('AWS_PATH')."documents/".$prelimfilename;
						}
					} else {
						$prelimFile = FCPATH.'uploads/documents/'.$prelimfilename;
						if (file_exists($prelimFile)) {
							$file[] = base_url().'uploads/documents/'.$prelimfilename;
						}
					}
					
					$emailContent['file_number'] = $file_number;
					$emailContent['tax'] = isset($email_data['tax']) && !empty($email_data['tax']) ? json_encode($email_data['tax']) : '';
					$emailContent['liens'] = isset($email_data['liens']) && !empty($email_data['liens']) ? json_encode($email_data['liens']) : '';

					$from_name = 'Pacific Coast Title Company';
					$from_mail = env('FROM_EMAIL');
					$prelim_message_body = $this->load->view('emails/prelim.php',$emailContent,TRUE);
					$message = $prelim_message_body; 
					$subject = 'The Prelim Hot Sheet';

					$message = 'Prelim is ready for order number #'.$orderDetails['file_number'];
					$notificationData = array(
						'sent_user_id' => $orderDetails['title_officer'],
						'message' => $message,
						'is_admin' => 0,
						'type' =>  'created'
					);
					$this->home_model->insert($notificationData, 'pct_order_notifications');
					$this->order->sendNotification($message, 'created', $orderDetails['title_officer'], 0);

					$notificationData = array(
						'sent_user_id' => $orderDetails['customer_id'],
						'message' => $message,
						'is_admin' => 0,
						'type' =>  'created'
					);
					$this->home_model->insert($notificationData, 'pct_order_notifications');
					$this->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);

					$notificationData = array(
						'sent_user_id' => $orderDetails['sales_representative'],
						'message' => $message,
						'is_admin' => 0,
						'type' =>  'created'
					);
					$this->home_model->insert($notificationData, 'pct_order_notifications');
					$this->order->sendNotification($message, 'created', $orderDetails['sales_representative'], 0);

					
					if ($_SERVER['SERVER_NAME'] == 'app.pacificcoasttitle.com') {
						/*if(!empty($orderUser['email_address'])) {
							$to = $orderUser['email_address'];
							//$to = 'hitesh.p@crestinfosystems.com';
							$mailParams = array(
								'from_mail'=>$from_mail, 
								'from_name'=>$from_name, 
								'to'=> $to,
								'subject'=>$subject
							);
							$this->load->helper('sendemail');
							$logid = $this->apiLogs->syncLogs(0, 'sendgrid', 'prelim_mail_to_client', '', $mailParams, array(), 0, 0);
							$mail_result = send_email($from_mail,$from_name, $to, $subject, $message,$file);
							$this->apiLogs->syncLogs(0, 'sendgrid', 'prelim_mail_to_client', '', $mailParams, array('status'=> $mail_result), 0, $logid);
							$result = array();
							if ($mail_result) {
								$result['mail_status'] = 'success';
							} else {
								$result['mail_status'] = 'error';		
							}
						}*/
					}
					/* Send email to customer */
					$result['message'] = "Data stored successfully.";
				} else {
					$result['message'] = "Order not found.";
				}			
			} else {
				$result['message'] = "Empty response received.";
			}
		} else {
			$result['message'] = "Empty response received.";
		}
    	echo json_encode($result);
    }

	function multiexplode($delimiters,$string) 
	{

	    $ready = str_replace($delimiters, $delimiters[0], $string);
	    $launch = explode($delimiters[0], $ready);
	    return  $launch;
	}

	public function getSearchResult($address, $locale)
    {
        $data=new stdClass();
        $data->Address= $address;
        $data->LastLine= (string) $locale;
        $data->ClientReference= '<CustCompFilter><CompNum>8</CompNum><MonthsBack>12</MonthsBack></CustCompFilter>';
        $data->OwnerName= '';
        $data->key= env('BLACK_KNIGHT_KEY');
        $data->ReportType= '187';

        $request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/AddressSearch?';

        $requestUrl = $request.http_build_query($data);

        $getsortedresults = isset($_GET['getsortedresults'])?$_GET['getsortedresults']:'false';
        
        $opts = array(
            'http'=>array(
                'header' => "User-Agent:MyAgent/1.0\r\n"
            ),
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            )
        );
        $context = stream_context_create($opts);
        $file = file_get_contents($requestUrl,false,$context);
        $xmlData = simplexml_load_string($file);
        $response = json_encode($xmlData);
        $result = json_decode($response,TRUE);
        $property_info = array();
        if(isset($result['Status']) && !empty($result['Status']) && $result['Status'] == 'OK')
        {
            $reportUrl = (isset($result['ReportURL']) && !empty($result['ReportURL'])) ? $result['ReportURL'] : '';

            if($reportUrl)
            {
                $rdata=new stdClass();
                $rdata->key= env('BLACK_KNIGHT_KEY');
                $requestUrl = $reportUrl.http_build_query($rdata);
                $reportFile = file_get_contents($requestUrl,false,$context);
                $reportData = simplexml_load_string($reportFile);
                $response = json_encode($reportData);
                $details = json_decode($response,TRUE);

                $property_info['property_type'] = isset($details['PropertyProfile']['PropertyCharacteristics']['UseCode']) && !empty($details['PropertyProfile']['PropertyCharacteristics']['UseCode']) ? $details['PropertyProfile']['PropertyCharacteristics']['UseCode'] : '';
                $property_info['legaldescription'] = isset($details['PropertyProfile']['LegalDescriptionInfo']['LegalBriefDescription']) && !empty($details['PropertyProfile']['LegalDescriptionInfo']['LegalBriefDescription']) ? $details['PropertyProfile']['LegalDescriptionInfo']['LegalBriefDescription'] : '';
                $property_info['apn'] = isset($details['PropertyProfile']['APN']) && !empty($details['PropertyProfile']['APN']) ? $details['PropertyProfile']['APN'] : '';

                $property_info['unit_no'] = isset($details['PropertyProfile']['SiteUnit']) && !empty($details['PropertyProfile']['SiteUnit']) ? $details['PropertyProfile']['SiteUnit'] : '';
                
                $property_info['fips'] = isset($details['SubjectValueInfo']['FIPS']) && !empty($details['SubjectValueInfo']['FIPS']) ? $details['SubjectValueInfo']['FIPS'] : '';

                $primaryOwner = isset($details['PropertyProfile']['PrimaryOwnerName']) && !empty($details['PropertyProfile']['PrimaryOwnerName']) ? $details['PropertyProfile']['PrimaryOwnerName'] : '';
                $secondaryOwner = isset($details['PropertyProfile']['SecondaryOwnerName']) && !empty($details['PropertyProfile']['SecondaryOwnerName']) ? $details['PropertyProfile']['SecondaryOwnerName'] : '';
                $property_info['primary_owner'] = $primaryOwner;
                $property_info['secondary_owner'] = $secondaryOwner;
            }
        }

        return $property_info;
    }

	public function importOrder($file_number) 
	{
		$order_id = 0;
		$data = json_encode(array('FileNumber' => $file_number));
		$userData = array(
			'admin_api' => 1
		);
		$logid = $this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API').'files/search', $data, array(), 0, 0);
		$res = $this->resware->make_request('POST', 'files/search', $data, $userData);
		$this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API').'files/search', $data, $res, 0, $logid);
		$result = json_decode($res,TRUE);
		
		if (isset($result['Files']) && !empty($result['Files'])) {
			foreach ($result['Files'] as $res) {
				$partner_fname = $res['Partners'][0]['PrimaryEmployee']['FirstName'];
				$partner_lname = $res['Partners'][0]['PrimaryEmployee']['LastName'];
				$partner_name = $res['Partners'][0]['PartnerName'];
				$condition = array(
					'first_name' => $partner_fname,
					'last_name' => $partner_lname,
					'company_name' => $partner_name,
					'is_pass' => $partner_name,
				);
				$user_details =  $this->home_model->get_user_by_name($condition);
				$customerId = 0;

				if (isset($user_details) && !empty($user_details)) {
					$customerId = $user_details['id'];
				}
				
				$FullProperty = $res['Properties'][0]['StreetNumber']." ".$res['Properties'][0]['StreetDirection']." ".$res['Properties'][0]['StreetName']." ".$res['Properties'][0]['StreetSuffix'].", ".$res['Properties'][0]['City'].", ".$res['Properties'][0]['State'].", ".$res['Properties'][0]['Zip'];
				$address = $res['Properties'][0]['StreetNumber']." ".$res['Properties'][0]['StreetDirection']." ".$res['Properties'][0]['StreetName']." ".$res['Properties'][0]['StreetSuffix'];
				$locale = $res['Properties'][0]['City'];
				
				if (($locale)) {
					if (!empty($res['Properties'][0]['State'])) {
						$locale .= ', '.$res['Properties'][0]['State'];
					} else {
						$locale .= ', CA';
					}
				}

				$property_details = $this->getSearchResult($address, $locale);
				$property_type = isset($property_details['property_type']) && !empty($property_details['property_type']) ? $property_details['property_type'] : '';
				$LegalDescription = isset($property_details['legaldescription']) && !empty($property_details['legaldescription']) ? $property_details['legaldescription'] : '';
				$apn = isset($property_details['apn']) && !empty($property_details['apn']) ? $property_details['apn'] : '';
				$propertyData = array(
					'customer_id' => $customerId,
					'buyer_agent_id' => 0,
					'listing_agent_id' => 0,
					'escrow_lender_id' => 0,
					'parcel_id' => $res['Properties'][0]['ParcelID'],
					'address' => $address,
					'city' => $res['Properties'][0]['City'],
					'state' => $res['Properties'][0]['State'],
					'zip' => $res['Properties'][0]['Zip'],
					'property_type' => $property_type,
					'full_address' => $FullProperty,
					'apn' => $apn,
					'county' => $res['Properties'][0]['County'],
					'legal_description' => $LegalDescription,
					'status'=> 1
				);

				$resultSales = array();
				if(!empty($salesRepName)) {
					$this->db->select('*');
					$this->db->from('customer_basic_details');
					$this->db->like("CONCAT_WS(' ', first_name, last_name)", $salesRepName);
					$this->db->where('is_sales_rep', 1);
					$query = $this->db->get();
					$resultSales = $query->row_array(); 
				}

				$transactionData = array(
					'customer_id' => $customerId,
					'sales_amount' =>  !empty($res['SalesPrice']) ? $res['SalesPrice'] : 0,
					'loan_number' => !empty($res['Loans'][0]['LoanNumber']) ? $res['Loans'][0]['LoanNumber'] : 0,
					'loan_amount' => !empty($res['Loans'][0]['LoanAmount']) ? $res['Loans'][0]['LoanAmount'] : 0,
					'transaction_type' => $res['TransactionProductType']['TransactionTypeID'],
					'purchase_type' => $res['TransactionProductType']['ProductTypeID'],
					'sales_representative' => !empty($resultSales) ? $resultSales['id'] : 0,
					'status'=> 1
				);

				$primary_owner = ($res['Buyers'][0]['Primary']['First'] && $res['Buyers'][0]['Primary']['First']) ? $res['Buyers'][0]['Primary']['First'] : '';
				$primary_owner .= ($res['Buyers'][0]['Primary']['Middle'] && $res['Buyers'][0]['Primary']['Middle']) ? " ".$res['Buyers'][0]['Primary']['Middle'] : '';
				$primary_owner .= ($res['Buyers'][0]['Primary']['Last'] && $res['Buyers'][0]['Primary']['Last']) ? " ".$res['Buyers'][0]['Primary']['Last'] : '';
				$secondary_owner = ($res['Buyers'][0]['Secondary']['First'] && $res['Buyers'][0]['Secondary']['First']) ? $res['Buyers'][0]['Secondary']['First'] : '';
				$secondary_owner .= ($res['Buyers'][0]['Secondary']['Middle'] && $res['Buyers'][0]['Secondary']['Middle']) ? $res['Buyers'][0]['Secondary']['Middle'] : '';
				$secondary_owner .= ($res['Buyers'][0]['Secondary']['Last'] && $res['Buyers'][0]['Secondary']['Last']) ? " ".$res['Buyers'][0]['Secondary']['Last'] : '';
				$ProductTypeTxt = $res['TransactionProductType']['ProductType'];

				if (strpos($ProductTypeTxt, 'Loan') !== false) {
					$propertyData['primary_owner'] = $primary_owner;
					$propertyData['secondary_owner'] = $secondary_owner;
					$loanFlag = 1;
				} elseif(strpos($ProductTypeTxt, 'Sale') !== false) {
					$transactionData['borrower'] = $primary_owner;
					$transactionData['secondary_borrower'] = $secondary_owner;
					$propertyData['primary_owner'] = isset($property_info['primary_owner']) && !empty($property_info['primary_owner']) ? $property_info['primary_owner'] : '';
					$propertyData['secondary_owner'] = isset($property_info['secondary_owner']) && !empty($property_info['secondary_owner']) ? $property_info['secondary_owner'] : '';
					$loanFlag = 0;
				}
				
				$propertyId = $this->home_model->insert($propertyData,'property_details');
				$transactionId = $this->home_model->insert($transactionData,'transaction_details');
				$time = round((int)(str_replace("-0000)/", "", str_replace("/Date(", "",$res['Dates']['OpenedDate'])))/1000);
				$created_date = date('Y-m-d H:i:s', $time);
				$randomString = $this->order->randomPassword();
				$randomString = md5($randomString);

				$completed_date = null;
				if (!empty($closedDate)) {
					$myDateTime = DateTime::createFromFormat('M d, Y', $closedDate);
					$completed_date = $myDateTime->format('Y-m-d H:i:s');
				} else {
					if (!empty($res['Dates']['FileCompletedDate'])) {
						$time = round((int)(str_replace("-0000)/", "", str_replace("/Date(", "",$res['Dates']['FileCompletedDate'])))/1000);
						$completed_date = date('Y-m-d H:i:s', $time);
					}
				}
				
				$orderData = array(
					'customer_id' => $customerId,
					'file_id' => $res['FileID'],
					'file_number' => $res['FileNumber'],
					'property_id' => $propertyId,
					'transaction_id' => $transactionId,
					'created_at' => $created_date,
					'prod_type' =>  $loanFlag == 1 ? 'loan' : 'sale',
					'status'=> 1,
					'is_imported'=> 1,
					'is_sales_rep_order'=> 1,
					'random_number' => $randomString,
					'resware_closed_status_date' => $completed_date,
					'resware_status'=> strtolower($res['Status']['Name']),
					'sent_to_accounting_date' => $completed_date
				);
				$this->home_model->insert($orderData,'order_details');
				$order_id = $this->db->insert_id();
			}
		}
		return $order_id;
	}
}

/**
* FINE granularity DIFF
*
* Computes a set of instructions to convert the content of
* one string into another.
*
* Copyright (c) 2011 Raymond Hill (http://raymondhill.net/blog/?p=441)
*
* Licensed under The MIT License
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*
* @copyright Copyright 2011 (c) Raymond Hill (http://raymondhill.net/blog/?p=441)
* @link http://www.raymondhill.net/finediff/
* @version 0.6
* @license MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

/**
* Usage (simplest):
*
*   include 'finediff.php';
*
*   // for the stock stack, granularity values are:
*   // FineDiff::$paragraphGranularity = paragraph/line level
*   // FineDiff::$sentenceGranularity = sentence level
*   // FineDiff::$wordGranularity = word level
*   // FineDiff::$characterGranularity = character level [default]
*
*  $opcodes = FineDiff::getDiffOpcodes($from_text, $to_text [, $granularityStack = null] );
*   // store opcodes for later use...
*
*   ...
*
*   // restore $to_text from $from_text + $opcodes
*   include 'finediff.php';
*   $to_text = FineDiff::renderToTextFromOpcodes($from_text, $opcodes);
*
*   ...
*/

/**
* Persisted opcodes (string) are a sequence of atomic opcode.
* A single opcode can be one of the following:
*   c | c{n} | d | d{n} | i:{c} | i{length}:{s}
*   'c'        = copy one character from source
*   'c{n}'     = copy n characters from source
*   'd'        = skip one character from source
*   'd{n}'     = skip n characters from source
*   'i:{c}     = insert character 'c'
*   'i{n}:{s}' = insert string s, which is of length n
*
* Do not exist as of now, under consideration:
*   'm{n}:{o}  = move n characters from source o characters ahead.
*   It would be essentially a shortcut for a delete->copy->insert
*   command (swap) for when the inserted segment is exactly the same
*   as the deleted one, and with only a copy operation in between.
*   TODO: How often this case occurs? Is it worth it? Can only
*   be done as a postprocessing method (->optimize()?)
*/
abstract class FineDiffOp {
	abstract public function getFromLen();
	abstract public function getToLen();
	abstract public function getOpcode();
	}

class FineDiffDeleteOp extends FineDiffOp {
	public function __construct($len) {
		$this->fromLen = $len;
		}
	public function getFromLen() {
		return $this->fromLen;
		}
	public function getToLen() {
		return 0;
		}
	public function getOpcode() {
		if ( $this->fromLen === 1 ) {
			return 'd';
			}
		return "d{$this->fromLen}";
		}
	}

class FineDiffInsertOp extends FineDiffOp {
	public function __construct($text) {
		$this->text = $text;
		}
	public function getFromLen() {
		return 0;
		}
	public function getToLen() {
		return strlen($this->text);
		}
	public function getText() {
		return $this->text;
		}
	public function getOpcode() {
		$to_len = strlen($this->text);
		if ( $to_len === 1 ) {
			return "i:{$this->text}";
			}
		return "i{$to_len}:{$this->text}";
		}
	}

class FineDiffReplaceOp extends FineDiffOp {
	public function __construct($fromLen, $text) {
		$this->fromLen = $fromLen;
		$this->text = $text;
		}
	public function getFromLen() {
		return $this->fromLen;
		}
	public function getToLen() {
		return strlen($this->text);
		}
	public function getText() {
		return $this->text;
		}
	public function getOpcode() {
		
		if ( $this->fromLen === 1 ) {
			$del_opcode = 'd';
			}
		else {
			$del_opcode = "d{$this->fromLen}";
			}
		$to_len = strlen($this->text);
		if ( $to_len === 1 ) {
			return "{$this->text}";
			}
		return "{$this->text}";
		}
	}

class FineDiffCopyOp extends FineDiffOp {
	public function __construct($len) {
		$this->len = $len;
		}
	public function getFromLen() {
		return $this->len;
		}
	public function getToLen() {
		return $this->len;
		}
	public function getOpcode() {
		if ( $this->len === 1 ) {
			return 'c';
			}
		return "c{$this->len}";
		}
	public function increase($size) {
		return $this->len += $size;
		}
	}

/**
* FineDiff ops
*
* Collection of ops
*/
class FineDiffOps {
	public function appendOpcode($opcode, $from, $from_offset, $from_len) {
		if ( $opcode === 'c' ) {
			$edits[] = new FineDiffCopyOp($from_len);
			}
		else if ( $opcode === 'd' ) {
			$edits[] = new FineDiffDeleteOp($from_len);
			}
		else /* if ( $opcode === 'i' ) */ {
			$edits[] = new FineDiffInsertOp(substr($from, $from_offset, $from_len));
			}
		}
	public $edits = array();
	}

/**
* FineDiff class
*
* TODO: Document
*
*/
class FineDiff {

	/**------------------------------------------------------------------------
	*
	* Public section
	*
	*/

	/**
	* Constructor
	* ...
	* The $granularityStack allows FineDiff to be configurable so that
	* a particular stack tailored to the specific content of a document can
	* be passed.
	*/
	public function __construct($from_text = '', $to_text = '', $granularityStack = null) {
		// setup stack for generic text documents by default
		$this->granularityStack = $granularityStack ? $granularityStack : FineDiff::$characterGranularity;
		$this->edits = array();
		$this->from_text = $from_text;
		$this->doDiff($from_text, $to_text);
		}

	public function getOps() {
		return $this->edits;
		}

	public function getOpcodes() {
		$opcodes = array();
		foreach ( $this->edits as $edit ) {
			
			if(isset($edit->text) && !empty($edit->text))
			{
				
				$edit->text = trim($edit->text);
				if(strlen($edit->text))
				{
					$opcodes[] = $edit->getOpcode();
				}				
			}
			
			}
		return implode('^^', $opcodes);
		}

	public function renderDiffToHTML() {
		$in_offset = 0;
		ob_start();
		foreach ( $this->edits as $edit ) {
			$n = $edit->getFromLen();
			if ( $edit instanceof FineDiffCopyOp ) {
				FineDiff::renderDiffToHTMLFromOpcode('c', $this->from_text, $in_offset, $n);
				}
			else if ( $edit instanceof FineDiffDeleteOp ) {
				FineDiff::renderDiffToHTMLFromOpcode('d', $this->from_text, $in_offset, $n);
				}
			else if ( $edit instanceof FineDiffInsertOp ) {
				FineDiff::renderDiffToHTMLFromOpcode('i', $edit->getText(), 0, $edit->getToLen());
				}
			else /* if ( $edit instanceof FineDiffReplaceOp ) */ {
				FineDiff::renderDiffToHTMLFromOpcode('d', $this->from_text, $in_offset, $n);
				FineDiff::renderDiffToHTMLFromOpcode('i', $edit->getText(), 0, $edit->getToLen());
				}
			$in_offset += $n;
			}
		return ob_get_clean();
		}

	/**------------------------------------------------------------------------
	* Return an opcodes string describing the diff between a "From" and a
	* "To" string
	*/
	public static function getDiffOpcodes($from, $to, $granularities = null) {
		$diff = new FineDiff($from, $to, $granularities);
		return $diff->getOpcodes();
		}

	/**------------------------------------------------------------------------
	* Return an iterable collection of diff ops from an opcodes string
	*/
	public static function getDiffOpsFromOpcodes($opcodes) {
		$diffops = new FineDiffOps();
		FineDiff::renderFromOpcodes(null, $opcodes, array($diffops,'appendOpcode'));
		return $diffops->edits;
		}

	/**------------------------------------------------------------------------
	* Re-create the "To" string from the "From" string and an "Opcodes" string
	*/
	public static function renderToTextFromOpcodes($from, $opcodes) {
		ob_start();
		FineDiff::renderFromOpcodes($from, $opcodes, array('FineDiff','renderToTextFromOpcode'));
		return ob_get_clean();
		}

	/**------------------------------------------------------------------------
	* Render the diff to an HTML string -- UTF8 unsafe
	*/
	public static function renderDiffToHTMLFromOpcodes($from, $opcodes) {
		ob_start();
		FineDiff::renderFromOpcodes($from, $opcodes, array('FineDiff','renderDiffToHTMLFromOpcode'));
		return ob_get_clean();
		}

	/**------------------------------------------------------------------------
	* Render the diff to an HTML string -- UTF8 safe
	*/
	public static function renderUTF8DiffToHTMLFromOpcodes($from, $opcodes) {
		ob_start();
		FineDiff::renderUTF8FromOpcode($from, $opcodes, array('FineDiff','renderDiffToHTMLFromOpcode'));
		return ob_get_clean();
		}

	/**------------------------------------------------------------------------
	* Generic opcodes parser, user must supply callback for handling
	* single opcode
	*/
	public static function renderFromOpcodes($from, $opcodes, $callback) {
		if ( !is_callable($callback) ) {
			return;
			}
		$opcodes_len = strlen($opcodes);
		$from_offset = $opcodes_offset = 0;
		while ( $opcodes_offset <  $opcodes_len ) {
			$opcode = substr($opcodes, $opcodes_offset, 1);
			$opcodes_offset++;
			$n = intval(substr($opcodes, $opcodes_offset));
			if ( $n ) {
				$opcodes_offset += strlen(strval($n));
				}
			else {
				$n = 1;
				}
			if ( $opcode === 'c' ) { // copy n characters from source
				call_user_func($callback, 'c', $from, $from_offset, $n, '');
				$from_offset += $n;
				}
			else if ( $opcode === 'd' ) { // delete n characters from source
				call_user_func($callback, 'd', $from, $from_offset, $n, '');
				$from_offset += $n;
				}
			else /* if ( $opcode === 'i' ) */ { // insert n characters from opcodes
				call_user_func($callback, 'i', $opcodes, $opcodes_offset + 1, $n);
				$opcodes_offset += 1 + $n;
				}
			}
		}

	/**------------------------------------------------------------------------
	* Generic opcodes parser, user must supply callback for handling
	* single opcode
	*/
	private static function renderUTF8FromOpcode($from, $opcodes, $callback) {
		if ( !is_callable($callback) ) {
			return;
			}
        $from_len = strlen($from);
		$opcodes_len = strlen($opcodes);
		$from_offset = $opcodes_offset = 0;
        $last_to_chars = '';
		while ( $opcodes_offset <  $opcodes_len ) {
			$opcode = substr($opcodes, $opcodes_offset, 1);
			$opcodes_offset++;
			$n = intval(substr($opcodes, $opcodes_offset));
			if ( $n ) {
				$opcodes_offset += strlen(strval($n));
				}
			else {
				$n = 1;
				}
            if ( $opcode === 'c' || $opcode === 'd' ) {
                $beg = $from_offset;
                $end = $from_offset + $n;
                while ( $beg > 0 && (ord($from[$beg]) & 0xC0) === 0x80 ) { $beg--; }
                while ( $end < $from_len && (ord($from[$end]) & 0xC0) === 0x80 ) { $end++; }
                if ( $opcode === 'c' ) { // copy n characters from source
                    call_user_func($callback, 'c', $from, $beg, $end - $beg, '');
                    $last_to_chars = substr($from, $from, $beg, $end - $beg);
                    }
                else /* if ( $opcode === 'd' ) */ { // delete n characters from source
                    call_user_func($callback, 'd', $from, $beg, $end - $beg, '');
                    }
                $from_offset += $n;
                }
			else /* if ( $opcode === 'i' ) */ { // insert n characters from opcodes
				$opcodes_offset += 1;
                if ( strlen($last_to_chars) > 0 && (ord($opcodes[$opcodes_offset]) & 0xC0) === 0x80 ) {
                    $beg = strlen($last_to_chars) - 1;
                    while ( $beg > 0 && (ord($last_to_chars[$beg]) & 0xC0) === 0x80 ) { $beg--; }
                    $prefix = substr($last_to_chars, $beg);
                } else {
                    $prefix = '';
                }
                $end = $from_offset;
                while ( $end < $from_len && (ord($from[$end]) & 0xC0) === 0x80 ) { $end++; }
                $toInsert = $prefix . substr($opcodes, $opcodes_offset, $n) . substr($from, $end, $end - $from_offset);
                call_user_func($callback, 'i', $toInsert, 0, strlen($toInsert));
				$opcodes_offset += $n;
                $last_to_chars = $toInsert;
                }
            }
        }

	/**
	* Stock granularity stacks and delimiters
	*/

	const paragraphDelimiters = "\n\r";
	public static $paragraphGranularity = array(
		FineDiff::paragraphDelimiters
		);
	const sentenceDelimiters = ".\n\r";
	public static $sentenceGranularity = array(
		FineDiff::paragraphDelimiters,
		FineDiff::sentenceDelimiters
		);
	const wordDelimiters = " \t.\n\r";
	public static $wordGranularity = array(
		FineDiff::paragraphDelimiters,
		FineDiff::sentenceDelimiters,
		FineDiff::wordDelimiters
		);
	const characterDelimiters = "";
	public static $characterGranularity = array(
		FineDiff::paragraphDelimiters,
		FineDiff::sentenceDelimiters,
		FineDiff::wordDelimiters,
		FineDiff::characterDelimiters
		);

	public static $textStack = array(
		".",
		" \t.\n\r",
		""
		);

	/**------------------------------------------------------------------------
	*
	* Private section
	*
	*/

	/**
	* Entry point to compute the diff.
	*/
	private function doDiff($from_text, $to_text) {
		$this->last_edit = false;
		$this->stackpointer = 0;
		$this->from_text = $from_text;
		$this->from_offset = 0;
		// can't diff without at least one granularity specifier
		if ( empty($this->granularityStack) ) {
			return;
			}
		$this->_processGranularity($from_text, $to_text);
		}

	/**
	* This is the recursive function which is responsible for
	* handling/increasing granularity.
	*
	* Incrementally increasing the granularity is key to compute the
	* overall diff in a very efficient way.
	*/
	private function _processGranularity($from_segment, $to_segment) {
		$delimiters = $this->granularityStack[$this->stackpointer++];
		$has_next_stage = $this->stackpointer < count($this->granularityStack);
		foreach ( FineDiff::doFragmentDiff($from_segment, $to_segment, $delimiters) as $fragment_edit ) {
			// increase granularity
			if ( $fragment_edit instanceof FineDiffReplaceOp && $has_next_stage ) {
				$this->_processGranularity(
					substr($this->from_text, $this->from_offset, $fragment_edit->getFromLen()),
					$fragment_edit->getText()
					);
				}
			// fuse copy ops whenever possible
			else if ( $fragment_edit instanceof FineDiffCopyOp && $this->last_edit instanceof FineDiffCopyOp ) {
				$this->edits[count($this->edits)-1]->increase($fragment_edit->getFromLen());
				$this->from_offset += $fragment_edit->getFromLen();
				}
			else {
				/* $fragment_edit instanceof FineDiffCopyOp */
				/* $fragment_edit instanceof FineDiffDeleteOp */
				/* $fragment_edit instanceof FineDiffInsertOp */
				$this->edits[] = $this->last_edit = $fragment_edit;
				$this->from_offset += $fragment_edit->getFromLen();
				}
			}
		$this->stackpointer--;
		}

	/**
	* This is the core algorithm which actually perform the diff itself,
	* fragmenting the strings as per specified delimiters.
	*
	* This function is naturally recursive, however for performance purpose
	* a local job queue is used instead of outright recursivity.
	*/
	private static function doFragmentDiff($from_text, $to_text, $delimiters) {
		// Empty delimiter means character-level diffing.
		// In such case, use code path optimized for character-level
		// diffing.
		if ( empty($delimiters) ) {
			return FineDiff::doCharDiff($from_text, $to_text);
			}

		$result = array();

		// fragment-level diffing
		$from_text_len = strlen($from_text);
		$to_text_len = strlen($to_text);
		$from_fragments = FineDiff::extractFragments($from_text, $delimiters);
		$to_fragments = FineDiff::extractFragments($to_text, $delimiters);

		$jobs = array(array(0, $from_text_len, 0, $to_text_len));

		$cached_array_keys = array();

		while ( $job = array_pop($jobs) ) {

			// get the segments which must be diff'ed
			list($from_segment_start, $from_segment_end, $to_segment_start, $to_segment_end) = $job;

			// catch easy cases first
			$from_segment_length = $from_segment_end - $from_segment_start;
			$to_segment_length = $to_segment_end - $to_segment_start;
			if ( !$from_segment_length || !$to_segment_length ) {
				if ( $from_segment_length ) {
					$result[$from_segment_start * 4] = new FineDiffDeleteOp($from_segment_length);
					}
				else if ( $to_segment_length ) {
					$result[$from_segment_start * 4 + 1] = new FineDiffInsertOp(substr($to_text, $to_segment_start, $to_segment_length));
					}
				continue;
				}

			// find longest copy operation for the current segments
			$best_copy_length = 0;

			$from_base_fragment_index = $from_segment_start;

			$cached_array_keys_for_current_segment = array();

			while ( $from_base_fragment_index < $from_segment_end ) {
				$from_base_fragment = $from_fragments[$from_base_fragment_index];
				$from_base_fragment_length = strlen($from_base_fragment);
				// performance boost: cache array keys
				if ( !isset($cached_array_keys_for_current_segment[$from_base_fragment]) ) {
					if ( !isset($cached_array_keys[$from_base_fragment]) ) {
						$to_all_fragment_indices = $cached_array_keys[$from_base_fragment] = array_keys($to_fragments, $from_base_fragment, true);
						}
					else {
						$to_all_fragment_indices = $cached_array_keys[$from_base_fragment];
						}
					// get only indices which falls within current segment
					if ( $to_segment_start > 0 || $to_segment_end < $to_text_len ) {
						$to_fragment_indices = array();
						foreach ( $to_all_fragment_indices as $to_fragment_index ) {
							if ( $to_fragment_index < $to_segment_start ) { continue; }
							if ( $to_fragment_index >= $to_segment_end ) { break; }
							$to_fragment_indices[] = $to_fragment_index;
							}
						$cached_array_keys_for_current_segment[$from_base_fragment] = $to_fragment_indices;
						}
					else {
						$to_fragment_indices = $to_all_fragment_indices;
						}
					}
				else {
					$to_fragment_indices = $cached_array_keys_for_current_segment[$from_base_fragment];
					}
				// iterate through collected indices
				foreach ( $to_fragment_indices as $to_base_fragment_index ) {
					$fragment_index_offset = $from_base_fragment_length;
					// iterate until no more match
					for (;;) {
						$fragment_from_index = $from_base_fragment_index + $fragment_index_offset;
						if ( $fragment_from_index >= $from_segment_end ) {
							break;
							}
						$fragment_to_index = $to_base_fragment_index + $fragment_index_offset;
						if ( $fragment_to_index >= $to_segment_end ) {
							break;
							}
						if ( $from_fragments[$fragment_from_index] !== $to_fragments[$fragment_to_index] ) {
							break;
							}
						$fragment_length = strlen($from_fragments[$fragment_from_index]);
						$fragment_index_offset += $fragment_length;
						}
					if ( $fragment_index_offset > $best_copy_length ) {
						$best_copy_length = $fragment_index_offset;
						$best_from_start = $from_base_fragment_index;
						$best_to_start = $to_base_fragment_index;
						}
					}
				$from_base_fragment_index += strlen($from_base_fragment);
				// If match is larger than half segment size, no point trying to find better
				// TODO: Really?
				if ( $best_copy_length >= $from_segment_length / 2) {
					break;
					}
				// no point to keep looking if what is left is less than
				// current best match
				if ( $from_base_fragment_index + $best_copy_length >= $from_segment_end ) {
					break;
					}
				}

			if ( $best_copy_length ) {
				$jobs[] = array($from_segment_start, $best_from_start, $to_segment_start, $best_to_start);
				$result[$best_from_start * 4 + 2] = new FineDiffCopyOp($best_copy_length);
				$jobs[] = array($best_from_start + $best_copy_length, $from_segment_end, $best_to_start + $best_copy_length, $to_segment_end);
				}
			else {
				$result[$from_segment_start * 4 ] = new FineDiffReplaceOp($from_segment_length, substr($to_text, $to_segment_start, $to_segment_length));
				}
			}

		ksort($result, SORT_NUMERIC);
		return array_values($result);
		}

	/**
	* Perform a character-level diff.
	*
	* The algorithm is quite similar to doFragmentDiff(), except that
	* the code path is optimized for character-level diff -- strpos() is
	* used to find out the longest common subequence of characters.
	*
	* We try to find a match using the longest possible subsequence, which
	* is at most the length of the shortest of the two strings, then incrementally
	* reduce the size until a match is found.
	*
	* I still need to study more the performance of this function. It
	* appears that for long strings, the generic doFragmentDiff() is more
	* performant. For word-sized strings, doCharDiff() is somewhat more
	* performant.
	*/
	private static function doCharDiff($from_text, $to_text) {
		$result = array();
		$jobs = array(array(0, strlen($from_text), 0, strlen($to_text)));
		while ( $job = array_pop($jobs) ) {
			// get the segments which must be diff'ed
			list($from_segment_start, $from_segment_end, $to_segment_start, $to_segment_end) = $job;
			$from_segment_len = $from_segment_end - $from_segment_start;
			$to_segment_len = $to_segment_end - $to_segment_start;

			// catch easy cases first
			if ( !$from_segment_len || !$to_segment_len ) {
				if ( $from_segment_len ) {
					$result[$from_segment_start * 4 + 0] = new FineDiffDeleteOp($from_segment_len);
					}
				else if ( $to_segment_len ) {
					$result[$from_segment_start * 4 + 1] = new FineDiffInsertOp(substr($to_text, $to_segment_start, $to_segment_len));
					}
				continue;
				}
			if ( $from_segment_len >= $to_segment_len ) {
				$copy_len = $to_segment_len;
				while ( $copy_len ) {
					$to_copy_start = $to_segment_start;
					$to_copy_start_max = $to_segment_end - $copy_len;
					while ( $to_copy_start <= $to_copy_start_max ) {
						$from_copy_start = strpos(substr($from_text, $from_segment_start, $from_segment_len), substr($to_text, $to_copy_start, $copy_len));
						if ( $from_copy_start !== false ) {
							$from_copy_start += $from_segment_start;
							break 2;
							}
						$to_copy_start++;
						}
					$copy_len--;
					}
				}
			else {
				$copy_len = $from_segment_len;
				while ( $copy_len ) {
					$from_copy_start = $from_segment_start;
					$from_copy_start_max = $from_segment_end - $copy_len;
					while ( $from_copy_start <= $from_copy_start_max ) {
						$to_copy_start = strpos(substr($to_text, $to_segment_start, $to_segment_len), substr($from_text, $from_copy_start, $copy_len));
						if ( $to_copy_start !== false ) {
							$to_copy_start += $to_segment_start;
							break 2;
							}
						$from_copy_start++;
						}
					$copy_len--;
					}
				}
			// match found
			if ( $copy_len ) {
				$jobs[] = array($from_segment_start, $from_copy_start, $to_segment_start, $to_copy_start);
				$result[$from_copy_start * 4 + 2] = new FineDiffCopyOp($copy_len);
				$jobs[] = array($from_copy_start + $copy_len, $from_segment_end, $to_copy_start + $copy_len, $to_segment_end);
				}
			// no match,  so delete all, insert all
			else {
				$result[$from_segment_start * 4] = new FineDiffReplaceOp($from_segment_len, substr($to_text, $to_segment_start, $to_segment_len));
				}
			}
		ksort($result, SORT_NUMERIC);
		return array_values($result);
		}

	/**
	* Efficiently fragment the text into an array according to
	* specified delimiters.
	* No delimiters means fragment into single character.
	* The array indices are the offset of the fragments into
	* the input string.
	* A sentinel empty fragment is always added at the end.
	* Careful: No check is performed as to the validity of the
	* delimiters.
	*/
	private static function extractFragments($text, $delimiters) {
		// special case: split into characters
		if ( empty($delimiters) ) {
			$chars = str_split($text, 1);
			$chars[strlen($text)] = '';
			return $chars;
			}
		$fragments = array();
		$start = $end = 0;
		for (;;) {
			$end += strcspn($text, $delimiters, $end);
			$end += strspn($text, $delimiters, $end);
			if ( $end === $start ) {
				break;
				}
			$fragments[$start] = substr($text, $start, $end - $start);
			$start = $end;
			}
		$fragments[$start] = '';
		return $fragments;
		}

	/**
	* Stock opcode renderers
	*/
	private static function renderToTextFromOpcode($opcode, $from, $from_offset, $from_len) {
		if ( $opcode === 'c' || $opcode === 'i' ) {
			echo substr($from, $from_offset, $from_len);
			}
		}

	private static function renderDiffToHTMLFromOpcode($opcode, $from, $from_offset, $from_len) {
		if ( $opcode === 'c' ) {
			echo htmlspecialchars(substr($from, $from_offset, $from_len));
			}
		else if ( $opcode === 'd' ) {
			$deletion = substr($from, $from_offset, $from_len);
			if ( strcspn($deletion, " \n\r") === 0 ) {
				$deletion = str_replace(array("\n","\r"), array('\n','\r'), $deletion);
				}
			echo '<del>', htmlspecialchars($deletion), '</del>';
			}
		else /* if ( $opcode === 'i' ) */ {
 			echo '<ins>', htmlspecialchars(substr($from, $from_offset, $from_len)), '</ins>';
			}
		}
	}

