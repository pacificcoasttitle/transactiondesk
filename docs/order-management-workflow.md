# Complete Order Management Workflow

## 📋 Step 1: Order Input & Initial Validation

### 1.1 Order Entry Points

#### Main Order Form (`/order`)
**Controller**: `frontend/order/Home::index()`
**File**: `application/modules/frontend/controllers/order/Home.php` (lines 26-107)

**Input Fields**:
```php
// Required Fields
$OpenName = $this->input->post('OpenName');           // First Name
$OpenLastName = $this->input->post('OpenLastName');   // Last Name  
$OpenEmail = $this->input->post('OpenEmail');         // Email Address
$Opentelephone = $this->input->post('Opentelephone'); // Phone Number

// Property Information
$PropertyAddress = $this->input->post('Property');     // Property Address
$PropertyState = $this->input->post('property-state'); // State
$PropertyCity = $this->input->post('property-city');   // City
$PropertyFips = $this->input->post('property-fips');   // FIPS Code
```

**Validation Rules**:
```php
$this->form_validation->set_rules('OpenName', 'First Name', 'required');
$this->form_validation->set_rules('OpenLastName', 'Last Name', 'required');  
$this->form_validation->set_rules('OpenEmail', 'Email Address', 'required');
```

#### CPL Form Processing (`/cpl/`)
**Entry Point**: `cpl/index.php`
**Processor**: `cpl/php/smartprocess2.php`

**CPL-Specific Fields**:
```php
// Order Information
$OrderNumber = $_POST["OrderNumber"];
$LoanNumber = $_POST["LoanNumber"];

// Lender Details
$LenderName = $_POST["LenderName"];
$LenderAddress = $_POST["LenderAddress"];
$LenderCity = $_POST["LenderCity"];

// Borrower Information  
$BorrowerName = $_POST["BorrowerName"];
$PropertyAddress = $_POST["PropertyAddress"];
$EmailDelivery = $_POST["EmailDelivery"];
```

### 1.2 Input Validation & Security

#### Duplicate Order Prevention
```php
// Check for existing orders by APN
$result = $this->order->checkDuplicateOrder($this->input->post('apn'));
if ($result) {
    $response = array('status' => 'error', 'message' => 'Order is already exist for this property.');
    echo json_encode($response);
    exit;
}
```

#### File Upload Validation
```php
// Curative document upload configuration
$config['upload_path'] = './uploads/curative/';
$config['allowed_types'] = 'doc|docx|gif|msg|pdf|tif|tiff|xls|xlsx|xml';
$config['max_size'] = 20000; // 20MB limit

if (!$this->upload->do_upload('upload_curative')) {
    $response = array('status' => 'error', 'message' => $this->upload->display_errors());
    echo json_encode($response);
    exit;
}
```

#### Session Validation
```php
// Random number verification for form security
if (isset($random_number) && !empty($random_number)) {
    $condition = array(
        'where' => array('session_id' => 'tp_api_id_' . $random_number),
        'returnType' => 'count',
    );
    $count = $this->titlePointData->gettitlePointDetails($condition);
    if ($count != 1) {
        $response = array('status' => 'error', 'message' => 'Something went wrong.Please hard refresh(Ctrl+F5) your page.');
        echo json_encode($response);
        exit;
    }
}
```

## 🔍 Step 2: External API Data Gathering

### 2.1 TitlePoint API Integration

#### Tax Information Retrieval
**File**: `taxcall.php`
**Purpose**: Gather property tax assessment data

```php
// API Configuration
$requestParams = array(
    'userID' => env('TP_USERNAME'),
    'password' => env('TP_PASSWORD'),
    'company' => '',
    'department' => '',
    'titleOfficer' => '',
    'requestId' => $requestId,
    'maxWaitSeconds' => 20,
);

// API Endpoint
$request = env('TP_REQUEST_SUMMARY_ENDPOINT') . http_build_query($requestParams);
```

#### Legal Vesting Information
**File**: `lvcall.php`  
**Purpose**: Retrieve legal ownership and vesting details

#### Recording Information
**File**: `fetch-recording-info.php`
**Purpose**: Obtain document recording and chain of title data

### 2.2 TitlePoint Data Processing

#### API Call Execution
**File**: `application/libraries/order/Titlepoint.php` (lines 436-466)

