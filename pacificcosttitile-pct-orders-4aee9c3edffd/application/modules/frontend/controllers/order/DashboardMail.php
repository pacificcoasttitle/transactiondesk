<?php

(defined('BASEPATH')) or exit('No direct script access allowed');
use mikehaertl\pdftk\Pdf;

class DashboardMail extends MX_Controller
{

    private $marital_status = array();
    private $vesting_choice = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url', 'form')
        );
        $this->load->library('session');
        $this->load->library('order/salesDashboardTemplate');
        $this->load->library('form_validation');
        $this->load->library('order/order');
        $this->load->model('order/apiLogs');
        $this->load->model('order/home_model');
        $this->load->model('order/fees_model');
        $this->load->library('order/resware');
        $this->load->library('order/twilio');
        $this->load->model('order/titleOfficer');
        $this->load->model('order/twilioMessage');
        $this->load->model('order/titlePointData');
        $this->load->library('order/titlepoint');
        $this->marital_status = array(
            "husband_and_wife" => ['show_married_option' => "1", "text" => "Husband and Wife"],
            "wife_and_husband" => ['show_married_option' => "1", "text" => "Wife and Husband"],
            "a_married_couple" => ['show_married_option' => "1", "text" => "A Married Couple"],
            "a_single_man" => ['show_married_option' => "0", "text" => "A Single Man (never married)"],
            "a_single_woman" => ['show_married_option' => "0", "text" => "A Single Woman (never married)"],
            "a_single_person" => ['show_married_option' => "0", "text" => "A Single Person (never married)"],
            "a_married_man" => ['show_married_option' => "0", "text" => "A Married Man (as his sole and separate property)*"],
            "a_married_woman" => ['show_married_option' => "0", "text" => "A Married Woman (as her sole and separate property)*"],
            "a_married_person" => ['show_married_option' => "0", "text" => "A Married Person (as his/her sole and separate property)*"],
            "an_unmarried_man" => ['show_married_option' => "0", "text" => "An Unmarried Man (divorced)"],
            "an_unmarried_woman" => ['show_married_option' => "0", "text" => "An Unmarried Woman (divorced)"],
            "an_unmarried_person" => ['show_married_option' => "0", "text" => "An Unmarried Person (divorced)"],
            "a_widow" => ['show_married_option' => "0", "text" => "A Widow (spouse deceased)"],
            "a_widower" => ['show_married_option' => "0", "text" => "A Widower (spouse deceased)"],
            "registered_domestic_partners" => ['show_married_option' => "1", "text" => "Registered Domestic Partners"],
        );

        $this->vesting_choice = array(
            "community_property" => ["text" => "Community Property", "pdf_val" => "Community Property"],
            "community_property_with_right" => ["text" => "Community Property with Right of Survivorship", "pdf_val" => "Community Property with Right of Survivorship"],
            "joint_tenants" => ["text" => "Joint Tenants", "pdf_val" => "Joint Tenants"],
            "tenants_in_common" => ["text" => "Tenants In Common (Please Give Interest Amounts)", "pdf_val" => "Tenants In Common (Please Give Interest Amounts)"],
            "sole_and_separate_property" => ["text" => "Sole and Separate Property (If Married or Domestic Partnership, an Interspousal Grant Deed, A Quitclaim Deed, Statement Of Information and Appropriate Instructions Will Need To Be Submitted.)", "pdf_val" => "Sole and Separ"],
            "partnership" => ["text" => "Partnership (Limited Or General) ", "pdf_val" => "Partnership"],
            "corporation" => ["text" => "Corporation (California Or Other State) ", "pdf_val" => "Corporation"],
            "a_trust" => ["text" => "A Trust (attach copy of Trust Agreement) ", "pdf_val" => "A Trust"],
            "other" => ["text" => "Other", "pdf_val" => "Other"],
        );
    }

    public function generateCplFromMail()
    {
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $random_number = $this->uri->segment(2);
        $order = $this->getOrderInfo($random_number);
        $fileId = $order[0]['file_id'];
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;
        $orderDetails = $this->order->get_order_details($fileId, 1);
        $data['file_number'] = $orderDetails['file_number'];
        $data['full_address'] = $orderDetails['full_address'];
        $data['created'] = !empty($orderDetails['created']) ? date("m/d/Y", strtotime($orderDetails['created'])) : '';
        $file_id = $orderDetails['file_id'];

        if (!empty($orderDetails['cpl_document_name'])) {
            $documentName = $orderDetails['cpl_document_name'];
            if (env('AWS_ENABLE_FLAG') == 1) {
                $documentUrl = env('AWS_PATH') . "documents/" . $documentName;
                // $data['action'] = "<div style='display:flex;'><a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"cpl"' . ");'><button class='btn btn-grad-2a' style='background: #d35411;' type='button'>Download</button></a>
                // <a onclick='return lender_pop_up(0, $file_id);' href='javascript:void(0);'><button class='btn btn-grad-2a generate button-color' type='button'>Edit</button></a></div>";
                $data['action'] = "<div style='display:flex;justify-content: space-around;'><a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"cpl"' . ");' title='Download' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-download'></i></span><span class='text'>Download</span></a>
                <a onclick='return lender_pop_up(0, $file_id);' href='javascript:void(0);' class='btn btn-primary btn-icon-split'><span class='icon text-white-50'><i class='fas fa-edit'></i></span><span class='text'>Edit</span></a></div>";
            } else {
                $documentUrl = FCPATH . 'uploads/documents/' . $documentName;
                // $data['action'] = "<div style='display:flex;'><a href='$documentUrl' download><button class='btn btn-grad-2a' style='background: #d35411;' type='button'>Download</button></a>
                //                 <a onclick='return lender_pop_up(0, $file_id);' href='javascript:void(0);'><button class='btn btn-grad-2a generate button-color' type='button'>Edit</button></a></div>";
                $data['action'] = "<div style='display:flex;justify-content: space-around;'><a href='$documentUrl' download title='Download' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-download'></i></span><span class='text'>Download</span></a>
                <a onclick='return lender_pop_up(0, $file_id);' href='javascript:void(0);' class='btn btn-primary btn-icon-split'><span class='icon text-white-50'><i class='fas fa-edit'></i></span><span class='text'>Edit</span></a></div>";
            }

        } else if (!empty($orderDetails['westcor_file_id'])) {
            $westcorFileId = $orderDetails['westcor_file_id'];
            $westcorOrderId = $orderDetails['westcor_order_id'];
            // $data['action'] = "<div style='display:flex;'><a onclick='download_for_pdf($westcorFileId, $westcorOrderId);' href='javascript:void(0);'><button class='btn btn-grad-2a' style='background: #d35411;' type='button'>Download</button></a>
            //                     <a onclick='return lender_pop_up(0, $file_id);' href='javascript:void(0);'><button class='btn btn-grad-2a generate button-color' type='button'>Edit</button></a></div>";
            $data['action'] = "<div style='display:flex;justify-content: space-around;'><a onclick='download_for_pdf($westcorFileId, $westcorOrderId);' href='javascript:void(0);' title='Download' title='Download' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-download'></i></span><span class='text'>Download</span></a>
                                <a onclick='return lender_pop_up(0, $file_id);' href='javascript:void(0);' class='btn btn-primary btn-icon-split'><span class='icon text-white-50'><i class='fas fa-edit'></i></span><span class='text'>Edit</span></a></div>";
        } else {
            // $data['action'] = "<div style='display:flex;'><form onclick='return lender_pop_up(0, $file_id);' action='" . base_url() . "create-cpl/" . $file_id . "' method='POST'><button class='btn btn-grad-2a generate button-color' type='submit'>GENERATE</button></form>
            //                     <a onclick='return lender_pop_up(0, $file_id);' href='javascript:void(0);'><button class='btn btn-grad-2a generate button-color' type='button'>Edit</button></a></div>";
            $data['action'] = "<div style='display:flex;justify-content: space-around;'><form onclick='return lender_pop_up(0, $file_id);' action='" . base_url() . "create-cpl/" . $order['file_id'] . "' method='POST'><a href='javascript:void(0);'  title='Generate' type='submit' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-seedling'></i></span><span class='text'>Generate</span></a></form>
                                <a onclick='return lender_pop_up(0, $file_id);' href='javascript:void(0);' class='btn btn-primary btn-icon-split'><span class='icon text-white-50'><i class='fas fa-edit'></i></span><span class='text'>Edit</span></a></div>";
        }
        // $this->load->view('layout/head_dashboard', $data);
        // $this->load->view('order/mail_cpl', $data);
        $data['displaySidebar'] = false;
        $this->salesdashboardtemplate->show("order", "mail_cpl", $data);
    }

    public function generateFeesFromMail()
    {
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;
        $random_number = $this->uri->segment(2);
        $order = $this->getOrderInfo($random_number);
        $fileId = $order[0]['file_id'];

        $orderDetails = $this->order->get_order_details($fileId, 1);
        $post_data = $result_decoded = array();
        $apiData = json_encode(array('FileNumber' => $orderDetails['file_number']));
        $userData = array(
            'admin_api' => 1,
        );

        $result = $this->resware->make_request('POST', 'files/search', $apiData, $userData);
        if (json_decode($result) && count(json_decode($result)->Files)) {
            $result_decoded = json_decode($result);
            $loanAmount = $result_decoded->Files[0]->Loans[0]->LoanAmount;
            $salesAmount = $result_decoded->Files[0]->SalesPrice;
        }

        $result_decoded = json_decode($result);

        if (!empty($orderDetails)) {
            $post_data['seller'] = $orderDetails['primary_owner'];
        } else {
            $post_data['seller'] = '';
        }
        $property_data = $result_decoded->Files[0]->Properties[0];
        $buyer_data = $result_decoded->Files[0]->Buyers[0];
        $buyer_name = $buyer_data->Primary;

        $post_data['file_id'] = $result_decoded->Files[0]->FileID;
        $post_data['file_number'] = $result_decoded->Files[0]->FileNumber;
        $post_data['loanAmount'] = $result_decoded->Files[0]->Loans[0]->LoanAmount ? $result_decoded->Files[0]->Loans[0]->LoanAmount : 0;
        $post_data['salesPrice'] = $result_decoded->Files[0]->SalesPrice;
        $post_data['city'] = $property_data->City;
        $post_data['county'] = $property_data->County;
        $post_data['borrower'] = $result_decoded->Files[0]->Buyers[0]->Primary->First . " " . $result_decoded->Files[0]->Buyers[0]->Primary->Last;
        $post_data['full_address'] = $property_data->StreetNumber;
        $post_data['full_address'] .= !empty($property_data->StreetDirection) ? ' ' . substr($property_data->StreetDirection, 0, 1) : '';
        $post_data['full_address'] .= ' ' . $property_data->StreetName;
        $post_data['full_address'] .= ' ' . $property_data->StreetSuffix;
        $post_data['full_address'] .= ', ' . $property_data->City;
        $post_data['full_address'] .= ', ' . $property_data->State;
        $post_data['full_address'] .= ' ' . $property_data->Zip;
        $post_data['borrower'] = !empty($buyer_name->First) ? $buyer_name->First : '';
        $post_data['borrower'] .= !empty($buyer_name->Middle) ? ' ' . $buyer_name->Middle : '';
        $post_data['borrower'] .= !empty($buyer_name->Last) ? ' ' . $buyer_name->Last : '';
        $post_data['borrower'] = trim($post_data['borrower']);

        if (empty($post_data['borrower'])) {
            $post_data['borrower'] = !empty($buyer_name->BusinessName) ? $buyer_name->BusinessName : '';
        }

        $post_data['ECD'] = '';
        if (!empty($result_decoded->Files[0]->Dates->FileCompletedDate)) {
            $ecd_timestamp = str_replace("-0000)/", "", str_replace("/Date(", "", $result_decoded->Files[0]->Dates->FileCompletedDate));
            $ecd_date = date('m/d/Y', $ecd_timestamp / 1000);
            $post_data['ECD'] = $ecd_date;
        }

        if (strpos($result_decoded->Files[0]->TransactionProductType->ProductType, 'Sale') !== false) {
            $netsheet_for = $this->input->post('req_type');
            $post_data['lenderInsurance'] = 1;
            $post_data['transactionType'] = 'Resale';
            $post_data['transferTaxesCheck'] = 1;

        } else {
            $post_data['netsheet_for'] = '';
            $post_data['lenderInsurance'] = 0;
            $post_data['transactionType'] = 'Re-Finance';
            $post_data['transferTaxesCheck'] = 0;
        }
        $post_data['escrowPriceCheck'] = 1;
        $post_data['recordingPriceCheck'] = 1;

        $ch = curl_init(env('CALC_API_URL') . 'index.php?welcome/createNetsheetDoc');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . env('PCT_CALC_TOKEN'),
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($post_data)))
        );
        $error_msg = curl_error($ch);
        $calcResult = json_decode(curl_exec($ch), true);

        $calcResult['transactionType'] = $post_data['transactionType'];
        //echo "<pre>";
        //print_r($calcResult);exit;
        $data['calcResult'] = $calcResult;
        $data['order_number'] = isset($orderDetails['file_number']) && !empty($orderDetails['file_number']) ? $orderDetails['file_number'] : '';
        $data['full_address'] = isset($orderDetails['full_address']) && !empty($orderDetails['full_address']) ? $orderDetails['full_address'] : '';
        $data['sales_amount'] = $salesAmount;
        $data['loan_amount'] = $loanAmount;

        // $this->load->view('layout/head_dashboard', $data);
        // $this->load->view('order/mail_fees');

        $data['displaySidebar'] = false;
        $this->salesdashboardtemplate->show("order", "mail_fees", $data);
    }

    public function proposedInsured()
    {
        $data['errors'] = array();
        $data['success'] = array();

        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }

        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $random_number = $this->uri->segment(2);
        $order = $this->getOrderInfo($random_number);
        $fileId = $order[0]['file_id'];
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;
        $orderDetails = $this->order->get_order_details($fileId, 1);

        $data['file_number'] = $orderDetails['file_number'];
        $data['full_address'] = $orderDetails['full_address'];
        $data['created'] = !empty($orderDetails['proposed_document_created_date']) ? date("m/d/Y", strtotime($orderDetails['proposed_document_created_date'])) : '';

        if (!empty($orderDetails['proposed_insured_document_name'])) {
            $file_id = $orderDetails['file_id'];
            $documentName = $orderDetails['proposed_insured_document_name'];
            if (env('AWS_ENABLE_FLAG') == 1) {
                $documentUrl = env('AWS_PATH') . "proposed-insured/" . $documentName;
                // $action = "<a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"proposed_insured"' . ");'><button class='btn btn-grad-2a' type='button' style='background: #d35411;'>Download</button></a>";
                $action = '<div style="display:flex;justify-content: space-around; width: 100%;" ><a href="' . $documentUrl . '" target="_blanck"  onclick="downloadDocumentFromAws(' . $documentUrl . ',' . 'proposed_insured' . ');" type="button" title="Download" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="fas fa-download"></i></span><span class="text">Download</span></a>';
            } else {
                $documentUrl = FCPATH . 'uploads/proposed-insured/' . $documentName;
                // $action = '<a href="' . $documentUrl . '" download><button class="btn btn-grad-2a" type="button" style="background: #d35411;">Download</button></a>';
                $action = '<div style="display:flex;justify-content: space-around; width: 100%;" ><a href="' . $documentUrl . '"  target="_blanck" download type="button" title="Download" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="fas fa-download"></i></span><span class="text">Download</span></a>';
            }
        } else {
            // $action = '<a href="javascript:void(0);" onclick="generateProposedInsured(' . $orderDetails['file_id'] . ');"><button class="btn btn-grad-2a button-color" type="button">Generate</button></a>';
            $action = '<div style="display:flex;justify-content: space-around; width: 100%;" ><a href="javascript:void(0);" onclick="generateProposedInsured(' . $orderDetails['file_id'] . ');" type="button" title="Generate" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="fas fa-seedling"></i></span><span class="text">Generate</span></a>';
        }

        // $action .= '<a href="javascript:void(0);" onclick="editInformation(' . $orderDetails['file_id'] . ');"><button class="btn btn-grad-2a button-color" type="button">Edit</button></a>';
        $action .= '<a href="javascript:void(0);" onclick="editInformation(' . $orderDetails['file_id'] . ');" class="btn btn-primary btn-icon-split" ><span class="icon text-white-50"><i class="fas fa-edit"></i></span><span class="text">Edit</span></a></div>';

        $data['action'] = $action;
        $condition = array(
            'where' => array(
                'status' => 1,
            ),
        );

        $data['titleOfficer'] = $this->titleOfficer->getTitleOfficerDetails($condition);
        $data['proposedBranches'] = $this->order->getProposedBranches();

        // $this->load->view('layout/head_dashboard', $data);
        // $this->load->view('order/mail_proposed_insured', $data);
        $data['displaySidebar'] = false;
        $this->salesdashboardtemplate->show("order", "mail_proposed_insured", $data);
    }

    public function borrowerInformation()
    {
        $random_number = $this->uri->segment(2);
        if ($random_number == 'seller') {
            $sellerFlag = 1;
            $random_number = $this->uri->segment(3);
        } else {
            $sellerFlag = 0;
        }

        $condition = array(
            'where' => array(
                'random_number' => $random_number,
            ),
        );
        $order = $this->order->get_order($condition);

        if (!empty($order)) {
            $orderNumber = isset($order[0]['file_number']) && !empty($order[0]['file_number']) ? $order[0]['file_number'] : '';
            $fileId = isset($order[0]['file_id']) && !empty($order[0]['file_id']) ? $order[0]['file_id'] : '';
            $data['sellerFlag'] = $sellerFlag;
            $orderDetails = $this->order->get_order_details($fileId, 1);
            if ($sellerFlag) {
                $borrower_info_submitted = $order[0]['borrower_info_submitted_for_seller'];
                $is_code_verified = $order[0]['is_code_verified_for_seller'];
                $borrower_mobile_number = isset($orderDetails['borrower_mobile_number_for_seller']) && !empty($orderDetails['borrower_mobile_number_for_seller']) ? $orderDetails['borrower_mobile_number_for_seller'] : '';
            } else {
                $borrower_info_submitted = $order[0]['borrower_info_submitted'];
                $is_code_verified = $order[0]['is_code_verified'];
                $borrower_mobile_number = isset($orderDetails['borrower_mobile_number']) && !empty($orderDetails['borrower_mobile_number']) ? $orderDetails['borrower_mobile_number'] : '';
            }

            if (!empty($orderDetails['escrow_officer_id'])) {
                $escrowOfficerCon = array(
                    'where' => array(
                        'partner_id' => $orderDetails['escrow_officer_id'],
                    ),
                );
                $escrowOfficerData = $this->home_model->get_company_rows($escrowOfficerCon);
                $data['escrow_officer'] = $escrowOfficerData[0]['partner_name'];
            } else {
                $data['escrow_officer'] = '';
            }

            if ($orderDetails['sales_amount'] > 0) {
                if ($sellerFlag) {
                    if (!empty($orderDetails['primary_owner'])) {
                        $borrowerNameInfo = explode(" ", $orderDetails['primary_owner']);
                        if (count($borrowerNameInfo) == 3) {
                            $data['borrower_first_name'] = $borrowerNameInfo[0];
                            $data['borrower_middle_name'] = $borrowerNameInfo[1];
                            $data['borrower_last_name'] = $borrowerNameInfo[2];
                        } else if (count($borrowerNameInfo) == 2) {
                            $data['borrower_first_name'] = $borrowerNameInfo[0];
                            $data['borrower_middle_name'] = '';
                            $data['borrower_last_name'] = $borrowerNameInfo[1];
                        } else {
                            $data['borrower_first_name'] = '';
                            $data['borrower_middle_name'] = '';
                            $data['borrower_last_name'] = '';
                        }
                    } else {
                        $data['borrower_first_name'] = '';
                        $data['borrower_middle_name'] = '';
                        $data['borrower_last_name'] = '';
                    }
                } else {
                    if (!empty($orderDetails['borrower'])) {
                        $borrowerNameInfo = explode(" ", $orderDetails['borrower']);
                        if (count($borrowerNameInfo) == 3) {
                            $data['borrower_first_name'] = $borrowerNameInfo[0];
                            $data['borrower_middle_name'] = $borrowerNameInfo[1];
                            $data['borrower_last_name'] = $borrowerNameInfo[2];
                        } else if (count($borrowerNameInfo) == 2) {
                            $data['borrower_first_name'] = $borrowerNameInfo[0];
                            $data['borrower_middle_name'] = '';
                            $data['borrower_last_name'] = $borrowerNameInfo[1];
                        } else {
                            $data['borrower_first_name'] = '';
                            $data['borrower_middle_name'] = '';
                            $data['borrower_last_name'] = '';
                        }
                    } else {
                        $data['borrower_first_name'] = '';
                        $data['borrower_middle_name'] = '';
                        $data['borrower_last_name'] = '';
                    }
                }
            } else {
                if (!empty($orderDetails['primary_owner'])) {
                    $borrowerNameInfo = explode(" ", $orderDetails['primary_owner']);
                    if (count($borrowerNameInfo) == 3) {
                        $data['borrower_first_name'] = $borrowerNameInfo[0];
                        $data['borrower_middle_name'] = $borrowerNameInfo[1];
                        $data['borrower_last_name'] = $borrowerNameInfo[2];
                    } else if (count($borrowerNameInfo) == 2) {
                        $data['borrower_first_name'] = $borrowerNameInfo[0];
                        $data['borrower_middle_name'] = '';
                        $data['borrower_last_name'] = $borrowerNameInfo[1];
                    } else {
                        $data['borrower_first_name'] = '';
                        $data['borrower_middle_name'] = '';
                        $data['borrower_last_name'] = '';
                    }
                } else {
                    $data['borrower_first_name'] = '';
                    $data['borrower_middle_name'] = '';
                    $data['borrower_last_name'] = '';
                }
            }

            if ($borrower_info_submitted == 1) {
                $data['is_borrower_info_submitted'] = 1;
                $propertyAddress = isset($orderDetails['full_address']) && !empty($orderDetails['full_address']) ? $orderDetails['full_address'] : '';
                $data['mail_dashboard'] = 1;
                $data['order_id'] = $order[0]['id'];
                $data['file_id'] = $fileId;
                $data['propertyAddress'] = $propertyAddress;
                $data['errors'] = array();
                $data['success'] = array();
                if ($this->session->userdata('errors')) {
                    $data['errors'] = $this->session->userdata('errors');
                    $this->session->unset_userdata('errors');
                }
                if ($this->session->userdata('success')) {
                    $data['success'] = $this->session->userdata('success');
                    $this->session->unset_userdata('success');
                }
                $borrowerInfo = $this->order->get_borrower_info($order[0]['id'], $sellerFlag ? 0 : 1);
                $borrowerResidenceInfo = $this->order->get_borrower_residence_info($order[0]['id'], $sellerFlag ? 0 : 1);
                $data['borrower_name'] = $borrowerInfo['first_name'] . " " . $borrowerInfo['middle_name'] . " " . $borrowerInfo['last_name'];
                $data['borrower_address'] = $borrowerResidenceInfo[0]['address'];
                $this->load->view('layout/head_dashboard', $data);
                $this->load->view('order/borrower', $data);
            } else if ($is_code_verified == 1 && $borrower_info_submitted == 0) {
                $propertyAddress = isset($orderDetails['full_address']) && !empty($orderDetails['full_address']) ? $orderDetails['full_address'] : '';
                $data['is_borrower_info_submitted'] = 0;
                $data['mail_dashboard'] = 1;
                $data['propertyAddress'] = $propertyAddress;
                $data['order_id'] = $order[0]['id'];
                $data['file_id'] = $fileId;
                $data['errors'] = array();
                $data['success'] = array();

                $data['borrower_mobile_number'] = $borrower_mobile_number;
                if ($this->session->userdata('errors')) {
                    $data['errors'] = $this->session->userdata('errors');
                    $this->session->unset_userdata('errors');
                }
                if ($this->session->userdata('success')) {
                    $data['success'] = $this->session->userdata('success');
                    $this->session->unset_userdata('success');
                }
                if ($sellerFlag) {
                    $this->home_model->update(array('is_code_verified_for_seller' => 0), array('file_id' => $fileId), 'order_details');
                } else {
                    $this->home_model->update(array('is_code_verified' => 0), array('file_id' => $fileId), 'order_details');
                }
                $this->load->view('layout/head_dashboard', $data);
                $this->load->view('order/borrower', $data);
            } else {
                $propertyAddress = isset($orderDetails['full_address']) && !empty($orderDetails['full_address']) ? $orderDetails['full_address'] : '';
                $data['orderNumber'] = $orderNumber;
                $data['propertyAddress'] = $propertyAddress;
                $data['randomNumber'] = $random_number;
                $data['fileId'] = $fileId;
                $data['borrower_mobile_number'] = $borrower_mobile_number;
                $this->load->view('order/borrower_login', $data);
            }
        } else {
            redirect(base_url() . 'order');
        }
    }

    public function generate_verification_code()
    {
        $phoneNumber = $this->input->post('phone_number');
        $is_seller = $this->input->post('is_seller');

        if (isset($phoneNumber) && !empty($phoneNumber)) {
            $randomNumber = $this->input->post('random_number');
            $code = rand(10000000, 99999999);
            $sid = env('TWILIO_SID');
            $token = env('TWILIO_TOKEN');
            $from = env('TWILIO_FROM');
            $message = "Your Pacific Coast Safe Wire code is: " . $code;
            $logid = $this->apiLogs->syncLogs('', 'twilio', 'send_message', '', array('message' => $message, 'account_sid' => $sid, 'token' => $token, 'to' => '', 'from' => $from), array(), 0, 0);

            try {
                $result = $this->twilio->message($phoneNumber, $message, '', array('from' => $from));
                $response = $result->toArray();
                $response['msg_status'] = 'success';
                $response['code'] = $code;

            } catch (Exception $e) {
                $response['sid'] = '';
                $response['to'] = $phoneNumber;
                $response['msg_status'] = 'error';
                $response['errorCode'] = $e->getCode();
                $response['errorMessage'] = $e->getMessage();
            } catch (\Twilio\Exceptions\RestException $e) {
                $response['sid'] = '';
                $response['to'] = $phoneNumber;
                $response['msg_status'] = 'error';
                $response['errorCode'] = $e->getCode();
                $response['errorMessage'] = $e->getMessage();
            }

            $this->apiLogs->syncLogs('', 'twilio', 'send_message', '', array('code' => $code, 'account_sid' => $sid, 'token' => $token, 'to' => '', 'from' => $from), $response, 0, $logid);

            if ($response['msg_status'] == 'success') {

                if ($is_seller) {
                    $this->home_model->update(array('borrower_mobile_number_for_seller' => $phoneNumber, 'verification_code_for_seller' => $code, 'is_code_verified_for_seller' => 0, 'code_created_at_for_seller' => date("Y-m-d H:i:s")), array('random_number' => $randomNumber), 'order_details');
                } else {
                    $this->home_model->update(array('borrower_mobile_number' => $phoneNumber, 'verification_code' => $code, 'is_code_verified' => 0, 'code_created_at' => date("Y-m-d H:i:s")), array('random_number' => $randomNumber), 'order_details');
                }

                $data = array(
                    'message' => $response['body'],
                    'sent_from' => $response['from'],
                    'sent_to' => $response['to'],
                    'status' => $response['status'],
                    'message_sid' => $response['sid'],
                    'error_code' => $response['errorCode'],
                    'error_message' => $response['errorMessage'],
                );

                $this->twilioMessage->insert($data);
                $result = array('msg_status' => 'success', 'message' => 'Code generated successfully.');
            } else {
                $result = array('msg_status' => 'error', 'error_message' => $response['errorMessage']);
            }
        } else {
            $result = array('msg_status' => 'error', 'error_message' => 'Please enter phone number.');
        }
        echo json_encode($result);exit;
    }

    public function borrowerInfoSubmit()
    {
        $errors = array();
        $success = array();
        $condition = array(
            'where' => array(
                'id' => $this->input->post('order_id'),
            ),
        );
        $order = $this->order->get_order($condition);
        $is_seller = $this->input->post('is_seller');
        $fileId = isset($order[0]['file_id']) && !empty($order[0]['file_id']) ? $order[0]['file_id'] : '';
        $orderDetails = $this->order->get_order_details($fileId, 1);
        $borrowerInfoData = array(
            'first_name' => $this->input->post('firstname'),
            'middle_name' => $this->input->post('middlename'),
            'last_name' => $this->input->post('lastname'),
            'mobile' => $this->input->post('mobile'),
            //'telephone' => $this->input->post('telephone'),
            'date_of_birth' => $this->input->post('date_of_birth'),
            //'birthplace' => $this->input->post('birthplace'),
            'ssn' => $this->input->post('ssn'),
            'email' => $this->input->post('email'),
            'dln' => $this->input->post('dln'),
            'status' => $this->input->post('status'),
            'spouse_first_name' => $this->input->post('spouse_firstname'),
            'spouse_middle_name' => $this->input->post('spouse_middlename'),
            'spouse_last_name' => $this->input->post('spouse_lastname'),
            'spouse_mobile' => $this->input->post('spouse_mobile'),
            //'spouse_telephone' => $this->input->post('spouse_telephone'),
            //'spouse_date_of_birth' => $this->input->post('spouse_date_of_birth'),
            //'spouse_birthplace' => $this->input->post('spouse_birthplace'),
            'spouse_ssn' => $this->input->post('spouse_ssn'),
            //'spouse_dln' => $this->input->post('spouse_dln'),
            'partner_first_name' => $this->input->post('partner_firstname'),
            'partner_middle_name' => $this->input->post('partner_middlename'),
            'partner_last_name' => $this->input->post('partner_lastname'),
            'partner_mobile' => $this->input->post('partner_mobile'),
            //'partner_telephone' => $this->input->post('partner_telephone'),
            //'partner_date_of_birth' => $this->input->post('partner_date_of_birth'),
            //'partner_birthplace' => $this->input->post('partner_birthplace'),
            'partner_ssn' => $this->input->post('partner_ssn'),
            //'partner_dln' => $this->input->post('partner_dln'),
            'order_id' => $this->input->post('order_id'),
            //'partnership_status' => $this->input->post('partnership_status'),
            //'prior_spouse_name' => $this->input->post('partnership_status') == 'both' ? $this->input->post('prior_spouse_name_both') : $this->input->post('prior_spouse_name'),
            //'prior_spouse_reason' => $this->input->post('partnership_status') == 'both' ? $this->input->post('prior_spouse_reason_both') : $this->input->post('prior_spouse_reason'),
            //'prior_spouse_end' => $this->input->post('partnership_status') == 'both' ? $this->input->post('prior_spouse_end_both') : $this->input->post('prior_spouse_end'),
            //'current_spouse_prior_spouse_name' => $this->input->post('partnership_status') == 'both' ? $this->input->post('current_spouse_prior_spouse_name_both') : $this->input->post('current_spouse_prior_spouse_name'),
            //'current_spouse_prior_spouse_reason' => $this->input->post('partnership_status') == 'both' ? $this->input->post('current_spouse_prior_spouse_reason_both') : $this->input->post('current_spouse_prior_spouse_reason'),
            //'current_spouse_prior_spouse_end' => $this->input->post('partnership_status') == 'both' ? $this->input->post('current_spouse_prior_spouse_end_both') : $this->input->post('current_spouse_prior_spouse_end'),
            'street_address' => $this->input->post('street_address'),
            'buyer_intends_to_reside' => $this->input->post('buyer_intends'),
            'land_is_unimproved' => $this->input->post('land_is_unimproved'),
            'type_of_property' => $this->input->post('type_of_property'),
            'work_done_last_6_month' => $this->input->post('work_done_last_6_month'),
            'previously_married' => $this->input->post('previously_married'),
            'general_terms' => 1,
            'signature' => $this->input->post('signature'),
            'is_buyer' => $this->input->post('is_seller') == 1 ? 0 : 1,
            'spouse_signature' => $this->input->post('spouse_signature'),
            'created_at' => date('Y-m-d H:i:s'),
        );
        $borrowerId = $this->home_model->insert($borrowerInfoData, 'pct_order_borrower_info');

        $residence_addresses = $this->input->post('residence_addresses');
        $residence_from_dates = $this->input->post('residence_from_dates');
        $residence_to_dates = $this->input->post('residence_to_dates');
        $i = 0;

        foreach ($residence_addresses as $residence_address) {
            $borrowerResidenceData = array(
                'address' => $residence_address,
                'from_date' => $residence_from_dates[$i],
                'to_date' => $residence_to_dates[$i],
                'order_id' => $this->input->post('order_id'),
                'is_buyer' => $this->input->post('is_seller') == 1 ? 0 : 1,
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->home_model->insert($borrowerResidenceData, 'pct_order_borrower_residence_info');
            $i++;
        }

        $employment_status = $this->input->post('employment_status');
        if ($employment_status == 'add_business') {
            $business_names = $this->input->post('business_names');
            $employment_addresses = $this->input->post('employment_addresses');
            $employment_from_dates = $this->input->post('employment_from_dates');
            $employment_to_dates = $this->input->post('employment_to_dates');
            $j = 0;

            foreach ($business_names as $business_name) {
                $borrowerEmploymentData = array(
                    'business_name' => $business_name,
                    'address' => $employment_addresses[$j],
                    'from_date' => $employment_from_dates[$j],
                    'to_date' => $employment_to_dates[$j],
                    'order_id' => $this->input->post('order_id'),
                    'is_partner_info' => 0,
                    'is_buyer' => $this->input->post('is_seller') == 1 ? 0 : 1,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $this->home_model->insert($borrowerEmploymentData, 'pct_order_borrower_employment_info');
                $j++;
            }

            /*$partner_business_names = $this->input->post('partner_business_names');
        $partner_addresses = $this->input->post('partner_addresses');
        $partner_from_dates = $this->input->post('partner_from_dates');
        $partner_to_dates = $this->input->post('partner_to_dates');
        $k = 0;

        if(!empty($partner_business_names)) {
        foreach($partner_business_names as $partner_business_name) {
        $borrowerEmploymentPartnerData = array(
        'business_name' => $partner_business_name,
        'address' => $partner_addresses[$k],
        'from_date' => $partner_from_dates[$k],
        'to_date' => $partner_to_dates[$k],
        'order_id' => $this->input->post('order_id'),
        'is_partner_info' => 1,
        'created_at' => date('Y-m-d H:i:s'),
        );
        $this->home_model->insert($borrowerEmploymentPartnerData, 'pct_order_borrower_employment_info');
        $k++;
        }
        }*/
        }

        if ($is_seller == 1) {
            $this->home_model->update(array('borrower_info_submitted_for_seller' => 1), array('random_number' => $order[0]['random_number']), 'order_details');
        } else {
            $this->home_model->update(array('borrower_info_submitted' => 1), array('random_number' => $order[0]['random_number']), 'order_details');
        }

        /* Generate PDF */

        $borrower_info = $this->order->get_borrower_info($this->input->post('order_id'), $this->input->post('is_seller') == 1 ? 0 : 1);

        $borrower_residence_info = $this->order->get_borrower_residence_info($this->input->post('order_id'), $this->input->post('is_seller') == 1 ? 0 : 1);

        $borrower_employment_info = $this->order->get_borrower_employment_info($this->input->post('order_id'), $this->input->post('is_seller') == 1 ? 0 : 1);

        $borrower_data['borrower_info'] = isset($borrower_info) && !empty($borrower_info) ? $borrower_info : array();

        $borrower_data['borrower_residence_info'] = isset($borrower_residence_info) && !empty($borrower_residence_info) ? $borrower_residence_info : array();

        $borrower_data['borrower_employment_info'] = isset($borrower_employment_info) && !empty($borrower_employment_info) ? $borrower_employment_info : array();

        $this->load->library('m_pdf');

        $html = $this->load->view('order/borrower_pdf', $borrower_data, true);

        $stylesheet = file_get_contents('assets/frontend/css/bootstrap.min.css');

        $customCss = '@media print { @page { size: auto; } }';

        $combinedCss = $stylesheet . $customCss;

        $this->m_pdf->pdf->WriteHTML($combinedCss, 1); // CSS Script goes here.
        $this->m_pdf->pdf->WriteHTML($html, 2);
        $this->load->model('order/document');
        $borrowerDocumentCount = $this->document->countBorrowerDocument($orderDetails['order_id']);
        $document_name = "borrower_" . $borrowerDocumentCount . "_" . $fileId . ".pdf";
        if (!is_dir('uploads/borrower-information')) {
            mkdir('./uploads/borrower-information', 0777, true);
        }

        $pdfFilePath = './uploads/borrower-information/' . $document_name;
        $this->m_pdf->pdf->Output($pdfFilePath, 'F');

        $contents = file_get_contents($pdfFilePath);
        $binaryData = base64_encode($contents);

        $this->home_model->update(array('borrower_information_document_name' => $document_name), array('file_id' => $fileId), 'order_details');

        $this->uploadBorrowerDocumentToResware($document_name, $orderDetails, $binaryData);
        $this->order->uploadDocumentOnAwsS3($document_name, 'borrower-information');
        /* Generate PDF */

        $success[] = "Borrower form submitted successfully.";
        $endPoint = 'files/' . $fileId . '/actions';
        $user_data['admin_api'] = 1;
        $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, array(), array(), 0, 0);
        $res = $this->resware->make_request('GET', $endPoint, array(), $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'get_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, array(), $res, 0, $logid);
        $result = json_decode($res, true);

        if (isset($result['Actions']) && !empty($result['Actions'])) {
            $array_keymap = $this->order->array_recursive_search_key_map(108, $result['Actions']);
            if (!empty($array_keymap)) {
                $actionData = array(
                    'StartTask' => array(
                        'CoordinatorTypeID' => 19,
                        'DueDate' => '/Date(' . (strtotime(date('Y-m-d H:i:s')) * 1000) . '-0000)/',
                    ),
                    'CompleteTask' => array(
                        'CoordinatorTypeID' => 30,
                        'DueDate' => '/Date(' . (strtotime("+1 day", strtotime(date('Y-m-d H:i:s'))) * 1000) . '-0000)/',
                    ),
                );
                $endPoint = 'files/' . $fileId . '/actions/' . $result['Actions'][$array_keymap[0]]['FileActionID'];
                $user_data['admin_api'] = 1;
                $actionData = json_encode($actionData);
                $logid = $this->apiLogs->syncLogs(0, 'resware', 'update_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, array(), $this->input->post('order_id'), 0);
                $res = $this->resware->make_request('PUT', $endPoint, $actionData, $user_data);
                $this->apiLogs->syncLogs(0, 'resware', 'update_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, $res, $this->input->post('order_id'), $logid);
                $result = json_decode($res, true);
            } else {
                $actionData = array(
                    'ActionType' => array(
                        'ActionTypeID' => 108,
                    ),
                    'Group' => array(
                        'ActionGroupID' => 6,
                    ),
                    'StartTask' => array(
                        'CoordinatorTypeID' => 19,
                        'DueDate' => '/Date(' . (strtotime(date('Y-m-d H:i:s')) * 1000) . '-0000)/',
                    ),
                    'CompleteTask' => array(
                        'CoordinatorTypeID' => 30,
                        'DueDate' => '/Date(' . (strtotime("+1 day", strtotime(date('Y-m-d H:i:s'))) * 1000) . '-0000)/',
                    ),
                );
                $endPoint = 'files/' . $fileId . '/actions/';
                $user_data['admin_api'] = 1;
                $actionData = json_encode($actionData);
                $logid = $this->apiLogs->syncLogs(0, 'resware', 'add_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, array(), $this->input->post('order_id'), 0);
                $res = $this->resware->make_request('POST', $endPoint, $actionData, $user_data);
                $this->apiLogs->syncLogs(0, 'resware', 'add_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, $res, $this->input->post('order_id'), $logid);
                $result = json_decode($res, true);
            }
        }
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        if ($is_seller == 1) {
            redirect(base_url() . '/borrower-information/seller/' . $order[0]['random_number']);
        } else {
            redirect(base_url() . '/borrower-information/' . $order[0]['random_number']);
        }
    }

    public function code_verification()
    {
        $code = $this->input->post('code');
        $is_seller = $this->input->post('is_seller');

        if (isset($code) && !empty($code)) {
            $fileId = $this->input->post('fileId');

            $orderDetails = $this->order->get_order_details($fileId, 1);

            if ($is_seller) {
                $verification_code = isset($orderDetails['verification_code_for_seller']) && !empty($orderDetails['verification_code_for_seller']) ? $orderDetails['verification_code_for_seller'] : '';
                $code_created_at = isset($orderDetails['code_created_at_for_seller']) && !empty($orderDetails['code_created_at_for_seller']) ? $orderDetails['code_created_at_for_seller'] : '';
            } else {
                $verification_code = isset($orderDetails['verification_code']) && !empty($orderDetails['verification_code']) ? $orderDetails['verification_code'] : '';
                $code_created_at = isset($orderDetails['code_created_at']) && !empty($orderDetails['code_created_at']) ? $orderDetails['code_created_at'] : '';
            }

            if ($verification_code == $code) {
                $expire_date = date('Y-m-d H:i', strtotime('+3 minutes', strtotime($code_created_at)));

                $now = date("Y-m-d H:i:s"); //current time

                if ($now > $expire_date) { //if current time is greater then created time
                    $response = array('status' => 'error', 'message' => 'Your verification code has been expired.');
                } else {
                    if ($is_seller) {
                        $this->home_model->update(array('is_code_verified_for_seller' => 1), array('id' => $orderDetails['order_id']), 'order_details');
                    } else {
                        $this->home_model->update(array('is_code_verified' => 1), array('id' => $orderDetails['order_id']), 'order_details');
                    }
                    $response = array('status' => 'success');
                }
            } else {
                $response = array('status' => 'error', 'message' => 'Please enter valid verification code.');
            }

        } else {
            $response = array('status' => 'error', 'message' => 'Please enter verification code.');
        }

        echo json_encode($response);exit;
    }

    public function genericLandingPage()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('order_number', 'Order Number', 'trim|required', array('required' => 'Please enter Order Number'));
            if ($this->form_validation->run($this) == false) {
                $response = array('status' => 'error', 'order_number_php_error' => form_error('order_number'));
                echo json_encode($response);exit;
            } else {
                $order_number = $this->input->post('order_number');
                $condition = array(
                    'where' => array(
                        'file_number' => $order_number,
                    ),
                );
                $order = $this->order->get_order($condition);
                if (!empty($order)) {
                    if (empty($order[0]['random_number'])) {
                        $randomString = $this->order->randomPassword();
                        $randomString = md5($order[0]['id'] . $randomString);
                        $this->home_model->update(array('random_number' => $randomString), array('id' => $order[0]['id']), 'order_details');
                        $order[0]['random_number'] = $randomString;
                    }
                    $response = array('status' => 'success', 'random_number' => $order[0]['random_number'], 'prod_type' => $order[0]['prod_type']);
                    echo json_encode($response);exit;
                } else {
                    $data = json_encode(array('FileNumber' => $order_number));
                    $userData = array(
                        'admin_api' => 1,
                    );
                    $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, array(), 0, 0);
                    $res = $this->resware->make_request('POST', 'files/search', $data, $userData);
                    $this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, $res, 0, $logid);
                    $result = json_decode($res, true);

                    if (isset($result['Files']) && !empty($result['Files'])) {

                        foreach ($result['Files'] as $res) {
                            /* partners details */
                            $partner_fname = $res['Partners'][0]['PrimaryEmployee']['FirstName'];
                            $partner_lname = $res['Partners'][0]['PrimaryEmployee']['LastName'];
                            $partner_name = $res['Partners'][0]['PartnerName'];

                            $condition = array(
                                'first_name' => $partner_fname,
                                'last_name' => $partner_lname,
                                'company_name' => $partner_name,
                                'is_pass' => $partner_name,
                            );
                            $user_details = $this->home_model->get_user_by_name($condition);

                            $customerId = 0;
                            if (isset($user_details) && !empty($user_details)) {
                                $customerId = $user_details['id'];
                            }
                            /* partners details */
                            $FullProperty = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'] . ", " . $res['Properties'][0]['City'] . ", " . $res['Properties'][0]['State'] . ", " . $res['Properties'][0]['Zip'];
                            $address = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'];
                            $locale = $res['Properties'][0]['City'];

                            if (($locale)) {
                                if (!empty($res['Properties'][0]['State'])) {
                                    $locale .= ', ' . $res['Properties'][0]['State'];
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
                                'address' => removeMultipleSpace($address),
                                'city' => $res['Properties'][0]['City'],
                                'state' => $res['Properties'][0]['State'],
                                'zip' => $res['Properties'][0]['Zip'],
                                'property_type' => $property_type,
                                'full_address' => removeMultipleSpace($FullProperty),
                                'apn' => $apn,
                                'county' => $res['Properties'][0]['County'],
                                'legal_description' => $LegalDescription,
                                'status' => 1,
                            );

                            $transactionData = array(
                                'customer_id' => $customerId,
                                'sales_amount' => !empty($res['SalesPrice']) ? $res['SalesPrice'] : 0,
                                'loan_number' => !empty($res['Loans'][0]['LoanNumber']) ? $res['Loans'][0]['LoanNumber'] : 0,
                                'loan_amount' => !empty($res['Loans'][0]['LoanAmount']) ? $res['Loans'][0]['LoanAmount'] : 0,
                                'transaction_type' => $res['TransactionProductType']['TransactionTypeID'],
                                'purchase_type' => $res['TransactionProductType']['ProductTypeID'],
                                'status' => 1,
                            );

                            $primary_owner = ($res['Buyers'][0]['Primary']['First'] && $res['Buyers'][0]['Primary']['First']) ? $res['Buyers'][0]['Primary']['First'] : '';
                            $primary_owner .= ($res['Buyers'][0]['Primary']['Middle'] && $res['Buyers'][0]['Primary']['Middle']) ? " " . $res['Buyers'][0]['Primary']['Middle'] : '';
                            $primary_owner .= ($res['Buyers'][0]['Primary']['Last'] && $res['Buyers'][0]['Primary']['Last']) ? " " . $res['Buyers'][0]['Primary']['Last'] : '';
                            $secondary_owner = ($res['Buyers'][0]['Secondary']['First'] && $res['Buyers'][0]['Secondary']['First']) ? $res['Buyers'][0]['Secondary']['First'] : '';
                            $secondary_owner .= ($res['Buyers'][0]['Secondary']['Middle'] && $res['Buyers'][0]['Secondary']['Middle']) ? $res['Buyers'][0]['Secondary']['Middle'] : '';
                            $secondary_owner .= ($res['Buyers'][0]['Secondary']['Last'] && $res['Buyers'][0]['Secondary']['Last']) ? " " . $res['Buyers'][0]['Secondary']['Last'] : '';
                            $ProductTypeTxt = $res['TransactionProductType']['ProductType'];

                            if (strpos($ProductTypeTxt, 'Loan') !== false) {
                                $propertyData['primary_owner'] = $primary_owner;
                                $propertyData['secondary_owner'] = $secondary_owner;
                                $prod_type = 'loan';
                            } elseif (strpos($ProductTypeTxt, 'Sale') !== false) {
                                $transactionData['borrower'] = $primary_owner;
                                $transactionData['secondary_borrower'] = $secondary_owner;
                                $propertyData['primary_owner'] = isset($property_details['primary_owner']) && !empty($property_details['primary_owner']) ? $property_details['primary_owner'] : '';
                                $propertyData['secondary_owner'] = isset($property_details['secondary_owner']) && !empty($property_details['secondary_owner']) ? $property_details['secondary_owner'] : '';
                                $prod_type = 'sale';
                            }

                            $propertyId = $this->home_model->insert($propertyData, 'property_details');
                            $transactionId = $this->home_model->insert($transactionData, 'transaction_details');
                            $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['OpenedDate']))) / 1000);
                            $created_date = date('Y-m-d H:i:s', $time);
                            $randomString = $this->order->randomPassword();

                            $randomString = md5($randomString);

                            $orderData = array(
                                'customer_id' => $customerId,
                                'file_id' => $res['FileID'],
                                'file_number' => $res['FileNumber'],
                                'property_id' => $propertyId,
                                'transaction_id' => $transactionId,
                                'created_at' => $created_date,
                                'status' => 1,
                                'is_imported' => 1,
                                'is_sales_rep_order' => 0,
                                'random_number' => $randomString,
                                'prod_type' => $prod_type,
                                'resware_status' => strtolower($res['Status']['Name']),
                            );

                            $orderId = $this->home_model->insert($orderData, 'order_details');
                            $response = array('status' => 'success', 'random_number' => $randomString, 'prod_type' => $order[0]['prod_type']);
                            echo json_encode($response);exit;
                        }
                    } else {
                        $response = array('status' => 'error', 'order_number_php_error' => 'Please enter correct order number');
                    }
                    echo json_encode($response);exit;
                }
            }
        } else {
            $this->load->view('order/order_number_cpl');
        }
    }

    public function uploadBorrowerDocumentToResware($document_name, $orderDetails, $binaryData)
    {
        $this->load->model('order/document');
        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');
        $fileSize = filesize('./uploads/borrower-information/' . $document_name);
        $documentData = array(
            'document_name' => $document_name,
            'original_document_name' => $document_name,
            'document_type_id' => 1037,
            'document_size' => $fileSize,
            'user_id' => 0,
            'order_id' => $orderDetails['order_id'],
            'description' => 'Statement Of Information Document',
            'is_sync' => 1,
            'is_prelim_document' => 0,
            'is_cpl_doc' => 0,
            'is_borrower_doc' => 1,
        );
        $documentId = $this->document->insert($documentData);
        $endPoint = 'files/' . $orderDetails['file_id'] . '/documents';
        $documentApiData = array(
            'DocumentName' => $document_name,
            'DocumentType' => array(
                'DocumentTypeID' => 1037,
            ),
            'Description' => 'Statement Of Information Document',
            'InternalOnly' => false,
            'DocumentBody' => $binaryData,
        );
        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

        $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));
        $user_data['email'] = $orderUser['email_address'];
        $user_data['password'] = $orderUser['random_password'];
        $user_data['from_mail'] = 1;

        $logid = $this->apiLogs->syncLogs(0, 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
        $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
        $res = json_decode($result);
        $this->document->update(array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));
    }

    public function getSearchResult($address, $locale)
    {
        $data = new stdClass();
        $data->Address = $address;
        $data->LastLine = (string) $locale;
        $data->ClientReference = '<CustCompFilter><CompNum>8</CompNum><MonthsBack>12</MonthsBack></CustCompFilter>';
        $data->OwnerName = '';
        $data->key = env('BLACK_KNIGHT_KEY');
        $data->ReportType = '187';

        $request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/AddressSearch?';

        $requestUrl = $request . http_build_query($data);

        $getsortedresults = isset($_GET['getsortedresults']) ? $_GET['getsortedresults'] : 'false';

        $opts = array(
            'http' => array(
                'header' => "User-Agent:MyAgent/1.0\r\n",
            ),
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $context = stream_context_create($opts);
        $file = file_get_contents($requestUrl, false, $context);
        $xmlData = simplexml_load_string($file);
        $response = json_encode($xmlData);
        $result = json_decode($response, true);
        $property_info = array();
        if (isset($result['Status']) && !empty($result['Status']) && $result['Status'] == 'OK') {
            $reportUrl = (isset($result['ReportURL']) && !empty($result['ReportURL'])) ? $result['ReportURL'] : '';

            if ($reportUrl) {
                $rdata = new stdClass();
                $rdata->key = env('BLACK_KNIGHT_KEY');
                $requestUrl = $reportUrl . http_build_query($rdata);
                $reportFile = file_get_contents($requestUrl, false, $context);
                $reportData = simplexml_load_string($reportFile);
                $response = json_encode($reportData);
                $details = json_decode($response, true);

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

    public function tpCreateService($methodId, $random_number, $propertyData = array(), $userdata = array())
    {
        /* Insert into table */
        if ($random_number) {
            $session_id = 'tp_api_id_' . $random_number;

            $con = array(
                'where' => array(
                    'session_id' => $session_id,
                ),
                'returnType' => 'count',
            );

            $prevCount = $this->titlePointData->gettitlePointDetails($con);
            if ($prevCount < 0) {
                $tpData = array(
                    'session_id' => $session_id,
                );
                $tpId = $this->titlePointData->insert($tpData);
            }

        }
        /* Insert into table */

        $requestParams = array(
            'userID' => env('TP_USERNAME'),
            'password' => env('TP_PASSWORD'),
            'orderNo' => '',
            'customerRef' => '',
            'company' => '',
            'department' => '',
            'titleOfficer' => '',
            'orderComment' => '',
            'starterRemarks' => '',
        );

        if ($methodId == 4) {
            $fipsCode = isset($propertyData['fipsCode']) && !empty($propertyData['fipsCode']) ? $propertyData['fipsCode'] : '';
            $address = isset($propertyData['address']) && !empty($propertyData['address']) ? $propertyData['address'] : '';
            $city = isset($propertyData['city']) && !empty($propertyData['city']) ? $propertyData['city'] : '';
            $unit_no = isset($propertyData['unit_no']) && !empty($propertyData['unit_no']) ? $propertyData['unit_no'] : '';
            $apn = isset($propertyData['apn']) && !empty($propertyData['apn']) ? $propertyData['apn'] : '';
            if ($unit_no) {
                $unitinfo = 'UnitNumber ' . $unit_no . ', ';
            }

            $requestParams['serviceType'] = env('SERVICE_TYPE');

            $requestParams['parameters'] = 'Address1=' . $address . ';City=' . $city . ';Pin=' . $apn . ';LvLookup=Address;LvLookupValue=' . $address . ', ' . $unitinfo . $city . ';LvReportFormat=LV;IncludeTaxAssessor=true';
            $requestParams['fipsCode'] = $fipsCode;
            $requestUrl = env('TP_CREATE_SERVICE_ENDPOINT');
            $request_type = 'sales_create_service_4';
        } else if ($methodId == 3) {
            $apn = isset($propertyData['apn']) && !empty($propertyData['apn']) ? $propertyData['apn'] : '';
            $apn = str_replace('0000', '0-000', $apn);

            $state = isset($propertyData['state']) && !empty($propertyData['state']) ? $propertyData['state'] : '';

            $county = isset($propertyData['county']) && !empty($propertyData['county']) ? $propertyData['county'] : '';

            $requestParams['serviceType'] = env('TAX_SEARCH_SERVICE_TYPE');

            $requestParams['parameters'] = 'Tax.APN=' . $apn . ';General.AutoSearchTaxes=true;General.AutoSearchProperty=false';
            $requestParams['state'] = $state;
            $requestParams['county'] = $county;
            $requestUrl = env('TP_TAX_INSTRUMENT_CREATE_SERVICE_ENDPOINT');
            $request_type = 'sales_create_service_3';
        }
        $request = $requestUrl . http_build_query($requestParams);

        $logid = $this->apiLogs->syncLogs(0, 'titlepoint', $request_type, $request, $requestParams, array(), $random_number, 0);

        $opts = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        $context = stream_context_create($opts);
        $file = file_get_contents($request, false, $context);

        $xmlData = simplexml_load_string($file);
        $response = json_encode($xmlData);
        $result = json_decode($response, true);

        $this->apiLogs->syncLogs(0, 'titlepoint', $request_type, $request, $requestParams, $result, $random_number, $logid);
        $session_id = 'tp_api_id_' . $random_number;
        $con = array(
            'where' => array(
                'session_id' => $session_id,
            ),
            'returnType' => 'count',
        );
        $prevCount = $this->titlePointData->gettitlePointDetails($con);

        if (isset($result) && empty($result)) {
            $tpData = array(
                'cs4_message' => 'Failed',
            );

            if ($prevCount > 0) {
                $session_id = 'tp_api_id_' . $random_number;
                $condition = array(
                    'session_id' => $session_id,
                );
                $this->titlePointData->update($tpData, $condition);
            } else {
                $tpData['session_id'] = 'tp_api_id_' . $random_number;

                $tpId = $this->titlePointData->insert($tpData);
            }
        } else {

            $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';

            if ($methodId == 4) {
                if ($responseStatus == 'Success') {
                    $requestId = isset($result['RequestID']) && !empty($result['RequestID']) ? $result['RequestID'] : '';
                    $tpData = array(
                        'cs4_request_id' => $requestId,
                    );

                    if ($prevCount > 0) {
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);
                    }

                    /* Get Request Summary */
                    $response = $this->tpGetRequestSummaries(4, $requestId, $random_number);
                    /* Get Request Summary */
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }

            }
            if ($methodId == 3) {
                if ($responseStatus == 'Success') {
                    $requestId = isset($result['RequestID']) && !empty($result['RequestID']) ? $result['RequestID'] : '';

                    $tpData = array(
                        'cs3_request_id' => $requestId,
                    );

                    if ($prevCount > 0) {
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);
                    }

                    /* Get Request Summary */
                    $response = $this->tpGetRequestSummaries(3, $requestId, $random_number);
                    /* Get Request Summary */
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }
        }
    }

    public function addLogs($methodId, $returnStatus, $status = '', $error, $random_number)
    {
        if ($returnStatus == 'Failed') {
            if ($methodId == 4) {
                $tpData = array(
                    'cs4_message' => $error,
                );
            } elseif ($methodId == 3) {
                $tpData = array(
                    'cs3_message' => $error,
                );
            }

        } else {
            if ($methodId == 4) {
                $tpData = array(
                    'cs4_message' => $status,
                );
            } elseif ($methodId == 3) {
                $tpData = array(
                    'cs3_message' => $status,
                );
            }
        }

        $session_id = 'tp_api_id_' . $random_number;
        $con = array(
            'where' => array(
                'session_id' => $session_id,
            ),
            'returnType' => 'count',
        );
        $prevCount = $this->titlePointData->gettitlePointDetails($con);

        if ($prevCount > 0) {
            $condition = array(
                'session_id' => $session_id,
            );
            $this->titlePointData->update($tpData, $condition);
        } else {
            $tpData['session_id'] = 'tp_api_id_' . $random_number;

            $tpId = $this->titlePointData->insert($tpData);
        }

    }

    public function tpGetRequestSummaries($methodId, $requestId, $random_number)
    {
        $requestParams = array(
            'userID' => env('TP_USERNAME'),
            'password' => env('TP_PASSWORD'),
            'company' => '',
            'department' => '',
            'titleOfficer' => '',
            'requestId' => $requestId,
            'maxWaitSeconds' => 20,
        );

        $request = env('TP_REQUEST_SUMMARY_ENDPOINT') . http_build_query($requestParams);

        $logid = $this->apiLogs->syncLogs(0, 'titlepoint', 'get_request_summary_' . $methodId, $request, $requestParams, array(), $random_number, 0);

        $opts = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $context = stream_context_create($opts);
        $file = file_get_contents($request, false, $context);
        $xmlData = simplexml_load_string($file);
        $response = json_encode($xmlData);
        $result = json_decode($response, true);

        $this->apiLogs->syncLogs(0, 'titlepoint', 'sales_get_request_summary_' . $methodId, $request, $requestParams, $result, $random_number, $logid);

        $session_id = 'tp_api_id_' . $random_number;

        $con = array(
            'where' => array(
                'session_id' => $session_id,
            ),
            'returnType' => 'count',
        );
        $prevCount = $this->titlePointData->gettitlePointDetails($con);

        if (isset($result) && empty($result)) {
            $tpData = array(
                'cs4_message' => 'Failed',
            );

            if ($prevCount > 0) {
                $condition = array(
                    'session_id' => $session_id,
                );
                $this->titlePointData->update($tpData, $condition);
            } else {
                $tpData['session_id'] = 'tp_api_id_' . $random_number;

                $tpId = $this->titlePointData->insert($tpData);
            }
        } else {
            $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';

            $session_data = array();

            if ($methodId == 4) {
                if ($responseStatus == 'Success') {
                    $status = isset($result['RequestSummaries']['RequestSummary']['Status']) && !empty($result['RequestSummaries']['RequestSummary']['Status']) ? $result['RequestSummaries']['RequestSummary']['Status'] : '';

                    if ($status == 'Complete') {
                        $resultId = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail'][0]['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail'][0]['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail'][0]['ID'] : '';
                        $serviceId = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID'] : '';

                        $tpData = array(
                            'cs4_result_id' => $resultId,
                            'cs4_service_id' => $serviceId,
                        );
                    } else {
                        $tpData = array(
                            'cs4_message' => $status,
                        );
                    }

                    if ($prevCount > 0) {
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);
                    }

                    if ($status == 'Complete') {
                        $this->tpGetResultById(4, $resultId, $random_number);
                    }

                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }
            if ($methodId == 3) {
                if ($responseStatus == 'Success') {
                    $status = isset($result['RequestSummaries']['RequestSummary']['Status']) && !empty($result['RequestSummaries']['RequestSummary']['Status']) ? $result['RequestSummaries']['RequestSummary']['Status'] : '';

                    if ($status == 'Complete') {
                        $resultId = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail']['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail']['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail']['ID'] : '';
                        $serviceId = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID'] : '';
                        $tpData = array(
                            'cs3_result_id' => $resultId,
                            'cs3_service_id' => $serviceId,
                        );
                    } else {
                        $tpData = array(
                            'cs3_message' => $status,
                        );
                    }

                    if ($prevCount > 0) {
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);
                    }
                    if ($status == 'Complete') {
                        $this->tpGetResultById(3, $resultId, $random_number);
                    }
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }
        }
    }

    public function tpGetResultById($methodId, $resultId, $random_number)
    {
        $requestParams = array(
            'userID' => env('TP_USERNAME'),
            'password' => env('TP_PASSWORD'),
            'company' => '',
            'department' => '',
            'titleOfficer' => '',
            'resultID' => $resultId,
        );

        $resultUrl = env('TP_GET_RESULT_BY_ID');

        if ($methodId == 3) {
            $requestParams['requestingTPXML'] = 'true';
            $resultUrl = env('TP_GET_RESULT_BY_ID_3');
        }

        $request = $resultUrl . http_build_query($requestParams);

        $logid = $this->apiLogs->syncLogs(0, 'titlepoint', 'sales_get_result_by_id_' . $methodId, $request, $requestParams, array(), $random_number, 0);

        $opts = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $context = stream_context_create($opts);
        $file = file_get_contents($request, false, $context);

        $xmlData = simplexml_load_string($file);
        $response = json_encode($xmlData);
        $result = json_decode($response, true);

        $this->apiLogs->syncLogs(0, 'titlepoint', 'sales_get_result_by_id_' . $methodId, $request, $requestParams, $result, $random_number, $logid);

        $session_id = 'tp_api_id_' . $random_number;

        $con = array(
            'where' => array(
                'session_id' => $session_id,
            ),
            'returnType' => 'count',
        );
        $prevCount = $this->titlePointData->gettitlePointDetails($con);

        if (isset($result) && empty($result)) {
            $tpData = array(
                'cs4_message' => 'Failed',
            );

            if ($prevCount > 0) {
                $condition = array(
                    'session_id' => $session_id,
                );
                $this->titlePointData->update($tpData, $condition);
            } else {
                $tpData['session_id'] = 'tp_api_id_' . $random_number;

                $tpId = $this->titlePointData->insert($tpData);
            }
        } else {
            $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';
            $session_data = array();

            if ($methodId == 4) {
                if ($responseStatus == 'Success') {
                    $briefLegal = isset($result['Result']['BriefLegal']) && !empty($result['Result']['BriefLegal']) ? $result['Result']['BriefLegal'] : '';

                    $vesting = isset($result['Result']['Vesting']) && !empty($result['Result']['Vesting']) ? $result['Result']['Vesting'] : '';

                    $fips = isset($result['Result']['Fips']) && !empty($result['Result']['Fips']) ? $result['Result']['Fips'] : '';

                    $legal_vesting_info = isset($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']) && !empty($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']) ? $result['Result']['LvDeeds']['LegalAndVesting2DeedInfo'] : array();

                    if (count($legal_vesting_info) == count($legal_vesting_info, COUNT_RECURSIVE)) {
                        $docType = isset($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['DocType']) && !empty($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['DocType']) ? $result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['DocType'] : '';
                        $docType = strtolower($docType);

                        if ($docType == 'grant deed' || $docType == 'intrafamily transfer & dissolution' || $docType == 'quit claim deed' || $docType == 'intra-family transfer or dissolution') {
                            $instrumentNumber = isset($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['InstrumentNumber']) && !empty($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['InstrumentNumber']) ? $result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['InstrumentNumber'] : '';
                            $recordedDate = isset($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['RecordedDate']) && !empty($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['RecordedDate']) ? $result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['RecordedDate'] : '';
                        }

                    } else {
                        foreach ($legal_vesting_info as $key => $value) {
                            $docType = isset($value['DocType']) && !empty($value['DocType']) ? $value['DocType'] : '';
                            $docType = strtolower($docType);

                            if ($docType == 'grant deed' || $docType == 'intrafamily transfer & dissolution' || $docType == 'quit claim deed' || $docType == 'intra-family transfer or dissolution') {
                                $instrumentNumber = isset($value['InstrumentNumber']) && !empty($value['InstrumentNumber']) ? $value['InstrumentNumber'] : '';
                                $recordedDate = isset($value['RecordedDate']) && !empty($value['RecordedDate']) ? $value['RecordedDate'] : '';
                                break;
                            }
                        }
                    }
                    $status = isset($result['Result']['Status']) && !empty($result['Result']['Status']) ? $result['Result']['Status'] : '';

                    $tpData = array(
                        'legal_description' => $briefLegal,
                        'vesting_information' => $vesting,
                        'cs4_instrument_no' => $instrumentNumber,
                        'cs4_recorded_date' => $recordedDate,
                        'grant_deed_type' => $docType,
                        'fips' => $fips,
                        // 'cs4_result_id_status' => $status,
                    );

                    if ($prevCount > 0) {
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);

                    }
                    $this->addLogs($methodId, $responseStatus, $status, $error, $random_number);
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }
            if ($methodId == 3) {
                if ($responseStatus == 'Success') {
                    $firstInstallment = $secondInstallment = array();
                    if (isset($result['Result']['TaxReport']['Installments']['Item'][0]) && !empty($result['Result']['TaxReport']['Installments']['Item'][0])) {
                        $firstInstallment = $result['Result']['TaxReport']['Installments']['Item'][0];
                    }

                    if (isset($result['Result']['TaxReport']['Installments']['Item'][1]) && !empty($result['Result']['TaxReport']['Installments']['Item'][1])) {
                        $secondInstallment = $result['Result']['TaxReport']['Installments']['Item'][1];
                    }

                    $status = isset($result['Result']['TaxReport']['Status']) && !empty($result['Result']['TaxReport']['Status']) ? $result['Result']['TaxReport']['Status'] : '';
                    if ($status == 'Success') {
                        $message = 'Success';
                    } else {
                        $message = isset($result['Result']['TaxReport']['WarningMessage']) && !empty($result['Result']['TaxReport']['WarningMessage']) ? $result['Result']['TaxReport']['WarningMessage'] : '';
                    }

                    $tpData = array(
                        'first_installment' => json_encode($firstInstallment),
                        'second_installment' => json_encode($secondInstallment),
                    );

                    if ($prevCount > 0) {
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);
                    }
                    $this->addLogs($methodId, $responseStatus, $message, $error, $random_number);
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }
        }
    }

    public function getOrderInfo($FileIdOrRandomNum)
    {
        $this->db->select('*')
            ->from('order_details');
        $this->db->group_start()
            ->where("file_id", $FileIdOrRandomNum)
            ->or_where('random_number', $FileIdOrRandomNum)
            ->group_end();
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function createOrderSafewire()
    {
        $file_id = $this->input->post('file_id');
        $order_id = $this->input->post('order_id');
        $orderDetails = $this->order->get_order_details($file_id);
        if ($orderDetails['sales_amount'] > 0) {
            $purchase_price = $orderDetails['sales_amount'];
        } else {
            $purchase_price = $orderDetails['loan_amount'];
        }
        $orderData = array(
            'FileNumber' => $orderDetails['file_number'],
            'FileID' => $orderDetails['file_id'],
            'Properties' => array(
                array(
                    'Address' => $orderDetails['address'],
                    'City' => $orderDetails['property_city'],
                    'State' => $orderDetails['property_state'],
                    'County' => $orderDetails['county'],
                    'Zip' => $orderDetails['property_zip'],
                ),
            ),
            'Buyers' => array(
                array(
                    'FirstName' => $this->input->post('firstname'),
                    'LastName' => $this->input->post('lastname'),
                    'MobilePhone' => $this->input->post('mobile'),
                    'Email' => $this->input->post('email'),
                ),
            ),
            'SalesPrice' => $purchase_price,
        );

        if (!empty($orderDetails['escrow_officer_id'])) {
            $escrowOfficerCon = array(
                'where' => array(
                    'partner_id' => $orderDetails['escrow_officer_id'],
                ),
            );
            $escrowOfficerData = $this->home_model->get_company_rows($escrowOfficerCon);
            if (!empty($escrowOfficerData)) {
                $name = explode(" ", $escrowOfficerData[0]['partner_name']);
                $orderData['EscrowPartner'][] = array(
                    'PartnerID' => $orderDetails['escrow_officer_id'],
                    'FirstName' => $name[0],
                    'LastName' => $name[1],
                    'Email' => $escrowOfficerData[0]['email'],
                    'MobilePhone' => null,
                );
            }

        }

        $bodyParams = array('FileInformations' => $orderData);
        $body_params = json_encode($bodyParams, JSON_UNESCAPED_SLASHES);
        if ($orderDetails['is_create_order_on_safewire'] == 1) {
            $url = env('SAFEWIRE_URL') . $file_id . "/synchronize";
            $method = 'GET';
        } else {
            $url = env('SAFEWIRE_URL') . "invite";
            $method = 'POST';
        }

        $logid = $this->apiLogs->syncLogs(0, 'safewire', 'create_order_on_safewire', $url, $body_params, array(), $order_id, 0);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body_params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Api-Key: ' . env('SAFEWIRE_API_KEY'),
            'Content-Type: application/json',
            'Content-Length: ' . strlen($body_params))
        );
        $error_msg = curl_error($ch);
        $result = curl_exec($ch);
        $this->apiLogs->syncLogs(0, 'safewire', 'create_order_on_safewire', $url, $body_params, $result, $order_id, $logid);
        $res = json_decode($result, true);
        if (isset($res['action_link']) && !empty($res['action_link'])) {
            $this->home_model->update(array('is_create_order_on_safewire' => 1, 'safewire_action_link' => $res['action_link']), array('id' => $orderDetails['order_id']), 'order_details');
            $response = array('success' => true, 'message' => 'order created successfully', 'action_link' => $res['action_link']);
        } else if (isset($res['result']) && $res['result'] == 'success') {
            $response = array('success' => true, 'message' => 'order updated successfully', 'action_link' => $orderDetails['safewire_action_link']);
        } else {
            if (isset($error_msg) && !empty($error_msg)) {
                $response = array('success' => false, 'message' => $error_msg);
            } else {
                $response = array('success' => false, 'message' => $res['error']);
            }
        }
        echo json_encode($response);exit;
    }

    public function getSafewireOrderStatus()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        $json = file_get_contents('php://input');
        $response = array();
        $logId = $this->apiLogs->syncLogs(0, 'safewire', 'send_order_status', null, $json, array());
        $order_id = 0;

        if ($json) {
            $data = json_decode($json, true);
            $orderDetails = $this->order->get_order_details($data['order_id'], 1);
            if (!empty($orderDetails)) {
                $order_id = $orderDetails['order_id'];
                $this->order->syncSafewireDocuments($data['order_details'], $data['wire_instruction_details'], $orderDetails);
                $this->home_model->update(array('safewire_order_status' => $data['status']), array('file_id' => $data['order_id']), 'order_details');
                $response = array('success' => true, 'message' => 'Received order information successfully.');
            } else {
                $response = array('success' => false, 'error_msg' => 'Order not found');
            }
        } else {
            $response = array('success' => false, 'error_msg' => 'No data received.');
        }
        $this->apiLogs->syncLogs(0, 'safewire', 'send_order_status', null, $json, $response, $order_id, $logId);
        header('HTTP/1.0 200 OK');
        header('Content-type: application/json');
        echo json_encode($response, true);
        exit;
    }

    public function policy()
    {
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $random_number = $this->uri->segment(2);
        $order = $this->getOrderInfo($random_number);
        $fileId = $order[0]['file_id'];
        $data['title'] = 'Get Policy | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;
        $orderDetails = $this->order->get_order_details($fileId, 1);
        $data['file_number'] = $orderDetails['file_number'];
        $data['full_address'] = $orderDetails['full_address'];
        $data['file_id'] = $orderDetails['file_id'];
        $data['order_id'] = $orderDetails['order_id'];
        $data['created'] = !empty($orderDetails['opened_date']) ? date("m/d/Y", strtotime($orderDetails['opened_date'])) : '';
        $user_data['admin_api'] = 1;
        $user_data['from_mail'] = 1;
        $endPoint = 'files/' . $fileId . '/documents';

        $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_resware_document', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $orderDetails['order_id'], 0);
        $result = $this->resware->make_request('GET', $endPoint, '', $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'get_resware_document', env('RESWARE_ORDER_API') . $endPoint, array(), $result, $orderDetails['order_id'], $logid);
        $res = json_decode($result, true);

        $policyDocuments = array();
        $i = 0;
        foreach ($res['Documents'] as $document) {
            if ($document['DocumentType']['DocumentTypeID'] == 103) {
                $policyDocuments[$i]['no'] = $i + 1;
                $policyDocuments[$i]['api_document_id'] = $document['DocumentID'];
                $policyDocuments[$i]['document_name'] = $document['DocumentName'];
                $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $document['CreateDate']))) / 1000);
                $created_date = date('m/d/Y', $time);
                $policyDocuments[$i]['created_at'] = $created_date;
                $i++;
            }
        }

        $data['policyDocuments'] = $policyDocuments;
        // $this->load->view('layout/head_dashboard', $data);
        // $this->load->view('order/mail_policy_package', $data);
        $data['displaySidebar'] = false;
        $this->salesdashboardtemplate->show("order", "mail_policy_package", $data);
    }

    public function downloadPolicyDoc()
    {
        $documentId = $this->input->post('documentId');
        $order_id = $this->input->post('order_id');
        $endPoint = 'documents/' . $documentId . '?format=json';
        $user_data['admin_api'] = 1;
        $user_data['from_mail'] = 1;

        $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_resware_document', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $order_id, 0);
        $result = $this->resware->make_request('GET', $endPoint, '', $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'get_resware_document', env('RESWARE_ORDER_API') . $endPoint, array(), $result, $order_id, $logid);
        $res = json_decode($result, true);
        //echo "<pre>";
        //print_r($res);exit;
        if (isset($res['Document']) && !empty($res['Document'])) {
            echo $res['Document']['DocumentBody'];exit;
        }
    }

    public function uploadBorrowerDocument($task, $random_number)
    {
        $this->load->model('admin/escrow/tasks_model');
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $order = $this->getOrderInfo($random_number);
        $fileId = $order[0]['file_id'];
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;
        $data['orderDetails'] = $this->order->get_order_details($fileId, 1);
        $prod_type = $data['orderDetails']['prod_type'];
        $data['borrowerDocuments'] = $this->order->getBorrowerDocuments($data['orderDetails']['order_id']);
        $data['task_name'] = $task;
        $data['tasks'] = $this->tasks_model->get_many_by("(status = 1 and parent_task_id = 0 and (prod_type = 'both' or prod_type = '$prod_type') )");
        $this->load->view('layout/head_dashboard', $data);
        $this->load->view('order/mail_borrower_document', $data);
    }

    public function borrower_document_upload()
    {
        $this->load->model('order/document');
        $success = array();
        $errors = array();
        $documentNames = array();
        $config['upload_path'] = './uploads/borrower/';
        $config['allowed_types'] = 'doc|docx|gif|msg|pdf|tif|tiff|xls|xlsx|xml';
        $config['max_size'] = 18000;
        $this->load->library('upload', $config);
        $fileId = $this->input->post('file_id');
        $task_name = $this->input->post('task_name');

        if (!is_dir('uploads/borrower')) {
            mkdir('./uploads/borrower', 0777, true);
        }

        if (!empty($_FILES['document_files']['name'])) {
            foreach ($_FILES['document_files']['name'] as $key => $file) {
                $_FILES['file']['name'] = $_FILES['document_files']['name'][$key];
                $_FILES['file']['type'] = $_FILES['document_files']['type'][$key];
                $_FILES['file']['tmp_name'] = $_FILES['document_files']['tmp_name'][$key];
                $_FILES['file']['error'] = $_FILES['document_files']['error'][$key];
                $_FILES['file']['size'] = $_FILES['document_files']['size'][$key];

                if (!$this->upload->do_upload('file')) {
                    $errors[] = $this->upload->display_errors();
                } else {
                    $data = $this->upload->data();
                    $contents = file_get_contents($data['full_path']);
                    $binaryData = base64_encode($contents);
                    $document_name = date('YmdHis') . "_" . $data['file_name'];
                    rename(FCPATH . "/uploads/borrower/" . $data['file_name'], FCPATH . "/uploads/borrower/" . $document_name);
                    $this->order->uploadDocumentOnAwsS3($document_name, 'borrower');

                    if ($this->input->post('task_id')) {
                        $task_id = $this->input->post('task_id');
                    } else {
                        $task_id = 62;
                    }

                    $documentData = array(
                        'document_name' => $document_name,
                        'original_document_name' => $data['file_name'],
                        'document_type_id' => 1041,
                        'document_size' => ($data['file_size'] * 1000),
                        'user_id' => 0,
                        'order_id' => $this->input->post('order_id'),
                        'task_id' => $task_id,
                        'description' => 'Borrower Document',
                        'is_sync' => 1,
                        'is_uploaded_by_borrower' => 1,
                    );
                    $this->document->insert($documentData);
                    $success[] = $data['file_name'] . " uploaded successfully";
                    $documentNames[] = $data['file_name'];
                }
            }
        }

        $data = array(
            "errors" => $errors,
            "success" => $success,
        );

        $this->load->model('admin/escrow/order_model');
        $this->load->model('admin/escrow/escrow_user_model');
        $this->load->library('order/common');
        $this->load->model('admin/hr/order_users_model');
        $this->load->model('admin/hr/users_model');

        $orderInfo = $this->order_model->get($this->input->post('order_id'));
        if (count($documentNames) == 1) {
            $message = implode(',', $documentNames) . " document uploaded by borrower for file number " . $orderInfo->file_number;
        } else {
            $message = implode(',', $documentNames) . " documents uploaded by borrower for file number " . $orderInfo->file_number;
        }
        $this->session->set_userdata($data);
        if (!empty($orderInfo->escrow_officer_id)) {
            $escrowInfoFromOrder = $this->common->getEscrowOfficerInfoBasedOnIdFromOrder($orderInfo->escrow_officer_id);
            $notificationData = array(
                'sent_user_id' => $escrowInfoFromOrder['id'],
                'message' => $message,
                'type' => 'completed',
            );
            $this->home_model->insert($notificationData, 'pct_order_notifications');
            $this->order->sendNotification($message, 'completed', $escrowInfoFromOrder['id'], 0);

            $escrowHrInfo = $this->escrow_user_model->get_by(array('email' => $escrowInfoFromOrder['email_address']));
            $assistantUsersInfo = json_decode(json_encode($this->escrow_user_model->get_many_by(array('branch_id' => $escrowHrInfo->branch_id, 'position_id' => 15))), true);
            $assistantUserEmails = array_column($assistantUsersInfo, 'email');
            $assistantOrderUsersInfo = $this->common->getAssistantUsers($assistantUserEmails);

            if (!empty($assistantOrderUsersInfo)) {
                foreach ($assistantOrderUsersInfo as $assistantUser) {
                    $notificationData = array(
                        'sent_user_id' => $assistantUser['id'],
                        'message' => $message,
                        'type' => 'completed',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'completed', $assistantUser['id'], 0);
                }
            }

            $managersInfo = $this->users_model->get_many_by(array('user_type_id' => 4, 'department_id' => 4));
            foreach ($managersInfo as $manager) {
                $notificationData = array(
                    'sent_user_id' => $manager->id,
                    'message' => $message,
                    'type' => 'completed',
                );
                $this->home_model->insert($notificationData, 'pct_hr_notifications');
                $this->order->sendNotification($message, 'completed', $manager->id, 1);
            }
        }
        redirect(base_url() . 'borrower-document/' . $task_name . "/" . $orderInfo->random_number);
    }

    public function borrowerSellerForm($random_number)
    {
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;
        $order = $this->getOrderInfo($random_number);
        $orderDetails = $this->order->get_order_details($order[0]['file_id'], 1);

        $this->db->select('*')
            ->from('pct_order_borrower_seller_owner_escrow_info');
        $this->db->where('order_id', $order[0]['id']);
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data['sellerInfo'] = $query->row_array();
        } else {
            $data['sellerInfo'] = array();
        }

        $this->db->select('*')
            ->from('pct_order_documents');
        $this->db->where('order_id', $order[0]['id']);
        $this->db->where('(is_commission_doc = 1 or is_escrow_instruction_doc = 1)');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data['docsInfo'] = $query->result_array();
        } else {
            $data['docsInfo'] = array();
        }

        if (!empty($orderDetails['borrower'])) {
            $orderDetails['primary_owner_name'] = $orderDetails['borrower'];
        } else {
            $orderDetails['primary_owner_name'] = '';
        }

        if (!empty($orderDetails['secondary_borrower'])) {
            $orderDetails['secondary_owner_name'] = $orderDetails['secondary_borrower'];
        } else {
            $orderDetails['secondary_owner_name'] = '';
        }

        if (!empty($orderDetails['borrower'])) {
            $seller_owner_names = explode(' ', $orderDetails['borrower']);
            if (count($seller_owner_names) == 3) {
                $orderDetails['seller_first_name'] = $seller_owner_names[0];
                $orderDetails['seller_middle_name'] = $seller_owner_names[1];
                $orderDetails['seller_last_name'] = $seller_owner_names[2];
            } else if (count($seller_owner_names) == 2) {
                $orderDetails['seller_first_name'] = $seller_owner_names[0];
                $orderDetails['seller_middle_name'] = '';
                $orderDetails['seller_last_name'] = $seller_owner_names[1];
            } else {
                $orderDetails['seller_first_name'] = $seller_owner_names[0];
                $orderDetails['seller_middle_name'] = '';
                $orderDetails['seller_last_name'] = '';
            }
        } else {
            $orderDetails['seller_first_name'] = '';
            $orderDetails['seller_middle_name'] = '';
            $orderDetails['seller_last_name'] = '';
        }

        if (!empty($orderDetails['secondary_borrower'])) {
            $second_seller_owner_names = explode(' ', $orderDetails['secondary_borrower']);
            if (count($second_seller_owner_names) == 3) {
                $orderDetails['second_seller_first_name'] = $second_seller_owner_names[0];
                $orderDetails['second_seller_middle_name'] = $second_seller_owner_names[1];
                $orderDetails['second_seller_last_name'] = $second_seller_owner_names[2];
            } else if (count($second_seller_owner_names) == 2) {
                $orderDetails['second_seller_first_name'] = $second_seller_owner_names[0];
                $orderDetails['second_seller_middle_name'] = '';
                $orderDetails['second_seller_last_name'] = $second_seller_owner_names[1];
            } else {
                $orderDetails['second_seller_first_name'] = $second_seller_owner_names[0];
                $orderDetails['second_seller_middle_name'] = '';
                $orderDetails['second_seller_last_name'] = '';
            }
        } else {
            $orderDetails['second_seller_first_name'] = '';
            $orderDetails['second_seller_middle_name'] = '';
            $orderDetails['second_seller_last_name'] = '';
        }

        $data['orderDetails'] = $orderDetails;
        $errors = array();
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }

        $user_data['admin_api'] = 1;
        $endPoint = 'files/' . $order[0]['file_id'] . '/documents';
        $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_documents', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $order[0]['id'], 0);
        $resultDocuments = $this->resware->make_request('GET', $endPoint, '', $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'get_documents', env('RESWARE_ORDER_API') . $endPoint, array(), $resultDocuments, $order[0]['id'], $logid);
        $resDocuments = json_decode($resultDocuments, true);
        $documentIds = array(1015, 1534, 1040, 1405, 1039, 1038, 1020);

        if (!empty($resDocuments['Documents'])) {
            foreach ($resDocuments['Documents'] as $document) {
                if (in_array($document['DocumentType']['DocumentTypeID'], $documentIds)) {
                    $key = array_search($document['DocumentID'], array_column($data['docsInfo'], 'api_document_id'));
                    // echo $document['DocumentID']."---";
                    // print_r($data['docsInfo']);
                    // print_r(array_column($data['docsInfo'], 'api_document_id'));exit;
                    $this->load->model('order/document');
                    if (strlen($key) == 0 && strpos(strtolower($document['DocumentName']), 'snapshot') === false) {
                        $document_name = date('YmdHis') . "_" . $document['DocumentName'];
                        $ext = end(explode('.', $document['DocumentName']));

                        if (strtolower($ext) == 'doc' || strtolower($ext) == 'docx') {
                            $document_name = str_replace($ext, 'pdf', $document_name);
                        }

                        $endPoint = 'documents/' . $document['DocumentID'] . '?format=json';
                        $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $order[0]['id'], 0);
                        $resultDocument = $this->resware->make_request('GET', $endPoint, '', $user_data);
                        $this->apiLogs->syncLogs(0, 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), $resultDocument, $order[0]['id'], $logid);
                        $resDocument = json_decode($resultDocument, true);

                        if (isset($resDocument['Document']) && !empty($resDocument['Document'])) {
                            $documentContent = base64_decode($resDocument['Document']['DocumentBody'], true);
                            if (!is_dir('uploads/instruction_documents')) {
                                mkdir(FCPATH . '/uploads/instruction_documents', 0777, true);
                            }
                            file_put_contents(FCPATH . '/uploads/instruction_documents/' . $document_name, $documentContent);
                            $this->order->uploadDocumentOnAwsS3($document_name, 'instruction_documents');
                            $documentData = array(
                                'document_name' => $document_name,
                                'original_document_name' => $document['DocumentName'],
                                'document_type_id' => $document['DocumentType']['DocumentTypeID'],
                                'api_document_id' => $document['DocumentID'],
                                'document_size' => $document['Size'],
                                'user_id' => 0,
                                'order_id' => $orderDetails['order_id'],
                                'description' => $document['DocumentName'],
                                'created' => date('Y-m-d H:i:s'),
                                'is_sync' => 0,
                                'is_commission_doc' => ($document['DocumentType']['DocumentTypeID'] == 1039 || $document['DocumentType']['DocumentTypeID'] == 1038 || $document['DocumentType']['DocumentTypeID'] == 1020) ? 1 : 0,
                                'is_escrow_instruction_doc' => ($document['DocumentType']['DocumentTypeID'] == 1015 || $document['DocumentType']['DocumentTypeID'] == 1534 || $document['DocumentType']['DocumentTypeID'] == 1040) ? 1 : 0,
                            );
                            $data['docsInfo'][] = $documentData;
                            $this->document->insert($documentData);
                        }
                    }
                }
            }
        }

        if ($this->input->post()) {

            $sellerEscrowInstructionData = array();
            $sellerCommissionInstructionData = array();
            $sellerOwnerEscrowInfoData = array(
                'order_id' => $this->input->post('order_id'),
                'seller_name' => $this->input->post('seller_name') ? $this->input->post('seller_name') : null,
                'escrow_home_phone_number' => $this->input->post('escrow_home_phone_number') ? $this->input->post('escrow_home_phone_number') : null,
                'work_phone_number' => $this->input->post('work_phone_number') ? $this->input->post('work_phone_number') : null,
                'fax_number' => $this->input->post('fax_number') ? $this->input->post('fax_number') : null,
                'cell_phone_number' => $this->input->post('cell_phone_number') ? $this->input->post('cell_phone_number') : null,
                'email_address' => $this->input->post('email_address') ? $this->input->post('email_address') : null,
                'cell_phone_number_2' => $this->input->post('cell_phone_number_2') ? $this->input->post('cell_phone_number_2') : null,
                'escrow_ssn' => $this->input->post('escrow_ssn') ? $this->input->post('escrow_ssn') : null,
                'ssn_2' => $this->input->post('ssn_2') ? $this->input->post('ssn_2') : null,
                'property_address' => $this->input->post('property_address') ? $this->input->post('property_address') : null,
                'seller_current_mailing_address' => $this->input->post('seller_current_mailing_address') ? $this->input->post('seller_current_mailing_address') : null,
                'seller_mailing_address_after_close_escrow' => $this->input->post('seller_mailing_address_after_close_escrow') ? $this->input->post('seller_mailing_address_after_close_escrow') : null,
                'seller_mailing_address_after_close_escrow_2' => $this->input->post('seller_mailing_address_after_close_escrow_2') ? $this->input->post('seller_mailing_address_after_close_escrow_2') : null,
                'first_trust_deed_lender' => $this->input->post('first_trust_deed_lender') ? $this->input->post('first_trust_deed_lender') : null,
                'lender_address' => $this->input->post('lender_address') ? $this->input->post('lender_address') : null,
                'loan_number' => $this->input->post('loan_number') ? $this->input->post('loan_number') : null,
                'lender_phone_number' => $this->input->post('lender_phone_number') ? $this->input->post('lender_phone_number') : null,
                'unpaid_principal_balance' => $this->input->post('unpaid_principal_balance') ? $this->input->post('unpaid_principal_balance') : null,
                'next_due' => $this->input->post('next_due') ? $this->input->post('next_due') : null,
                'type_of_loan' => $this->input->post('type_of_loan') ? $this->input->post('type_of_loan') : null,
                'va' => $this->input->post('va') ? $this->input->post('va') : null,
                'fha' => $this->input->post('fha') ? $this->input->post('fha') : null,
                'conventional' => $this->input->post('conventional') ? $this->input->post('conventional') : null,
                'taxes' => $this->input->post('taxes') ? $this->input->post('taxes') : null,
                'paid' => $this->input->post('paid') ? $this->input->post('paid') : null,
                'unpaid' => $this->input->post('unpaid') ? $this->input->post('unpaid') : null,
                'is_impound_acc' => $this->input->post('is_impound_acc') ? $this->input->post('is_impound_acc') : null,
                'second_trust_deed_lender' => $this->input->post('second_trust_deed_lender') ? $this->input->post('second_trust_deed_lender') : null,
                'second_lender_address' => $this->input->post('second_lender_address') ? $this->input->post('second_lender_address') : null,
                'second_loan_number' => $this->input->post('second_loan_number') ? $this->input->post('second_loan_number') : null,
                'second_lender_phone_number' => $this->input->post('second_lender_phone_number') ? $this->input->post('second_lender_phone_number') : null,
                'second_unpaid_principal_balance' => $this->input->post('second_unpaid_principal_balance') ? $this->input->post('second_unpaid_principal_balance') : null,
                'second_type_of_loan' => $this->input->post('second_type_of_loan') ? $this->input->post('second_type_of_loan') : null,
                'second_va' => $this->input->post('second_va') ? $this->input->post('second_va') : null,
                'second_fha' => $this->input->post('second_fha') ? $this->input->post('second_fha') : null,
                'second_conventional' => $this->input->post('second_conventional') ? $this->input->post('second_conventional') : null,
                'homeowner_association' => $this->input->post('homeowner_association') ? $this->input->post('homeowner_association') : null,
                'management_company' => $this->input->post('management_company') ? $this->input->post('management_company') : null,
                'management_mailing_address' => $this->input->post('management_mailing_address') ? $this->input->post('management_mailing_address') : null,
                'contact_person' => $this->input->post('contact_person') ? $this->input->post('contact_person') : null,
                'management_phone_number' => $this->input->post('management_phone_number') ? $this->input->post('management_phone_number') : null,
                'second_homeowner_association' => $this->input->post('second_homeowner_association') ? $this->input->post('second_homeowner_association') : null,
                'second_management_company' => $this->input->post('second_management_company') ? $this->input->post('second_management_company') : null,
                'second_management_mailing_address' => $this->input->post('second_management_mailing_address') ? $this->input->post('second_management_mailing_address') : null,
                'second_contact_person' => $this->input->post('second_contact_person') ? $this->input->post('second_contact_person') : null,
                'second_management_phone_number' => $this->input->post('second_management_phone_number') ? $this->input->post('second_management_phone_number') : null,
                'water_company_name' => $this->input->post('water_company_name') ? $this->input->post('water_company_name') : null,
                'water_contract_name' => $this->input->post('water_contract_name') ? $this->input->post('water_contract_name') : null,
                'water_company_address' => $this->input->post('water_company_address') ? $this->input->post('water_company_address') : null,
                'water_company_phone' => $this->input->post('water_company_phone') ? $this->input->post('water_company_phone') : null,
                'amount_of_assessment' => $this->input->post('amount_of_assessment') ? $this->input->post('amount_of_assessment') : null,
                'water_next_due' => $this->input->post('water_next_due') ? $this->input->post('water_next_due') : null,
                'no_of_shares' => $this->input->post('no_of_shares') ? $this->input->post('no_of_shares') : null,
                'date' => $this->input->post('date') ? $this->input->post('date') : null,
                'escrow_signature' => $this->input->post('escrow_signature') ? $this->input->post('escrow_signature') : null,
            );
            $this->home_model->insert($sellerOwnerEscrowInfoData, 'pct_order_borrower_seller_owner_escrow_info');

            $sellerStatementInfoData = array(
                'order_id' => $this->input->post('order_id'),
                'first_name' => $this->input->post('first_name') ? $this->input->post('first_name') : null,
                'middle_name' => $this->input->post('middle_name') ? $this->input->post('middle_name') : null,
                'last_name' => $this->input->post('last_name') ? $this->input->post('last_name') : null,
                'maiden_name' => $this->input->post('maiden_name') ? $this->input->post('maiden_name') : null,
                'date_of_birth' => $this->input->post('date_of_birth') ? $this->input->post('date_of_birth') : null,
                'home_phone_number' => $this->input->post('home_phone_number') ? $this->input->post('home_phone_number') : null,
                'business_phone_number' => $this->input->post('business_phone_number') ? $this->input->post('business_phone_number') : null,
                'birthplace' => $this->input->post('birthplace') ? $this->input->post('birthplace') : null,
                'ssn' => $this->input->post('ssn') ? $this->input->post('ssn') : null,
                'driver_license_no' => $this->input->post('driver_license_no') ? $this->input->post('driver_license_no') : null,
                'another_name_that_used' => $this->input->post('another_name_that_used') ? $this->input->post('another_name_that_used') : null,
                'residence_state' => $this->input->post('residence_state') ? $this->input->post('residence_state') : null,
                'lived_year' => $this->input->post('lived_year') ? $this->input->post('lived_year') : null,
                'is_married' => $this->input->post('is_married') ? $this->input->post('is_married') : null,
                'date_and_place_marriage' => $this->input->post('date_and_place_marriage') ? $this->input->post('date_and_place_marriage') : null,
                'spouse_first_name' => $this->input->post('spouse_first_name') ? $this->input->post('spouse_first_name') : null,
                'spouse_middle_name' => $this->input->post('spouse_middle_name') ? $this->input->post('spouse_middle_name') : null,
                'spouse_last_name' => $this->input->post('spouse_last_name') ? $this->input->post('spouse_last_name') : null,
                'spouse_maiden_name' => $this->input->post('spouse_maiden_name') ? $this->input->post('spouse_maiden_name') : null,
                'spouse_date_of_birth' => $this->input->post('spouse_date_of_birth') ? $this->input->post('spouse_date_of_birth') : null,
                'spouse_home_phone_number' => $this->input->post('spouse_home_phone_number') ? $this->input->post('spouse_home_phone_number') : null,
                'spouse_business_phone_number' => $this->input->post('spouse_business_phone_number') ? $this->input->post('spouse_business_phone_number') : null,
                'spouse_birthplace' => $this->input->post('spouse_birthplace') ? $this->input->post('spouse_birthplace') : null,
                'spouse_ssn' => $this->input->post('spouse_ssn') ? $this->input->post('spouse_ssn') : null,
                'spouse_driver_license_no' => $this->input->post('spouse_driver_license_no') ? $this->input->post('spouse_driver_license_no') : null,
                'spouse_another_name_that_used' => $this->input->post('spouse_another_name_that_used') ? $this->input->post('spouse_another_name_that_used') : null,
                'spouse_state_residence' => $this->input->post('spouse_state_residence') ? $this->input->post('spouse_state_residence') : null,
                'spouse_lived_year' => $this->input->post('spouse_lived_year') ? $this->input->post('spouse_lived_year') : null,
                'is_domestic_partner' => $this->input->post('is_domestic_partner') ? $this->input->post('is_domestic_partner') : null,
                'domestic_first_name' => $this->input->post('domestic_first_name') ? $this->input->post('domestic_first_name') : null,
                'domestic_middle_name' => $this->input->post('domestic_middle_name') ? $this->input->post('domestic_middle_name') : null,
                'domestic_last_name' => $this->input->post('domestic_last_name') ? $this->input->post('domestic_last_name') : null,
                'domestic_maiden_name' => $this->input->post('domestic_maiden_name') ? $this->input->post('domestic_maiden_name') : null,
                'domestic_date_of_birth' => $this->input->post('domestic_date_of_birth') ? $this->input->post('domestic_date_of_birth') : null,
                'domestic_home_phone_number' => $this->input->post('domestic_home_phone_number') ? $this->input->post('domestic_home_phone_number') : null,
                'domestic_business_phone_number' => $this->input->post('domestic_business_phone_number') ? $this->input->post('domestic_business_phone_number') : null,
                'domestic_birthplace' => $this->input->post('domestic_birthplace') ? $this->input->post('domestic_birthplace') : null,
                'domestic_ssn' => $this->input->post('domestic_ssn') ? $this->input->post('domestic_ssn') : null,
                'domestic_driver_license_no' => $this->input->post('domestic_driver_license_no') ? $this->input->post('domestic_driver_license_no') : null,
                'domestic_another_name_that_used' => $this->input->post('domestic_another_name_that_used') ? $this->input->post('domestic_another_name_that_used') : null,
                'domestic_state_residence' => $this->input->post('domestic_state_residence') ? $this->input->post('domestic_state_residence') : null,
                'domestic_lived_year' => $this->input->post('domestic_lived_year') ? $this->input->post('domestic_lived_year') : null,
                'residence_number_street' => $this->input->post('residence_number_street') ? $this->input->post('residence_number_street') : null,
                'residence_city' => $this->input->post('residence_city') ? $this->input->post('residence_city') : null,
                'residence_from_date_to_date' => $this->input->post('residence_from_date_to_date') ? $this->input->post('residence_from_date_to_date') : null,
                'second_residence_number_street' => $this->input->post('second_residence_number_street') ? $this->input->post('second_residence_number_street') : null,
                'second_residence_city' => $this->input->post('second_residence_city') ? $this->input->post('second_residence_city') : null,
                'second_residence_from_date_to_date' => $this->input->post('second_residence_from_date_to_date') ? $this->input->post('second_residence_from_date_to_date') : null,
                'business_address' => $this->input->post('business_address') ? $this->input->post('business_address') : null,
                'firm_or_business_name' => $this->input->post('firm_or_business_name') ? $this->input->post('firm_or_business_name') : null,
                'business_from_date_to_date' => $this->input->post('business_from_date_to_date') ? $this->input->post('business_from_date_to_date') : null,
                'second_firm_or_business_name' => $this->input->post('second_firm_or_business_name') ? $this->input->post('second_firm_or_business_name') : null,
                'second_business_address' => $this->input->post('second_business_address') ? $this->input->post('second_business_address') : null,
                'second_business_from_date_to_date' => $this->input->post('second_business_from_date_to_date') ? $this->input->post('second_business_from_date_to_date') : null,
                'is_buyer_intends' => $this->input->post('is_buyer_intends') ? $this->input->post('is_buyer_intends') : null,
                'owner_street_address' => $this->input->post('owner_street_address') ? $this->input->post('owner_street_address') : null,
                'unimproved' => $this->input->post('unimproved') ? $this->input->post('unimproved') : null,
                'single_family' => $this->input->post('single_family') ? $this->input->post('single_family') : null,
                'condo_unit' => $this->input->post('condo_unit') ? $this->input->post('condo_unit') : null,
                'other' => $this->input->post('other') ? $this->input->post('other') : null,
                'is_improvement' => $this->input->post('is_improvement') ? $this->input->post('is_improvement') : null,
                'is_materials' => $this->input->post('is_materials') ? $this->input->post('is_materials') : null,
                'is_loan' => $this->input->post('is_loan') ? $this->input->post('is_loan') : null,
                'lender' => $this->input->post('lender') ? $this->input->post('lender') : null,
                'loan_amount' => $this->input->post('loan_amount') ? $this->input->post('loan_amount') : null,
                'loan_account' => $this->input->post('loan_account') ? $this->input->post('loan_account') : null,
                'second_lender' => $this->input->post('second_lender') ? $this->input->post('second_lender') : null,
                'second_loan_amount' => $this->input->post('second_loan_amount') ? $this->input->post('second_loan_amount') : null,
                'second_loan_account' => $this->input->post('second_loan_account') ? $this->input->post('second_loan_account') : null,
                'executed_date' => $this->input->post('executed_date') ? $this->input->post('executed_date') : null,
                'executed_year' => $this->input->post('executed_year') ? $this->input->post('executed_year') : null,
                'executed_time' => $this->input->post('executed_time') ? $this->input->post('executed_time') : null,
                'signature' => $this->input->post('signature') ? $this->input->post('signature') : null,
                'second_signature' => $this->input->post('second_signature') ? $this->input->post('second_signature') : null,
            );
            $this->home_model->insert($sellerStatementInfoData, 'pct_order_borrower_seller_statement_of_info');

            $seller593CPart1Data = array(
                'order_id' => $this->input->post('order_id'),
                'is_amended' => $this->input->post('is_amended') ? $this->input->post('is_amended') : null,
                'remitter_info' => $this->input->post('remitter_info') ? implode(',', $this->input->post('remitter_info')) : null,
                'business_name' => $this->input->post('business_name') ? $this->input->post('business_name') : null,
                'business_num' => $this->input->post('business_num') ? implode(',', $this->input->post('business_num')) : null,
                'remitter_first_name' => $this->input->post('remitter_first_name') ? $this->input->post('remitter_first_name') : null,
                'remitter_initial_name' => $this->input->post('remitter_initial_name') ? $this->input->post('remitter_initial_name') : null,
                'remitter_last_name' => $this->input->post('remitter_last_name') ? $this->input->post('remitter_last_name') : null,
                'remitter_ssn_or_itin' => $this->input->post('remitter_ssn_or_itin') ? $this->input->post('remitter_ssn_or_itin') : null,
                'remitter_address' => $this->input->post('remitter_address') ? $this->input->post('remitter_address') : null,
                'remitter_city' => $this->input->post('remitter_city') ? $this->input->post('remitter_city') : null,
                'remitter_state' => $this->input->post('remitter_state') ? $this->input->post('remitter_state') : null,
                'remitter_zip_code' => $this->input->post('remitter_zip_code') ? $this->input->post('remitter_zip_code') : null,
                'remitter_telephone_num' => $this->input->post('remitter_telephone_num') ? $this->input->post('remitter_telephone_num') : null,
                'trust_types' => $this->input->post('trust_types') ? implode(',', $this->input->post('trust_types')) : null,
                'transferor_first_name' => $this->input->post('transferor_first_name') ? $this->input->post('transferor_first_name') : null,
                'transferor_initial_name' => $this->input->post('transferor_initial_name') ? $this->input->post('transferor_initial_name') : null,
                'transferor_last_name' => $this->input->post('transferor_last_name') ? $this->input->post('transferor_last_name') : null,
                'transferor_ssn_or_itin' => $this->input->post('transferor_ssn_or_itin') ? $this->input->post('transferor_ssn_or_itin') : null,
                'transferor_spouse_first_name' => $this->input->post('transferor_spouse_first_name') ? $this->input->post('transferor_spouse_first_name') : null,
                'transferor_spouse_middle_name' => $this->input->post('transferor_spouse_middle_name') ? $this->input->post('transferor_spouse_middle_name') : null,
                'transferor_spouse_last_name' => $this->input->post('transferor_spouse_last_name') ? $this->input->post('transferor_spouse_last_name') : null,
                'transferor_spouse_ssn_or_itin' => $this->input->post('transferor_spouse_ssn_or_itin') ? $this->input->post('transferor_spouse_ssn_or_itin') : null,
                'nongrantor_trust_name' => $this->input->post('nongrantor_trust_name') ? $this->input->post('nongrantor_trust_name') : null,
                'transferor_business_num' => $this->input->post('transferor_business_num') ? implode(',', $this->input->post('transferor_business_num')) : null,
                'transferor_address' => $this->input->post('transferor_address') ? $this->input->post('transferor_address') : null,
                'transferor_city' => $this->input->post('transferor_city') ? $this->input->post('transferor_city') : null,
                'transferor_state' => $this->input->post('transferor_state') ? $this->input->post('transferor_state') : null,
                'transferor_zip_code' => $this->input->post('transferor_zip_code') ? $this->input->post('transferor_zip_code') : null,
                'transferor_telephone_number' => $this->input->post('transferor_telephone_number') ? $this->input->post('transferor_telephone_number') : null,
                'transferor_property_address' => $this->input->post('transferor_property_address') ? $this->input->post('transferor_property_address') : null,
                'ownership_percentage' => $this->input->post('ownership_percentage') ? $this->input->post('ownership_percentage') : null,
                'certifications' => $this->input->post('certifications') ? implode(',', $this->input->post('certifications')) : null,
                'remitter_name' => $this->input->post('remitter_name') ? $this->input->post('remitter_name') : null,
                'remitter_ssn_fein' => $this->input->post('remitter_ssn_fein') ? $this->input->post('remitter_ssn_fein') : null,
                'transferee_first_name' => $this->input->post('transferee_first_name') ? $this->input->post('transferee_first_name') : null,
                'transferee_initial_name' => $this->input->post('transferee_initial_name') ? $this->input->post('transferee_initial_name') : null,
                'transferee_last_name' => $this->input->post('transferee_last_name') ? $this->input->post('transferee_last_name') : null,
                'transferee_ssn_or_itin' => $this->input->post('transferee_ssn_or_itin') ? $this->input->post('transferee_ssn_or_itin') : null,
                'transferee_spouse_first_name' => $this->input->post('transferee_spouse_first_name') ? $this->input->post('transferee_spouse_first_name') : null,
                'transferee_spouse_initial_name' => $this->input->post('transferee_spouse_initial_name') ? $this->input->post('transferee_spouse_initial_name') : null,
                'transferee_spouse_last_name' => $this->input->post('transferee_spouse_last_name') ? $this->input->post('transferee_spouse_last_name') : null,
            );
            $this->home_model->insert($seller593CPart1Data, 'pct_order_borrower_seller_593_c_form_part_1');

            $seller593CPart2Data = array(
                'transferee_spouse_ssn_or_itin' => $this->input->post('transferee_spouse_ssn_or_itin') ? $this->input->post('transferee_spouse_ssn_or_itin') : null,
                'transferee_nongrantor_trust_name' => $this->input->post('transferee_nongrantor_trust_name') ? $this->input->post('transferee_nongrantor_trust_name') : null,
                'transferee_business_num' => $this->input->post('transferee_business_num') ? implode(',', $this->input->post('transferee_business_num')) : null,
                'transferee_address' => $this->input->post('transferee_address') ? $this->input->post('transferee_address') : null,
                'transferee_city' => $this->input->post('transferee_city') ? $this->input->post('transferee_city') : null,
                'transferee_state' => $this->input->post('transferee_state') ? $this->input->post('transferee_state') : null,
                'transferee_zip_code' => $this->input->post('transferee_zip_code') ? $this->input->post('transferee_zip_code') : null,
                'transferee_telephone_number' => $this->input->post('transferee_telephone_number') ? $this->input->post('transferee_telephone_number') : null,
                'principal_amount_of_promissory_note' => $this->input->post('principal_amount_of_promissory_note') ? $this->input->post('principal_amount_of_promissory_note') : null,
                'installment_amount' => $this->input->post('installment_amount') ? $this->input->post('installment_amount') : null,
                'principal_interrest_rate' => $this->input->post('principal_interrest_rate') ? $this->input->post('principal_interrest_rate') : null,
                'repayment_period' => $this->input->post('repayment_period') ? $this->input->post('repayment_period') : null,
                'selling_price' => $this->input->post('selling_price') ? $this->input->post('selling_price') : null,
                'selling_expenses' => $this->input->post('selling_expenses') ? $this->input->post('selling_expenses') : null,
                'amount_realized' => $this->input->post('amount_realized') ? $this->input->post('amount_realized') : null,
                'paid_price_to_purchase' => $this->input->post('paid_price_to_purchase') ? $this->input->post('paid_price_to_purchase') : null,
                'seller_paid_months' => $this->input->post('seller_paid_months') ? $this->input->post('seller_paid_months') : null,
                'seller_depreciation' => $this->input->post('seller_depreciation') ? $this->input->post('seller_depreciation') : null,
                'other_decreases' => $this->input->post('other_decreases') ? $this->input->post('other_decreases') : null,
                'total_decrease_line_17' => $this->input->post('total_decrease_line_17') ? $this->input->post('total_decrease_line_17') : null,
                'subtract_line_20' => $this->input->post('subtract_line_20') ? $this->input->post('subtract_line_20') : null,
                'cost_of_addition' => $this->input->post('cost_of_addition') ? $this->input->post('cost_of_addition') : null,
                'other_increase_to_basis' => $this->input->post('other_increase_to_basis') ? $this->input->post('other_increase_to_basis') : null,
                'total_decrease_line_22' => $this->input->post('total_decrease_line_22') ? $this->input->post('total_decrease_line_22') : null,
                'adjusted_basis_line_21' => $this->input->post('adjusted_basis_line_21') ? $this->input->post('adjusted_basis_line_21') : null,
                'suspended_passive_lossed' => $this->input->post('suspended_passive_lossed') ? $this->input->post('suspended_passive_lossed') : null,
                'add_line_25' => $this->input->post('add_line_25') ? $this->input->post('add_line_25') : null,
                'estimated_gain_or_loss' => $this->input->post('estimated_gain_or_loss') ? $this->input->post('estimated_gain_or_loss') : null,
                'remitter_name_2' => $this->input->post('remitter_name_2') ? $this->input->post('remitter_name_2') : null,
                'remitter_ssn_itin_fein_2' => $this->input->post('remitter_ssn_itin_fein_2') ? $this->input->post('remitter_ssn_itin_fein_2') : null,
                'calculation_amount' => $this->input->post('calculation_amount') ? implode(',', $this->input->post('calculation_amount')) : null,
                'calculation_amount_value' => $this->input->post('calculation_amount_value') ? $this->input->post('calculation_amount_value') : null,
                'sales_price_withholding_amount' => $this->input->post('sales_price_withholding_amount') ? $this->input->post('sales_price_withholding_amount') : null,
                'escrow_exchange_number' => $this->input->post('escrow_exchange_number') ? $this->input->post('escrow_exchange_number') : null,
                'date_of_transfer' => $this->input->post('date_of_transfer') ? $this->input->post('date_of_transfer') : null,
                'boot_amount' => $this->input->post('boot_amount') ? $this->input->post('boot_amount') : null,
                'exchange_ownership_percentage_from' => $this->input->post('exchange_ownership_percentage_from') ? $this->input->post('exchange_ownership_percentage_from') : null,
                'exchange_ownership_percentage_to' => $this->input->post('exchange_ownership_percentage_to') ? $this->input->post('exchange_ownership_percentage_to') : null,
                'amount_withheld_from' => $this->input->post('amount_withheld_from') ? $this->input->post('amount_withheld_from') : null,
                'amount_withheld_to' => $this->input->post('amount_withheld_to') ? $this->input->post('amount_withheld_to') : null,
                'transaction' => $this->input->post('transaction') ? $this->input->post('transaction') : null,
                'with_holding' => $this->input->post('with_holding') ? $this->input->post('with_holding') : null,
                'amount_withheld' => $this->input->post('amount_withheld') ? $this->input->post('amount_withheld') : null,
                'perjury' => $this->input->post('perjury') ? implode(',', $this->input->post('perjury')) : null,
                'seller_transferor_signature' => $this->input->post('seller_transferor_signature') ? $this->input->post('seller_transferor_signature') : null,
                'seller_transferor_date' => $this->input->post('seller_transferor_date') ? $this->input->post('seller_transferor_date') : null,
                'seller_transferor_spouse_signature' => $this->input->post('seller_transferor_spouse_signature') ? $this->input->post('seller_transferor_spouse_signature') : null,
                'seller_transferor_spouse_date' => $this->input->post('seller_transferor_spouse_date') ? $this->input->post('seller_transferor_spouse_date') : null,
                'buyer_transferor_signature' => $this->input->post('buyer_transferor_signature') ? $this->input->post('buyer_transferor_signature') : null,
                'buyer_transferor_date' => $this->input->post('buyer_transferor_date') ? $this->input->post('buyer_transferor_date') : null,
                'buyer_transferor_spouse_signature' => $this->input->post('buyer_transferor_spouse_signature') ? $this->input->post('buyer_transferor_date') : null,
                'buyer_transferor_spouse_date' => $this->input->post('buyer_transferor_spouse_date') ? $this->input->post('buyer_transferor_spouse_date') : null,
            );
            $this->home_model->insert($seller593CPart2Data, 'pct_order_borrower_seller_593_c_form_part_2');

            $sellerOtherInfoData = array(
                'order_id' => $this->input->post('order_id'),
                'date_of_closing' => $this->input->post('date_of_closing'),
                'sellers_name' => $this->input->post('sellers_name') ? $this->input->post('sellers_name') : null,
                'personal_representative' => $this->input->post('personal_representative') ? $this->input->post('personal_representative') : null,
                'federal_tax' => $this->input->post('federal_tax') ? $this->input->post('federal_tax') : null,
                'total_consideration' => $this->input->post('total_consideration') ? $this->input->post('total_consideration') : null,
                'percentage_of_ownership' => $this->input->post('percentage_of_ownership') ? $this->input->post('percentage_of_ownership') : null,
                'gross_allocated' => $this->input->post('gross_allocated') ? $this->input->post('gross_allocated') : null,
                'is_exchange' => $this->input->post('is_exchange') ? $this->input->post('is_exchange') : null,
                'tax_credit' => $this->input->post('tax_credit') ? $this->input->post('tax_credit') : null,
                'mailing_address_1099_s_1' => $this->input->post('mailing_address_1099_s_1') ? $this->input->post('mailing_address_1099_s_1') : null,
                'mailing_address_1099_s_2' => $this->input->post('mailing_address_1099_s_2') ? $this->input->post('mailing_address_1099_s_2') : null,
                'is_outside' => $this->input->post('is_outside') ? $this->input->post('is_outside') : null,
                'is_regulations' => $this->input->post('is_regulations') ? $this->input->post('is_regulations') : null,
                'tranferor_signature' => $this->input->post('tranferor_signature') ? $this->input->post('tranferor_signature') : null,
                'spouse_signature' => $this->input->post('spouse_signature') ? $this->input->post('spouse_signature') : null,
                'spouse_date' => $this->input->post('spouse_date') ? $this->input->post('spouse_date') : null,
                'taxpayer_identifying_num' => $this->input->post('taxpayer_identifying_num') ? $this->input->post('taxpayer_identifying_num') : null,
                'home_address' => $this->input->post('home_address') ? $this->input->post('home_address') : null,
                'home_address_2' => $this->input->post('home_address_2') ? $this->input->post('home_address_2') : null,
                'firpta_date' => $this->input->post('firpta_date') ? $this->input->post('firpta_date') : null,
                'firpta_signature' => $this->input->post('firpta_signature') ? $this->input->post('firpta_signature') : null,
                'tenant_id' => $this->input->post('tenant_id') ? $this->input->post('tenant_id') : null,
                'doc_type' => $this->input->post('doc_type') ? $this->input->post('doc_type') : null,
            );
            $this->home_model->insert($sellerOtherInfoData, 'pct_order_borrower_seller_other_info');

            $pdfData = array_merge($sellerEscrowInstructionData, $sellerCommissionInstructionData, $sellerOwnerEscrowInfoData, $sellerStatementInfoData, $seller593CPart1Data, $seller593CPart2Data, $sellerOtherInfoData);
            $pdfData['seller_invoices'] = $this->input->post('seller_invoices');
            $pdfData['full_address'] = $data['orderDetails']['full_address'];
            $pdfData['apn'] = $data['orderDetails']['apn'];
            $pdfData['remitter_info'] = $this->input->post('remitter_info') ? $this->input->post('remitter_info') : array();
            $pdfData['business_num'] = $this->input->post('business_num') ? $this->input->post('business_num') : array();
            $pdfData['trust_types'] = $this->input->post('trust_types') ? $this->input->post('trust_types') : array();
            $pdfData['transferor_business_num'] = $this->input->post('transferor_business_num') ? $this->input->post('transferor_business_num') : array();
            $pdfData['certifications'] = $this->input->post('certifications') ? $this->input->post('certifications') : array();
            $pdfData['transferee_business_num'] = $this->input->post('transferee_business_num') ? $this->input->post('transferee_business_num') : array();
            $pdfData['calculation_amount'] = $this->input->post('calculation_amount') ? $this->input->post('calculation_amount') : array();
            $pdfData['perjury'] = $this->input->post('perjury') ? $this->input->post('perjury') : array();
            $pdfData['docsInfo'] = $data['docsInfo'];

            $this->load->model('order/document');
            $document_name = "borrower_seller_" . date('YmdHis') . "_" . $order[0]['file_id'] . ".pdf";
            if (!is_dir('uploads/borrower')) {
                mkdir('./uploads/borrower', 0777, true);
            }
            $pdfFilePath = './uploads/borrower/' . $document_name;

            try {
                ob_clean();
                $mpdf = new \Mpdf\Mpdf();
                ini_set("pcre.backtrack_limit", "5000000");
                $html = $this->load->view('order/borrower_seller_pdf', $pdfData, true);
                $stylesheet = file_get_contents('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,200&display=swap');
                $mpdf->WriteHTML($stylesheet, 1);
                $stylesheet1 = file_get_contents('assets/frontend/css/buyer-seller-package/bootstrap.min.css');
                $mpdf->WriteHTML($stylesheet1, 1);
                $stylesheet2 = file_get_contents('assets/frontend/css/buyer-seller-package/style_pdf.css');
                $mpdf->WriteHTML($stylesheet2, 1);
                $mpdf->WriteHTML($html, 2);
                $mpdf->Output($pdfFilePath, 'F');
                ob_end_flush();
            } catch (\Mpdf\MpdfException $e) {
                echo $e->getMessage();
            }

            $this->home_model->update(array('borrower_information_document_name' => $document_name), array('file_id' => $data['orderDetails']['file_id']), 'order_details');
            $documentData = array(
                'document_name' => $document_name,
                'original_document_name' => $document_name,
                'document_type_id' => 1041,
                'document_size' => 0,
                'user_id' => 0,
                'order_id' => $data['orderDetails']['order_id'],
                'task_id' => 4,
                'description' => 'Borrower Seller Document',
                'is_sync' => 1,
                'is_uploaded_by_borrower' => 1,
            );
            $this->document->insert($documentData);
            $this->order->uploadDocumentOnAwsS3($document_name, 'borrower');

            $success[] = "Borrower seller info saved successfully and sent to Escrow officer/assistant users to verify data.";
            $data = array(
                "errors" => $errors,
                "success" => $success,
            );
            $this->session->set_userdata($data);
            redirect(base_url() . 'borrower-seller-form/' . $random_number);exit;
        }
        $this->load->view('order/borrower_seller', $data);
    }

    public function borrowerBuyerForm($random_number)
    {
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;
        $order = $this->getOrderInfo($random_number);
        $orderDetails = $this->order->get_order_details($order[0]['file_id'], 1);

        $this->db->select('*')
            ->from('pct_order_borrower_buyer_info');
        $this->db->where('order_id', $order[0]['id']);
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data['buyerInfo'] = $query->row_array();
        } else {
            $data['buyerInfo'] = array();
        }

        if (!empty($orderDetails['primary_owner'])) {
            $buyer_owner_names = explode(' ', $orderDetails['primary_owner']);
            if (count($buyer_owner_names) == 3) {
                $orderDetails['buyer_first_name'] = $buyer_owner_names[0];
                $orderDetails['buyer_middle_name'] = $buyer_owner_names[1];
                $orderDetails['buyer_last_name'] = $buyer_owner_names[2];
            } else if (count($buyer_owner_names) == 2) {
                $orderDetails['buyer_first_name'] = $buyer_owner_names[0];
                $orderDetails['buyer_middle_name'] = '';
                $orderDetails['buyer_last_name'] = $buyer_owner_names[1];
            } else {
                $orderDetails['buyer_first_name'] = $buyer_owner_names[0];
                $orderDetails['buyer_middle_name'] = '';
                $orderDetails['buyer_last_name'] = '';
            }
        } else {
            $orderDetails['buyer_first_name'] = '';
            $orderDetails['buyer_middle_name'] = '';
            $orderDetails['buyer_last_name'] = '';
        }

        if (!empty($orderDetails['secondary_owner'])) {
            $second_buyer_owner_names = explode(' ', $orderDetails['secondary_owner']);
            if (count($second_buyer_owner_names) == 3) {
                $orderDetails['second_buyer_first_name'] = $second_buyer_owner_names[0];
                $orderDetails['second_buyer_middle_name'] = $second_buyer_owner_names[1];
                $orderDetails['second_buyer_last_name'] = $second_buyer_owner_names[2];
            } else if (count($buyer_owner_names) == 2) {
                $orderDetails['second_buyer_first_name'] = $second_buyer_owner_names[0];
                $orderDetails['second_buyer_middle_name'] = '';
                $orderDetails['second_buyer_last_name'] = $second_buyer_owner_names[1];
            } else {
                $orderDetails['second_buyer_first_name'] = $second_buyer_owner_names[0];
                $orderDetails['second_buyer_middle_name'] = '';
                $orderDetails['second_buyer_last_name'] = '';
            }
        } else {
            $orderDetails['second_buyer_first_name'] = '';
            $orderDetails['second_buyer_middle_name'] = '';
            $orderDetails['second_buyer_last_name'] = '';
        }

        $data['orderDetails'] = $orderDetails;
        $errors = array();
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }

        if ($this->input->post()) {
            $borrowerBuyerInfoData = array(
                'order_id' => $this->input->post('order_id'),
                'property_address' => $this->input->post('property_address'),
                'property_address2' => $this->input->post('property_address2'),
                'property_city' => $this->input->post('property_city'),
                'property_state' => 'CA',
                'property_zip_code' => $this->input->post('property_zipcode'),
                'buyer_full_name' => $this->input->post('buyer_full_name'),
                'buyer_home_number' => $this->input->post('buyer_home_number'),
                'buyer_work_number' => $this->input->post('buyer_work_number'),
                'buyer_email_address' => $this->input->post('buyer_email_address'),
                'buyer_fax_number' => $this->input->post('buyer_fax_number'),
                'buyer_ssn' => $this->input->post('buyer_ssn'),
                'lender_name' => $this->input->post('lender_name'),
                'lender_address' => $this->input->post('lender_address'),
                'buyer_current_mailing_address' => $this->input->post('buyer_current_mailing_address'),
                'buyer_mailing_address_after_close' => $this->input->post('buyer_mailing_address_after_close'),
                'agent_name' => $this->input->post('agent_name'),
                'agent_phone_number' => $this->input->post('agent_phone_number'),
                'second_lender_name' => $this->input->post('second_lender_name') ? $this->input->post('second_lender_name') : null,
                'seond_lender_address' => $this->input->post('seond_lender_address') ? $this->input->post('seond_lender_address') : null,
                'second_agent_name' => $this->input->post('second_agent_name') ? $this->input->post('second_agent_name') : null,
                'seond_agent_phone_number' => $this->input->post('seond_agent_phone_number') ? $this->input->post('seond_agent_phone_number') : null,
                'insurance_name' => $this->input->post('insurance_name'),
                'insurance_phone_number' => $this->input->post('insurance_phone_number'),
                'insurance_address' => $this->input->post('insurance_address'),
                'insurance_company' => $this->input->post('insurance_company'),
                'buyer_date' => $this->input->post('buyer_date'),
                'buyer_signature' => $this->input->post('buyer_signature'),
                'names' => $this->input->post('names'),
                'pick_ups' => $this->input->post('pick_ups') ? implode(',', $this->input->post('pick_ups')) : null,
                'names_of_spouse' => $this->input->post('names_of_spouse'),
                'appropriate_choice' => $this->input->post('appropriate_choice') ? implode(',', $this->input->post('appropriate_choice')) : null,
                'partnership_name' => $this->input->post('partnership_name') ? $this->input->post('partnership_name') : null,
                'corporation_name' => $this->input->post('corporation_name') ? $this->input->post('corporation_name') : null,
                'vesting_form_date' => $this->input->post('vesting_form_date'),
                'vesting_form_signature' => $this->input->post('vesting_form_signature'),
                'tenant_id' => $this->input->post('tenant_id'),
                'doc_type' => $this->input->post('doc_type'),
            );
            $this->home_model->insert($borrowerBuyerInfoData, 'pct_order_borrower_buyer_packet_info');

            $borrowerBuyerEscrowInsData = array(
                'order_id' => $this->input->post('order_id'),
                'is_mortgage' => $this->input->post('is_mortgage'),
                'is_liens' => $this->input->post('is_liens'),
                'is_condominium' => $this->input->post('is_condominium'),
                'is_residence' => $this->input->post('is_residence'),
                'is_divorced' => $this->input->post('is_divorced'),
                'is_changed' => $this->input->post('is_changed'),
                'is_death' => $this->input->post('is_death'),
                'is_survey' => $this->input->post('is_survey'),
                'is_structural' => $this->input->post('is_structural') ? $this->input->post('is_structural') : 'no',
                'is_insurance' => $this->input->post('is_insurance'),
                'water_service' => $this->input->post('water_service'),
                'other_water_service_name' => $this->input->post('other_water_service_name') ? $this->input->post('other_water_service_name') : null,
                'water_service_provider_name' => $this->input->post('water_service_provider_name'),
                'first_mortgage_company' => $this->input->post('first_mortgage_company') ? $this->input->post('first_mortgage_company') : null,
                'first_mortgage_loan_number' => $this->input->post('first_mortgage_loan_number') ? $this->input->post('first_mortgage_loan_number') : null,
                'first_mortgage_area_code' => $this->input->post('first_mortgage_area_code') ? $this->input->post('first_mortgage_area_code') : null,
                'first_mortgage_phone_number' => $this->input->post('first_mortgage_phone_number') ? $this->input->post('first_mortgage_phone_number') : null,
                'second_mortgage_company' => $this->input->post('second_mortgage_company') ? $this->input->post('second_mortgage_company') : null,
                'second_mortgage_loan_number' => $this->input->post('second_mortgage_loan_number') ? $this->input->post('second_mortgage_loan_number') : null,
                'second_mortgage_area_code' => $this->input->post('second_mortgage_area_code') ? $this->input->post('second_mortgage_area_code') : null,
                'second_mortgage_phone_number' => $this->input->post('second_mortgage_phone_number') ? $this->input->post('second_mortgage_phone_number') : null,
                'third_mortgage_company' => $this->input->post('third_mortgage_company') ? $this->input->post('third_mortgage_company') : null,
                'third_mortgage_loan_number' => $this->input->post('third_mortgage_loan_number') ? $this->input->post('third_mortgage_loan_number') : null,
                'third_mortgage_area_code' => $this->input->post('third_mortgage_area_code') ? $this->input->post('third_mortgage_area_code') : null,
                'third_mortgage_phone_number' => $this->input->post('third_mortgage_phone_number') ? $this->input->post('third_mortgage_phone_number') : null,
                'first_lien_holder_name' => $this->input->post('first_lien_holder_name') ? $this->input->post('first_lien_holder_name') : null,
                'first_amount_owed' => $this->input->post('first_amount_owed') ? $this->input->post('first_amount_owed') : null,
                'second_lien_holder_name' => $this->input->post('second_lien_holder_name') ? $this->input->post('second_lien_holder_name') : null,
                'second_amount_owed' => $this->input->post('second_amount_owed') ? $this->input->post('second_amount_owed') : null,
                'first_homeowners_association' => $this->input->post('first_homeowners_association') ? $this->input->post('first_homeowners_association') : null,
                'first_property_management_company' => $this->input->post('first_property_management_company') ? $this->input->post('first_property_management_company') : null,
                'first_property_management_number' => $this->input->post('first_property_management_number') ? $this->input->post('first_property_management_number') : null,
                'second_homeowners_association' => $this->input->post('second_homeowners_association') ? $this->input->post('second_homeowners_association') : null,
                'second_property_management_company' => $this->input->post('second_property_management_company') ? $this->input->post('second_property_management_company') : null,
                'second_property_management_number' => $this->input->post('second_property_management_number') ? $this->input->post('second_property_management_number') : null,
            );
            $this->home_model->insert($borrowerBuyerEscrowInsData, 'pct_order_borrower_buyer_escrow_instructions');

            $borrowerBuyerStatementInfoData = array(
                'order_id' => $this->input->post('order_id'),
                'first_name' => $this->input->post('first_name'),
                'middle_name' => $this->input->post('middle_name'),
                'last_name' => $this->input->post('last_name'),
                'maiden_name' => $this->input->post('maiden_name'),
                'date_of_birth' => $this->input->post('date_of_birth'),
                'home_phone_number' => $this->input->post('home_phone_number'),
                'business_phone_number' => $this->input->post('business_phone_number'),
                'birthplace' => $this->input->post('birthplace'),
                'ssn' => $this->input->post('ssn'),
                'driver_license_no' => $this->input->post('driver_license_no'),
                'another_name_that_used' => $this->input->post('another_name_that_used'),
                'residence_state' => $this->input->post('residence_state'),
                'lived_year' => $this->input->post('lived_year'),
                'is_married' => $this->input->post('is_married') ? $this->input->post('is_married') : 'no',
                'date_and_place_marriage' => $this->input->post('date_and_place_marriage') ? $this->input->post('date_and_place_marriage') : null,
                'spouse_first_name' => $this->input->post('spouse_first_name') ? $this->input->post('spouse_first_name') : null,
                'spouse_middle_name' => $this->input->post('spouse_middle_name') ? $this->input->post('spouse_middle_name') : null,
                'spouse_last_name' => $this->input->post('spouse_last_name') ? $this->input->post('spouse_last_name') : null,
                'spouse_maiden_name' => $this->input->post('spouse_maiden_name') ? $this->input->post('spouse_maiden_name') : null,
                'spouse_date_of_birth' => $this->input->post('spouse_date_of_birth') ? $this->input->post('spouse_date_of_birth') : null,
                'spouse_home_phone_number' => $this->input->post('spouse_home_phone_number') ? $this->input->post('spouse_home_phone_number') : null,
                'spouse_business_phone_number' => $this->input->post('spouse_business_phone_number') ? $this->input->post('spouse_business_phone_number') : null,
                'spouse_birthplace' => $this->input->post('spouse_birthplace') ? $this->input->post('spouse_birthplace') : null,
                'spouse_ssn' => $this->input->post('spouse_ssn') ? $this->input->post('spouse_ssn') : null,
                'spouse_driver_license_no' => $this->input->post('spouse_driver_license_no') ? $this->input->post('spouse_driver_license_no') : null,
                'spouse_another_name_that_used' => $this->input->post('spouse_another_name_that_used') ? $this->input->post('spouse_another_name_that_used') : null,
                'spouse_state_residence' => $this->input->post('spouse_state_residence') ? $this->input->post('spouse_state_residence') : null,
                'spouse_lived_year' => $this->input->post('spouse_lived_year') ? $this->input->post('spouse_lived_year') : null,
                'is_domestic_partner' => $this->input->post('is_domestic_partner') ? $this->input->post('is_domestic_partner') : null,
                'domestic_first_name' => $this->input->post('domestic_first_name') ? $this->input->post('domestic_first_name') : null,
                'domestic_middle_name' => $this->input->post('domestic_middle_name') ? $this->input->post('domestic_middle_name') : null,
                'domestic_last_name' => $this->input->post('domestic_last_name') ? $this->input->post('domestic_last_name') : null,
                'domestic_maiden_name' => $this->input->post('domestic_maiden_name') ? $this->input->post('domestic_maiden_name') : null,
                'domestic_date_of_birth' => $this->input->post('domestic_date_of_birth') ? $this->input->post('domestic_date_of_birth') : null,
                'domestic_home_phone_number' => $this->input->post('domestic_home_phone_number') ? $this->input->post('domestic_home_phone_number') : null,
                'domestic_business_phone_number' => $this->input->post('domestic_business_phone_number') ? $this->input->post('domestic_business_phone_number') : null,
                'domestic_birthplace' => $this->input->post('domestic_birthplace') ? $this->input->post('domestic_birthplace') : null,
                'domestic_ssn' => $this->input->post('domestic_ssn') ? $this->input->post('domestic_ssn') : null,
                'domestic_driver_license_no' => $this->input->post('domestic_driver_license_no') ? $this->input->post('domestic_driver_license_no') : null,
                'domestic_another_name_that_used' => $this->input->post('domestic_another_name_that_used') ? $this->input->post('domestic_another_name_that_used') : null,
                'domestic_state_residence' => $this->input->post('domestic_state_residence') ? $this->input->post('domestic_state_residence') : null,
                'domestic_lived_year' => $this->input->post('domestic_lived_year') ? $this->input->post('domestic_lived_year') : null,
                'residence_number_street' => $this->input->post('residence_number_street'),
                'residence_city' => $this->input->post('residence_city'),
                'residence_from_date_to_date' => $this->input->post('residence_from_date_to_date'),
                'second_residence_number_street' => $this->input->post('second_residence_number_street') ? $this->input->post('second_residence_number_street') : null,
                'second_residence_city' => $this->input->post('second_residence_city') ? $this->input->post('second_residence_city') : null,
                'second_residence_from_date_to_date' => $this->input->post('second_residence_from_date_to_date') ? $this->input->post('second_residence_from_date_to_date') : null,
                'business_address' => $this->input->post('business_address'),
                'firm_or_business_name' => $this->input->post('firm_or_business_name'),
                'business_from_date_to_date' => $this->input->post('business_from_date_to_date'),
                'second_firm_or_business_name' => $this->input->post('second_firm_or_business_name') ? $this->input->post('second_firm_or_business_name') : null,
                'second_business_address' => $this->input->post('second_business_address') ? $this->input->post('second_business_address') : null,
                'second_business_from_date_to_date' => $this->input->post('second_business_from_date_to_date') ? $this->input->post('second_business_from_date_to_date') : null,
                'is_buyer_intends' => $this->input->post('is_buyer_intends'),
                'owner_street_address' => $this->input->post('owner_street_address'),
                'unimproved' => $this->input->post('unimproved') ? $this->input->post('unimproved') : null,
                'single_family' => $this->input->post('single_family') ? $this->input->post('single_family') : null,
                'condo_unit' => $this->input->post('condo_unit') ? $this->input->post('condo_unit') : null,
                'other' => $this->input->post('other') ? $this->input->post('other') : null,
                'is_improvement' => $this->input->post('is_improvement'),
                'is_materials' => $this->input->post('is_materials'),
                'is_loan' => $this->input->post('is_loan') ? $this->input->post('is_loan') : null,
                'lender' => $this->input->post('lender') ? $this->input->post('lender') : null,
                'loan_amount' => $this->input->post('loan_amount') ? $this->input->post('loan_amount') : null,
                'loan_account' => $this->input->post('loan_account') ? $this->input->post('loan_account') : null,
                'second_lender' => $this->input->post('second_lender') ? $this->input->post('second_lender') : null,
                'second_loan_amount' => $this->input->post('second_loan_amount') ? $this->input->post('second_loan_amount') : null,
                'second_loan_account' => $this->input->post('second_loan_account') ? $this->input->post('second_loan_account') : null,
                'executed_date' => $this->input->post('executed_date'),
                'executed_year' => $this->input->post('executed_year'),
                'executed_time' => $this->input->post('executed_time'),
                'signature' => $this->input->post('signature'),
                'second_signature' => $this->input->post('second_signature'),
            );
            $this->home_model->insert($borrowerBuyerStatementInfoData, 'pct_order_borrower_buyer_statement_of_info');

            $borrowerBuyerPreliminaryInfoData = array(
                'order_id' => $this->input->post('order_id'),
                'assessors_parcel_number' => $this->input->post('assessors_parcel_number'),
                'transferor' => $this->input->post('transferor'),
                'buyer_daytime_phone_number' => $this->input->post('buyer_daytime_phone_number'),
                'buyer_email_address' => $this->input->post('buyer_email_address'),
                'real_property_addres' => $this->input->post('real_property_addres'),
                'is_principal_residence' => $this->input->post('is_principal_residence'),
                'date_of_occupancy' => $this->input->post('intended_occupancy_day') . "-" . $this->input->post('intended_occupancy_month') . "-" . $this->input->post('intended_occupancy_year'),
                'is_disabled_veteran' => $this->input->post('is_disabled_veteran'),
                'mail_property_tax_name' => $this->input->post('mail_property_tax_name'),
                'mail_property_tax_address' => $this->input->post('mail_property_tax_address'),
                'mail_property_tax_city' => $this->input->post('mail_property_tax_city'),
                'mail_property_tax_state' => $this->input->post('mail_property_tax_state'),
                'mail_property_tax_zipcode' => $this->input->post('mail_property_tax_zipcode'),
            );
            $this->home_model->insert($borrowerBuyerPreliminaryInfoData, 'pct_order_borrower_buyer_preliminary_change_info');

            $borrowerBuyerTransferInfoData = array(
                'order_id' => $this->input->post('order_id'),
                'is_transfer_between_spouses' => $this->input->post('is_transfer_between_spouses'),
                'is_transfer_between_domestic_partners' => $this->input->post('is_transfer_between_domestic_partners'),
                'is_transfer' => $this->input->post('is_transfer'),
                'is_parent_child_transfer' => $this->input->post('is_parent_child_transfer'),
                'is_principal_residence' => $this->input->post('is_principal_residence'),
                'date_of_death' => $this->input->post('date_of_death'),
                'is_cotenant_death' => $this->input->post('is_cotenant_death'),
                'is_replace_principal_residence_own' => $this->input->post('is_replace_principal_residence_own'),
                'is_replace_principal_residence_own_in_same_county' => $this->input->post('is_replace_principal_residence_own_in_same_county'),
                'is_replace_principal_residence_person_disabled' => $this->input->post('is_replace_principal_residence_person_disabled'),
                'is_replace_principal_residence_person_disabled_in_same_county' => $this->input->post('is_replace_principal_residence_person_disabled_in_same_county'),
                'is_replace_principal_residence_damaged' => $this->input->post('is_replace_principal_residence_damaged'),
                'is_replace_principal_residence_damaged_in_same_county' => $this->input->post('is_replace_principal_residence_damaged_in_same_county'),
                'is_name_change' => $this->input->post('is_name_change'),
                'name_change_reason' => $this->input->post('name_change_reason') ? $this->input->post('name_change_reason') : '',
                'is_lender_interest' => $this->input->post('is_lender_interest'),
                'lender_interest_reason' => $this->input->post('lender_interest_reason') ? $this->input->post('lender_interest_reason') : '',
                'is_financing_purpose' => $this->input->post('is_financing_purpose'),
                'financing_purpose_reason' => $this->input->post('financing_purpose_reason') ? $this->input->post('financing_purpose_reason') : null,
                'is_trustee_of_trust' => $this->input->post('is_trustee_of_trust'),
                'is_transfer_property' => $this->input->post('is_transfer_property'),
                'benefit' => $this->input->post('benefit'),
                'trustor' => $this->input->post('trustor'),
                'is_subject_to_lease' => $this->input->post('is_subject_to_lease'),
                'is_transfer_between_parties' => $this->input->post('is_transfer_between_parties'),
                'is_subsidized_low_income' => $this->input->post('is_subsidized_low_income'),
                'is_solar_energy_system' => $this->input->post('is_solar_energy_system'),
                'is_transfer_other' => $this->input->post('is_transfer_other'),
                'other_transfer' => $this->input->post('other_transfer') ? $this->input->post('other_transfer') : null,
                'recording_date' => $this->input->post('recording_date'),
                'types_of_transfer' => implode(",", $this->input->post('types_of_transfer')),
                'date_of_contract' => $this->input->post('date_of_contract'),
                'date_of_lease_began' => $this->input->post('date_of_lease_began'),
                'date_of_death_transfer' => $this->input->post('date_of_death_transfer'),
                'original_terms_in_year' => $this->input->post('original_terms_in_year') ? $this->input->post('original_terms_in_year') : null,
                'remaining_terms_in_year' => $this->input->post('remaining_terms_in_year') ? $this->input->post('remaining_terms_in_year') : null,
                'is_partial_interest' => $this->input->post('is_partial_interest'),
                'start_percentage_range' => $this->input->post('start_percentage_range') ? $this->input->post('start_percentage_range') : null,
                'end_percentage_range' => $this->input->post('end_percentage_range') ? $this->input->post('end_percentage_range') : null,
            );
            $this->home_model->insert($borrowerBuyerTransferInfoData, 'pct_order_borrower_buyer_transfer_info');

            $borrowerBuyerPurchaseSaleInfoData = array(
                'order_id' => $this->input->post('order_id'),
                'total_purchase_price' => $this->input->post('total_purchase_price'),
                'cash_down_payment' => $this->input->post('cash_down_payment'),
                'first_deed_of_trust_interest' => $this->input->post('first_deed_of_trust_interest') ? $this->input->post('first_deed_of_trust_interest') : null,
                'first_deed_of_trust_years' => $this->input->post('first_deed_of_trust_years') ? $this->input->post('first_deed_of_trust_years') : null,
                'first_deed_of_trust_monthly_payment' => $this->input->post('first_deed_of_trust_monthly_payment') ? $this->input->post('first_deed_of_trust_monthly_payment') : null,
                'first_deed_payment_types' => $this->input->post('first_deed_payment_types') ? implode(',', $this->input->post('first_deed_payment_types')) : null,
                'first_deed_due_date' => $this->input->post('first_deed_due_date') ? $this->input->post('first_deed_due_date') : null,
                'second_deed_of_trust_interest' => $this->input->post('second_deed_of_trust_interest') ? $this->input->post('second_deed_of_trust_interest') : null,
                'second_deed_of_trust_years' => $this->input->post('second_deed_of_trust_years') ? $this->input->post('second_deed_of_trust_years') : null,
                'second_deed_of_trust_monthly_payment' => $this->input->post('second_deed_of_trust_monthly_payment') ? $this->input->post('second_deed_of_trust_monthly_payment') : null,
                'second_deed_of_trust_amount' => $this->input->post('second_deed_of_trust_amount') ? $this->input->post('second_deed_of_trust_amount') : null,
                'second_deed_payment_types' => $this->input->post('second_deed_payment_types') ? implode(',', $this->input->post('second_deed_payment_types')) : null,
                'ballon_payment' => $this->input->post('ballon_payment') ? $this->input->post('ballon_payment') : null,
                'second_deed_due_date' => $this->input->post('second_deed_due_date') ? $this->input->post('second_deed_due_date') : null,
                'is_financing' => $this->input->post('is_financing') ? $this->input->post('is_financing') : null,
                'outstanding_balance' => $this->input->post('outstanding_balance') ? $this->input->post('outstanding_balance') : null,
                'real_estate_commission' => $this->input->post('real_estate_commission') ? $this->input->post('real_estate_commission') : null,
                'property_purchase_via' => $this->input->post('property_purchase_via'),
                'broker_phone_number' => $this->input->post('broker_phone_number') ? $this->input->post('broker_phone_number') : null,
                'property_purchase_via_name' => $this->input->post('property_purchase_via_name') ? $this->input->post('property_purchase_via_name') : null,
                'broker_name' => $this->input->post('broker_name') ? $this->input->post('broker_name') : null,
                'other_through' => $this->input->post('other_through') ? $this->input->post('other_through') : null,
                'types_of_property_transferred' => implode(',', $this->input->post('types_of_property_transferred')),
                'num_of_units' => $this->input->post('num_of_units') ? $this->input->post('num_of_units') : null,
                'is_personal_property' => $this->input->post('is_personal_property'),
                'peronal_property_value' => $this->input->post('peronal_property_value') ? $this->input->post('peronal_property_value') : null,
                'incentives' => $this->input->post('incentives') ? $this->input->post('incentives') : null,
                'is_manufacture_home_included_in_purchase_price' => $this->input->post('is_manufacture_home_included_in_purchase_price'),
                'value_manufacture_home' => $this->input->post('value_manufacture_home') ? $this->input->post('value_manufacture_home') : null,
                'is_manufacture_home_tax' => $this->input->post('is_manufacture_home_tax'),
                'deal_number' => $this->input->post('deal_number') ? $this->input->post('deal_number') : null,
                'is_property_produce_income' => $this->input->post('is_property_produce_income'),
                'income_type' => $this->input->post('income_type') ? $this->input->post('income_type') : null,
                'other_income_type' => $this->input->post('other_income_type') ? $this->input->post('other_income_type') : null,
                'property_condition' => $this->input->post('property_condition'),
                'property_condition_describe' => $this->input->post('property_condition_describe') ? $this->input->post('property_condition_describe') : null,
                'signature_corporate_officer_date' => $this->input->post('signature_corporate_officer_date'),
                'corporate_officer_telephone' => $this->input->post('corporate_officer_telephone'),
                'corporate_officer_name' => $this->input->post('corporate_officer_name'),
                'corporate_officer_email' => $this->input->post('corporate_officer_email'),
            );
            $this->home_model->insert($borrowerBuyerPurchaseSaleInfoData, 'pct_order_borrower_buyer_purchase_sale_info');

            $pdfData = array_merge($borrowerBuyerInfoData, $borrowerBuyerEscrowInsData, $borrowerBuyerStatementInfoData, $borrowerBuyerPreliminaryInfoData, $borrowerBuyerTransferInfoData, $borrowerBuyerPurchaseSaleInfoData);
            $pdfData['full_address'] = $data['orderDetails']['full_address'];
            $pdfData['types_of_transfer'] = $this->input->post('types_of_transfer');
            $pdfData['first_deed_payment_types'] = $this->input->post('first_deed_payment_types') ? $this->input->post('first_deed_payment_types') : array();
            $pdfData['types_of_property_transferred'] = $this->input->post('types_of_property_transferred') ? $this->input->post('types_of_property_transferred') : array();
            $pdfData['second_deed_payment_types'] = $this->input->post('second_deed_payment_types') ? $this->input->post('second_deed_payment_types') : array();
            $pdfData['appropriate_choice'] = $this->input->post('appropriate_choice') ? $this->input->post('appropriate_choice') : array();
            $pdfData['pick_ups'] = $this->input->post('pick_ups') ? $this->input->post('pick_ups') : array();

            $this->load->model('order/document');
            $borrowerDocumentCount = $this->document->countBorrowerDocument($data['orderDetails']['id']);
            $document_name = "borrower_buyer_" . date('YmdHis') . "_" . $order[0]['file_id'] . ".pdf";
            if (!is_dir('uploads/borrower')) {
                mkdir('./uploads/borrower', 0777, true);
            }
            $pdfFilePath = './uploads/borrower/' . $document_name;

            try {
                ob_clean();
                $mpdf = new \Mpdf\Mpdf();
                ini_set("pcre.backtrack_limit", "5000000");
                $html = $this->load->view('order/borrower_buyer_pdf', $pdfData, true);
                $stylesheet = file_get_contents('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,200&display=swap');
                $mpdf->WriteHTML($stylesheet, 1);
                $stylesheet1 = file_get_contents('assets/frontend/css/buyer-seller-package/bootstrap.min.css');
                $mpdf->WriteHTML($stylesheet1, 1);
                $stylesheet2 = file_get_contents('assets/frontend/css/buyer-seller-package/style_pdf.css');
                $mpdf->WriteHTML($stylesheet2, 1);
                $mpdf->WriteHTML($html, 2);
                $mpdf->Output($pdfFilePath, 'F');
                ob_end_flush();
            } catch (\Mpdf\MpdfException $e) {
                echo $e->getMessage();
            }

            $this->home_model->update(array('borrower_information_document_name' => $document_name), array('file_id' => $data['orderDetails']['file_id']), 'order_details');
            $documentData = array(
                'document_name' => $document_name,
                'original_document_name' => $document_name,
                'document_type_id' => 1041,
                'document_size' => 0,
                'user_id' => 0,
                'order_id' => $data['orderDetails']['order_id'],
                'task_id' => 4,
                'description' => 'Borrower Buyer Document',
                'is_sync' => 1,
                'is_uploaded_by_borrower' => 1,
            );
            $this->document->insert($documentData);
            $this->order->uploadDocumentOnAwsS3($document_name, 'borrower');
            $success[] = "Borrower buyer info saved successfully and sent to Escrow officer/assistant users to verify data.";
            $data = array(
                "errors" => $errors,
                "success" => $success,
            );
            $this->session->set_userdata($data);
            redirect(base_url() . 'borrower-buyer-form/' . $random_number);exit;
        }
        $this->load->view('order/borrower_buyer', $data);
    }

    public function buyerInfo($random_number)
    {
        $this->load->model('order/document');
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;
        $order = $this->getOrderInfo($random_number);
        $orderDetails = $this->order->get_order_details($order[0]['file_id'], 1);
        $data['orderDetails'] = $orderDetails;
        $buyer_where['order_id'] = $orderDetails['order_id'];
        $buyer_order['is_main_buyer'] = 'desc';
        $data['buyers'] = $this->home_model->get_records('pct_order_borrower_buyer_info', $buyer_where, $buyer_order);
        $data['marital_status'] = $this->marital_status;
        $data['vesting_choice'] = $this->vesting_choice;

        $errors = array();
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }

        if ($this->input->post()) {
            $buyers_names = $buyer_pdf = array();
            $buyer_infos = $this->input->post('buyer');
            $vesting_info = $skip_vesting = $main_buyer = array();
            $is_first = 1;
            $buyer_email = '';
            foreach ($buyer_infos as $buyer_key => $buyer_value) {
                // Check Keys
                $buyer_info = [
                    'order_id' => $this->input->post('order_id'),
                    'first_name' => $buyer_value['first_name'] ? $buyer_value['first_name'] : null,
                    'last_name' => $buyer_value['last_name'] ? $buyer_value['last_name'] : null,
                    'email' => $buyer_value['email'] ? $buyer_value['email'] : null,
                    'phone' => $buyer_value['phone'] ? $buyer_value['phone'] : null,
                    'birth_month' => $buyer_value['birth_month'] ? $buyer_value['birth_month'] : null,
                    'birth_date' => $buyer_value['birth_date'] ? $buyer_value['birth_date'] : null,
                    'birth_year' => $buyer_value['birth_year'] ? $buyer_value['birth_year'] : null,
                    'ssn' => $buyer_value['ssn'] ? $buyer_value['ssn'] : null,
                    'current_mailing_address' => $buyer_value['current_mailing_address'] ? $buyer_value['current_mailing_address'] : null,
                    'mailing_address_port_closing' => $buyer_value['mailing_address_port_closing'] ? $buyer_value['mailing_address_port_closing'] : null,
                    'marital_status' => $buyer_value['marital_status'] ? $buyer_value['marital_status'] : null,
                    'married_to' => $buyer_value['married_to'] ? $buyer_value['married_to'] : null,
                ];
                if ($buyer_info['first_name'] && $buyer_info['last_name']) {
                    if ($buyer_key == 'new') {
                        $buyer_info['is_main_buyer'] = 0;
                        $new_buyer_id = $this->home_model->insert($buyer_info, 'pct_order_borrower_buyer_info');
                    } else {
                        //Update Value
                        $buyer_update['id'] = $buyer_key;
                        $this->home_model->update($buyer_info, $buyer_update, 'pct_order_borrower_buyer_info');
                    }

                    $buyers_names[] = $buyer_info['first_name'] . ' ' . $buyer_info['last_name'];
                    $buyer_pdf['first_names'][] = $buyer_info['first_name'];
                    $buyer_pdf['last_names'][] = $buyer_info['last_name'];
                    $buyer_pdf['current_mailing_address'][] = $buyer_info['current_mailing_address'];
                    $buyer_pdf['phones'][] = $buyer_info['phone'];
                    $buyer_pdf['emails'][] = $buyer_info['email'];
                    $buyer_pdf['ssns'][] = $buyer_info['ssn'];
                    $buyer_pdf['birth_dates'][] = $buyer_info['birth_month'] . "/" . $buyer_info['birth_date'] . "/" . $buyer_info['birth_year'];

                    if ($is_first) {
                        $buyer_pdf['current_address'] = $buyer_info['current_mailing_address'];
                        $buyer_pdf['closing_address'] = $buyer_info['mailing_address_port_closing'];
                        $main_buyer = $buyer_info;
                        $buyer_email = $buyer_value['email'];
                        $is_first = 0;
                    }
                    if (!(in_array($buyer_key, $skip_vesting))) {

                        $vesting_name = $buyer_info['first_name'] . ' ' . $buyer_info['last_name'];

                        if ($buyer_info['married_to'] && $buyer_infos[$buyer_info['married_to']]) {
                            // echo "in";die;

                            $vesting_name .= ' And ' . $buyer_infos[$buyer_info['married_to']]['first_name'] . ' ' . $buyer_infos[$buyer_info['married_to']]['last_name'];
                            $vesting_name .= ' ' . ucwords(str_replace("_", " ", $buyer_info['marital_status']));

                            $skip_vesting[] = $buyer_info['married_to'];
                        } else {
                            $vesting_name .= ' ' . ucwords(str_replace("_", " ", $buyer_info['marital_status']));
                        }

                        $vesting_info['names'][] = $vesting_name;
                        $vesting_info['marital_status'][] = $buyer_info['marital_status'];
                    }
                }
            }
            // echo '<pre>';var_dump($vesting_info);
            // die;
            $buyer_pdf['buyers_name'] = $buyers_names;

            $this->home_model->update(array('married_to' => $new_buyer_id), array('married_to' => 'new_buyer', 'order_id' => $this->input->post('order_id')), 'pct_order_borrower_buyer_info');
            $borrowerBuyerInfoData = array(
                'order_id' => $this->input->post('order_id'),
                'is_same_property' => $this->input->post('is_same_property'),
                'loan_amount' => $this->input->post('loan_amount'),
                'lender_name' => $this->input->post('lender_name'),
                'loan_officer_name' => $this->input->post('loan_officer_name'),
                'loan_officer_email' => $this->input->post('loan_officer_email'),
                'loan_officer_phone' => $this->input->post('loan_officer_phone'),
                'is_loan_processor' => $this->input->post('is_loan_processor'),
                'loan_processor_name' => $this->input->post('loan_processor_name'),
                'loan_processor_email' => $this->input->post('loan_processor_email'),
                'loan_processor_phone' => $this->input->post('loan_processor_phone'),
                'is_home_ins' => $this->input->post('is_home_ins'),
                'ins_agency_name' => $this->input->post('ins_agency_name'),
                'ins_agent_name' => $this->input->post('ins_agent_name'),
                'ins_agent_email' => $this->input->post('ins_agent_email'),
                'ins_agent_phone' => $this->input->post('ins_agent_phone'),
                'annual_premium' => $this->input->post('annual_premium'),
                'property_vested' => $this->input->post('property_vested'),
            );
            $inserted_wizard_id = $this->home_model->insert($borrowerBuyerInfoData, 'pct_order_borrower_buyer_info_wizard');

            $buyerInfo2Data = array(
                'is_used_another_last_name' => $this->input->post('is_used_another_last_name') ? $this->input->post('is_used_another_last_name') : null,
                'another_last_name' => $this->input->post('another_last_name') ? $this->input->post('another_last_name') : null,
                'is_married_or_domestic_partner' => $this->input->post('is_married_or_domestic_partner') ? $this->input->post('is_married_or_domestic_partner') : null,
                'marriage_or_domestic_day' => $this->input->post('marriage_or_domestic_day') ? $this->input->post('marriage_or_domestic_day') : null,
                'marriage_or_domestic_month' => $this->input->post('marriage_or_domestic_month') ? $this->input->post('marriage_or_domestic_month') : null,
                'marriage_or_domestic_year' => $this->input->post('marriage_or_domestic_year') ? $this->input->post('marriage_or_domestic_year') : null,
                'spouse_first_name' => $this->input->post('spouse_first_name') ? $this->input->post('spouse_first_name') : null,
                'spouse_last_name' => $this->input->post('spouse_last_name') ? $this->input->post('spouse_last_name') : null,
                'spouse_email' => $this->input->post('spouse_email') ? $this->input->post('spouse_email') : null,
                'spouse_phone' => $this->input->post('spouse_phone') ? $this->input->post('spouse_phone') : null,
                'spouse_birth_day' => $this->input->post('spouse_birth_day') ? $this->input->post('spouse_birth_day') : null,
                'spouse_birth_month' => $this->input->post('spouse_birth_month') ? $this->input->post('spouse_birth_month') : null,
                'spouse_birth_year' => $this->input->post('spouse_birth_year') ? $this->input->post('spouse_birth_year') : null,
                'spouse_ssn' => $this->input->post('spouse_ssn') ? $this->input->post('spouse_ssn') : null,
                'is_property_sell_2' => $this->input->post('is_property_sell') ? $this->input->post('is_property_sell') : null,
                'another_property_sell' => $this->input->post('another_property_sell') ? $this->input->post('another_property_sell') : null,
                'from_date' => $this->input->post('from_date') ? $this->input->post('from_date') : null,
                'from_to' => $this->input->post('from_to') ? $this->input->post('from_to') : null,
                'is_another_residence' => $this->input->post('is_another_residence') ? $this->input->post('is_another_residence') : null,
                'another_residence' => $this->input->post('another_residence') ? $this->input->post('another_residence') : null,
                'another_from_date' => $this->input->post('another_from_date') ? $this->input->post('another_from_date') : null,
                'another_to_date' => $this->input->post('another_to_date') ? $this->input->post('another_to_date') : null,
                'is_currently_employed' => $this->input->post('is_currently_employed') ? $this->input->post('is_currently_employed') : null,
                'employee_company_name' => $this->input->post('employee_company_name') ? $this->input->post('employee_company_name') : null,
                'from_employee_date' => $this->input->post('from_employee_date') ? $this->input->post('from_employee_date') : null,
                'to_employee_date' => $this->input->post('to_employee_date') ? $this->input->post('to_employee_date') : null,
                'is_add_another_occupation' => $this->input->post('is_add_another_occupation') ? $this->input->post('is_add_another_occupation') : null,
                'employee_another_company_name' => $this->input->post('employee_another_company_name') ? $this->input->post('employee_another_company_name') : null,
                'another_from_employee_date' => $this->input->post('another_from_employee_date') ? $this->input->post('another_from_employee_date') : null,
                'another_to_employee_date' => $this->input->post('another_to_employee_date') ? $this->input->post('another_to_employee_date') : null,
                'is_spouse_domestic_partner_employed' => $this->input->post('is_spouse_domestic_partner_employed') ? $this->input->post('is_spouse_domestic_partner_employed') : null,
                'spouse_company_name' => $this->input->post('spouse_company_name') ? $this->input->post('spouse_company_name') : null,
                'from_spouse_date' => $this->input->post('from_spouse_date') ? $this->input->post('from_spouse_date') : null,
                'is_another_occupation_spouse_domestic' => $this->input->post('another_to_employee_date') ? $this->input->post('another_to_employee_date') : null,
                'another_spouse_company_name' => $this->input->post('another_spouse_company_name') ? $this->input->post('another_spouse_company_name') : null,
                'another_from_spouse_date' => $this->input->post('another_from_spouse_date') ? $this->input->post('another_from_spouse_date') : null,
                'another_to_spouse_date' => $this->input->post('another_to_spouse_date') ? $this->input->post('another_to_spouse_date') : null,
            );
            $this->home_model->insert($buyerInfo2Data, 'pct_order_borrower_buyer_info_wizard_2');

            $vesting_buyer_name = implode(', ', $vesting_info['names']);

            $pdf_fields_val = [
                'buyer_another_name' => $this->input->post('is_used_another_last_name') ? $this->input->post('is_used_another_last_name') : null,
                'escrow_number' => $orderDetails['escrow_number'] ? $orderDetails['escrow_number'] : $orderDetails['file_number'],
                'escrow_number_1' => $orderDetails['escrow_number'] ? $orderDetails['escrow_number'] : $orderDetails['file_number'],
                'title_number' => $orderDetails['file_number'],
                'title_number_1' => $orderDetails['file_number'],
                'property_address' => $orderDetails['full_address'],
                'property_address_1' => $orderDetails['full_address'],
                'lender_name' => $this->input->post('lender_name') ? $this->input->post('lender_name') : '',
                'lender_loan_amount' => $this->input->post('loan_amount') ? $this->input->post('loan_amount') : '',
                'lender_loan_officer' => $this->input->post('loan_officer_name') ? $this->input->post('loan_officer_name') : '',
                'lender_email' => $this->input->post('loan_officer_email') ? $this->input->post('loan_officer_email') : '',
                'lender_phone' => $this->input->post('loan_officer_phone') ? $this->input->post('loan_officer_phone') : '',
                'lender_name_1' => $this->input->post('loan_processor_name') ? $this->input->post('loan_processor_name') : '',
                'lender_loan_amount_1' => $this->input->post('loan_amount') ? $this->input->post('loan_amount') : '',
                'lender_loan_officer_1' => $this->input->post('loan_officer_name') ? $this->input->post('loan_officer_name') : '',
                'lender_email_1' => $this->input->post('loan_processor_email') ? $this->input->post('loan_processor_email') : '',
                'lender_phone_1' => $this->input->post('loan_processor_phone') ? $this->input->post('loan_processor_phone') : '',
                'hoa_company' => $this->input->post('ins_agency_name') ? $this->input->post('ins_agency_name') : '',
                'hoa_agent' => $this->input->post('ins_agent_name') ? $this->input->post('ins_agent_name') : '',
                'hoa_email' => $this->input->post('ins_agent_email') ? $this->input->post('ins_agent_email') : '',
                'hoa_phone' => $this->input->post('ins_agent_phone') ? $this->input->post('ins_agent_phone') : '',
                'hoa_quote' => $this->input->post('annual_premium') ? $this->input->post('annual_premium') : '',
                'tc_company' => '',
                'tc_name' => '',
                'tc_phone' => '',
                'tc_email' => '',
                'buyer_married' => $this->input->post('marriage_or_domestic_month') ? 'Yes' : 'No',
                'escrow_number_3' => $orderDetails['escrow_number'] ? $orderDetails['escrow_number'] : $orderDetails['file_number'],
                'escrow_number_2' => $orderDetails['escrow_number'] ? $orderDetails['escrow_number'] : $orderDetails['file_number'],
                'residence_address' => $this->input->post('is_property_sell_2') == 'Yes' ? $orderDetails['full_address'] : $this->input->post('another_property_sell'),
                'residence_address_1' => $this->input->post('another_residence') ? $this->input->post('another_residence') : '',
                'residence_address_2' => '',
                'residence_from' => $this->input->post('from_date') ? $this->input->post('from_date') : '',
                'residence_from_1' => $this->input->post('another_from_date') ? $this->input->post('another_from_date') : '',
                'residence_from_2' => '',
                'residence_to' => $this->input->post('from_to') ? $this->input->post('from_to') : '',
                'residence_to_1' => $this->input->post('another_to_date') ? $this->input->post('another_to_date') : '',
                'residence_to_2' => '',
                'buyer_birth_place' => '',
                'buyer_last_name_2' => $buyer_pdf['last_names'][0],
                'buyer_lived_in_usa' => '',
                'buyer_married' => $this->input->post('is_married') == 'married' ? 'Yes' : 'No',
                'buyer_no' => '1',
                'buyer_occupation' => $this->input->post('employee_company_name') ? $this->input->post('employee_company_name') : '',
                'buyer_occupation_1' => $this->input->post('employee_another_company_name') ? $this->input->post('employee_another_company_name') : '',
                'buyer_occupation_2' => '',
                'buyer_occupation_from' => $this->input->post('from_employee_date') ? $this->input->post('from_employee_date') : '',
                'buyer_occupation_from_1' => $this->input->post('another_from_employee_date') ? $this->input->post('another_from_employee_date') : '',
                'buyer_occupation_from_2' => '',
                'buyer_occupation_to' => $this->input->post('to_employee_date') ? $this->input->post('to_employee_date') : '',
                'buyer_occupation_to_1' => $this->input->post('another_to_employee_date') ? $this->input->post('another_to_employee_date') : '',
                'buyer_occupation_to_2' => '',
                'buyer_ssn_2' => $buyer_pdf['ssns'][0],
                'buyer_state_residence' => '',
                'buyer_first_name_2' => $buyer_pdf['first_names'][0],
                'buyer_birth_date_2' => $buyer_pdf['birth_dates'][0],
                'spouse_birth_place' => '',
                'spouse_date_of_birth' => $this->input->post('spouse_birth_day') ? $this->input->post('spouse_birth_month') . "/" . $this->input->post('spouse_birth_day') . "/" . $this->input->post('spouse_birth_year') : '',
                'spouse_domestic_address' => $this->input->post('spouse_company_name') ? $this->input->post('spouse_company_name') : '',
                'spouse_domestic_address_1' => $this->input->post('another_spouse_company_name') ? $this->input->post('another_spouse_company_name') : '',
                'spouse_domestic_address_2' => '',
                'spouse_domestic_from' => $this->input->post('from_spouse_date') ? $this->input->post('from_spouse_date') : '',
                'spouse_domestic_from_1' => $this->input->post('another_from_spouse_date') ? $this->input->post('another_from_spouse_date') : '',
                'spouse_domestic_from_2' => '',
                'spouse_domestic_to' => $this->input->post('to_spouse_date') ? $this->input->post('to_spouse_date') : '',
                'spouse_domestic_to_1' => $this->input->post('another_to_spouse_date') ? $this->input->post('another_to_spouse_date') : '',
                'spouse_domestic_to_2' => '',
                'spouse_driver_license' => '',
                'spouse_first_name' => $this->input->post('spouse_first_name') ? $this->input->post('spouse_first_name') : '',
                'spouse_last_name' => $this->input->post('spouse_last_name') ? $this->input->post('spouse_last_name') : '',
                'spouse_lived_in_usa' => '',
                'spouse_marriage_date' => $this->input->post('marriage_or_domestic_month') ? $this->input->post('marriage_or_domestic_month') . "/" . $this->input->post('marriage_or_domestic_day') . "/" . $this->input->post('marriage_or_domestic_year') : '',
                'buyer_date_of_marriage' => $this->input->post('marriage_or_domestic_month') ? $this->input->post('marriage_or_domestic_month') . "/" . $this->input->post('marriage_or_domestic_day') . "/" . $this->input->post('marriage_or_domestic_year') : '',
                'spouse_other_last_name' => '',
                'spouse_residence' => '',
                'spouse_ssn' => $this->input->post('spouse_ssn') ? $this->input->post('spouse_ssn') : '',
                'title_number_2' => $orderDetails['file_number'],
                'title_number_3' => $orderDetails['file_number'],
                'vesting_names' => $vesting_buyer_name,
                'property_vested' => $this->input->post('property_vested'),
                'proceeds_refund' => $this->input->post('proceeds_refund'),
                'transfer_all_proceeds_att' => $this->input->post('transfer_all_proceeds_att') ? $this->input->post('transfer_all_proceeds_att') : '',
                'transfer_all_proceeds_esc' => $this->input->post('transfer_all_proceeds_esc') ? $this->input->post('transfer_all_proceeds_esc') : '',
                'transfer_portion_att' => $this->input->post('transfer_portion_att') ? $this->input->post('transfer_portion_att') : '',
                'transfer_portion_att_esc' => $this->input->post('transfer_portion_att_esc') ? $this->input->post('transfer_portion_att_esc') : '',
                'fed_Ex_check_address' => $this->input->post('fed_Ex_check_address') ? $this->input->post('fed_Ex_check_address') : '',
                'bank_name' => $this->input->post('bank_name') ? $this->input->post('bank_name') : '',
                'account_name' => $this->input->post('account_name') ? $this->input->post('account_name') : '',
                'wire_proceed_phone' => $this->input->post('wire_proceed_phone') ? $this->input->post('wire_proceed_phone') : '',
                'routing_number' => $this->input->post('routing_number') ? $this->input->post('routing_number') : '',
                'account_number' => $this->input->post('account_number') ? $this->input->post('account_number') : '',
                'is_reside_property' => $this->input->post('is_reside_property') ? $this->input->post('is_reside_property') : '',
                'is_property_address' => $this->input->post('is_property_address') ? $this->input->post('is_property_address') : '',
                'is_unimproved_improved' => $this->input->post('is_unimproved_improved') ? $this->input->post('is_unimproved_improved') : '',
                'is_improvement' => $this->input->post('is_improvement') ? $this->input->post('is_improvement') : '',
                'is_full_paid' => $this->input->post('is_full_paid') ? $this->input->post('is_full_paid') : '',
                'is_loan' => $this->input->post('is_loan') ? $this->input->post('is_loan') : '',
                'lender_name_2' => $this->input->post('lender_name_2') ? $this->input->post('lender_name_2') : '',
                'lender_loan_amount' => $this->input->post('lender_loan_amount') ? $this->input->post('lender_loan_amount') : '',
                'lender_acct_no' => $this->input->post('lender_acct_no') ? $this->input->post('lender_acct_no') : '',
            ];

            //print_r($vesting_info['marital_status']);
            if ($vesting_info['marital_status'] && is_array($vesting_info['marital_status'])) {
                foreach ($vesting_info['marital_status'] as $vesting_info) {
                    $pdf_fields_val[$vesting_info] = 'Checked';
                }
            }

            //print_r($pdf_fields_val);exit;

            if (count($buyer_pdf['emails']) > 0) {
                $total_count = count($buyer_pdf['emails']);
                $i = 1;
                $j = 1;
                $email_sent_flag = 0;
                foreach ($buyer_pdf['emails'] as $buyer_email) {
                    if ($total_count == $i) {
                        if ($i % 2 != 0) {
                            $pdf_fields_val['buyer_first_name'] = $buyer_pdf['first_names'][$i - 1];
                            $pdf_fields_val['buyer_last_name'] = $buyer_pdf['last_names'][$i - 1];
                            $pdf_fields_val['buyer_cell_phone'] = $buyer_pdf['phones'][$i - 1];
                            $pdf_fields_val['buyer_home_phone'] = '';
                            $pdf_fields_val['buyer_email'] = $buyer_pdf['emails'][$i - 1];
                            $pdf_fields_val['buyer_ssn'] = $buyer_pdf['ssns'][$i - 1];
                            $pdf_fields_val['buyer_current_mailing_address'] = $buyer_pdf['current_mailing_address'][$i - 1];
                            $pdf_fields_val['buyer_num_field_1'] = $i;
                            $pdf_fields_val['signature_1_name'] = $buyer_pdf['first_names'][$i - 1] . " " . $buyer_pdf['last_names'][$i - 1];

                            $pdf_fields_val['buyer_first_name_1'] = "";
                            $pdf_fields_val['buyer_last_name_1'] = "";
                            $pdf_fields_val['buyer_cell_phone_1'] = "";
                            $pdf_fields_val['buyer_home_phone_1'] = "";
                            $pdf_fields_val['buyer_email_1'] = "";
                            $pdf_fields_val['buyer_ssn_1'] = "";
                            $pdf_fields_val['buyer_current_mailing_address_1'] = "";
                            $pdf_fields_val['buyer_num_field_2'] = '';
                            $pdf_fields_val['signature_2_name'] = '';
                            $email_sent_odd_flag = 1;
                        }
                    }
                    if ($i % 2 == 0) {
                        $pdf_fields_val['buyer_first_name'] = $buyer_pdf['first_names'][$i - 2];
                        $pdf_fields_val['buyer_last_name'] = $buyer_pdf['last_names'][$i - 2];
                        $pdf_fields_val['buyer_cell_phone'] = $buyer_pdf['phones'][$i - 2];
                        $pdf_fields_val['buyer_home_phone'] = '';
                        $pdf_fields_val['buyer_email'] = $buyer_pdf['emails'][$i - 2];
                        $pdf_fields_val['buyer_ssn'] = $buyer_pdf['ssns'][$i - 2];
                        $pdf_fields_val['buyer_current_mailing_address'] = $buyer_pdf['current_mailing_address'][$i - 2];
                        $pdf_fields_val['buyer_num_field_1'] = $i - 1;
                        $pdf_fields_val['signature_1_name'] = $buyer_pdf['first_names'][$i - 2] . " " . $buyer_pdf['last_names'][$i - 2];

                        $pdf_fields_val['buyer_first_name_1'] = $buyer_pdf['first_names'][$i - 1];
                        $pdf_fields_val['buyer_last_name_1'] = $buyer_pdf['last_names'][$i - 1];
                        $pdf_fields_val['buyer_cell_phone_1'] = $buyer_pdf['phones'][$i - 1];
                        $pdf_fields_val['buyer_home_phone_1'] = '';
                        $pdf_fields_val['buyer_email_1'] = $buyer_pdf['emails'][$i - 1];
                        $pdf_fields_val['buyer_ssn_1'] = $buyer_pdf['ssns'][$i - 1];
                        $pdf_fields_val['buyer_current_mailing_address_1'] = $buyer_pdf['current_mailing_address'][$i - 1];

                        $pdf_fields_val['signature_2_name'] = $buyer_pdf['first_names'][$i - 1] . " " . $buyer_pdf['last_names'][$i - 1];
                        $pdf_fields_val['buyer_num_field_2'] = $i;
                        $email_sent_even_flag = 1;
                    }

                    if ($email_sent_odd_flag == 1 || $email_sent_even_flag == 1) {
                        $mergeFieldInfo = array();
                        foreach ($pdf_fields_val as $key => $value) {
                            $mergeFieldInfo[] = array(
                                'fieldName' => $key,
                                'defaultValue' => $value,
                            );
                        }

                        if ($email_sent_odd_flag == 1) {
                            $postData = array(
                                'fileInfos' => [
                                    [
                                        'libraryDocumentId' => getenv('ADOBE_BUYER_DOCUMENT_ID'),
                                    ],
                                    [
                                        'libraryDocumentId' => getenv('ADOBE_BUYER_STATEMENT_DOCUMENT_ID'),
                                    ],
                                    [
                                        'libraryDocumentId' => getenv('ADOBE_BUYER_VESTING_DOCUMENT_ID'),
                                    ],
                                    [
                                        'libraryDocumentId' => getenv('ADOBE_BUYER_PROCEED_DOCUMENT_ID'),
                                    ],
                                ],
                                'name' => 'Test',
                                'participantSetsInfo' => array(
                                    array(
                                        'memberInfos' => array(
                                            array(
                                                'email' => $pdf_fields_val['buyer_email'],
                                            ),
                                        ),
                                        'name' => $pdf_fields_val['buyer_first_name'] . " " . $pdf_fields_val['buyer_last_name'],
                                        'order' => 1,
                                        'role' => 'SIGNER',
                                    ),
                                ),
                                'mergeFieldInfo' => $mergeFieldInfo,
                                'signatureType' => 'ESIGN',
                                'state' => 'IN_PROCESS',
                            );
                        } else {
                            $postData = array(
                                'fileInfos' => [
                                    [
                                        'libraryDocumentId' => getenv('ADOBE_BUYER_DOCUMENT_ID'),
                                    ],
                                    [
                                        'libraryDocumentId' => getenv('ADOBE_BUYER_STATEMENT_DOCUMENT_ID'),
                                    ],
                                    [
                                        'libraryDocumentId' => getenv('ADOBE_BUYER_VESTING_DOCUMENT_ID'),
                                    ],
                                    [
                                        'libraryDocumentId' => getenv('ADOBE_BUYER_PROCEED_DOCUMENT_ID'),
                                    ],
                                ],
                                'name' => 'Test',
                                'participantSetsInfo' => array(
                                    array(
                                        'memberInfos' => array(
                                            array(
                                                'email' => $pdf_fields_val['buyer_email'],
                                            ),
                                        ),
                                        'name' => $pdf_fields_val['buyer_first_name'] . " " . $pdf_fields_val['buyer_last_name'],
                                        'order' => 1,
                                        'role' => 'SIGNER',
                                    ),
                                    array(
                                        'memberInfos' => array(
                                            array(
                                                'email' => $pdf_fields_val['buyer_email_1'],
                                            ),
                                        ),
                                        'name' => $pdf_fields_val['buyer_first_name_1'] . " " . $pdf_fields_val['buyer_last_name_1'],
                                        'order' => 1,
                                        'role' => 'SIGNER',
                                    ),
                                ),
                                'mergeFieldInfo' => $mergeFieldInfo,
                                'signatureType' => 'ESIGN',
                                'state' => 'IN_PROCESS',
                            );
                        }

                        $request_data = [
                            'url' => 'api/rest/v6/agreements',
                            'request_type' => 'POST',
                            'data_type' => 'JSON',
                            'post_data' => $postData,
                        ];
                        $this->load->library('order/adobe');
                        $response = $this->adobe->send_request($request_data);

                        if ($response['status'] && !empty($response['result'])) {
                            $result = json_decode($response['result'], true);
                            if (!empty($result['id'])) {
                                $request_data = [
                                    'url' => 'api/rest/v6/agreements/' . $result['id'] . '/combinedDocument',
                                    'request_type' => 'GET',
                                ];
                                $response_doc = $this->adobe->send_request($request_data);
                                if (!empty($response_doc['status']) && $response_doc['result']) {
                                    if (!is_dir('uploads/borrower')) {
                                        mkdir(FCPATH . '/uploads/borrower', 0777, true);
                                    }
                                    $document_name = $orderDetails['file_number'] . '_seller_sign_' . $j . '.pdf';
                                    $j++;
                                    $email_sent_odd_flag = 0;
                                    $email_sent_even_flag = 0;
                                    file_put_contents(FCPATH . '/uploads/borrower/' . $document_name, $response_doc['result']);
                                    $this->order->uploadDocumentOnAwsS3($document_name, 'borrower');
                                    $documentData = array(
                                        'document_name' => $document_name,
                                        'original_document_name' => $document_name,
                                        'document_type_id' => 1041,
                                        'document_size' => ($data['file_size'] * 1000),
                                        'user_id' => 0,
                                        'order_id' => $orderDetails['order_id'],
                                        'task_id' => 4,
                                        'description' => 'Borrower Document',
                                        'is_seller_pdf_adobe_doc' => 1,
                                        'is_sync' => 1,
                                        'is_uploaded_by_borrower' => 1,
                                    );
                                    $this->document->insert($documentData);
                                    $pdf_url = env('AWS_PATH') . "borrower/" . $document_name;
                                    $success[0] = "Borrower buyer info saved successfully. <br>
                                    We also sent mail to buyer users for sign document.";
                                }
                            } else {
                                $errors[] = "Something went wrong. Please try again.";
                            }
                        } else {
                            $errors[] = "Something went wrong. Please try again.";
                        }
                    }
                    $i++;
                }
            }
            $data = array(
                "errors" => $errors,
                "success" => $success,
            );
            $this->session->set_userdata($data);
            redirect(base_url() . 'buyer-info/' . $random_number);exit;
        }
        $this->load->view('order/borrower_buyer_info1', $data);
    }

    public function sellerInfo($random_number)
    {
        $this->load->model('order/document');
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;
        $order = $this->getOrderInfo($random_number);
        $orderDetails = $this->order->get_order_details($order[0]['file_id'], 1);
        $data['orderDetails'] = $orderDetails;
        $seller_where['order_id'] = $orderDetails['order_id'];
        $seller_order['is_main_seller'] = 'desc';
        $data['sellers'] = $this->home_model->get_records('pct_order_borrower_seller_info', $seller_where, $seller_order);
        $errors = array();
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }

        if ($this->input->post()) {
            $seller_infos = $this->input->post('seller');
            $vesting_info = $skip_vesting = $main_seller = $seller_info_pdf = array();
            $is_first = 1;
            $seller_email = '';
            foreach ($seller_infos as $seller_key => $seller_value) {
                // Check Keys
                $seller_info = [
                    'order_id' => $this->input->post('order_id'),
                    'first_name' => $seller_value['first_name'] ? $seller_value['first_name'] : null,
                    'last_name' => $seller_value['last_name'] ? $seller_value['last_name'] : null,
                    'email' => $seller_value['email'] ? $seller_value['email'] : null,
                    'phone' => $seller_value['phone'] ? $seller_value['phone'] : null,
                    'birth_month' => $seller_value['birth_month'] ? $seller_value['birth_month'] : null,
                    'birth_date' => $seller_value['birth_date'] ? $seller_value['birth_date'] : null,
                    'birth_year' => $seller_value['birth_year'] ? $seller_value['birth_year'] : null,
                    'ssn' => $seller_value['ssn'] ? $seller_value['ssn'] : null,
                    'current_mailing_address' => $seller_value['current_mailing_address'] ? $seller_value['current_mailing_address'] : null,
                    'mailing_address_port_closing' => $seller_value['mailing_address_port_closing'] ? $seller_value['mailing_address_port_closing'] : null,
                    'marital_status' => $seller_value['marital_status'] ? $seller_value['marital_status'] : null,
                    'married_to' => $seller_value['married_to'] ? $seller_value['married_to'] : null,
                ];

                if ($is_first) {
                    $seller_email = $seller_value['email'];
                    $is_first = 0;
                }
                if ($seller_info['first_name'] && $seller_info['last_name']) {
                    if ($seller_key == 'new') {
                        $seller_info['is_main_seller'] = 0;
                        $new_seller_id = $this->home_model->insert($seller_info, 'pct_order_borrower_seller_info');
                    } else {
                        //Update Value
                        $seller_update['id'] = $seller_key;
                        $this->home_model->update($seller_info, $seller_update, 'pct_order_borrower_seller_info');
                    }
                    if (empty($main_seller)) {
                        $main_seller = $seller_info;
                    }

                    $seller_info_pdf['first_names'][] = $seller_info['first_name'];
                    $seller_info_pdf['last_names'][] = $seller_info['last_name'];
                    $seller_info_pdf['phones'][] = $seller_info['phone'];
                    $seller_info_pdf['current_mailing_address'][] = $seller_info['current_mailing_address'];
                    $seller_info_pdf['emails'][] = $seller_info['email'];
                    $seller_info_pdf['ssns'][] = $seller_info['ssn'];
                    $seller_info_pdf['birth_dates'][] = $seller_info['birth_month'] . "/" . $seller_info['birth_date'] . "/" . $seller_info['birth_year'];
                }
            }

            $sellerInfoData = array(
                'order_id' => $this->input->post('order_id'),
                'is_trustee' => $this->input->post('is_trustee') ? $this->input->post('is_trustee') : null,
                'current_trustees' => $this->input->post('current_trustees') ? $this->input->post('current_trustees') : null,
                'is_original_trustees' => $this->input->post('is_original_trustees') ? $this->input->post('is_original_trustees') : null,
                'is_limited_company' => $this->input->post('is_limited_company') ? $this->input->post('is_limited_company') : null,
                'is_married' => $this->input->post('is_married') ? $this->input->post('is_married') : null,
                'is_property_sell' => $this->input->post('is_property_sell') ? $this->input->post('is_property_sell') : null,
                'is_property_owned_free_clear' => $this->input->post('is_property_owned_free_clear') ? $this->input->post('is_property_owned_free_clear') : null,
                'lender_name' => $this->input->post('lender_name') ? $this->input->post('lender_name') : null,
                'lender_address' => $this->input->post('lender_address') ? $this->input->post('lender_address') : null,
                'loan_number' => $this->input->post('loan_number') ? $this->input->post('loan_number') : null,
                'lender_phone_number' => $this->input->post('lender_phone_number') ? $this->input->post('lender_phone_number') : null,
                'unpaid_balance' => $this->input->post('unpaid_balance') ? $this->input->post('unpaid_balance') : null,
                'payment_due_date' => $this->input->post('payment_due_date') ? $this->input->post('payment_due_date') : null,
                'loan_type' => $this->input->post('loan_type') ? $this->input->post('loan_type') : null,
                'is_impound_account' => $this->input->post('is_impound_account') ? $this->input->post('is_impound_account') : null,
                'is_another_loan' => $this->input->post('is_another_loan') ? $this->input->post('is_another_loan') : null,
                'second_lender_name' => $this->input->post('second_lender_name') ? $this->input->post('second_lender_name') : null,
                'second_lender_address' => $this->input->post('second_lender_address') ? $this->input->post('second_lender_address') : null,
                'second_loan_number' => $this->input->post('second_loan_number') ? $this->input->post('second_loan_number') : null,
                'second_lender_phone_number' => $this->input->post('second_lender_phone_number') ? $this->input->post('second_lender_phone_number') : null,
                'second_unpaid_balance' => $this->input->post('second_unpaid_balance') ? $this->input->post('second_unpaid_balance') : null,
                'second_payment_due_date' => $this->input->post('second_payment_due_date') ? $this->input->post('second_payment_due_date') : null,
                'second_loan_type' => $this->input->post('second_loan_type') ? $this->input->post('second_loan_type') : null,
                'second_is_impound_account' => $this->input->post('second_is_impound_account') ? $this->input->post('second_is_impound_account') : null,
                'second_tax_status' => $this->input->post('second_tax_status') ? $this->input->post('second_tax_status') : null,
                'second_is_paid_impound' => $this->input->post('second_is_paid_impound') ? $this->input->post('second_is_paid_impound') : null,
                'is_private_water_company' => $this->input->post('is_private_water_company') ? $this->input->post('is_private_water_company') : null,
                'water_company' => $this->input->post('water_company') ? $this->input->post('water_company') : null,
                'water_company_address' => $this->input->post('water_company_address') ? $this->input->post('water_company_address') : null,
                'water_account_number' => $this->input->post('water_account_number') ? $this->input->post('water_account_number') : null,
                'water_phone_number' => $this->input->post('water_phone_number') ? $this->input->post('water_phone_number') : null,
                'is_hoa' => $this->input->post('is_hoa') ? $this->input->post('is_hoa') : null,
                'hoa_company' => $this->input->post('hoa_company') ? $this->input->post('hoa_company') : null,
                'hoa_company_address' => $this->input->post('hoa_company_address') ? $this->input->post('hoa_company_address') : null,
                'hoa_contact_person' => $this->input->post('hoa_contact_person') ? $this->input->post('hoa_contact_person') : null,
                'hoa_contact_number' => $this->input->post('hoa_contact_number') ? $this->input->post('hoa_contact_number') : null,
                'second_hoa_company' => $this->input->post('second_hoa_company') ? $this->input->post('second_hoa_company') : null,
                'second_hoa_company_address' => $this->input->post('second_hoa_company_address') ? $this->input->post('second_hoa_company_address') : null,
                'second_hoa_contact_person' => $this->input->post('second_hoa_contact_person') ? $this->input->post('second_hoa_contact_person') : null,
                'second_hoa_contact_number' => $this->input->post('second_phone_mask') ? $this->input->post('second_phone_mask') : null,
            );
            $this->home_model->insert($sellerInfoData, 'pct_order_borrower_seller_packet_info');

            $sellerInfo2Data = array(
                'is_used_another_last_name' => $this->input->post('is_used_another_last_name') ? $this->input->post('is_used_another_last_name') : null,
                'another_last_name' => $this->input->post('another_last_name') ? $this->input->post('another_last_name') : null,
                'is_married_or_domestic_partner' => $this->input->post('is_married_or_domestic_partner') ? $this->input->post('is_married_or_domestic_partner') : null,
                'marriage_or_domestic_day' => $this->input->post('marriage_or_domestic_day') ? $this->input->post('marriage_or_domestic_day') : null,
                'marriage_or_domestic_month' => $this->input->post('marriage_or_domestic_month') ? $this->input->post('marriage_or_domestic_month') : null,
                'marriage_or_domestic_year' => $this->input->post('marriage_or_domestic_year') ? $this->input->post('marriage_or_domestic_year') : null,
                'spouse_first_name' => $this->input->post('spouse_first_name') ? $this->input->post('spouse_first_name') : null,
                'spouse_last_name' => $this->input->post('spouse_last_name') ? $this->input->post('spouse_last_name') : null,
                'spouse_email' => $this->input->post('spouse_email') ? $this->input->post('spouse_email') : null,
                'spouse_phone' => $this->input->post('spouse_phone') ? $this->input->post('spouse_phone') : null,
                'spouse_birth_day' => $this->input->post('spouse_birth_day') ? $this->input->post('spouse_birth_day') : null,
                'spouse_birth_month' => $this->input->post('spouse_birth_month') ? $this->input->post('spouse_birth_month') : null,
                'spouse_birth_year' => $this->input->post('spouse_birth_year') ? $this->input->post('spouse_birth_year') : null,
                'spouse_ssn' => $this->input->post('spouse_ssn') ? $this->input->post('spouse_ssn') : null,
                'is_property_sell_2' => $this->input->post('is_property_sell') ? $this->input->post('is_property_sell') : null,
                'another_property_sell' => $this->input->post('another_property_sell') ? $this->input->post('another_property_sell') : null,
                'from_date' => $this->input->post('from_date') ? $this->input->post('from_date') : null,
                'from_to' => $this->input->post('from_to') ? $this->input->post('from_to') : null,
                'is_another_residence' => $this->input->post('is_another_residence') ? $this->input->post('is_another_residence') : null,
                'another_residence' => $this->input->post('another_residence') ? $this->input->post('another_residence') : null,
                'another_from_date' => $this->input->post('another_from_date') ? $this->input->post('another_from_date') : null,
                'another_to_date' => $this->input->post('another_to_date') ? $this->input->post('another_to_date') : null,
                'is_currently_employed' => $this->input->post('is_currently_employed') ? $this->input->post('is_currently_employed') : null,
                'employee_company_name' => $this->input->post('employee_company_name') ? $this->input->post('employee_company_name') : null,
                'from_employee_date' => $this->input->post('from_employee_date') ? $this->input->post('from_employee_date') : null,
                'to_employee_date' => $this->input->post('to_employee_date') ? $this->input->post('to_employee_date') : null,
                'is_add_another_occupation' => $this->input->post('is_add_another_occupation') ? $this->input->post('is_add_another_occupation') : null,
                'employee_another_company_name' => $this->input->post('employee_another_company_name') ? $this->input->post('employee_another_company_name') : null,
                'another_from_employee_date' => $this->input->post('another_from_employee_date') ? $this->input->post('another_from_employee_date') : null,
                'another_to_employee_date' => $this->input->post('another_to_employee_date') ? $this->input->post('another_to_employee_date') : null,
                'is_spouse_domestic_partner_employed' => $this->input->post('is_spouse_domestic_partner_employed') ? $this->input->post('is_spouse_domestic_partner_employed') : null,
                'spouse_company_name' => $this->input->post('spouse_company_name') ? $this->input->post('spouse_company_name') : null,
                'from_spouse_date' => $this->input->post('from_spouse_date') ? $this->input->post('from_spouse_date') : null,
                'is_another_occupation_spouse_domestic' => $this->input->post('another_to_employee_date') ? $this->input->post('another_to_employee_date') : null,
                'another_spouse_company_name' => $this->input->post('another_spouse_company_name') ? $this->input->post('another_spouse_company_name') : null,
                'another_from_spouse_date' => $this->input->post('another_from_spouse_date') ? $this->input->post('another_from_spouse_date') : null,
                'another_to_spouse_date' => $this->input->post('another_to_spouse_date') ? $this->input->post('another_to_spouse_date') : null,
            );
            $this->home_model->insert($sellerInfo2Data, 'pct_order_borrower_seller_packet_info_2');

            //Generate PDF
            $pdf_fields_val = [
                'Custom Field 1' => $orderDetails['escrow_number'] ? $orderDetails['escrow_number'] : $orderDetails['file_number'],
                'Custom Field 2' => $orderDetails['file_number'],
                'Custom Field 17' => $orderDetails['full_address'],
                'Custom Field 24' => $orderDetails['full_address'],
                'Custom Field 37' => $orderDetails['escrow_number'] ? $orderDetails['escrow_number'] : $orderDetails['file_number'], 'Custom Field 38' => $orderDetails['file_number'],
                'Custom Field 18' => $this->input->post('lender_name') ? $this->input->post('lender_name') : '',
                'Copy of Custom Field 9 (1)' => $this->input->post('lender_address') ? $this->input->post('lender_address') : '',
                'Custom Field 19' => $this->input->post('loan_number') ? $this->input->post('loan_number') : '',
                'Custom Field 23' => $this->input->post('tax_status') ? $this->input->post('tax_status') : '',
                'Custom Field 20' => $this->input->post('unpaid_balance') ? $this->input->post('unpaid_balance') : '',
                'Custom Field 21' => $this->input->post('payment_due_date') ? $this->input->post('payment_due_date') : '',
                'Custom Field 22' => $this->input->post('loan_type') ? $this->input->post('loan_type') : '',
                'Copy of Custom Field 18 (1)' => $this->input->post('second_lender_name') ? $this->input->post('second_lender_name') : '',
                'Copy of Custom Field 9 (2)' => $this->input->post('second_lender_address') ? $this->input->post('second_lender_address') : '',
                'Copy of Custom Field 19 (1)' => $this->input->post('second_loan_number') ? $this->input->post('second_loan_number') : '',
                'Copy of Custom Field 19 (5)' => $this->input->post('second_tax_status') ? $this->input->post('second_tax_status') : '',
                'Copy of Custom Field 19 (2)' => $this->input->post('second_unpaid_balance') ? $this->input->post('second_unpaid_balance') : '',
                'Copy of Custom Field 19 (3)' => $this->input->post('second_payment_due_date') ? $this->input->post('second_payment_due_date') : '',
                'Copy of Custom Field 19 (4)' => $this->input->post('second_loan_type') ? $this->input->post('second_loan_type') : '',
                'Custom Field 25' => $this->input->post('hoa_company') ? $this->input->post('hoa_company') : '',
                'Custom Field 28' => $this->input->post('hoa_company_address') ? $this->input->post('hoa_company_address') : '',
                'Custom Field 26' => $this->input->post('hoa_contact_person') ? $this->input->post('hoa_contact_person') : '',
                'Custom Field 27' => $this->input->post('hoa_contact_number') ? $this->input->post('hoa_contact_number') : '',
                'Custom Field 29' => $this->input->post('water_company') ? $this->input->post('water_company') : '',
                'Copy of Custom Field 28 (1)' => $this->input->post('water_company_address') ? $this->input->post('water_company_address') : '',
                'Custom Field 31' => $this->input->post('water_phone_number') ? $this->input->post('water_phone_number') : '',
                'Drop Down 1' => $this->input->post('is_married') == 'married' ? 'Yes' : 'No',
                'escrow_number' => $orderDetails['escrow_number'] ? $orderDetails['escrow_number'] : $orderDetails['file_number'],
                'escrow_number_2' => $orderDetails['escrow_number'] ? $orderDetails['escrow_number'] : $orderDetails['file_number'],
                'residence_address' => $this->input->post('is_property_sell_2') == 'Yes' ? $orderDetails['full_address'] : $this->input->post('another_property_sell'),
                'residence_address_1' => $this->input->post('another_residence') ? $this->input->post('another_residence') : '',
                'residence_address_2' => '',
                'residence_from' => $this->input->post('from_date') ? $this->input->post('from_date') : '',
                'residence_from_1' => $this->input->post('another_from_date') ? $this->input->post('another_from_date') : '',
                'residence_from_2' => '',
                'residence_to' => $this->input->post('from_to') ? $this->input->post('from_to') : '',
                'residence_to_1' => $this->input->post('another_to_date') ? $this->input->post('another_to_date') : '',
                'residence_to_2' => '',
                'seller_birth_place' => '',
                'seller_date_of_marriage' => $this->input->post('marriage_or_domestic_month') ? $this->input->post('marriage_or_domestic_month') . "/" . $this->input->post('marriage_or_domestic_day') . "/" . $this->input->post('marriage_or_domestic_year') : '',
                'seller_last_name' => $seller_info_pdf['last_names'][0],
                'seller_lived_in_usa' => '',
                'seller_married' => $this->input->post('is_married') == 'married' ? 'Yes' : 'No',
                'seller_no' => '1',
                'seller_occupation' => $this->input->post('employee_company_name') ? $this->input->post('employee_company_name') : '',
                'seller_occupation_1' => $this->input->post('employee_another_company_name') ? $this->input->post('employee_another_company_name') : '',
                'seller_occupation_2' => '',
                'seller_occupation_from' => $this->input->post('from_employee_date') ? $this->input->post('from_employee_date') : '',
                'seller_occupation_from_1' => $this->input->post('another_from_employee_date') ? $this->input->post('another_from_employee_date') : '',
                'seller_occupation_from_2' => '',
                'seller_occupation_to' => $this->input->post('to_employee_date') ? $this->input->post('to_employee_date') : '',
                'seller_occupation_to_1' => $this->input->post('another_to_employee_date') ? $this->input->post('another_to_employee_date') : '',
                'seller_occupation_to_2' => '',
                'seller_ssn' => $seller_info_pdf['ssns'][0],
                'seller_state_residence' => '',
                'seller_first_name' => $seller_info_pdf['first_names'][0],
                'signature_3_name' => $seller_info_pdf['first_names'][0] . " " . $seller_info_pdf['last_names'][0],
                'seller_birth_date' => $seller_info_pdf['birth_dates'][0],
                'signature_4_name' => $seller_info_pdf['first_names'][1] . " " . $seller_info_pdf['last_names'][1],
                'spouse_birth_place' => '',
                'spouse_date_of_birth' => $this->input->post('spouse_birth_day') ? $this->input->post('spouse_birth_month') . "/" . $this->input->post('spouse_birth_day') . "/" . $this->input->post('spouse_birth_year') : '',
                'spouse_domestic_address' => $this->input->post('spouse_company_name') ? $this->input->post('spouse_company_name') : '',
                'spouse_domestic_address_1' => $this->input->post('another_spouse_company_name') ? $this->input->post('another_spouse_company_name') : '',
                'spouse_domestic_address_2' => '',
                'spouse_domestic_from' => $this->input->post('from_spouse_date') ? $this->input->post('from_spouse_date') : '',
                'spouse_domestic_from_1' => $this->input->post('another_from_spouse_date') ? $this->input->post('another_from_spouse_date') : '',
                'spouse_domestic_from_2' => '',
                'spouse_domestic_to' => $this->input->post('to_spouse_date') ? $this->input->post('to_spouse_date') : '',
                'spouse_domestic_to_1' => $this->input->post('another_to_spouse_date') ? $this->input->post('another_to_spouse_date') : '',
                'spouse_domestic_to_2' => '',
                'spouse_driver_license' => '',
                'spouse_first_name' => $this->input->post('spouse_first_name') ? $this->input->post('spouse_first_name') : '',
                'spouse_last_name' => $this->input->post('spouse_last_name') ? $this->input->post('spouse_last_name') : '',
                'spouse_lived_in_usa' => '',
                'spouse_marriage_date' => $this->input->post('marriage_or_domestic_month') ? $this->input->post('marriage_or_domestic_month') . "/" . $this->input->post('marriage_or_domestic_day') . "/" . $this->input->post('marriage_or_domestic_year') : '',
                'spouse_other_last_name' => '',
                'spouse_residence' => '',
                'spouse_ssn' => $this->input->post('spouse_ssn') ? $this->input->post('spouse_ssn') : '',
                'title_number' => $orderDetails['file_number'],
                'title_number_2' => $orderDetails['file_number'],
                'escrow_number_disposition' => $orderDetails['escrow_number'] ? $orderDetails['escrow_number'] : $orderDetails['file_number'],
                'title_number_disposition' => $orderDetails['file_number'],
                'address_disposition' => $orderDetails['full_address'],
                'escrow_number_proceed' => $orderDetails['escrow_number'] ? $orderDetails['escrow_number'] : $orderDetails['file_number'],
                'title_number_proceed' => $orderDetails['file_number'],
            ];

            if (count($seller_info_pdf['emails']) > 0) {
                $total_count = count($seller_info_pdf['emails']);
                $i = 1;
                $j = 1;
                $email_sent_flag = 0;
                foreach ($seller_info_pdf['emails'] as $seller_email) {
                    if ($total_count == $i) {
                        if ($i % 2 != 0) {
                            $pdf_fields_val['Custom Field 3'] = $seller_info_pdf['first_names'][$i - 1];
                            $pdf_fields_val['Custom Field 4'] = $seller_info_pdf['last_names'][$i - 1];
                            $pdf_fields_val['Custom Field 5'] = $seller_info_pdf['phones'][$i - 1];
                            $pdf_fields_val['Custom Field 6'] = '';
                            $pdf_fields_val['Custom Field 7'] = $seller_info_pdf['emails'][$i - 1];
                            $pdf_fields_val['Custom Field 8'] = $seller_info_pdf['ssns'][$i - 1];
                            $pdf_fields_val['Custom Field 9'] = $seller_info_pdf['current_mailing_address'][$i - 1];
                            $pdf_fields_val['Custom Field 35'] = $seller_info_pdf['first_names'][$i - 1];
                            $pdf_fields_val['Custom Field 39'] = $i;

                            $pdf_fields_val['Custom Field 10'] = "";
                            $pdf_fields_val['Custom Field 11'] = "";
                            $pdf_fields_val['Custom Field 12'] = "";
                            $pdf_fields_val['Custom Field 13'] = "";
                            $pdf_fields_val['Custom Field 14'] = "";
                            $pdf_fields_val['Custom Field 15'] = "";
                            $pdf_fields_val['Custom Field 16'] = "";
                            $pdf_fields_val['Custom Field 35'] = "";
                            $pdf_fields_val['Custom Field 36'] = "";
                            $pdf_fields_val['Copy of Custom Field 39 (1)'] = "";
                            $email_sent_odd_flag = 1;
                        }
                    }
                    if ($i % 2 == 0) {
                        $pdf_fields_val['Custom Field 3'] = $seller_info_pdf['first_names'][$i - 2];
                        $pdf_fields_val['Custom Field 4'] = $seller_info_pdf['last_names'][$i - 2];
                        $pdf_fields_val['Custom Field 5'] = $seller_info_pdf['phones'][$i - 2];
                        $pdf_fields_val['Custom Field 6'] = '';
                        $pdf_fields_val['Custom Field 7'] = $seller_info_pdf['emails'][$i - 2];
                        $pdf_fields_val['Custom Field 8'] = $seller_info_pdf['ssns'][$i - 2];
                        $pdf_fields_val['Custom Field 9'] = $seller_info_pdf['current_mailing_address'][$i - 2];
                        $pdf_fields_val['Custom Field 5'] = $seller_info_pdf['phones'][$i - 2];
                        $pdf_fields_val['Custom Field 39'] = $i - 1;

                        $pdf_fields_val['Custom Field 10'] = $seller_info_pdf['first_names'][$i - 1];
                        $pdf_fields_val['Custom Field 11'] = $seller_info_pdf['last_names'][$i - 1];
                        $pdf_fields_val['Custom Field 12'] = $seller_info_pdf['phones'][$i - 1];
                        $pdf_fields_val['Custom Field 13'] = '';
                        $pdf_fields_val['Custom Field 14'] = $seller_info_pdf['emails'][$i - 1];
                        $pdf_fields_val['Custom Field 15'] = $seller_info_pdf['ssns'][$i - 1];
                        $pdf_fields_val['Custom Field 16'] = $seller_info_pdf['current_mailing_address'][$i - 1];
                        $pdf_fields_val['Custom Field 35'] = $seller_info_pdf['first_names'][$i - 2] . " " . $seller_info_pdf['last_names'][$i - 2];
                        $pdf_fields_val['Custom Field 36'] = $seller_info_pdf['first_names'][$i - 1] . " " . $seller_info_pdf['last_names'][$i - 1];
                        $pdf_fields_val['Copy of Custom Field 39 (1)'] = $i;
                        $email_sent_even_flag = 1;
                    }

                    if ($email_sent_odd_flag == 1 || $email_sent_even_flag == 1) {
                        $mergeFieldInfo = array();
                        foreach ($pdf_fields_val as $key => $value) {
                            $mergeFieldInfo[] = array(
                                'fieldName' => $key,
                                'defaultValue' => $value,
                            );
                        }

                        if ($email_sent_odd_flag == 1) {
                            $postData = array(
                                'fileInfos' => [
                                    [
                                        'libraryDocumentId' => getenv('ADOBE_SELLER_DOCUMENT_ID'),
                                    ],
                                    [
                                        'libraryDocumentId' => getenv('ADOBE_SELLER_STATEMENT_DOCUMENT_ID'),
                                    ],
                                    [
                                        'libraryDocumentId' => 'CBJCHBCAABAADo5s4v2-sHYm9aHJf-iURR5n-EsI6NKK',
                                    ],
                                    [
                                        'libraryDocumentId' => 'CBJCHBCAABAAJE8Vvvn8ale4sCEVAPYEt0tOmP5LqekR',
                                    ],
                                ],
                                'name' => 'Test',
                                'participantSetsInfo' => array(
                                    array(
                                        'memberInfos' => array(
                                            array(
                                                'email' => $pdf_fields_val['Custom Field 7'],
                                            ),
                                        ),
                                        'name' => $pdf_fields_val['Custom Field 3'] . " " . $pdf_fields_val['Custom Field 4'],
                                        'order' => 1,
                                        'role' => 'SIGNER',
                                    ),
                                ),
                                'mergeFieldInfo' => $mergeFieldInfo,
                                'signatureType' => 'ESIGN',
                                'state' => 'IN_PROCESS',
                            );
                        } else {
                            $postData = array(
                                'fileInfos' => [
                                    [
                                        'libraryDocumentId' => getenv('ADOBE_SELLER_DOCUMENT_ID'),
                                    ],
                                    [
                                        'libraryDocumentId' => getenv('ADOBE_SELLER_STATEMENT_DOCUMENT_ID'),
                                    ],
                                    [
                                        'libraryDocumentId' => 'CBJCHBCAABAADo5s4v2-sHYm9aHJf-iURR5n-EsI6NKK',
                                    ],
                                    [
                                        'libraryDocumentId' => 'CBJCHBCAABAAJE8Vvvn8ale4sCEVAPYEt0tOmP5LqekR',
                                    ],
                                ],
                                'name' => 'Test',
                                'participantSetsInfo' => array(
                                    array(
                                        'memberInfos' => array(
                                            array(
                                                'email' => $pdf_fields_val['Custom Field 7'],
                                            ),
                                        ),
                                        'name' => $pdf_fields_val['Custom Field 3'] . " " . $pdf_fields_val['Custom Field 4'],
                                        'order' => 1,
                                        'role' => 'SIGNER',
                                    ),
                                    array(
                                        'memberInfos' => array(
                                            array(
                                                'email' => $pdf_fields_val['Custom Field 14'],
                                            ),
                                        ),
                                        'name' => $pdf_fields_val['Custom Field 10'] . " " . $pdf_fields_val['Custom Field 11'],
                                        'order' => 1,
                                        'role' => 'SIGNER',
                                    ),
                                ),
                                'mergeFieldInfo' => $mergeFieldInfo,
                                'signatureType' => 'ESIGN',
                                'state' => 'IN_PROCESS',
                            );
                        }

                        $request_data = [
                            'url' => 'api/rest/v6/agreements',
                            'request_type' => 'POST',
                            'data_type' => 'JSON',
                            'post_data' => $postData,
                        ];
                        $this->load->library('order/adobe');
                        $response = $this->adobe->send_request($request_data);

                        if ($response['status'] && !empty($response['result'])) {
                            $result = json_decode($response['result'], true);
                            if (!empty($result['id'])) {
                                $request_data = [
                                    'url' => 'api/rest/v6/agreements/' . $result['id'] . '/combinedDocument',
                                    'request_type' => 'GET',
                                ];
                                $response_doc = $this->adobe->send_request($request_data);
                                if (!empty($response_doc['status']) && $response_doc['result']) {
                                    if (!is_dir('uploads/borrower')) {
                                        mkdir(FCPATH . '/uploads/borrower', 0777, true);
                                    }
                                    $document_name = $orderDetails['file_number'] . '_seller_sign_' . $j . '.pdf';
                                    $j++;
                                    $email_sent_odd_flag = 0;
                                    $email_sent_even_flag = 0;
                                    file_put_contents(FCPATH . '/uploads/borrower/' . $document_name, $response_doc['result']);
                                    $this->order->uploadDocumentOnAwsS3($document_name, 'borrower');
                                    $documentData = array(
                                        'document_name' => $document_name,
                                        'original_document_name' => $document_name,
                                        'document_type_id' => 1041,
                                        'document_size' => ($data['file_size'] * 1000),
                                        'user_id' => 0,
                                        'order_id' => $orderDetails['order_id'],
                                        'task_id' => 4,
                                        'description' => 'Borrower Document',
                                        'is_seller_pdf_adobe_doc' => 1,
                                        'is_sync' => 1,
                                        'is_uploaded_by_borrower' => 1,
                                    );
                                    $this->document->insert($documentData);
                                    $pdf_url = env('AWS_PATH') . "borrower/" . $document_name;
                                    $success[0] = "Borrower seller info saved successfully. <br>
                                    We also sent mail to seller users for sign document.";
                                }
                            } else {
                                $errors[] = "Something went wrong. Please try again.";
                            }
                        } else {
                            $errors[] = "Something went wrong. Please try again.";
                        }
                    }
                    $i++;
                }
            }

            $data = array(
                "errors" => $errors,
                "success" => $success,
            );
            $this->session->set_userdata($data);
            redirect(base_url() . 'seller-info/' . $random_number);exit;
        }
        $this->load->view('order/borrower_seller_info_1', $data);
    }

    public function generatPdfTest($random_number, $type = 'seller')
    {

        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;
        $order = $this->getOrderInfo($random_number);
        $orderDetails = $this->order->get_order_details($order[0]['file_id'], 1);

        $this->db->select('*')
            ->from('pct_order_borrower_seller_owner_escrow_info');
        $this->db->where('order_id', $order[0]['id']);
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data['sellerInfo'] = $query->row_array();
        } else {
            $data['sellerInfo'] = array();
        }

        $this->db->select('*')
            ->from('pct_order_documents');
        $this->db->where('order_id', $order[0]['id']);
        $this->db->where('(is_commission_doc = 1 or is_escrow_instruction_doc = 1)');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data['docsInfo'] = $query->result_array();
        } else {
            $data['docsInfo'] = array();
        }

        if (!empty($orderDetails['borrower'])) {
            $orderDetails['primary_owner_name'] = $orderDetails['borrower'];
        } else {
            $orderDetails['primary_owner_name'] = '';
        }

        if (!empty($orderDetails['secondary_borrower'])) {
            $orderDetails['secondary_owner_name'] = $orderDetails['secondary_borrower'];
        } else {
            $orderDetails['secondary_owner_name'] = '';
        }

        if (!empty($orderDetails['borrower'])) {
            $seller_owner_names = explode(' ', $orderDetails['borrower']);
            if (count($seller_owner_names) == 3) {
                $orderDetails['seller_first_name'] = $seller_owner_names[0];
                $orderDetails['seller_middle_name'] = $seller_owner_names[1];
                $orderDetails['seller_last_name'] = $seller_owner_names[2];
            } else if (count($seller_owner_names) == 2) {
                $orderDetails['seller_first_name'] = $seller_owner_names[0];
                $orderDetails['seller_middle_name'] = '';
                $orderDetails['seller_last_name'] = $seller_owner_names[1];
            } else {
                $orderDetails['seller_first_name'] = $seller_owner_names[0];
                $orderDetails['seller_middle_name'] = '';
                $orderDetails['seller_last_name'] = '';
            }
        } else {
            $orderDetails['seller_first_name'] = '';
            $orderDetails['seller_middle_name'] = '';
            $orderDetails['seller_last_name'] = '';
        }

        if (!empty($orderDetails['secondary_borrower'])) {
            $second_seller_owner_names = explode(' ', $orderDetails['secondary_borrower']);
            if (count($second_seller_owner_names) == 3) {
                $orderDetails['second_seller_first_name'] = $second_seller_owner_names[0];
                $orderDetails['second_seller_middle_name'] = $second_seller_owner_names[1];
                $orderDetails['second_seller_last_name'] = $second_seller_owner_names[2];
            } else if (count($second_seller_owner_names) == 2) {
                $orderDetails['second_seller_first_name'] = $second_seller_owner_names[0];
                $orderDetails['second_seller_middle_name'] = '';
                $orderDetails['second_seller_last_name'] = $second_seller_owner_names[1];
            } else {
                $orderDetails['second_seller_first_name'] = $second_seller_owner_names[0];
                $orderDetails['second_seller_middle_name'] = '';
                $orderDetails['second_seller_last_name'] = '';
            }
        } else {
            $orderDetails['second_seller_first_name'] = '';
            $orderDetails['second_seller_middle_name'] = '';
            $orderDetails['second_seller_last_name'] = '';
        }

        $data['orderDetails'] = $orderDetails;
        $errors = array();
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }

        $user_data['admin_api'] = 1;
        $endPoint = 'files/' . $order[0]['file_id'] . '/documents';
        $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_documents', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $order[0]['id'], 0);
        $resultDocuments = $this->resware->make_request('GET', $endPoint, '', $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'get_documents', env('RESWARE_ORDER_API') . $endPoint, array(), $resultDocuments, $order[0]['id'], $logid);
        $resDocuments = json_decode($resultDocuments, true);
        $documentIds = array(1015, 1534, 1040, 1405, 1039, 1038, 1020);

        if (!empty($resDocuments['Documents'])) {
            foreach ($resDocuments['Documents'] as $document) {
                if (in_array($document['DocumentType']['DocumentTypeID'], $documentIds)) {
                    $key = array_search($document['DocumentID'], array_column($data['docsInfo'], 'api_document_id'));
                    // echo $document['DocumentID']."---";
                    // print_r($data['docsInfo']);
                    // print_r(array_column($data['docsInfo'], 'api_document_id'));exit;
                    $this->load->model('order/document');
                    if (strlen($key) == 0 && strpos(strtolower($document['DocumentName']), 'snapshot') === false) {
                        $document_name = date('YmdHis') . "_" . $document['DocumentName'];
                        $ext = end(explode('.', $document['DocumentName']));

                        if (strtolower($ext) == 'doc' || strtolower($ext) == 'docx') {
                            $document_name = str_replace($ext, 'pdf', $document_name);
                        }

                        $endPoint = 'documents/' . $document['DocumentID'] . '?format=json';
                        $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $order[0]['id'], 0);
                        $resultDocument = $this->resware->make_request('GET', $endPoint, '', $user_data);
                        $this->apiLogs->syncLogs(0, 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), $resultDocument, $order[0]['id'], $logid);
                        $resDocument = json_decode($resultDocument, true);

                        if (isset($resDocument['Document']) && !empty($resDocument['Document'])) {
                            $documentContent = base64_decode($resDocument['Document']['DocumentBody'], true);
                            if (!is_dir('uploads/instruction_documents')) {
                                mkdir(FCPATH . '/uploads/instruction_documents', 0777, true);
                            }
                            file_put_contents(FCPATH . '/uploads/instruction_documents/' . $document_name, $documentContent);
                            $this->order->uploadDocumentOnAwsS3($document_name, 'instruction_documents');
                            $documentData = array(
                                'document_name' => $document_name,
                                'original_document_name' => $document['DocumentName'],
                                'document_type_id' => $document['DocumentType']['DocumentTypeID'],
                                'api_document_id' => $document['DocumentID'],
                                'document_size' => $document['Size'],
                                'user_id' => 0,
                                'order_id' => $orderDetails['order_id'],
                                'description' => $document['DocumentName'],
                                'created' => date('Y-m-d H:i:s'),
                                'is_sync' => 0,
                                'is_commission_doc' => ($document['DocumentType']['DocumentTypeID'] == 1039 || $document['DocumentType']['DocumentTypeID'] == 1038 || $document['DocumentType']['DocumentTypeID'] == 1020) ? 1 : 0,
                                'is_escrow_instruction_doc' => ($document['DocumentType']['DocumentTypeID'] == 1015 || $document['DocumentType']['DocumentTypeID'] == 1534 || $document['DocumentType']['DocumentTypeID'] == 1040) ? 1 : 0,
                            );
                            $data['docsInfo'][] = $documentData;
                            $this->document->insert($documentData);
                        }
                    }
                }
            }
        }

        $this->load->library('snappy_pdf');

        // header('Content-Type: application/pdf');
        $document_name = $type . '_' . time() . '.pdf';
        $dir_to_upload = 'uploads/escrow/' . $type;
        if (!is_dir(FCPATH . $dir_to_upload)) {
            mkdir(FCPATH . $dir_to_upload, 0777, true);
        }
        chmod(FCPATH . $dir_to_upload, 0777);
        $dir_name = FCPATH . $dir_to_upload . '/';
        $dir_name = str_replace('\\', '/', $dir_name);
        // echo $dir_name.$document_name;die;
        $report_data = $data;
        if ($type == 'seller') {

            $html = $this->load->view('order/borrower_seller', $report_data, true);
        } else {
            $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
            $data['mail_dashboard'] = 1;
            $order = $this->getOrderInfo($random_number);
            $orderDetails = $this->order->get_order_details($order[0]['file_id'], 1);

            $this->db->select('*')
                ->from('pct_order_borrower_buyer_info');
            $this->db->where('order_id', $order[0]['id']);
            $this->db->order_by('id', 'desc');
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $data['buyerInfo'] = $query->row_array();
            } else {
                $data['buyerInfo'] = array();
            }

            if (!empty($orderDetails['primary_owner'])) {
                $buyer_owner_names = explode(' ', $orderDetails['primary_owner']);
                if (count($buyer_owner_names) == 3) {
                    $orderDetails['buyer_first_name'] = $buyer_owner_names[0];
                    $orderDetails['buyer_middle_name'] = $buyer_owner_names[1];
                    $orderDetails['buyer_last_name'] = $buyer_owner_names[2];
                } else if (count($buyer_owner_names) == 2) {
                    $orderDetails['buyer_first_name'] = $buyer_owner_names[0];
                    $orderDetails['buyer_middle_name'] = '';
                    $orderDetails['buyer_last_name'] = $buyer_owner_names[1];
                } else {
                    $orderDetails['buyer_first_name'] = $buyer_owner_names[0];
                    $orderDetails['buyer_middle_name'] = '';
                    $orderDetails['buyer_last_name'] = '';
                }
            } else {
                $orderDetails['buyer_first_name'] = '';
                $orderDetails['buyer_middle_name'] = '';
                $orderDetails['buyer_last_name'] = '';
            }

            if (!empty($orderDetails['secondary_owner'])) {
                $second_buyer_owner_names = explode(' ', $orderDetails['secondary_owner']);
                if (count($second_buyer_owner_names) == 3) {
                    $orderDetails['second_buyer_first_name'] = $second_buyer_owner_names[0];
                    $orderDetails['second_buyer_middle_name'] = $second_buyer_owner_names[1];
                    $orderDetails['second_buyer_last_name'] = $second_buyer_owner_names[2];
                } else if (count($buyer_owner_names) == 2) {
                    $orderDetails['second_buyer_first_name'] = $second_buyer_owner_names[0];
                    $orderDetails['second_buyer_middle_name'] = '';
                    $orderDetails['second_buyer_last_name'] = $second_buyer_owner_names[1];
                } else {
                    $orderDetails['second_buyer_first_name'] = $second_buyer_owner_names[0];
                    $orderDetails['second_buyer_middle_name'] = '';
                    $orderDetails['second_buyer_last_name'] = '';
                }
            } else {
                $orderDetails['second_buyer_first_name'] = '';
                $orderDetails['second_buyer_middle_name'] = '';
                $orderDetails['second_buyer_last_name'] = '';
            }

            $data['orderDetails'] = $orderDetails;
            $errors = array();
            $data['errors'] = array();
            $data['success'] = array();
            $report_data = $data;
            $html = $this->load->view('order/borrower_buyer', $report_data, true);
        }
        $this->snappy_pdf->pdf->generateFromHtml($html, $dir_name . $document_name);

        echo $dir_name . $document_name;

        // $returnData = array();
        // $returnData['pdfLink'] = $dir_to_upload.'/'.$document_name;
        // $response = $this->order->uploadDocumentOnAwsS3($document_name, 'sales-rep/pma');
        // if($response) {
        //     //report_url
        //     $returnData['pdfLink'] = 'sales-rep/pma/'.$document_name;
        //     if(is_file($dir_name.$document_name)) {
        //         unlink($dir_name.$document_name);
        //     }
        // }
        // echo json_encode($returnData);exit;
    }

    public function testPdfkt()
    {

        $pdf_templates_file = FCPATH . 'assets/pdf_templates/buyer_check.pdf';
        $pdf = new Pdf($pdf_templates_file);
        $type = 'buyer';
        $document_name = $type . '_' . time() . '.pdf';
        $dir_to_upload = 'uploads/escrow/' . $type;
        if (!is_dir(FCPATH . $dir_to_upload)) {
            mkdir(FCPATH . $dir_to_upload, 0777, true);
        }
        chmod(FCPATH . $dir_to_upload, 0777);
        $dir_name = FCPATH . $dir_to_upload . '/';
        $dir_name = str_replace('\\', '/', $dir_name);
        $file_full_path = $dir_name . $document_name;

        $pdf->fillForm([
            'Date' => '10/08/2022',
            'Escrow#' => '123',
            'The Undersigned hereby authorizes and directs Pacific Coast Title Company to disburse proceeds as follows' => 1,
            'All' => 'No',
            'Or' => 'Yes',
            'All Net Proceeds or' => '123.34',
            'To' => 'Test to',
            'Attn' => 'TestAtn',
            'Escrow No' => 'es1',
        ])
            ->needAppearances()
            ->saveAs($file_full_path);
        echo $file_full_path;
    }

    public function fillPDFSample()
    {
        include APPPATH . 'libraries/SetaPDF/Autoload.php'; // library for filling in PDF text fields
        $reader = new SetaPDF_Core_Reader_File('./uploads/borrower/borrower_seller.pdf');
        $writer = new SetaPDF_Core_Writer_File('new' . time() . '.pdf');
        // $document = SetaPDF_Core_Document::loadByFilename('test.pdf');
        $document = SetaPDF_Core_Document::load($reader, $writer);

        $formFiller = new SetaPDF_FormFiller($document);
        $fields = $formFiller->getFields();

        $allFields = $fields->getAll();

        $keyReplaceInfo = array(
            'order_id' => '1 Sellers',
            'escrow_home_phone_number' => 'undefined#2',
            'work_phone_number' => 'undefined_2#2',
            'fax_number' => 'undefined_3',
            'cell_phone_number' => 'undefined_4',
            'email_address' => 'EMail Address',
            'cell_phone_number_2' => 'undefined_5',
            'escrow_ssn' => '2 Social Security',
            'ssn_2' => 'Social Security',
            'property_address' => '3 Property Address',
            'seller_current_mailing_address' => '4 Sellers Current Mailing Address',
            'seller_mailing_address_after_close_escrow' => '5 Sellers Mailing Address after Close of Escrow 1',
            'seller_mailing_address_after_close_escrow_2' => '5 Sellers Mailing Address after Close of Escrow 2',
            'first_trust_deed_lender' => 'FIRST TRUST DEED LENDER',
            'lender_address' => 'Address#1',
            'loan_number' => 'Loan Number',
            'lender_phone_number' => 'undefined_6',
            'unpaid_principal_balance' => 'Unpaid Principal Balance',
            'next_due' => 'Next Due#1',
            'type_of_loan' => 'type of Loan',
            'va' => 'VA',
            'fha' => 'FHA',
            'conventional' => 'Conventional',
            'taxes' => 'TAXES',
            'paid' => 'Paid',
            'unpaid' => 'Unpaid',
            'is_impound_acc' => '',
            'second_trust_deed_lender' => 'SECOND TRUST DEED LENDER',
            'second_lender_address' => 'Address_2',
            'second_loan_number' => 'Loan Number_2',
            'second_lender_phone_number' => 'undefined_7',
            'second_unpaid_principal_balance' => 'Unpaid Principal Balance_2',
            'second_type_of_loan' => 'type of Loan_2',
            'second_va' => 'VA_2',
            'second_fha' => 'FHA_2',
            'second_conventional' => 'Conventional_2',
            'homeowner_association' => '7 Homeowners Association',
            'management_company' => 'Management Company#1',
            'management_mailing_address' => 'Mailing Address#1',
            'contact_person' => 'Contact Person#1',
            'management_phone_number' => 'undefined#2',
            'second_homeowner_association' => '8 Homeowners Association',
            'second_management_company' => 'Management Company#1',
            'second_management_mailing_address' => 'Mailing Address#1',
            'second_contact_person' => 'Contact Person#1',
            'second_management_phone_number' => 'undefined#2',
            'water_company_name' => 'Name of Company',
            'water_contract_name' => 'Name of Contact',
            'water_company_address' => 'Address#1',
            'water_company_phone' => 'undefined_2#2',
            'amount_of_assessment' => 'Amount of assessment',
            'water_next_due' => 'Next Due#1',
            'no_of_shares' => 'No of Shares',
            'date' => 'Date#2',
            'escrow_signature' => 'Client Name',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'maiden_name' => 'Maiden Name',
            'date_of_birth' => 'Date of Birth',
            'home_phone_number' => 'Home Phone',
            'business_phone_number' => 'Business Phone',
            'birthplace' => 'Birthplace',
            'ssn' => 'Social Security No',
            'driver_license_no' => 'Drivers License No',
            'another_name_that_used' => 'List any other name you have used or been known by',
            'residence_state' => 'State of residence',
            'lived_year' => 'I have lived continuously in the USA since',
            'is_married' => '',
            'date_and_place_marriage' => 'Date and place of marriage',
            'spouse_first_name' => '',
            'spouse_middle_name' => '',
            'spouse_last_name' => '',
            'spouse_maiden_name' => '',
            'spouse_date_of_birth' => 'Date of Birth_2',
            'spouse_home_phone_number' => 'Home Phone_2',
            'spouse_business_phone_number' => 'Business Phone_2',
            'spouse_birthplace' => 'Birthplace_2',
            'spouse_ssn' => 'Social Security No_2',
            'spouse_driver_license_no' => 'Drivers License No_2',
            'spouse_another_name_that_used' => 'List any other names you have used or been known by',
            'spouse_state_residence' => 'State of residence_2',
            'spouse_lived_year' => 'I have lived continuously in the USA since_2',
            'is_domestic_partner' => '',
            'domestic_first_name' => 'Domestic Partner',
            'domestic_middle_name' => '',
            'domestic_last_name' => '',
            'domestic_maiden_name' => '',
            'domestic_date_of_birth' => 'Date of Birth_3',
            'domestic_home_phone_number' => 'Home Phone_3',
            'domestic_business_phone_number' => 'Business Phone_3',
            'domestic_birthplace' => 'Birthplace_3',
            'domestic_ssn' => 'Social Security No_3',
            'domestic_driver_license_no' => 'Drivers License No_3',
            'domestic_another_name_that_used' => 'List any other names you have used or been known by_2',
            'domestic_state_residence' => 'State of residence_3',
            'domestic_lived_year' => 'I have lived continuously in the USA since_3',
            'residence_number_street' => 'Number  Street',
            'residence_city' => 'City',
            'residence_from_date_to_date' => 'From date to date',
            'second_residence_number_street' => 'Number  Street_2',
            'second_residence_city' => 'City_2',
            'second_residence_from_date_to_date' => 'From date to date_2',
            'business_address' => 'Address_3#1',
            'firm_or_business_name' => 'Firm or Business name',
            'business_from_date_to_date' => 'From date to date_3',
            'second_firm_or_business_name' => 'Firm or Business name_2',
            'second_business_address' => 'Address_4',
            'second_business_from_date_to_date' => 'From date to date_4',
            'is_buyer_intends' => '',
            'owner_street_address' => 'APN, City, Zip',
            'unimproved' => 'The land is unimproved',
            'single_family' => 'or improved with a structure of the following type  A Single or 14 Family',
            'condo_unit' => 'Condo Unit',
            'other' => 'Other_3',
            'is_improvement' => '',
            'is_materials' => '',
            'is_loan' => '',
            'lender' => 'Lender',
            'loan_amount' => 'Loan Amount',
            'loan_account' => 'Loan Account',
            'second_lender' => 'Lender_2',
            'second_loan_amount' => 'Loan Amount_2',
            'second_loan_account' => 'Loan Account_2',
            'executed_date' => 'Executed on',
            'executed_year' => 'undefined_22',
            'executed_time' => 'at',
            'signature' => '',
            'second_signature' => '',
            'is_amended' => '',
            'remitter_info' => '',
            'business_name' => 'Business name Pacific Coast Title Company',
            'business_num' => '',
            'remitter_first_name' => 'First name',
            'remitter_initial_name' => '',
            'remitter_last_name' => '',
            'remitter_ssn_or_itin' => '',
            'remitter_address' => '',
            'remitter_city' => '',
            'remitter_state' => '',
            'remitter_zip_code' => '',
            'remitter_telephone_num' => '',
            'trust_types' => '',
            'transferor_first_name' => '',
            'transferor_initial_name' => '',
            'transferor_last_name' => '',
            'transferor_ssn_or_itin' => '',
            'transferor_spouse_first_name' => '',
            'transferor_spouse_middle_name' => '',
            'transferor_spouse_last_name' => '',
            'transferor_spouse_ssn_or_itin' => '',
            'nongrantor_trust_name' => '',
            'transferor_business_num' => '',
            'transferor_address' => '',
            'transferor_city' => '',
            'transferor_state' => '',
            'transferor_zip_code' => '',
            'transferor_telephone_number' => '',
            'transferor_property_address' => '',
            'ownership_percentage' => '',
            'certifications' => '',
            'remitter_name' => '',
            'remitter_ssn_fein' => '',
            'transferee_first_name' => '',
            'transferee_initial_name' => '',
            'transferee_last_name' => '',
            'transferee_ssn_or_itin' => '',
            'transferee_spouse_first_name' => '',
            'transferee_spouse_initial_name' => '',
            'transferee_spouse_last_name' => '',
        );

        $fields = $formFiller->getFields();
        $fieldNames = $fields->getNames();

        $i = 0;

        $keyReplaceInfo = array('seller_name' => '1 Sellers', 'escrow_home_phone_number' => 'undefined#2', 'work_phone_number' => 'undefined_2#2', 'd' => 'newD');

        foreach ($fieldNames as $key => $fieldName) {
            echo $fieldName . "<br>";
            $fields[$fieldName]->setValue($fieldName);
            $i++;
        }

        // $fields->flatten();

        $document->save()->finish();
    }

    public function create_netsheet($random_number)
    {
        $order = $this->getOrderInfo($random_number);
        $data = json_encode(array('FileNumber' => $order[0]['file_number']));
        $userData = array(
            'admin_api' => 1,
        );
        $logid = $this->apiLogs->syncLogs(0, 'resware', 'create_sheet_generate_landing_page', env('RESWARE_ORDER_API') . 'files/search', $data, array(), $order[0]['file_number'], 0);
        $result = $this->resware->make_request('POST', 'files/search', $data, $userData);
        $this->apiLogs->syncLogs(0, 'resware', 'create_note', env('RESWARE_ORDER_API') . 'files/search', $data, $result, $order[0]['file_number'], $logid);

        if (json_decode($result) && count(json_decode($result)->Files)) {
            $post_data = $result_decoded = array();
            $result_decoded = json_decode($result);
            $orderDetails = $this->order->get_order_details($result_decoded->Files[0]->FileID, 1);

            if (!empty($orderDetails)) {
                $post_data['seller'] = $orderDetails['primary_owner'];
            } else {
                $post_data['seller'] = '';
            }
            $property_data = $result_decoded->Files[0]->Properties[0];
            $buyer_data = $result_decoded->Files[0]->Buyers[0];
            $buyer_name = $buyer_data->Primary;

            $post_data['file_id'] = $result_decoded->Files[0]->FileID;
            $post_data['file_number'] = $result_decoded->Files[0]->FileNumber;
            $post_data['loanAmount'] = $result_decoded->Files[0]->Loans[0]->LoanAmount ? $result_decoded->Files[0]->Loans[0]->LoanAmount : 0;
            $post_data['salesPrice'] = $result_decoded->Files[0]->SalesPrice;
            $post_data['city'] = $property_data->City;
            $post_data['county'] = $property_data->County;
            $post_data['borrower'] = $result_decoded->Files[0]->Buyers[0]->Primary->First . " " . $result_decoded->Files[0]->Buyers[0]->Primary->Last;
            $post_data['full_address'] = $property_data->StreetNumber;
            $post_data['full_address'] .= !empty($property_data->StreetDirection) ? ' ' . substr($property_data->StreetDirection, 0, 1) : '';
            $post_data['full_address'] .= ' ' . $property_data->StreetName;
            $post_data['full_address'] .= ' ' . $property_data->StreetSuffix;
            $post_data['full_address'] .= ', ' . $property_data->City;
            $post_data['full_address'] .= ', ' . $property_data->State;
            $post_data['full_address'] .= ' ' . $property_data->Zip;
            $post_data['borrower'] = !empty($buyer_name->First) ? $buyer_name->First : '';
            $post_data['borrower'] .= !empty($buyer_name->Middle) ? ' ' . $buyer_name->Middle : '';
            $post_data['borrower'] .= !empty($buyer_name->Last) ? ' ' . $buyer_name->Last : '';
            $post_data['borrower'] = trim($post_data['borrower']);

            if (empty($post_data['borrower'])) {
                $post_data['borrower'] = !empty($buyer_name->BusinessName) ? $buyer_name->BusinessName : '';
            }

            $post_data['ECD'] = '';
            if (!empty($result_decoded->Files[0]->Dates->FileCompletedDate)) {
                $ecd_timestamp = str_replace("-0000)/", "", str_replace("/Date(", "", $result_decoded->Files[0]->Dates->FileCompletedDate));
                $ecd_date = date('m/d/Y', $ecd_timestamp / 1000);
                $post_data['ECD'] = $ecd_date;
            }

            if (strpos($result_decoded->Files[0]->TransactionProductType->ProductType, 'Sale') !== false) {
                $netsheet_for = $this->input->post('req_type');
                $post_data['lenderInsurance'] = 1;
                $post_data['transactionType'] = 'Resale';
                $post_data['transferTaxesCheck'] = 1;
                if ($netsheet_for == 'buyer') {
                    $post_data['netsheet_for'] = 'buyer';
                    $post_data['buyer_fees']['origin_charge'] = $this->input->post('origin_charge');
                    $post_data['buyer_fees']['appraisal_fee'] = $this->input->post('appraisal_fee');
                    $post_data['buyer_fees']['credit_repot'] = $this->input->post('credit_repot');
                    $post_data['buyer_fees']['prepaid_interest'] = $this->input->post('prepaid_interest');
                    $post_data['buyer_fees']['prepaid_interest_days'] = $this->input->post('prepaid_interest_days');
                    $post_data['buyer_fees']['home_ins'] = $this->input->post('home_ins');
                    $post_data['buyer_fees']['process_fee'] = $this->input->post('process_fee');
                } else {
                    $post_data['netsheet_for'] = 'seller';
                }
            } else {
                $post_data['netsheet_for'] = '';
                $post_data['lenderInsurance'] = 0;
                $post_data['transactionType'] = 'Re-Finance';
                $post_data['transferTaxesCheck'] = 0;
            }
            $post_data['escrowPriceCheck'] = 1;
            $post_data['recordingPriceCheck'] = 1;
            $post_data['print_pdf'] = 1;
            //print_r($post_data);
            $ch = curl_init(env('CALC_API_URL') . 'index.php?welcome/createNetsheetDoc');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . env('PCT_CALC_TOKEN'),
                'Content-Type: application/json',
                'Content-Length: ' . strlen(json_encode($post_data)))
            );
            $error_msg = curl_error($ch);
            $calcResult = json_decode(curl_exec($ch), true);
            $this->apiLogs->syncLogs(0, 'calculator_create_netsheet', 'createNetsheetDoc', env('CALC_API_URL') . 'index.php?welcome/createNetsheetDoc', $post_data, json_encode($calcResult), $order[0]['file_number'], 0);
            //print_r($calcResult);exit;
            if (!empty($calcResult)) {
                if ($calcResult['success'] && !empty($calcResult['document_name'])) {
                    $this->home_model->update(array('calc_title_doc_name' => $calcResult['document_name']), array('file_id' => $order[0]['file_id']), 'order_details');
                    $success[] = "Netsheet document generated successfully.";
                } else {
                    $errors[] = "Something went wrong.Please try again.";
                }
            } else {
                $errors[] = "Something went wrong.Please try again.";
            }
        } else {
            $errors[] = "Order not found.";
        }
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        redirect(base_url() . '/get-netsheet/' . $order[0]['random_number']);
    }

    public function get_netsheet($random_number)
    {
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $order = $this->getOrderInfo($random_number);
        $fileId = $order[0]['file_id'];
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;
        $orderDetails = $this->order->get_order_details($fileId, 1);
        $data['random_number'] = $orderDetails['random_number'];
        $data['file_number'] = $orderDetails['file_number'];
        $data['full_address'] = $orderDetails['full_address'];
        $data['created'] = !empty($orderDetails['created']) ? date("m/d/Y", strtotime($orderDetails['created'])) : '';
        $file_id = $orderDetails['file_id'];

        if (!empty($order[0]['calc_title_doc_name'])) {
            $documentName = $order[0]['calc_title_doc_name'];
            $documentUrl = env('AWS_PATH') . "calc_title_rates/" . $documentName;
            if ($order[0]['prod_type'] == 'loan') {
                $data['action'] = "<div style='display:flex;justify-content: space-around;'><a href=" . $documentUrl . " target='_blanck' download onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"netsheet"' . ");'  title='Download' type='submit' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-download'></i></span>
                <span class='text'>Download</span></a>
                <form onclick='return generate_netsheet(1);' action='" . base_url() . "create-netsheet/" . $orderDetails['random_number'] . "' method='POST'>
                <button title='Regenerate' type='submit' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-seedling'></i></span>
                        <span class='text'>Regenerate</span></button></form></div>";
            } else {
                $data['action'] = "<div style='display:flex;justify-content: space-around;'>
                <a href=" . $documentUrl . " target='_blanck' download onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"netsheet"' . ");' title='Download' type='submit' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-download'></i></span>
                <span class='text'>Download</span></a>
                <button onclick='return generate_netsheet(0);' title='Regenerate' type='submit' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-seedling'></i></span>
                <span class='text'>Regenerate</span></button></div>";
            }
        } else {
            if ($order[0]['prod_type'] == 'loan') {
                $data['action'] = "<div style='display:flex;justify-content: space-around;'>
                        <form onclick='return generate_netsheet(1);' action='" . base_url() . "create-netsheet/" . $orderDetails['random_number'] . "' method='POST'>
                        <button title='Generate' type='submit' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-seedling'></i></span>
                        <span class='text'>Generate</span></button></form></div>";
            } else {
                $data['action'] = "<div style='display:flex;'>
                <button onclick='return generate_netsheet(0);' title='Generate' type='submit' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-seedling'></i></span>
                <span class='text'>Generate</span></button>
                </div>";
            }
        }

        $data['displaySidebar'] = false;
        $this->salesdashboardtemplate->show("order", "get_netsheet", $data);

        // $this->load->view('layout/head_dashboard', $data);
        // $this->load->view('order/get_netsheet', $data);
    }

    public function sendPackage()
    {
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $random_number = $this->uri->segment(2);
        $order = $this->getOrderInfo($random_number);
        $fileId = $order[0]['file_id'];
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;
        $orderDetails = $this->order->get_order_details($fileId, 1);
        $data['orderDetails'] = $orderDetails;
        $data['file_number'] = $orderDetails['file_number'];
        $data['full_address'] = $orderDetails['full_address'];
        $data['created'] = !empty($orderDetails['created']) ? date("m/d/Y", strtotime($orderDetails['created'])) : '';

        $buyer_color = $order[0]['is_buyer_packet_mail_sent'] == '1' ? 'green' : '#f96414';
        $seller_color = $order[0]['is_seller_packet_mail_sent'] == '1' ? 'green' : '#f96414';
        $data['action'] = '<div style="display:flex;">
                            <a data-target="#buyer_welcome" data-toggle="modal" style="margin-right:10px;">
                                <button class="btn btn-grad-2a generate" style="background:' . $buyer_color . ';color:white" type="submit">Send Buyer welcome</button>
                            </a>
                            <a data-target="#seller_welcome" data-toggle="modal">
                                <button class="btn btn-grad-2a generate" style="background:' . $seller_color . ';color:white" type="submit">Send Seller welcome</button>
                            </a>

                        </div>';

        $this->load->view('layout/head_dashboard', $data);
        $this->load->view('order/send_package', $data);
    }

    public function addBuyerOnOrder()
    {
        $userdata = $this->session->userdata('user');
        $file_id = $this->input->post('file_id');
        $order_id = $this->input->post('order_id');
        $buyer_emails = $this->input->post('buyer_emails');
        $buyer_first_names = $this->input->post('buyer_first_names');
        $buyer_last_names = $this->input->post('buyer_last_names');
        $is_main_buyer = $this->input->post('is_main_buyer');
        $orderDetails = $this->order->get_order_details($file_id);
        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        $errors = array();
        $success = array();
        $i = 0;

        $this->home_model->update(array('is_buyer_packet_mail_sent' => 1), array('file_id' => $file_id), 'order_details');

        $this->db->delete('pct_order_borrower_buyer_info', array('order_id' => $order_id));

        foreach ($buyer_emails as $buyerEmail) {
            $is_main_buyer_flag = ((str_replace("is_main_buyer", "", $is_main_buyer)) == $i) ? 1 : 0;
            $buyer_email = $buyerEmail;
            $buyerInfo = array(
                'order_id' => $order_id,
                'first_name' => $buyer_first_names[$i],
                'last_name' => $buyer_last_names[$i],
                'email' => $buyerEmail,
                'is_main_buyer' => $is_main_buyer_flag,
            );
            $this->home_model->insert($buyerInfo, 'pct_order_borrower_buyer_info');

            $form_url = base_url() . 'buyer-info/' . $orderDetails['random_number'];
            $email_data = array(
                'name' => $buyer_first_names[$i] . " " . $buyer_last_names[$i],
                'buyer_first_names' => $buyer_first_names,
                'buyer_last_names' => $buyer_last_names,
                'file_number' => $orderDetails['file_number'],
                'property_address' => $orderDetails['full_address'],
                'random_number' => $orderDetails['random_number'],
                'borrrower' => $orderDetails['primary_owner'],
                'form_url' => $form_url,
                'escrow_officer' => $userdata['name'],
            );

            $borrower_message_body = $this->load->view('emails/welcome_buyer.php', $email_data, true);
            $message_body = $borrower_message_body;
            $subject = $orderDetails['file_number'] . ' - ' . $orderDetails['full_address'] . ' - Welcome to Escrow';

            $mailParams = array(
                'from_mail' => $from_mail,
                'from_name' => $from_name,
                'subject' => $subject,
                'message' => json_encode($email_data),
            );

            if (!empty($buyer_email)) {
                $to = $buyer_email;
                $mailParams['to'] = $to;
                $this->load->helper('sendemail');
                $logid = $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_buyer', '', $mailParams, array(), $order_id, 0);
                $buyer_mail_result = send_email($from_mail, $from_name, $to, $subject, $message_body);
                $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_buyer', '', $mailParams, array('status' => $buyer_mail_result), $order_id, $logid);
            }
            $i++;
        }

        $request = array();
        $endPoint = 'files/' . $file_id . '/notes';
        $request['Subject'] = 'Buyer Welcome Email';
        $request['Body'] = 'Buyers welcome email sent to successfully to ' . implode(', ', $buyer_emails) . " users.";
        $request['FileID'] = $file_id;
        $notes_data = json_encode($request);
        $user_data['admin_api'] = 1;

        $logid = $this->apiLogs->syncLogs(0, 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, array(), $order_id, 0);
        $result = $this->resware->make_request('POST', $endPoint, $notes_data, $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, $result, $order_id, $logid);

        if (isset($result) && !empty($result)) {
            $response = json_decode($result, true);
            if (isset($response['ResponseStatus']) && !empty($response['ResponseStatus'])) {
                $message = isset($response['ResponseStatus']['Message']) && !empty($response['ResponseStatus']['Message']) ? $response['ResponseStatus']['Message'] : '';

            } else {
                $noteId = isset($response['Note']['NoteID']) && !empty($response['Note']['NoteID']) ? $response['Note']['NoteID'] : '';
                $notesData = array(
                    'resware_note_id' => $noteId,
                    'subject' => $request['Subject'],
                    'note' => $request['Body'],
                    'user_id' => 0,
                    'order_id' => $order_id,
                    'task_id' => 4,
                );
                $this->home_model->insert($notesData, 'pct_order_notes');
            }
        }

        $success[] = "Mail sent succesfully to buyers.";
        $data['errors'] = $errors;
        $data['success'] = $success;
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        redirect(base_url() . 'send-package/' . $orderDetails['random_number']);
    }

    public function addSellerOnOrder()
    {
        $userdata = $this->session->userdata('user');
        $file_id = $this->input->post('file_id');
        $order_id = $this->input->post('order_id');
        $seller_emails = $this->input->post('seller_emails');
        $seller_first_names = $this->input->post('seller_first_names');
        $seller_last_names = $this->input->post('seller_last_names');
        $is_main_seller = $this->input->post('is_main_seller');
        $orderDetails = $this->order->get_order_details($file_id);
        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        $errors = array();
        $success = array();
        $i = 0;

        $this->home_model->update(array('is_seller_packet_mail_sent' => 1), array('file_id' => $file_id), 'order_details');
        $this->db->delete('pct_order_borrower_seller_info', array('order_id' => $order_id));

        foreach ($seller_emails as $sellerEmail) {
            $is_main_seller_flag = ((str_replace("is_main_buyer", "", $is_main_seller)) == $i) ? 1 : 0;
            $seller_email = $sellerEmail;
            $sellerInfo = array(
                'order_id' => $order_id,
                'first_name' => $seller_first_names[$i],
                'last_name' => $seller_last_names[$i],
                'email' => $sellerEmail,
                'is_main_seller' => $is_main_seller_flag,
            );
            $this->home_model->insert($sellerInfo, 'pct_order_borrower_seller_info');

            $form_url = base_url() . 'seller-info/' . $orderDetails['random_number'];
            $email_data = array(
                'name' => $seller_first_names[$i] . " " . $seller_last_names[$i],
                'seller_first_names' => $seller_first_names,
                'seller_last_names' => $seller_last_names,
                'file_number' => $orderDetails['file_number'],
                'property_address' => $orderDetails['full_address'],
                'random_number' => $orderDetails['random_number'],
                'borrrower' => $orderDetails['primary_owner'],
                'form_url' => $form_url,
                'escrow_officer' => $userdata['name'],
            );

            $borrower_message_body = $this->load->view('emails/welcome_seller.php', $email_data, true);
            $message_body = $borrower_message_body;
            $subject = $orderDetails['file_number'] . ' - ' . $orderDetails['full_address'] . ' - Welcome to Escrow';

            $mailParams = array(
                'from_mail' => $from_mail,
                'from_name' => $from_name,
                'subject' => $subject,
                'message' => json_encode($email_data),
            );

            if (!empty($seller_email)) {
                $to = $seller_email;
                $mailParams['to'] = $to;
                $this->load->helper('sendemail');
                $logid = $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_seller', '', $mailParams, array(), $order_id, 0);
                $seller_mail_result = send_email($from_mail, $from_name, $to, $subject, $message_body);
                $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_seller', '', $mailParams, array('status' => $seller_mail_result), $order_id, $logid);
            }
            $i++;
        }

        $request = array();
        $endPoint = 'files/' . $file_id . '/notes';
        $request['Subject'] = 'Seller Welcome Email';
        $request['Body'] = 'Sellers welcome email sent to successfully to ' . implode(', ', $seller_emails) . " users.";
        $request['FileID'] = $file_id;
        $notes_data = json_encode($request);
        $user_data['admin_api'] = 1;

        $logid = $this->apiLogs->syncLogs(0, 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, array(), $order_id, 0);
        $result = $this->resware->make_request('POST', $endPoint, $notes_data, $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'create_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, $result, $order_id, $logid);

        if (isset($result) && !empty($result)) {
            $response = json_decode($result, true);
            if (isset($response['ResponseStatus']) && !empty($response['ResponseStatus'])) {
                $message = isset($response['ResponseStatus']['Message']) && !empty($response['ResponseStatus']['Message']) ? $response['ResponseStatus']['Message'] : '';

            } else {
                $noteId = isset($response['Note']['NoteID']) && !empty($response['Note']['NoteID']) ? $response['Note']['NoteID'] : '';
                $notesData = array(
                    'resware_note_id' => $noteId,
                    'subject' => $request['Subject'],
                    'note' => $request['Body'],
                    'user_id' => 0,
                    'order_id' => $order_id,
                    'task_id' => 4,
                );
                $this->home_model->insert($notesData, 'pct_order_notes');
            }
        }

        $success[] = "Mail sent succesfully to sellers.";
        $data['errors'] = $errors;
        $data['success'] = $success;
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        redirect(base_url() . 'send-package/' . $orderDetails['random_number']);
    }

    public function nationalForm()
    {
        $this->load->model('order/document');
        $data['title'] = 'National Form | Pacific Coast Title Company';
        $data['mail_dashboard'] = 1;

        $errors = array();
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }

        if ($this->input->post()) {
            $buyers_names = $buyer_pdf = array();
            $input = $this->input->post();
            $this->form_validation->set_rules('buyer_name', 'Buyer/borrower name', 'required', array('required' => 'Enter Buyer/borrower name'));
            $this->form_validation->set_rules('buyer_current_address', 'Buyer/borrower current address', 'required', array('required' => 'Enter buyer/borrower current address'));
            $this->form_validation->set_rules('buyer_email', 'Borrower Email', 'required', array('required' => 'Please enter borrower Email'));
            $this->form_validation->set_rules('buyer_mobile', 'Borrower Phone', 'required', array('required' => 'Please enter borrower Phone'));

            $this->form_validation->set_rules('buyer_property_address', 'Subject property address', 'required', array('required' => 'Please enter subject property address'));
            $this->form_validation->set_rules('title_hold_reason', 'How will buyer/borrower(s) hold Title?', 'required', array('required' => 'Please enter hold title'));
            $this->form_validation->set_rules('ssn', 'SSN', 'required', array('required' => 'Please enter SSN'));
            $this->form_validation->set_rules('estimated_closing_date', 'Estimated closing date', 'required', array('required' => 'Please enter Estimated closing date'));
            $this->form_validation->set_rules('lender', 'Lender', 'required', array('required' => 'Please enter lender'));
            $this->form_validation->set_rules('loan_amount', 'Loan amount', 'required', array('required' => 'Please enter Loan amount'));
            $this->form_validation->set_rules('loan_number', 'Loan number', 'required', array('required' => 'Please enter Loan number'));
            $this->form_validation->set_rules('type_of_loan', 'Type of loan', 'required', array('required' => 'Please enter Type of loan'));
            $this->form_validation->set_rules('title_items_required_by', 'Title items required by Lender', 'required', array('required' => 'Please enter Title items required by Lender'));
            $this->form_validation->set_rules('lender_clause', 'Lender/Mortgagee clause', 'required', array('required' => 'Please enter Lender/Mortgagee clause'));
            $this->form_validation->set_rules('return_document_to', 'Return documents to', 'required', array('required' => 'Please enter Return documents to'));
            $this->form_validation->set_rules('main_lender_contact', 'Main Lender contact', 'required', array('required' => 'Please enter Main Lender contact'));
            $this->form_validation->set_rules('loan_officer', 'Loan officer', 'required', array('required' => 'Please enter Loan officer'));
            $this->form_validation->set_rules('sales_rep', 'Sales Rep', 'required', array('required' => 'Please select Sales Rep'));
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'required', array('required' => 'Please select Marital Status'));

            if ($this->form_validation->run($this) == true) {
                $salesRepId = $input['sales_rep'];
                $inputData = [
                    "buyer_name" => isset($input['buyer_name']) && !empty($input['buyer_name']) ? $input['buyer_name'] : null,
                    "buyer_current_address" => isset($input['buyer_current_address']) && !empty($input['buyer_current_address']) ? $input['buyer_current_address'] : null,
                    "buyer_email" => isset($input['buyer_email']) && !empty($input['buyer_email']) ? $input['buyer_email'] : null,
                    "buyer_mobile" => isset($input['buyer_mobile']) && !empty($input['buyer_mobile']) ? $input['buyer_mobile'] : null,
                    "buyer_property_address" => isset($input['buyer_property_address']) && !empty($input['buyer_property_address']) ? $input['buyer_property_address'] : null,
                    "title_hold_reason" => isset($input['title_hold_reason']) && !empty($input['title_hold_reason']) ? $input['title_hold_reason'] : null,
                    "ssn" => isset($input['ssn']) && !empty($input['ssn']) ? $input['ssn'] : null,
                    "estimated_closing_date" => isset($input['estimated_closing_date']) && !empty($input['estimated_closing_date']) ? $input['estimated_closing_date'] : null,
                    "lender" => isset($input['lender']) && !empty($input['lender']) ? $input['lender'] : null,
                    "loan_amount" => isset($input['loan_amount']) && !empty($input['loan_amount']) ? $input['loan_amount'] : null,
                    "loan_number" => isset($input['loan_number']) && !empty($input['loan_number']) ? $input['loan_number'] : null,
                    "type_of_loan" => isset($input['type_of_loan']) && !empty($input['type_of_loan']) ? $input['type_of_loan'] : null,
                    "title_items_required_by" => isset($input['title_items_required_by']) && !empty($input['title_items_required_by']) ? $input['title_items_required_by'] : null,
                    "lender_clause" => isset($input['lender_clause']) && !empty($input['lender_clause']) ? $input['lender_clause'] : null,
                    "return_document_to" => isset($input['return_document_to']) && !empty($input['return_document_to']) ? $input['return_document_to'] : null,
                    "main_lender_contact" => isset($input['main_lender_contact']) && !empty($input['main_lender_contact']) ? $input['main_lender_contact'] : null,
                    "loan_officer" => isset($input['loan_officer']) && !empty($input['loan_officer']) ? $input['loan_officer'] : null,
                    "marital_status" => isset($input['marital_status']) && !empty($input['marital_status']) ? $input['marital_status'] : null,
                    "sales_rep" => isset($input['sales_rep']) && !empty($input['sales_rep']) ? $salesRepId : null,

                ];

                if ($inputData['sales_rep']) {
                    $condition = array(
                        'where' => array(
                            'id' => $inputData['sales_rep'],
                        ),
                    );
                    $salesRepDetails = $this->home_model->getSalesRepDetails($condition)[0];
                }
                $this->home_model->insert($inputData, 'pct_order_national_form_data');
                $inputData['sales_rep_name'] = $salesRepDetails['first_name'] . ' ' . $salesRepDetails['last_name'];

                $borrower_message_body = $this->load->view('emails/national_form.php', $inputData, true);
                $message_body = $borrower_message_body;
                $subject = 'Subject to national form submission';
                $from_mail = env('FROM_EMAIL');
                $from_name = 'Pacific Coast Title Company';
                $mailParams = array(
                    'from_mail' => $from_mail,
                    'from_name' => $from_name,
                    'subject' => $subject,
                    'message' => json_encode($email_data),
                );

                // if (!empty($buyer_email)) {
                $to = "national@pct.com";
                $cc = ['piyush.j@crestinfosystems.net'];
                $mailParams['to'] = $to;
                $this->load->helper('sendemail');
                $logid = $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_national_pct', '', $mailParams, array(), 0, 0);
                $result = send_email($from_mail, $from_name, $to, $subject, $message_body, null, $cc, []);
                // $mail_result = send_email($from_mail, $from_name, $to, $subject, $message, $file, $cc, array());
                $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_national_pct', '', $mailParams, array('status' => $result), 0, $logid);
                if ($result) {
                    $success = 'Email sent successfully.';
                } else {
                    $error = 'Please try again later.';
                }
                $data['errors'] = $error;
                $data['success'] = $success;
                $this->session->set_userdata($data);
                redirect(base_url() . 'national-form/');exit;
            } else {
                $data['buyer_name_error_msg'] = form_error('buyer_name');
                $data['buyer_current_address_error_msg'] = form_error('buyer_current_address');
                $data['buyer_email_error_msg'] = form_error('buyer_email');
                $data['buyer_mobile_error_msg'] = form_error('buyer_mobile');
                $data['buyer_property_address_error_msg'] = form_error('buyer_property_address');
                $data['title_hold_reason_error_msg'] = form_error('title_hold_reason');
                $data['ssn_error_msg'] = form_error('ssn');
                $data['estimated_closing_date_error_msg'] = form_error('estimated_closing_date');
                $data['lender_error_msg'] = form_error('lender');
                $data['loan_amount_error_msg'] = form_error('loan_amount');
                $data['loan_number_error_msg'] = form_error('loan_number');
                $data['type_of_loan_error_msg'] = form_error('type_of_loan');
                $data['title_items_required_by_error_msg'] = form_error('title_items_required_by');
                $data['lender_clause_error_msg'] = form_error('lender_clause');
                $data['return_document_to_error_msg'] = form_error('return_document_to');
                $data['main_lender_contact_error_msg'] = form_error('main_lender_contact');
                $data['loan_officer_error_msg'] = form_error('loan_officer');
                $data['marital_status_error_msg'] = form_error('marital_status');
                $data['sales_rep_error_msg'] = form_error('sales_rep');
            }

        }
        $condition = array(
            'where' => array(
                'is_sales_rep' => 1,
                'status' => 1,
            ),
        );
        $data['salesRep'] = $this->home_model->getSalesRepDetails($condition);
        $this->load->view('order/national_form', $data);
    }
}
