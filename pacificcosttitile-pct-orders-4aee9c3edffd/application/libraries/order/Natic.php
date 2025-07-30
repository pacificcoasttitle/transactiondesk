<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Natic
{
    public static $CI;

    public function __construct($params = array())
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
        $this->CI->load->library('email');
        $this->CI->load->library('session');
        self::$CI = $this->CI;
    }

    public function make_request($xml, $endpoint)
    {
        $headers = array(
            "Content-type: text/xml",
            "Content-length: " . strlen($xml),
            "Connection: close",
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        } else {
            curl_close($ch);
            return $data;
        }
    }

    public function getBranchesFromApi($cplApi = 'natic')
    {
        if (!empty($this->CI->session->userdata('user'))) {
            $userdata = $this->CI->session->userdata('user');
        } else if (!empty($this->CI->session->userdata('admin'))) {
            $userdata = $this->CI->session->userdata('admin');
        } else {
            $userdata = array();
            $userdata['id'] = 0;
        }

        $username = getenv('NATIC_USERNAME');
        $password = getenv('NATIC_PASSWORD');
        $companyName = getenv('NATIC_COMPANY');
        $url = getenv('NATIC_URL');
        $branchTableName = 'pct_order_natic_branches';

        if ($cplApi == 'doma') {
            $username = getenv('DOMA_USERNAME');
            $password = getenv('DOMA_PASSWORD');
            $companyName = getenv('DOMA_COMPANY');
            $url = getenv('DOMA_URL');
            $branchTableName = 'pct_order_doma_branches';
        }

        $xmlData = "<?xml version='1.0' encoding='utf-8'?>
                        <RequestWrapper>
                            <UserName>" . $username . "</UserName>
                            <Password>" . $password . "#</Password>
                            <TransactionId>" . rand(10000, 99999) . "</TransactionId>
                            <CompanyName>" . $companyName . "</CompanyName>
                            <AuthorizationRequest>
                                <PropertyState>CA</PropertyState>
                                <RequestType>ClosingProtectionLetter</RequestType>
                            </AuthorizationRequest>
                        </RequestWrapper>";

        $endPoint = 'Authorize';
        $this->CI->load->model('order/apiLogs');
        $logid = $this->CI->apiLogs->syncLogs($userdata['id'], $cplApi, 'get_branches', $url . $endPoint, $xmlData, array(), 0, 0);
        $resultAuthorize = $this->make_request($xmlData, $url . $endPoint);
        $this->CI->apiLogs->syncLogs($userdata['id'], $cplApi, 'get_branches', $url . $endPoint, $xmlData, $resultAuthorize, 0, $logid);
        $responseData = $this->xml2array($resultAuthorize, 0);
        if (!empty($responseData['ResponseWrapper']['AuthorizationResponse']['DocumentCollection']['ApprovedSettlementOfficeList'])) {

            foreach ($responseData['ResponseWrapper']['AuthorizationResponse']['DocumentCollection']['ApprovedSettlementOfficeList']['ApprovedSettlementOffice'] as $branches) {
                if (strpos($branches['Name'], 'Pacific Coast Title Company') !== false) {
                    $addressInfo = explode(',', $branches['Name']);
                    if (count($addressInfo) == 5) {
                        $address = $addressInfo[1];
                        $address1 = $addressInfo[2];
                        $city = $addressInfo[3];
                        $zipcode = str_replace('CA ', '', $addressInfo[4]);
                    } else if (count($addressInfo) == 4) {
                        $address = $addressInfo[1];
                        $address1 = null;
                        $city = $addressInfo[2];
                        $zipcode = str_replace('CA ', '', $addressInfo[3]);
                    } else {
                        $address = null;
                        $address1 = null;
                        $city = null;
                        $zipcode = null;
                    }

                    if (!empty($address) && !empty($city) && !empty($zipcode)) {
                        $this->CI->db->select('*');
                        $this->CI->db->from($branchTableName);
                        $this->CI->db->like('city', $city);
                        $query = $this->CI->db->get();
                        $result = $query->row_array();
                        if (!empty($result)) {
                            $condition = array(
                                'city' => $result['city'],
                            );
                            $branchData = array(
                                'unique_id' => $branches['UniqueId'],
                                'address' => $address,
                                'address1' => $address1,
                                'state' => 'CA',
                                'zip' => $zipcode,
                                'updated_at' => date('Y-m-d H:i:s'),
                            );
                            $this->CI->db->update($branchTableName, $branchData, $condition);
                            $branchData['city'] = $result['city'];
                        } else {
                            $branchData = array(
                                'unique_id' => $branches['UniqueId'],
                                'address' => $address,
                                'address1' => $address1,
                                'city' => $city,
                                'state' => 'CA',
                                'zip' => $zipcode,
                                'created_at' => date('Y-m-d H:i:s'),
                            );
                            $this->CI->db->insert($branchTableName, $branchData);
                        }
                        $branchesData[] = $branchData;
                    }
                }
            }
            return $branchesData;
        } else {
            return false;
        }
    }

    public function getBranches($id = 0)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('pct_order_natic_branches');
        if (!empty($id)) {
            $this->CI->db->where('id', $id);
        }
        $query = $this->CI->db->get();
        if ($id == 0) {
            $result = $query->result_array();
        } else {
            $result = $query->row_array();
        }
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    public function getDomaBranches($id = 0)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('pct_order_doma_branches');
        if (!empty($id)) {
            $this->CI->db->where('id', $id);
        }
        $query = $this->CI->db->get();
        if ($id == 0) {
            $result = $query->result_array();
        } else {
            $result = $query->row_array();
        }
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    public function getDocumentContentForCpl($fileId, $orderDetails, $cplApi = 'natic')
    {
        $this->CI->load->library('order/order');
        if (!empty($this->CI->session->userdata('user'))) {
            $userdata = $this->CI->session->userdata('user');
        } else if (!empty($this->CI->session->userdata('admin'))) {
            $userdata = $this->CI->session->userdata('admin');
        } else {
            $userdata = array();
            $userdata['id'] = 0;
        }

        $propertyDetail = explode(",", $orderDetails['full_address']);
        $xmlData = '';
        $address = $orderDetails['cpl_proposed_property_address'];
        $city = $orderDetails['cpl_proposed_property_city'];
        $state = $orderDetails['cpl_proposed_property_state'];
        $zipcode = $orderDetails['cpl_proposed_property_zip'];

        $this->CI->load->model('order/home_model');
        $orderUser = $this->CI->home_model->get_user(array('id' => $orderDetails['customer_id']));

        if (!empty($orderDetails['cpl_lender_id'])) {
            $lenderDetails = $this->CI->home_model->get_user(array('id' => $orderDetails['cpl_lender_id']));
            $lenderFormData = $this->CI->session->has_userdata('lender_details') ? $this->CI->session->userdata('lender_details') : [];
            $orderDetails['lender_assignment_clause'] = (!empty($lenderFormData) ? $lenderFormData['assignment_clause'] : ($lenderDetails['assignment_clause'] ? $lenderDetails['assignment_clause'] : ''));
            $orderDetails['lender_address'] = !empty($lenderFormData) ? $lenderFormData['street_address'] : $lenderDetails['street_address'];
            $orderDetails['lender_city'] = !empty($lenderFormData) ? $lenderFormData['city'] : $lenderDetails['city'];
            $orderDetails['lender_state'] = !empty($lenderFormData) ? $lenderFormData['state'] : $lenderDetails['state'];
            $orderDetails['lender_zipcode'] = !empty($lenderFormData) ? $lenderFormData['zip_code'] : $lenderDetails['zip_code'];
            $orderDetails['lender_company_name'] = !empty($lenderFormData) ? $lenderFormData['company_name'] : $lenderDetails['company_name'];
            $orderDetails['lender_first_name'] = $lenderDetails['first_name'];
            $orderDetails['lender_last_name'] = $lenderDetails['last_name'];
            $orderDetails['lender_fullname'] = $lenderFormData['lender_fullname'];
        }

        $borrower = $orderDetails['borrowers_vesting'];
        $username = getenv('NATIC_USERNAME');
        $password = getenv('NATIC_PASSWORD');
        $companyName = getenv('NATIC_COMPANY');
        $url = getenv('NATIC_URL');
        $documentId = getenv('NATIC_DOCUMENT_ID');
        $branchTableName = 'pct_order_natic_branches';

        if ($cplApi == 'doma') {
            $username = getenv('DOMA_USERNAME');
            $password = getenv('DOMA_PASSWORD');
            $companyName = getenv('DOMA_COMPANY');
            $url = getenv('DOMA_URL');
            $documentId = getenv('DOMA_DOCUMENT_ID');
            $branchTableName = 'pct_order_doma_branches';
            $branchData = $this->getDomaBranches($orderDetails['fnf_agent_id']);
        } else {
            $branchData = $this->getBranches($orderDetails['fnf_agent_id']);
        }

        $xmlData = "<Field>
                    <FieldId>FileNumber</FieldId>
                    <Name>Agent's File Number</Name>
                    <Value>" . $orderDetails['file_number'] . "</Value>
                    <Type>String</Type>
                    <Required>true</Required>
                </Field>
                <Field>
                    <FieldId>PropertyAddress1</FieldId>
                    <Name>Property Address 1</Name>
                    <Value>" . htmlspecialchars($address, ENT_XML1) . "</Value>
                    <Type>String</Type>
                    <Required>true</Required>
                </Field>

                <Field>
                    <FieldId>PropertyCity</FieldId>
                    <Name>Property City</Name>
                    <Value>" . $city . "</Value>
                    <Type>String</Type>
                    <Required>true</Required>
                </Field>
                <Field>
                    <FieldId>PropertyState</FieldId>
                    <Name>Property State</Name>
                    <Value>" . $state . "</Value>
                    <Type>String</Type>
                    <Required>true</Required>
                </Field>
                <Field>
                    <FieldId>PropertyPostalCode</FieldId>
                    <Name>Property Postal Code</Name>
                    <Value>" . $zipcode . "</Value>
                    <Type>String</Type>
                    <Required>true</Required>
                </Field>
                <Field>
                    <FieldId>PropertyDescription</FieldId>
                    <Name>Brief Legal Description</Name>
                    <Value>" . htmlspecialchars($orderDetails['legal_description'], ENT_XML1) . "</Value>
                    <Type>String</Type>
                    <Required>true</Required>
                </Field>
                <Field>
                    <FieldId>LoanNumber</FieldId>
                    <Name>Loan Number</Name>
                    <Value>Loan No: " . $orderDetails['loan_number'] . "</Value>
                    <Type>String</Type>
                    <Required>false</Required>
                </Field>
                <Field>
                    <FieldId>LoanAmount</FieldId>
                    <Name>Loan Amount</Name>
                    <Value>" . $orderDetails['loan_amount'] . "</Value>
                    <Type>Decimal</Type>
                    <Required>false</Required>
                </Field>
                <Field>
                    <FieldId>LenderName</FieldId>
                    <Name>Lender Name</Name>
                    <Value>" . htmlspecialchars($orderDetails['lender_company_name'], ENT_XML1) . "</Value>
                    <Type>String</Type>
                    <Required>true</Required>
                </Field>
                <Field>
                    <FieldId>LenderNote</FieldId>
                    <Name>Lender Note</Name>
                    <Value>" . htmlspecialchars($orderDetails['lender_assignment_clause'], ENT_XML1) . "</Value>
                    <Type>String</Type>
                    <Required>true</Required>
                </Field>
                <Field>
                    <FieldId>LenderContactName</FieldId>
                    <Name>Lender Contact Name</Name>
                    <Value>" . $orderDetails['lender_fullname'] . "</Value>
                    <Type>String</Type>
                    <Required>true</Required>
                </Field>
                <Field>
                    <FieldId>LenderAddress1</FieldId>
                    <Name>Lender Address 1</Name>
                    <Value>" . htmlspecialchars($orderDetails['lender_address'], ENT_XML1) . "</Value>
                    <Type>String</Type>
                    <Required>true</Required>
                </Field>
                <Field>
                    <FieldId>LenderAddress2</FieldId>
                    <Name>Lender Address 2</Name>
                    <Value></Value>
                    <Type>String</Type>
                    <Required>false</Required>
                </Field>
                <Field>
                    <FieldId>LenderCity</FieldId>
                    <Name>Lender City</Name>
                    <Value>" . $orderDetails['lender_city'] . "</Value>
                    <Type>String</Type>
                    <Required>true</Required>
                </Field>
                <Field>
                    <FieldId>LenderState</FieldId>
                    <Name>Lender State</Name>
                    <Value>" . $orderDetails['lender_state'] . "</Value>
                    <Type>String</Type>
                    <Required>true</Required>
                </Field>
                <Field>
                    <FieldId>LenderPostalCode</FieldId>
                    <Name>Lender Postal Code</Name>
                    <Value>" . $orderDetails['lender_zipcode'] . "</Value>
                    <Type>String</Type>
                    <Required>true</Required>
                </Field>
                <Field>
                    <FieldId>TitleCompanyName</FieldId>
                    <Name>Title Company Name</Name>
                    <Type>String</Type>
                    <Required>false</Required>
                    <Value>Pacific Coast Title Company</Value>
                </Field>
                <Field>
                    <FieldId>TitleCompanyAddress1</FieldId>
                    <Name>Title Company Address 1</Name>
                    <Type>String</Type>
                    <Required>false</Required>
                    <Value>" . htmlspecialchars($branchData['address'], ENT_XML1) . "</Value>
                </Field>
                <Field>
                    <FieldId>TitleCompanyAddress2</FieldId>
                    <Name>Title Company Address 2</Name>
                    <Type>String</Type>
                    <Required>false</Required>
                    <Value>" . $branchData['address1'] . "</Value>
                </Field>
                <Field>
                    <FieldId>TitleCompanyCity</FieldId>
                    <Name>Title Company City</Name>
                    <Type>String</Type>
                    <Required>false</Required>
                    <Value>" . $branchData['city'] . "</Value>
                </Field>
                <Field>
                    <FieldId>TitleCompanyState</FieldId>
                    <Name>Title Company State</Name>
                    <Type>String</Type>
                    <Required>false</Required>
                    <Value>" . $branchData['state'] . "</Value>
                </Field>
                <Field>
                    <FieldId>TitleCompanyPostalCode</FieldId>
                    <Name>Title Company Postal Code</Name>
                    <Type>String</Type>
                    <Required>false</Required>
                    <Value>" . $branchData['zip'] . "</Value>
                </Field>
                <Field>
                    <FieldId>Buyer</FieldId>
                    <Name>Buyer/Borrower Name</Name>
                    <Value>" . htmlspecialchars($borrower, ENT_XML1) . "</Value>
                    <Type>String</Type>
                    <Required>false</Required>
                </Field>
                ";

        $xmlData = "<?xml version='1.0' encoding='utf-8'?>
                        <RequestWrapper>
                            <UserName>" . $username . "</UserName>
                            <Password>" . $password . "#</Password>
                            <TransactionId>" . rand(10000, 99999) . "</TransactionId>
                            <CompanyName>" . $companyName . "</CompanyName>
                            <DocumentCollection>
                                <PropertyState>CA</PropertyState>
                                <DocumentList>
                                    <Document>
                                        <DocumentId>" . $documentId . "</DocumentId>
                                        <ReferenceId>" . rand(100000, 999999) . "</ReferenceId>
                                        <Name>CAStateLetter</Name>
                                        <RequestType>ClosingProtectionLetter</RequestType>
                                        <FieldList>
                                        " . $xmlData . "
                                        </FieldList>
                                    </Document>
                                </DocumentList>
                                <ApprovedAttorneyList />
                                <ApprovedSettlementOffice />
                            </DocumentCollection>
                        </RequestWrapper>";

        $endPoint = 'GetDocuments';

        $this->CI->load->model('order/apiLogs');
        $logid = $this->CI->apiLogs->syncLogs($userdata['id'], $cplApi, 'get_document', $url . $endPoint, $xmlData, array(), $orderDetails['order_id'], 0);
        $resultDocument = $this->make_request($xmlData, $url . $endPoint);
        $this->CI->apiLogs->syncLogs($userdata['id'], $cplApi, 'get_document', $url . $endPoint, $xmlData, $resultDocument, $orderDetails['order_id'], $logid);
        $responseData = $this->xml2array($resultDocument, 0);
        if (!empty($responseData['ResponseWrapper']['DocumentCollection']['DocumentList']['Document']['Content'])) {
            return array('success' => true, 'content' => $responseData['ResponseWrapper']['DocumentCollection']['DocumentList']['Document']['Content']);
        } else {
            return array('success' => false, 'error' => $responseData['ResponseWrapper']['Error']['ErrorMessage']);
        }
    }

    public function xml2array($contents, $get_attributes = 1, $priority = 'tag')
    {
        if (!$contents) {
            return array();
        }

        if (!function_exists('xml_parser_create')) {
            return array();
        }

        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if (!$xml_values) {
            return;
        }

        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();
        $current = &$xml_array;
        $repeated_tag_index = array();

        foreach ($xml_values as $data) {

            unset($attributes, $value);
            extract($data);

            $result = array();
            $attributes_data = array();

            if (isset($value)) {
                if ($priority == 'tag') {
                    $result = $value;
                } else {
                    $result['value'] = $value;
                }

            }
            if (isset($attributes) and $get_attributes) {
                foreach ($attributes as $attr => $val) {
                    if ($priority == 'tag') {
                        $attributes_data[$attr] = $val;
                    } else {
                        $result['attr'][$attr] = $val;
                    }

                }
            }
            if ($type == "open") {
                $parent[$level - 1] = &$current;
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) {
                    $current[$tag] = $result;
                    if ($attributes_data) {
                        $current[$tag . '_attr'] = $attributes_data;
                    }

                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    $current = &$current[$tag];
                } else {
                    if (isset($current[$tag][0])) {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else {
                        $current[$tag] = array($current[$tag], $result);
                        $repeated_tag_index[$tag . '_' . $level] = 2;
                        if (isset($current[$tag . '_attr'])) {
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }
                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = &$current[$tag][$last_item_index];
                }

            } elseif ($type == "complete") {
                if (!isset($current[$tag])) {
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data) {
                        $current[$tag . '_attr'] = $attributes_data;
                    }

                } else {
                    if (isset($current[$tag][0]) and is_array($current[$tag])) {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        if ($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else {
                        $current[$tag] = array($current[$tag], $result);
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) {
                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }
                            if ($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;
                    }
                }
            } elseif ($type == 'close') {
                $current = &$parent[$level - 1];
            }
        }
        return ($xml_array);
    }

}