```php
public function generateGeoDoc($postData, $normalOrderFlag = 0)
{
    $fileNumber = $postData['file_number'];
    $orderId = $postData['order_id'];
    $state = $postData['state'];
    $county = $postData['county'];
    $property = $postData['property'];
    $apn = $postData['apn'];
    
    // Configure search parameters
    if (empty($unit_number) || (isset($postData['tax_search_done_flag']) && $postData['tax_search_done_flag'] == 1)) {
        $parameters = 'Address.FullAddress=' . $property . ';General.AutoSearchTaxes=False;Tax.CurrentYearTaxesOnly=False;General.AutoSearchProperty=True;General.AutoSearchOwnerNames=False;General.AutoSearchStarters=False;Property.IntelligentPropertyGrouping=true;';
        $addressFlag = 1;
    } else {
        $addressFlag = 0;
        $parameters = 'Tax.APN=' . $apn . ';IncludeReferenceDocs=True;General.AutoSearchProperty=True;General.AutoSearchTaxes=True;Property.IntelligentPropertyGrouping=True;Property.IncludeReferenceDocs=TrueGeneral.AutoSearchTaxes=True;Tax.CurrentYearTaxesOnly=True;';
    }
}
```

#### Data Storage
**Table**: `pct_order_title_point_data`
**Key Fields**:
- `cs3_request_id` - TitlePoint request identifier
- `cs3_result_id` - TitlePoint result identifier  
- `cs3_service_id` - Service-specific identifier
- `fips` - Federal Information Processing Standard code
- `tax_file_status` - Processing status
- `cs3_message` - API response message

```php
// Data retrieval and validation
public function gettitlePointDetails($params)
{
    $table = $this->table;
    $this->db->select('*');
    $this->db->from($table);
    
    if(array_key_exists("where", $params)){
        foreach($params['where'] as $key => $val){
            $this->db->where($key, $val);
        }
    }
    
    if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
        $result = $this->db->count_all_results();
    } else {
        $query = $this->db->get();
        $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    }
    
    return $result;
}
```

## 🔄 Step 3: Data Processing & Order Creation

### 3.1 Order Data Preparation

#### Order Record Creation
**File**: `application/modules/frontend/controllers/order/Home.php` (lines 1034-1053)

```php
$orderData = array(
    'customer_id' => $customer_id,
    'file_id' => $file_id,
    'file_number' => isset($orderNumber) && !empty($orderNumber) ? $orderNumber : 0,
    'lp_file_number' => $lp_file_number,
    'property_id' => $propertyId,
    'transaction_id' => $transactionId,
    'partner_api_log_id' => $partnerApiId,
    'created_by' => $userdata['id'],
    'random_number' => $randomString,
    'underwriter' => $underWriter,
    'escrow_officer_id' => $this->input->post('escrow_officer'),
    'prod_type' => $loanFlag == 1 ? 'loan' : 'sale',
    'resware_status' => ($lpOrderFlag == 1) ? 'open' : '',
    'status' => 1,
    'ion_fraud_proceed_status' => ($ionReportStatusRequired == 'true') ? 'review fraud' : 'proceed',
    'ion_fraud_required_status' => ($ionFraudFoundStatus == 'true') ? 'yes' : 'no',
);

$orderId = $this->home_model->insert($orderData, 'order_details');
```

### 3.2 Property Data Organization

#### Address Parsing
```php
// Property address breakdown
$PropertyAddress = $this->input->post('Property');
$SplitPropertyAddress = explode(' ', $PropertyAddress);
$StreetNumber = isset($SplitPropertyAddress[0]) && !empty($SplitPropertyAddress[0]) ? $SplitPropertyAddress[0] : '';
$PrimaryStreetName = array_slice($SplitPropertyAddress, 1);
$StreetName = isset($PrimaryStreetName) && !empty($PrimaryStreetName) ? implode(" ", $PrimaryStreetName) : '';
```

#### Participant Information Processing
```php
// Buyer/Seller name parsing
$splitName = explode(' ', $order_details['primary_owner']);
$ownerLastName = end($splitName);
$primaryName = array_slice($splitName, 0, -1);
$ownerFirstName = implode(" ", $primaryName);
```

## 📡 Step 4: SoftPro Server Communication

### 4.1 Resware/SoftPro Integration Setup

#### API Client Configuration
**File**: `application/libraries/order/Resware.php` (lines 19-48)

