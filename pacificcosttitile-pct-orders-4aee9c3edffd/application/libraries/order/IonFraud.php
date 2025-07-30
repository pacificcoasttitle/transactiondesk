<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class IonFraud
{
    public static $CI;

    public function __construct($params = array())
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
        $this->CI->load->library('session');
        $this->CI->load->library('order/order');
        self::$CI = $this->CI;
    }

    public function make_xml_request($xml, $endpoint)
    {
        $headers = array(
            "Content-type: text/xml",
            "SOAPAction: http://www.valuecheckonline.com/ExecuteSearch",
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

    public function getIONFraudPropertyDetails($address, $state = 'CA')
    {
        // echo "Hello service call";die;
        if (!empty($this->CI->session->userdata('user'))) {
            $userdata = $this->CI->session->userdata('user');
        } else if (!empty($this->CI->session->userdata('admin'))) {
            $userdata = $this->CI->session->userdata('admin');
        } else {
            $userdata = array();
            $userdata['id'] = 0;
        }

        $siteid = getenv('ION_FRAUD_SITEID');
        $wspass = getenv('ION_FRAUD_WSPASS');
        $url = getenv('ION_FRAUD_URL');
        $branchTableName = 'order_natic_branches';
        // print_r($siteid);die;

        $siteId = 'VCPCT';
        $password = 'X2Z#O)ze!+Z?vt@@';
        $parameters = "<PropertySearchService>
                            <Parameters>
                                <SearchType>Address</SearchType>
                                <ReturnFormat>AddressVerifyExt</ReturnFormat>
                                <State>" . $state . "</State>
                                <Address>" . $address . "</Address>
                            </Parameters>
                        </PropertySearchService>";
        $xmlData = '<?xml version="1.0" encoding="utf-8"?>
                        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                        <soap:Body>
                        <ExecuteSearch xmlns="http://www.valuecheckonline.com/">
                        <SiteID>' . $siteid . '</SiteID>
                        <Password>' . $wspass . '</Password>
                        <xmlParameters>' . htmlentities($parameters) . '</xmlParameters>
                        </ExecuteSearch>
                        </soap:Body>
                        </soap:Envelope>';
        // print_r($xmlData);die;

        $this->CI->load->model('order/apiLogs');
        $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'ion_fraud', 'ion_fraud_address_search', $url, $xmlData, array(), 0, 0);
        $resultAuthorize = $this->make_xml_request($xmlData, $url);
        $this->CI->apiLogs->syncLogs($userdata['id'], 'ion_fraud', 'ion_fraud_address_search', $url, $xmlData, $resultAuthorize, 0, $logid);
        // echo "<pre>";
        // print_r($xmlData);
        return $this->xml2array($resultAuthorize, 0);

    }

    public function generateIONReport($orderNumber, $ionProfileData)
    {
        $this->CI->load->library('snappy_pdf');
        // $this->CI->snappy_pdf->pdf->setOption('zoom', '1');
        $html = $this->CI->load->view('report/ion_fraud_report', $ionProfileData, true);

        $document_name = $orderNumber . '-Fraud.pdf';
        if (!is_dir(FCPATH . 'uploads/ion-fraud/report')) {
            mkdir(FCPATH . 'uploads/ion-fraud/report', 0777, true);
        }

        chmod(FCPATH . 'uploads/ion-fraud/report', 0777);

        $dir_name = FCPATH . 'uploads/ion-fraud/report/';
        $dir_name = str_replace('\\', '/', $dir_name);
        $this->CI->snappy_pdf->pdf->generateFromHtml($html, $dir_name . $document_name);
        $response = $this->CI->order->uploadDocumentOnAwsS3($document_name, 'ion-fraud/report');

        $document_name = $orderNumber . '-Letter.pdf';
        if (!is_dir(FCPATH . 'uploads/ion-fraud/letter')) {
            mkdir(FCPATH . 'uploads/ion-fraud/letter', 0777, true);
        }

        $html = $this->CI->load->view('report/ion_fraud_letter', $ionProfileData, true);
        chmod(FCPATH . 'uploads/ion-fraud-report', 0777);

        $dir_name = FCPATH . 'uploads/ion-fraud/letter/';
        $dir_name = str_replace('\\', '/', $dir_name);
        $this->CI->snappy_pdf->pdf->generateFromHtml($html, $dir_name . $document_name);

        $response = $this->CI->order->uploadDocumentOnAwsS3($document_name, 'ion-fraud/letter');
        // echo "leter hello";die;

    }

    public function xml2array($contents, $get_attributes = 1, $priority = 'tag')
    {
        $xml = simplexml_load_string($contents);

        // Register namespaces to parse the XML correctly
        $xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('ns', 'http://www.valuecheckonline.com/');

        // Get the ExecuteSearchResult content, which is an escaped XML string
        $searchResult = $xml->xpath('//ns:ExecuteSearchResult')[0];

        // Decode the escaped XML string inside the ExecuteSearchResult
        $decodedXmlString = htmlspecialchars_decode((string) $searchResult);

        // Load the decoded XML into another SimpleXMLElement object
        $propertyXml = simplexml_load_string($decodedXmlString);

        // Convert the SimpleXMLElement object to a PHP array
        return $propertyArray = json_decode(json_encode($propertyXml), true);

        // Print the result
        print_r($propertyArray);
    }

    public function normalizeName($name)
    {
        // Convert to uppercase and remove extra spaces
        $name = strtolower(trim($name));

        // Split the name into an array
        $parts = explode(' ', $name);

        // Assume the first and last name are at the start and end
        $firstName = $parts[0];
        $lastName = $parts[count($parts) - 1];

        // Check if there's a middle name or initial
        $middleName = count($parts) > 2 ? $parts[1] : '';

        // Return first, middle, and last name in an array
        return [
            'firstName' => $firstName,
            'middleName' => $middleName,
            'lastName' => $lastName,
        ];
    }

    public function compareNames($name1, $name2)
    {
        // Normalize both names
        $normalized1 = normalizeName($name1);
        $normalized2 = normalizeName($name2);

        // Compare first and last names
        if ($normalized1['firstName'] === $normalized2['firstName'] &&
            $normalized1['lastName'] === $normalized2['lastName']) {

            // Optionally, check for initials or middle names match
            if (empty($normalized1['middleName']) || empty($normalized2['middleName']) ||
                $normalized1['middleName'][0] === $normalized2['middleName'][0]) {
                return true;
            } else {
                return true;
            }
        }

        return false;
    }

}