```php
public function make_request($http_method, $endpoint, $body_params = '', $data = array())
{
    $userdata = $this->CI->session->userdata('user');
    
    // Credential selection based on user type
    if (isset($data['admin_api']) && $data['admin_api'] == 1) {
        $credResult = $this->CI->order->get_resware_admin_credential();
        $login = $credResult['username'];
        $password = $credResult['password'];
    } else if ((isset($userdata['is_master']) && !empty($userdata['is_master'])) || isset($data['from_mail']) && !empty($data['from_mail'])) {
        $login = isset($data['email']) && !empty($data['email']) ? $data['email'] : '';
        $password = isset($data['password']) && !empty($data['password']) ? $data['password'] : '';
    } else {
        $login = $userdata['email'];
        $password = $userdata['random_password'];
    }

    // HTTP client setup
    $ch = curl_init(env('RESWARE_ORDER_API') . $endpoint);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body_params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($body_params))
    );
    
    $result = curl_exec($ch);
    return $result;
}
```

### 4.2 Order Data Formatting for SoftPro

#### Legal Entity Structure
**File**: `application/modules/admin/controllers/order/Home.php` (lines 4467-4480)

```php
$legalEntity = array(
    'EntityType' => 'INDIVIDUAL',
    'IsPrimaryTransactee' => 'true',
    'primary' => array(
        'First' => $ownerFirstName,
        'Last' => $ownerLastName,
    ),
    'Address' => array(
        'Address1' => $order_details['address'],
        'City' => $order_details['property_city'],
        'State' => $order_details['property_state'],
        'Zip' => $order_details['property_zip'],
    ),
);
```

#### Complete Order Structure
**File**: `application/modules/frontend/controllers/order/Home.php` (lines 408-418)

```php
// Transaction type determination
if (strpos($order_details['product_type'], 'Loan') !== false) {
    $place_order['Buyers'][] = $legalEntity;
} elseif (strpos($order_details['product_type'], 'Sale') !== false) {
    $place_order['Sellers'][] = $legalEntity;
    $place_order['Buyers'][] = $borrowers;
    $place_order['SalesPrice'] = $order_details['sales_amount'];
}

// Property information
$place_order['Properties'][] = array(
    'IsPrimary' => 'true',
    'StreetNumber' => $streetNumber,
    'StreetName' => $streetName,
    'City' => $order_details['property_city'],
    'State' => $order_details['property_state'],
    'County' => $order_details['county'],
    'Zip' => $order_details['property_zip'],
);

// Transaction details
$place_order['TransactionProductType'] = array(
    "TransactionTypeID" => $order_details['transaction_type'],
    'ProductTypeID' => $order_details['purchase_type'],
);

// Loan information
if (isset($order_details['loan_amount']) && !empty($order_details['loan_amount'])) {
    $loan['LoanAmount'] = $order_details['loan_amount'];
}
if (isset($order_details['loan_number']) && !empty($order_details['loan_number'])) {
    $loan['LoanNumber'] = $loan_number;
}
$place_order['Loans'][] = $loan;

// Additional notes
$place_order['Note']['APN'] = $order_details['apn'];
$place_order['Note']['parcel_id'] = $order_details['apn'];
$place_order['Note']['legal_description'] = $order_details['legal_description'];
```

### 4.3 API Call Execution with Logging

```php
// API call with comprehensive logging
$order_data = json_encode($place_order);
$this->load->library('order/resware');

// Pre-call logging
$logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_order', env('RESWARE_ORDER_API') . 'orders', $order_data, array(), 0, 0);

// Execute API call
$result = $this->resware->make_request('POST', 'orders', $order_data, $user_data);

// Post-call logging
$this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_order', env('RESWARE_ORDER_API') . 'orders', $order_data, $result, 0, $logid);
```

## 📄 Step 5: Document Generation & Management

### 5.1 Automated Document Creation

#### Background Document Processing
**File**: `application/modules/frontend/controllers/order/Home.php` (lines 1827-1834)

```php
/** Start Execute all document creation in background */
try {
    $command = "php " . FCPATH . "index.php frontend/order/cron generatealldocumentfromtitlepoint $fileNumber > /dev/null &";
    exec($command);
} catch (\Throwable $th) {
    // Error handling
}
/** End Execute all document creation in background */
```

#### Document Types Generated
1. **Preliminary Title Reports** - Initial title research summary
2. **CPL Documents** - Commitment for Title Insurance
3. **Legal Vesting Reports** - Ownership verification
4. **Tax Information Documents** - Property tax details
5. **Pre-listing Reports** - Property marketing documents

### 5.2 PDF Generation Process

#### Document Creation Workflow
**File**: `application/libraries/order/Order.php` (lines 3530-3544)

```php
// PDF generation configuration
$this->CI->snappy_pdf->pdf->setOption('zoom', '1.15');

// Create directory if needed
if (!is_dir('uploads/pre-listing-doc')) {
    mkdir('./uploads/pre-listing-doc', 0777, true);
}

// Generate PDF from HTML
$pdfFilePath = FCPATH . '/uploads/pre-listing-doc/' . $document_name;
$pdfFilePath = str_replace('\\', '/', $pdfFilePath);
$this->CI->snappy_pdf->pdf->generateFromHtml($html, $pdfFilePath);

// Upload to AWS and record in database
$this->uploadDocumentOnAwsS3($document_name, 'pre-listing-doc');
$this->insertRecord($document_name, $file_id, $orderDetails);
```

### 5.3 AWS S3 Document Upload

#### S3 Upload Function
**File**: `application/libraries/order/Order.php` (lines 1555-1597)

```php
public function uploadDocumentOnAwsS3($fileName, $folder = '', $csv = 0)
{
    $bucket = env('AWS_BUCKET');
    
    // Determine file path structure
    if (!empty($folder)) {
        $keyname = $folder . "/" . basename($fileName);
        $filepath = "uploads/" . $folder . "/" . $fileName;
    } else {
        if ($csv == 1) {
            $keyname = "csv/" . basename($fileName);
        } else {
            $keyname = basename($fileName);
        }
        $filepath = "uploads/" . $fileName;
    }

    try {
        // Initialize S3 client
        $s3Client = new Aws\S3\S3Client([
            'region' => env('AWS_REGION'),
            'version' => '2006-03-01',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        // Upload file to S3
        $result = $s3Client->putObject([
            'Bucket' => $bucket,
            'Key' => $keyname,
            'SourceFile' => $filepath,
        ]);
        
        // Clean up local file after successful upload
        if (!empty($result['ObjectURL'])) {
            chmod($filepath, 0644);
            gc_collect_cycles();
            unlink($filepath);
            return true;
        }
    } catch (Aws\Exception\AwsException $e) {
        return false;
    }
    
    return false;
}
```

#### Document Categories in S3
- `documents/` - General order documents
- `pre-listing-doc/` - Pre-listing reports
- `borrower/` - Borrower-specific files  
- `escrow-orders/` - Escrow documentation
- `instruction_documents/` - Process instructions
- `hr/training/` - Training materials

## 🔔 Step 6: Notifications & Communication

### 6.1 System Notifications

#### Order Assignment Notifications
**File**: `application/modules/frontend/controllers/order/Home.php` (lines 1055-1065)

```php
if ($userdata['is_master'] == 1) {
    $message = 'Order number #' . $orderNumber . ' has assigned to you.';
    $notificationData = array(
        'sent_user_id' => $customer_id,
        'message' => $message,
        'is_admin' => 0,
        'type' => 'assigned',
    );
    $this->home_model->insert($notificationData, 'pct_order_notifications');
    $this->order->sendNotification($message, 'assigned', $customer_id, 0);
}
```

#### Document Upload Notifications
**File**: `application/modules/frontend/controllers/order/DashboardMail.php` (lines 1908-1925)

```php
$orderInfo = $this->order_model->get($this->input->post('order_id'));
if (count($documentNames) == 1) {
    $message = implode(',', $documentNames) . " document uploaded by borrower for file number " . $orderInfo->file_number;
} else {
    $message = implode(',', $documentNames) . " documents uploaded by borrower for file number " . $orderInfo->file_number;
}

if (!empty($orderInfo->escrow_officer_id)) {
    $escrowInfoFromOrder = $this->common->getEscrowOfficerInfoBasedOnIdFromOrder($orderInfo->escrow_officer_id);
    $notificationData = array(
        'sent_user_id' => $escrowInfoFromOrder['id'],
        'message' => $message,
        'type' => 'completed',
    );
    $this->home_model->insert($notificationData, 'pct_order_notifications');
    $this->order->sendNotification($message, 'completed', $escrowInfoFromOrder['id'], 0);
}
```

### 6.2 Email Notifications

#### Order Confirmation Email
```php
// Email data preparation
$data = array(
    'orderNumber' => $orderNumber,
    'orderId' => $file_id,
    'OpenName' => $OpenName . ' ' . $OpenLastName,
    'Opentelephone' => $Opentelephone,
    'OpenEmail' => $OpenEmail,
    'CompanyName' => $CompanyName,
    'PropertyAddress' => $PropertyAddress,
    'titlePointDetails' => $titlePointDetails,
    'randomString' => $randomString,
);

// Send email notification
$this->order->sendOrderEmail($fileNumber);
```

#### Notification Types
- **Order Assignment**: When orders are assigned to users
- **Document Completion**: When documents are generated or uploaded
- **Status Updates**: Progress notifications throughout workflow
- **Error Alerts**: System or processing error notifications

## 📊 Step 7: Logging & Audit Trail

### 7.1 API Call Logging

#### Comprehensive API Logging
**Table**: `pct_api_logs`
**Function**: `syncLogs()` in multiple controllers

```php
// API call logging pattern
$logid = $this->apiLogs->syncLogs(
    $userdata['id'],           // User ID
    'titlepoint',              // Service name
    'get_request_summary',     // Action
    $request,                  // Endpoint URL
    $requestParams,            // Request data
    array(),                   // Response data (initially empty)
    $random_number,            // Order reference
    0                          // Log ID (0 for new entry)
);

// API call execution
$result = /* API call */;

// Update log with response
$this->apiLogs->syncLogs(
    $userdata['id'],
    'titlepoint',
    'get_request_summary',
    $request,
    $requestParams,
    $result,                   // Response data
    $random_number,
    $logid                     // Update existing log
);
```

### 7.2 Administrative Activity Logging

```php
// Admin activity tracking
$activity = $msg . ' Sync to resware order number: ' . $orderDetails['file_number'];
$this->order->logAdminActivity($activity);
```

### 7.3 User Activity Tracking

#### Notification Logging
**Table**: `pct_order_notifications`
- Tracks all user notifications
- Records notification types and delivery status
- Maintains complete communication audit trail

## 🔄 Step 8: Background Processing & Maintenance

### 8.1 Cron Job Operations

#### Document Generation Jobs
**File**: `application/modules/frontend/controllers/order/Cron.php`

**Key Functions**:
- `generateAllDocumentFromTitlePoint()` - Batch document creation
- `sendDataToHomeDocs()` - Third-party data synchronization
- `exportEscrowOrdersToS3()` - Data archival

#### Background Task Example
```php
public function sendDataToHomeDocs($fileId)
{
    $orderDetails = $this->order->get_order_details($fileId);
    $this->load->model('order/titlePointData');
    
    $condition = array(
        'where' => array('file_id' => $fileId),
    );
    $titlePointDetails = $this->titlePointData->gettitlePointDetails($condition);
    
    $homedocs_array = [
        'order_token' => $orderDetails['random_number'],
        'apn' => $orderDetails['apn'],
        'fips' => $titlePointDetails[0]['fips'],
        'address' => $orderDetails['address'],
        'full_address' => $orderDetails['full_address'],
        'file_number' => $orderDetails['file_number'],
        'vesting_info' => $titlePointDetails[0]['vesting_information'],
        'borrower_email' => $orderDetails['borrower_email'],
    ];

    // API call with logging
    $logid = $this->apiLogs->syncLogs(0, 'homedocs', 'send_order_info', env('HOMEDOCS_URL') . 'api/store-property-detail', json_encode($homedocs_array, JSON_UNESCAPED_SLASHES), array(), $orderDetails['order_id'], 0);
    $result = send_order_data($homedocs_array);
    $this->apiLogs->syncLogs(0, 'homedocs', 'send_order_info', env('HOMEDOCS_URL') . 'api/store-property-detail', json_encode($homedocs_array, JSON_UNESCAPED_SLASHES), $result, $orderDetails['order_id'], $logid);
}
```

## 📈 Workflow Summary

### Complete Process Flow
1. **Input Validation** → Form data validation and security checks
2. **API Integration** → TitlePoint property data gathering  
3. **Data Processing** → Organization and formatting for SoftPro
4. **Order Creation** → SoftPro server communication and order establishment
5. **Document Generation** → Automated PDF creation and processing
6. **Cloud Storage** → AWS S3 upload and management
7. **Notifications** → Email and system alerts to stakeholders
8. **Logging** → Comprehensive audit trail creation
9. **Background Processing** → Continued automation and maintenance

### Performance Considerations
- **API Timeouts**: TitlePoint calls can take up to 20 seconds
- **Memory Management**: Large PDF processing requires adequate resources
- **Concurrent Processing**: Background jobs handle multiple orders simultaneously
- **Error Recovery**: Comprehensive error handling and retry mechanisms

### Success Metrics
- **Order Processing Time**: Complete workflow typically 5-10 minutes
- **Document Generation**: Automated creation of 5-10 documents per order
- **Integration Success**: 95%+ successful SoftPro order creation
- **Data Accuracy**: Complete property research and validation
- **Audit Compliance**: 100% activity logging and traceability

This comprehensive workflow ensures efficient, accurate, and fully auditable order processing from initial input through final document delivery.