<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|    example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|    https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|    $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|    $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|    $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:    my-controller/index    -> my_controller/index
|        my-controller/my-method    -> my_controller/my_method
 */

/* Route for PCT static pages*/
$route['default_controller'] = 'frontend/order/home/index';
$route['our-role'] = 'frontend/aboutus/role';
$route['protecting-you'] = 'frontend/aboutus/protect';
$route['why-pacific-coast-title'] = 'frontend/aboutus/pacific';
$route['about-us'] = 'frontend/aboutus/about';
$route['join-our-team'] = 'frontend/aboutus/joinOurTeam';
$route['residential-title'] = 'frontend/residential/title';
$route['escrow-settlement'] = 'frontend/residential/escrowSettlement';
$route['what-is-title-insurance'] = 'frontend/residential/titleInsurance';
$route['benefits-title-insurance'] = 'frontend/residential/benefitsTitleInsurance';
$route['life-of-title-search'] = 'frontend/residential/lifeOfTitleSearch';
$route['top-10-title-problems'] = 'frontend/residential/topTitleProblems';
$route['what-is-escrow'] = 'frontend/residential/whatIsEscrow';
$route['life-of-escrow'] = 'frontend/residential/lifeOfEscrow';
$route['escrow-terms'] = 'frontend/residential/escrowTerms';
$route['commercial-services'] = 'frontend/commercial/commercialServices';
$route['commercial-resources'] = 'frontend/commercial/commercialResources';
$route['commercial-expertise'] = 'frontend/commercial/commercialExpertise';
$route['blank-forms'] = 'frontend/agentResources/blankForms';
$route['educational-booklets'] = 'frontend/agentResources/educationalBooklets';
$route['flyer-center'] = 'frontend/agentResources/flyerCenter';
$route['recording-fees'] = 'frontend/agentResources/recordingFees';
$route['rate-book'] = 'frontend/agentResources/rateBook';
$route['training-center'] = 'frontend/agentResources/trainingCenter';
$route['downey'] = 'frontend/contact/downey';
$route['orange'] = 'frontend/contact/orange';
$route['oxnard'] = 'frontend/contact/oxnard';
$route['sandiego'] = 'frontend/contact/sandiego';
$route['glendale'] = 'frontend/contact/glendale';

/* Route for PCT-Order Frontside*/
$route['order'] = 'frontend/order/home/index';
$route['dashboard'] = 'frontend/order/dashboard/index';
$route['sales-dashboard/:any'] = 'frontend/order/salesRep/index';
$route['title-officer-dashboard'] = 'frontend/order/titleOfficers/index';
$route['pay-off-dashboard'] = 'frontend/order/payOff/index';
$route['home/getSearchResults'] = 'frontend/order/common/getSearchResults';
$route['home/getDetailsByName'] = 'frontend/order/common/getDetailsByName';
$route['special-dashboard'] = 'frontend/order/dashboard/index';
$route['order/login'] = 'frontend/order/login/index';
$route['order/login_test'] = 'frontend/order/login/loginTest';
$route['do_login'] = 'frontend/order/login/do_login';
$route['do_login_test'] = 'frontend/order/login/do_login_test';
$route['change-password/:any'] = 'frontend/order/login/change_password';
$route['logout'] = 'frontend/order/common/logout';
$route['getSearchResults'] = 'frontend/order/common/getSearchResults';
$route['getIonReport'] = 'frontend/order/home/getIonReport';
$route['home/checkEmail'] = 'frontend/order/home/checkEmail';
$route['home/getCustomerNumber'] = 'frontend/order/home/getCustomerNumber';
$route['agent/getAgentDetails'] = 'frontend/order/agent/getAgentDetails';
$route['home/getCustomerDetails'] = 'frontend/order/home/getCustomerDetails';
$route['getDetailsByName'] = 'frontend/order/common/getDetailsByName';
$route['order-submit/:num'] = 'frontend/order/home/orderSubmit';
$route['pre-listing-doc'] = 'frontend/order/home/preListingDocs';
$route['createService'] = 'frontend/order/TitlePoint/createService';
$route['getRequestSummaries'] = 'frontend/order/TitlePoint/getRequestSummaries';
$route['getResultById'] = 'frontend/order/TitlePoint/getResultById';
$route['imageCreateRequest'] = 'frontend/order/TitlePoint/imageCreateRequest';
$route['getRequestStatus'] = 'frontend/order/TitlePoint/getRequestStatus';
$route['generateImage'] = 'frontend/order/TitlePoint/generateImage';
$route['notifyAdmin'] = 'frontend/order/home/notifyAdmin';
$route['prelim-files'] = 'frontend/order/common/prelimFiles';
$route['getFiles'] = 'frontend/order/dashboard/getFiles';
$route['recordings'] = 'frontend/order/dashboard/recordings';
$route['order/get-recordings'] = 'frontend/order/dashboard/get_recordings';
$route['review-prelim'] = 'frontend/order/resware/reviewPrelim';
$route['instrumentService'] = 'frontend/order/TitlePoint/instrumentService';
$route['upload-doc-orders'] = 'frontend/order/common/uploadDocOrders';
$route['get-orders-upload-doc'] = 'frontend/order/common/getOrdersUploadDoc';
$route['policy-orders'] = 'frontend/order/common/policyOrders';
$route['get-orders-policy'] = 'frontend/order/common/getOrdersPolicy';
$route['policy-order/:any'] = 'frontend/order/common/policy';
$route['upload-documents/:num'] = 'frontend/order/common/upload_documents';
$route['get-order-documents'] = 'frontend/order/common/getOrderDocuments';
$route['files-upload'] = 'frontend/order/common/files_upload';
$route['fees'] = 'frontend/order/dashboard/fees';
$route['get-transaction-orders'] = 'frontend/order/dashboard/get_transaction_orders';
$route['get-fees/:num'] = 'frontend/order/dashboard/get_fees';
$route['get-fee-estimate-pdf'] = 'frontend/order/dashboard/get_fee_estimate_pdf';
$route['import-orders'] = 'frontend/order/cron/import_orders';
$route['uplod-file-document'] = 'frontend/order/titleOfficers/uploadFileDocument';
$route['get-file-document'] = 'frontend/order/titleOfficers/getFileDocument';
$route['download-aws-file'] = 'frontend/order/titleOfficers/downloadAwsDocument';
$route['notes'] = 'frontend/order/titleOfficers/notes';
$route['get-notes-orders'] = 'frontend/order/titleOfficers/get_notes_orders';
$route['get-notes/(:num)'] = 'frontend/order/common/get_notes/$1';
$route['create-note'] = 'frontend/order/common/create_note';
$route['proposed-insured'] = 'frontend/order/common/proposed_insured';
$route['get-proposed-orders'] = 'frontend/order/common/get_proposed_orders';
$route['generate-proposed-insured'] = 'frontend/order/common/generate_proposed_insured';
$route['cpl-dashboard'] = 'frontend/order/common/cpl';
$route['get-orders-cpl'] = 'frontend/order/common/get_orders_cpl';
$route['create-cpl/:num'] = 'frontend/order/common/create_cpl';
$route['add-lender-order'] = 'frontend/order/common/addLenderOnOrder';
$route['add-order-details'] = 'frontend/order/common/add_order_details';
$route['get-orders-prelim'] = 'frontend/order/common/get_orders_prelim';
$route['review-file/:num'] = 'frontend/order/common/review_file';
$route['import-orders-all-users'] = 'frontend/order/cron/import_orders_all_users';
$route['summary'] = 'frontend/order/common/summary';
$route['prelim'] = 'frontend/order/dashboard/prelim';
$route['load-doc'] = 'frontend/order/common/load_doc';
$route['legal-vesting'] = 'frontend/order/common/legal_vesting';
$route['plat-map'] = 'frontend/order/common/plat_map';
$route['download-document'] = 'frontend/order/common/download_document';
$route['resware-fetch-data'] = 'frontend/order/ReviewPrelim/fetchData';
$route['test-mail'] = 'frontend/order/ReviewPrelim/testMail';
$route['generate-plat-map'] = 'frontend/order/common/generate_plat_map';
$route['get-order-details'] = 'frontend/order/dashboard/get_order_details';
$route['update-order-details'] = 'frontend/order/dashboard/update_order_details';
$route['get-order-details-cpl'] = 'frontend/order/common/getOrderDetailsCpl';
$route['create-cpl-for-doma/:num'] = 'frontend/order/common/createCPlForNatic';
$route['create-cpl-for-natic/:num'] = 'frontend/order/common/createCPlForNatic';
$route['import-product-types'] = 'frontend/order/cron/import_product_types';
$route['check-update-password'] = 'frontend/order/cron/check_update_password';
$route['generate-grant-deed'] = 'frontend/order/TitlePoint/generateGrantDeed';
$route['get-product-types'] = 'frontend/order/home/getProductTypes';
$route['update-user-details'] = 'frontend/order/cron/update_user_details';
$route['create-cpl-for-fnf/:num'] = 'frontend/order/common/createCPlForFnf';
$route['update-password'] = 'frontend/order/cron/updatePassword';
$route['company-information'] = 'frontend/order/cron/getCompanyInformation';
$route['generate-tax-doc'] = 'frontend/order/TitlePoint/generateTaxDoc';
$route['home/checkDuplicateOrder'] = 'frontend/order/home/checkDuplicateOrder';
$route['special-lender-dashboard'] = 'frontend/order/SpecialDashboard/index';
$route['get-special-lenders-orders'] = 'frontend/order/SpecialDashboard/get_special_lenders_orders';
$route['generate-cpl/:any'] = 'frontend/order/dashboardMail/generateCplFromMail';
$route['generate-fees/:any'] = 'frontend/order/dashboardMail/generateFeesFromMail';
$route['proposed-insured/:any'] = 'frontend/order/dashboardMail/proposedInsured';
$route['update-remote-file-numbers'] = 'frontend/order/cron/updateRemoteFileNumberForAllOrders';
$route['get-sales-orders'] = 'frontend/order/salesRep/get_sales_orders';
$route['get-title-officer-orders'] = 'frontend/order/titleOfficers/get_title_officer_orders';
$route['import-sales-rep-orders'] = 'frontend/order/cron/import_sales_rep_orders';
$route['get-partners'] = 'frontend/order/common/get_partners';
$route['import-all-sales-rep-orders'] = 'frontend/order/cron/import_all_sales_rep_orders';
$route['export-users/:any'] = 'frontend/order/cron/exportUsers';
$route['borrower-information/:any'] = 'frontend/order/dashboardMail/borrowerInformation';
$route['borrower-information/:any/:any'] = 'frontend/order/dashboardMail/borrowerInformation';
$route['generate-verification-code'] = 'frontend/order/dashboardMail/generate_verification_code';
$route['code-verification'] = 'frontend/order/dashboardMail/code_verification';
$route['borrower-details'] = 'frontend/order/dashboardMail/borrowerDetails';
$route['borrower-info-submit'] = 'frontend/order/dashboardMail/borrowerInfoSubmit';
$route['generic-landing-page'] = 'frontend/order/dashboardMail/genericLandingPage';
$route['get-order-information/:any'] = 'frontend/order/cron/getOrderInformation';
$route['update-order-status'] = 'frontend/order/cron/updateOrderStatus';
$route['create-order-safewire'] = 'frontend/order/dashboardMail/createOrderSafewire';
$route['get-safewire-order-status'] = 'frontend/order/dashboardMail/getSafewireOrderStatus';
$route['update-safewire-orders-status'] = 'frontend/order/cron/updateSafewireStatusForAllorders';
$route['update-prelim-action/:num'] = 'frontend/order/common/updatePrelimAction';
$route['send-mail-escrow-users'] = 'frontend/order/cron/sendMailEscrowUsers';
$route['transfer-all-files-on-aws'] = 'frontend/order/cron/transferAllFilesOnAws';
$route['download-aws-document'] = 'frontend/order/common/downloadAwsDocument';
$route['import-data-from-csv-file'] = 'frontend/order/cron/importDataFromCsvFile';
$route['send-borrower-verification-mail'] = 'frontend/order/cron/sendMailEscrowUsersForBorrowerVerification';
$route['import-orders-using-file-number'] = 'frontend/order/cron/importOrdersUsingFileNumber';
$route['import-escrow-fee'] = 'frontend/order/cron/importEscrowFee';
$route['get-orders-dashboard'] = 'frontend/order/dashboard/getOrdersDashboard';
$route['sales-production-history/:any'] = 'frontend/order/salesRep/salesProductionHistory';
$route['password-update-all'] = 'frontend/order/cron/passwordUpdateAll';
$route['update-all-order-status'] = 'frontend/order/cron/updateAllOrderStatus';
$route['policy/:any'] = 'frontend/order/dashboardMail/policy';
$route['download-policy-doc'] = 'frontend/order/dashboardMail/downloadPolicyDoc';
$route['remove-doc-from-server'] = 'frontend/order/cron/removeDocServer';
$route['send-message-recording-confirmation'] = 'frontend/order/cron/sendMessageRecordingConfirmation';
$route['sync-prelim-data'] = 'frontend/order/cron/syncPrelimData';
$route['send-summary-mail-sales'] = 'frontend/order/cron/sendSummaryMailSalesRepUsers';
$route['upload-document'] = 'frontend/order/common/upload_document';
$route['add-partner-for-orders'] = 'frontend/order/cron/addPartnerForOrders';
$route['import-data-for-pay-off'] = 'frontend/order/cron/importDataForPayOff';
$route['delete-old-logs'] = 'frontend/order/cron/deleteOldLogs';
$route['get-pay-off-orders'] = 'frontend/order/payOff/get_pay_off_orders';
$route['get-transactees'] = 'frontend/order/payOff/get_transactees';
$route['add-transactee'] = 'frontend/order/payOff/addTransactee';
$route['upload-transactee-documents'] = 'frontend/order/payOff/uploadTransacteeDocuments';
$route['get-transactee-document-list'] = 'frontend/order/payOff/getTransacteeDocumentList';

$route['download-pay-off-document'] = 'frontend/order/payOff/downloadPayOffDocument';
$route['update-pay-off-action'] = 'frontend/order/payOff/updatePayOffAction';
$route['create-payoff/:num'] = 'frontend/order/payOff/createPayoff';
$route['generate-payoff'] = 'frontend/order/payOff/generatePayoff';
$route['trends/:any'] = 'frontend/order/salesRep/trends';
$route['sales-summary/:any'] = 'frontend/order/salesRep/summary';
$route['sales-reports/:any'] = 'frontend/order/salesRep/salesReports';
$route['mark-as-read'] = 'frontend/order/common/markAsRead';
$route['escrow-dashboard'] = 'frontend/order/escrow/index';
$route['order/escrow/order-tasks/(:num)'] = 'frontend/order/escrow/orderTasks/$1';
$route['get-escrow-orders'] = 'frontend/order/escrow/get_escrow_orders';
$route['upload-documet-resware/(:any)'] = 'frontend/order/escrow/uploadBorrowerDocumentResware/$1';
$route['borrower-document/(:any)/(:any)'] = 'frontend/order/dashboardMail/uploadBorrowerDocument/$1/$2';
$route['borrower-document-upload'] = 'frontend/order/dashboardMail/borrower_document_upload';
$route['escrow-create-note'] = 'frontend/order/escrow/create_note';
$route['add-borrower-on-order'] = 'frontend/order/escrow/addBorrowerOnOrder';
$route['add-borrower-on-order-for-payoff'] = 'frontend/order/escrow/addBorrowerOnOrderForPayoff';
$route['add-lender-on-order'] = 'frontend/order/escrow/addLenderOnOrder';
$route['borrower-seller-form/(:any)'] = 'frontend/order/dashboardMail/borrowerSellerForm/$1';
$route['borrower-buyer-form/(:any)'] = 'frontend/order/dashboardMail/borrowerBuyerForm/$1';
$route['sales-commission/(:num)'] = 'frontend/order/salesRep/commission/$1';
$route['task-documents'] = 'frontend/order/escrow/taskDocuments';
$route['add-buyer-on-order'] = 'frontend/order/escrow/addBuyerOnOrder';
$route['add-seller-on-order'] = 'frontend/order/escrow/addSellerOnOrder';
$route['buyer-info/(:any)'] = 'frontend/order/dashboardMail/buyerInfo/$1';
$route['seller-info/(:any)'] = 'frontend/order/dashboardMail/sellerInfo/$1';
$route['get-netsheet/(:any)'] = 'frontend/order/dashboardMail/get_netsheet/$1';
$route['create-netsheet/(:any)'] = 'frontend/order/dashboardMail/create_netsheet/$1';
$route['get-data-from-adobe'] = 'frontend/order/adobe/getDataFromAdobe';
$route['sales-current-month-history'] = 'frontend/order/salesRep/salesCurrentMonthSummary';
$route['add-escrow-ins-order'] = 'frontend/order/escrow/addEscrowInsOrder';
$route['send-request-docs'] = 'frontend/order/escrow/sendRequestDocs';
$route['generate-all-document-from-title-point'] = 'frontend/order/home/generateAllDocumentFromTitlePoint';
$route['get-revenue-data'] = 'frontend/order/salesRep/getRevenueData';
$route['send-package/:any'] = 'frontend/order/dashboardMail/sendPackage';
$route['add-buyer-on-order-mail'] = 'frontend/order/dashboardMail/addBuyerOnOrder';
$route['add-seller-on-order-mail'] = 'frontend/order/dashboardMail/addSellerOnOrder';
$route['survey-result'] = 'frontend/order/common/surveysResult';
$route['get-survey-details'] = 'frontend/order/common/getSurveyDetails';

$route['national-form'] = 'frontend/order/dashboardMail/nationalForm';
/* Route for PCT-Order backend*/
$route['order/admin'] = 'admin/order/login/login';
$route['order/admin/login/do_login'] = 'admin/order/login/do_login';
$route['order/admin/dashboard'] = 'admin/order/home/index';
$route['order/admin/escrow'] = 'admin/order/home/dashboard';
$route['order/admin/import'] = 'admin/order/home/import';
$route['order/admin/import-lenders'] = 'admin/order/home/import_lenders';
$route['order/admin/lenders'] = 'admin/order/home/lenders';
$route['order/admin/agents'] = 'admin/order/agent/index';
$route['order/admin/edit-agent/:num'] = 'admin/order/agent/edit';
$route['order/admin/import-agents'] = 'admin/order/agent/import_agents';
$route['order/admin/logout'] = 'admin/order/home/logout';
$route['order/admin/sales-rep'] = 'admin/order/sales/index';
$route['order/admin/get-sales-rep-list'] = 'admin/order/sales/get_sales_rep_list';
$route['order/admin/add-sales-rep'] = 'admin/order/sales/add_sales_rep';
$route['order/admin/export-sales-rep-client'] = 'admin/order/sales/export_sales_rep_client';
$route['order/admin/edit-sales-rep/:num'] = 'admin/order/sales/edit_sales_rep';
$route['order/admin/title-officers'] = 'admin/order/title/index';
$route['order/admin/get-title-officer-list'] = 'admin/order/title/get_title_officer_list';
$route['order/admin/add-title-officer'] = 'admin/order/title/add_title_officer';
$route['order/admin/edit-title-officer/:num'] = 'admin/order/title/edit_title_officer';
$route['order/admin/credentials-check'] = 'admin/order/customer/index';
$route['order/admin/lv-log'] = 'admin/order/TitlePoint/index';
$route['order/admin/pre-listing'] = 'admin/order/TitlePoint/preListing';
$route['order/admin/ion-fraud'] = 'admin/order/home/ionFraud';
$route['order/admin/primary-check'] = 'admin/order/home/primaryCheck';
$route['order/admin/get-user-check-list'] = 'admin/order/home/get_user_check_list';
$route['order/admin/orders(/:any)?'] = 'admin/order/order/orders';
$route['order/admin/get-order-list'] = 'admin/order/order/get_order_list';
$route['order/admin/cpl-documents'] = 'admin/order/home/cpl_document';
$route['order/admin/ion-fraud-documents'] = 'admin/order/home/ion_fraud_document';
$route['order/admin/order-details/:num'] = 'admin/order/order/order_details';
$route['order/admin/export-orders'] = 'admin/order/order/export_orders';
$route['order/admin/export-sales-reps'] = 'admin/order/sales/export_sales_reps';

$route['order/admin/export_lp_orders'] = 'admin/order/order/exportLpOrders';
$route['order/admin/new-users'] = 'admin/order/home/newUsers';
$route['order/admin/add-new-user'] = 'admin/order/home/addNewUser';
$route['order/admin/grant-deed-documents'] = 'admin/order/home/grant_deed_document';
$route['order/admin/lv-documents'] = 'admin/order/home/lv_document';
$route['order/admin/master-users'] = 'admin/order/home/masterUsers';
$route['order/admin/add-new-master-user'] = 'admin/order/home/addNewMasterUser';
$route['order/admin/tax-log'] = 'admin/order/TitlePoint/taxLog';
$route['order/admin/tax-data'] = 'admin/order/TitlePoint/taxData';
$route['order/admin/tax-documents'] = 'admin/order/home/tax_document';
$route['order/admin/grant-deed-log'] = 'admin/order/TitlePoint/grantDeedLog';
$route['order/admin/curative-documents'] = 'admin/order/home/curative_document';
$route['order/admin/file-documents'] = 'admin/order/home/file_document';
$route['get-form-details'] = 'admin/order/home/getFormDetails';
$route['delete-form'] = 'admin/order/home/deleteForm';
$route['order/admin/companies'] = 'admin/order/home/companies';
$route['order/admin/add-company'] = 'admin/order/home/addCompany';
$route['order/admin/delete-company'] = 'admin/order/home/deleteCompany';
$route['order/admin/incorrect-users'] = 'admin/order/home/incorrect_users';
$route['order/admin/partner-api-log'] = 'admin/order/order/partnerApiLogs';
$route['order/admin/update-order-details'] = 'admin/order/order/update_order_details';
$route['order/admin/fees'] = 'admin/order/fees/index';
$route['order/admin/add-fee'] = 'admin/order/fees/add_fee';
$route['order/admin/edit-fee/:num'] = 'admin/order/fees/edit_fee';
$route['order/admin/import-underwriters'] = 'admin/order/home/import_underwriters';
$route['order/admin/update-underwriter'] = 'admin/order/home/updateUnderwriter';
$route['order/admin/update-title-sales-company'] = 'admin/order/home/updateTitleSalesCompany';
$route['order/admin/update-sales-rep-order'] = 'admin/order/home/updateSalesUserForOrder';
$route['order/admin/fees-types'] = 'admin/order/FeesTypes/index';
$route['order/admin/add-fee-type'] = 'admin/order/FeesTypes/add_fee_type';
$route['order/admin/edit-fee-type/:num'] = 'admin/order/FeesTypes/edit_fee_type';
$route['order/admin/code-book'] = 'admin/order/CodeBook/index';
$route['order/admin/add-code-book'] = 'admin/order/CodeBook/add_code_book';
$route['order/admin/import-code-book'] = 'admin/order/CodeBook/import_code_book';
$route['order/admin/update-type'] = 'admin/order/CodeBook/updateType';
$route['order/admin/cpl-proposed-users'] = 'admin/order/home/cplProposedUsers';
$route['order/admin/edit-cpl-proposed-user/:num'] = 'admin/order/home/editCplProposedUser';
$route['order/admin/reject-cpl-proposed-user/:num'] = 'admin/order/home/rejectCplProposedUser';
$route['order/admin/edit-code-book/:num'] = 'admin/order/CodeBook/editCodeBook';
$route['order/admin/send-password'] = 'admin/order/home/sendPassword';
$route['order/admin/resware-admin-credential'] = 'admin/order/home/reswareAdminCredential';
$route['order/admin/edit-master-user/:num'] = 'admin/order/home/editMasterUser';
$route['order/admin/import-orders'] = 'admin/order/home/importOrders';
$route['order/admin/cpl-error-logs'] = 'admin/order/order/cplErrorLogs';
$route['order/admin/get-cpl-error-logs'] = 'admin/order/order/getCplErrorLogs';
$route['order/admin/resware-logs'] = 'admin/order/order/reswareLogs';
$route['order/admin/get-resware-logs'] = 'admin/order/order/getReswareLogs';

$route['order/admin/export_sales_rep_reports'] = 'admin/order/home/exportSalesRepReports';
$route['order/admin/update-transaction'] = 'admin/order/home/updateTransaction';
$route['order/admin/rules-manager'] = 'admin/order/rulesManager/index';
$route['order/admin/notifications'] = 'admin/order/home/notifications';
$route['order/admin/safewire-orders'] = 'admin/order/order/safewireOrders';
$route['order/admin/get-safewire-orders-list'] = 'admin/order/order/get_safewire_orders_list';
$route['store-deliverables'] = 'admin/order/home/storeDeliverables';
$route['download-aws-document-admin'] = 'admin/order/home/downloadAwsDocument';
$route['update-avoid-duplication-flag'] = 'admin/order/home/updateAvoidDuplicationFlag';
$route['order/admin/holidays'] = 'admin/order/holidays/index';
$route['order/admin/add-holiday'] = 'admin/order/holidays/add_holiday';
$route['order/admin/edit-holiday/:num'] = 'admin/order/holidays/edit_holiday';
// $route['order/admin/doma-branches'] = 'admin/order/cpl/domaBranches';
$route['order/admin/north-american-branches'] = 'admin/order/cpl/domaBranches';
$route['order/admin/westcor-branches'] = 'admin/order/cpl/westcorBranches';
$route['order/admin/commonwealth-branches'] = 'admin/order/cpl/commonwealthBranches';
$route['get-north-american-branches'] = 'admin/order/cpl/getNorthAmericanBranches';
$route['get-doma-branches'] = 'admin/order/cpl/getDomaBranches';
$route['get-westcor-branches'] = 'admin/order/cpl/getWestcorBranches';
$route['get-commonwealth-branches'] = 'admin/order/cpl/getCommonwealthBranches';
$route['update-mortgage-user'] = 'admin/order/home/updateMortgageUser';
$route['order/admin/mortgage-brokers'] = 'admin/order/home/mortgageBrokers';
$route['is-mortgage-primary-user'] = 'admin/order/home/isMortgagePrimaryUser';
$route['order/admin/client-users-list'] = 'admin/order/home/clientList';
$route['update-client-type'] = 'admin/order/home/updateClientType';
$route['is-password-required'] = 'admin/order/home/isPasswordRequired';
$route['order/admin/commission-range(/:any)?(/:num)?'] = 'admin/order/commissionRange/index$1$2';
$route['order/admin/add-commission-range'] = 'admin/order/commissionRange/add_commission_range';
$route['order/admin/import-commission-range'] = 'admin/order/commissionRange/import_commission_range';
$route['order/admin/export-commission-range'] = 'admin/order/commissionRange/export_commission_range';
$route['order/admin/template-commission-range'] = 'admin/order/commissionRange/template_commission_range';
$route['order/admin/edit-commission-range/(:num)'] = 'admin/order/commissionRange/edit_commission_range/$1';
$route['order/admin/delete-commission-range/(:num)'] = 'admin/order/commissionRange/delete_commission_range/$1';
$route['order/admin/commission-files(/:num)?(/:num)?(/:num)?'] = 'admin/order/commissionRange/commission_files$1$2$3';
$route['order/admin/delete-commission-file/(:num)'] = 'admin/order/commissionRange/delete_commission_file/$1';
$route['update-dual-cpl-user'] = 'admin/order/home/updateDualCplUser';
$route['update-all-only-resware-order'] = 'admin/order/home/updateAllOnlyReswareOrder';

$route['order/admin/commission-bonus'] = 'admin/order/commissionRange/index_bonus';
$route['order/admin/add-commission-bonus'] = 'admin/order/commissionRange/add_bonus';
$route['order/admin/edit-commission-bonus/(:num)'] = 'admin/order/commissionRange/edit_bonus/$1';
$route['order/admin/delete-commission-bonus/(:num)'] = 'admin/order/commissionRange/delete_bonus/$1';

$route['order/admin/underwriter-tier'] = 'admin/order/commissionRange/index_underwriter_tier';
$route['order/admin/add-underwriter-tier'] = 'admin/order/commissionRange/add_underwriter_tier';
$route['order/admin/edit-underwriter-tier/(:num)'] = 'admin/order/commissionRange/edit_underwriter_tier/$1';
$route['order/admin/delete-underwriter-tier/(:num)'] = 'admin/order/commissionRange/delete_underwriter_tier/$1';

$route['order/admin/commission-config'] = 'admin/order/commissionRange/commission_config';
$route['order/admin/edit-commission-config/(:num)'] = 'admin/order/commissionRange/edit_commission_config/$1';

$route['order/admin/sales-rep-commission/(:num)'] = 'admin/order/commissionRange/sales_rep_commission/$1';

$route['order/admin/get-realtors-list'] = 'admin/order/home/get_realtors_list';
$route['order/admin/escrow-officers'] = 'admin/order/home/escrow_officers';
$route['order/admin/add-escrow-officer'] = 'admin/order/home/add_escrow_officer';
$route['order/admin/edit-escrow-officer/:num'] = 'admin/order/home/edit_escrow_officer';

$route['order/admin/payoff-users'] = 'admin/order/home/payoff_users';
$route['order/admin/add-payoff-user'] = 'admin/order/home/add_payoff_user';
$route['order/admin/edit-payoff-user/:num'] = 'admin/order/home/edit_payoff_user';
$route['order/admin/update-user-status'] = 'admin/order/home/updateUserStatus';
$route['order/admin/delete-payoff-user'] = 'admin/order/home/delete_payoff_user';

$route['order/admin/transactees-list'] = 'admin/order/payoff/transactees_list';
$route['order/admin/add-transactee'] = 'admin/order/payoff/add_transactee';
$route['order/admin/get-transactee-list'] = 'admin/order/payoff/get_transactees_list';
$route['order/admin/update-transactee-status'] = 'admin/order/payoff/update_transactee_status';
$route['order/admin/get-transactee-details'] = 'admin/order/payoff/get_transactee_details';
$route['order/admin/edit-transactee-details/(:num)'] = 'admin/order/payoff/edit_transactee_details/$1';
$route['order/admin/delete-transactee-details'] = 'admin/order/payoff/delete_transactee_details';

$route['order/admin/upload-transactee-documents'] = 'admin/order/payoff/uploadTransacteeDocuments';
$route['order/admin/get-transactee-document-list'] = 'admin/order/payoff/getTransacteeDocumentList';

$route['order/admin/admin_users'] = 'admin/order/adminUsers/index';
$route['order/admin/admin_users_email'] = 'admin/order/adminUsers/email_check';
$route['order/admin/get_admin_details'] = 'admin/order/adminUsers/admin_details';
$route['order/admin/delete-admin-record/(:num)'] = 'admin/order/adminUsers/delete_admin_user/$1';
$route['order/admin/roles'] = 'admin/order/usersRole/index';
$route['order/admin/delete-role-record/(:num)'] = 'admin/order/usersRole/delete_user_role/$1';
$route['order/admin/proposed-branches'] = 'admin/order/proposedInsured/index';
$route['order/admin/get_branch_details'] = 'admin/order/proposedInsured/get_branch_details';
$route['order/admin/delete-proposed-branch/(:num)'] = 'admin/order/proposedInsured/delete_proposed_branch/$1';
$route['order/admin/dashboard'] = 'admin/order/home/index';
$route['order/admin/pre-listing-documents'] = 'admin/order/home/pre_listing_document';
$route['order/admin/lp-listing-documents'] = 'admin/order/home/lp_listing_document';
$route['order/admin/lp-orders'] = 'admin/order/order/lpOrders';
$route['order/admin/search-document-type'] = 'admin/order/order/searchDocumentType';
$route['order/admin/search-document-sub-type'] = 'admin/order/order/searchDocumentSubType';

$route['order/admin/lp-xml-logs'] = 'admin/order/titlePoint/lpXmlLogs';
$route['order/admin/get-lp-xml-logs'] = 'admin/order/titlePoint/getLpXmlLogs';

$route['order/admin/add-ion-fraud-notes'] = 'admin/order/home/addIonFraudNotes';
$route['order/admin/send-order-to-resware'] = 'admin/order/home/sendOrderToResware';
$route['order/admin/update-lp-report-status'] = 'admin/order/home/updateLpReportStatus';
$route['order/admin/update-doc-section'] = 'admin/order/home/updateDocumentSection';
$route['order/admin/admin-user-logs'] = 'admin/order/home/adminUserLogs';
$route['order/admin/get-instrument-data'] = 'admin/order/home/getInstrumentData';
$route['order/admin/store-lp-document-info'] = 'admin/order/home/storeLpDocumentInfo';
$route['order/admin/lp-document-types'] = 'admin/order/home/lpDocumentTypes';
$route['order/admin/import-lp-document-types'] = 'admin/order/home/importLpDocumentTypes';
$route['order/admin/add-lp-document-types'] = 'admin/order/home/addLpDocumentTypes';
$route['order/admin/delete-lp-document-type'] = 'admin/order/home/deleteLpDocumentType';
$route['order/admin/edit-lp-document-type/:num'] = 'admin/order/home/editLpDocumentType';
$route['order/admin/lp-alert'] = 'admin/order/home/lpAlert';
$route['order/admin/add-lp-alert'] = 'admin/order/home/addLpAlert';
$route['order/admin/delete-lp-alert'] = 'admin/order/home/deleteLpAlert';
$route['order/admin/edit-lp-alert/:num'] = 'admin/order/home/editLpAlert';
$route['order/admin/surveys'] = 'admin/order/home/surveys';
$route['send-survey-sample-email'] = 'admin/order/home/sendSurveySampleEmail';

$route['order/admin/daily-email-control'] = 'admin/order/home/dailyEmailControl';
$route['order/admin/get-daily-emailer'] = 'admin/order/home/getDailyEmailer';
$route['order/admin/add-daily-emailer'] = 'admin/order/home/addDailyEmailer';
$route['order/admin/edit-daily-emailer/:num'] = 'admin/order/home/editDailyEmailer';
$route['order/admin/delete-daily-emailer'] = 'admin/order/home/deleteDailyEmailerReceiver';
// $route['order/admin/new-pct-lp-document-types'] = 'admin/order/home/newPctLpDocumentTypes';
$route['order/admin/settings'] = 'admin/order/home/settings';

$route['update-title-officer-email-receive-flag'] = 'admin/order/title/updateTitleOfficerEmailFlag';
$route['update-lp-document-type-flag'] = 'admin/order/home/updateLpDocumentTypeFlag';
$route['update-lp-document-is-ves-type-flag'] = 'admin/order/home/updateLpDocumentTypeIsVesFlag';
$route['order/admin/regenerate-report'] = 'admin/order/home/regenerateReport';
$route['order/admin/store-vesting-info'] = 'admin/order/home/addVestingInfo';
$route['order/admin/get-vesting-info'] = 'admin/order/home/getVestingInfo';
$route['order/admin/add-instrument-info'] = 'admin/order/home/addInstrumentInfo';
$route['order/admin/change-client'] = 'admin/order/home/changeClient';
$route['order/admin/manual-report'] = 'admin/order/home/manualReport';
$route['order/admin/delete-unaproved-customer'] = 'admin/order/customer/delete_unapproved_user';
$route['send-summary-mail-sales-rep'] = 'admin/order/home/sendSummaryMailSalesRep';
$route['send-non-openers-email'] = 'admin/order/home/sendNonOpenersEmail';

/* Route for PCT-Order backend*/

/* Start Route for HR-Center frontend */
$route['hr'] = 'frontend/hr/login/index';
$route['hr/login'] = 'frontend/hr/login/login';
$route['hr/forgot-password'] = 'frontend/hr/login/forgot_password';
$route['hr/change-password/:any'] = 'frontend/hr/login/change_password';
$route['hr/dashboard'] = 'frontend/hr/dashboard/index';
$route['hr/time-cards'] = 'frontend/hr/timeCards/index';
$route['hr/get-time-cards'] = 'frontend/hr/timeCards/getTimeCards';
$route['hr/save-time-cards'] = 'frontend/hr/timeCards/saveTimeCards';
$route['hr/submit-timesheet'] = 'frontend/hr/timeCards/submitTimesheet';
$route['hr/vacation-requests'] = 'frontend/hr/vacationRequests/index';
$route['hr/get-vacation-requests'] = 'frontend/hr/vacationRequests/getVacationRequests';
$route['hr/save-vacation-requests'] = 'frontend/hr/vacationRequests/saveVacationRequests';
$route['hr/incident-reports'] = 'frontend/hr/incidentReports/index';
$route['hr/get-incident-reports'] = 'frontend/hr/incidentReports/getIncidentReports';
$route['hr/save-incident-reports'] = 'frontend/hr/incidentReports/saveIncidentReports';
$route['hr/profile'] = 'frontend/hr/user/index';
$route['hr/update-profile'] = 'frontend/hr/user/updateProfile';
$route['hr/update-password'] = 'frontend/hr/user/updatePassword';
$route['hr/upload-profile-pic'] = 'frontend/hr/user/uploadProfilePic';
$route['hr/memos'] = 'frontend/hr/memos/index';
$route['hr/get-memos'] = 'frontend/hr/memos/getMemos';
$route['hr/get-memo-info'] = 'frontend/hr/memos/getMemoInfo';
$route['hr/accept-memo'] = 'frontend/hr/memos/acceptMemo';
$route['hr/acknowledge-memo/(:any)/(:any)'] = 'frontend/hr/memos/acknowledgeMemo/$1/$2';
$route['hr/onboarding/employees'] = 'frontend/hr/taskList/empoyees';
$route['hr/onboarding/get-employees'] = 'frontend/hr/taskList/getEmpoyees';
$route['hr/onboarding/employee-task/(:num)'] = 'frontend/hr/taskList/tasks/$1';
$route['hr/mark-as-read'] = 'frontend/hr/hrCommon/markAsRead';
$route['hr/notifications'] = 'frontend/hr/notifications/index';
$route['hr/get-notifications'] = 'frontend/hr/notifications/getNotifications';
$route['hr/trainings'] = 'frontend/hr/trainings/index';
$route['hr/get-trainings'] = 'frontend/hr/trainings/getTrainings';
$route['hr/view-trainings-docs/:num'] = 'frontend/hr/trainings/viewTrainingsDocs';
$route['hr/complete-training/(:num)'] = 'frontend/hr/hrCommon/completeTraining/$1';
$route['hr/record-time'] = 'frontend/hr/hrCommon/recordTime';
$route['hr/get-vacation-data-for-calendar-user'] = 'frontend/hr/vacationRequests/getVacationDataForCalendarUser';
$route['hr/view-time-sheet/(:any)'] = 'frontend/hr/hrCommon/viewTimeSheet/$1';
$route['hr/logout'] = 'frontend/hr/dashboard/logout';
/* End Route for HR-Center frontend */

/* Start Route for HR-Center backend*/
$route['hr/admin'] = 'admin/hr/login/login';
$route['hr/admin/login/do_login'] = 'admin/hr/login/do_login';
$route['hr/admin/dashboard(/:num)?(/:num)?'] = 'admin/hr/dashboard/index';
$route['hr/admin/get-vacation-data-for-calendar'] = 'admin/hr/dashboard/getVacationDataForCalendar';
$route['hr/admin/admin-users'] = 'admin/hr/adminUsers/index';
$route['hr/admin/add-admin-user'] = 'admin/hr/adminUsers/addAdminUser';
$route['hr/admin/get-admin-users'] = 'admin/hr/adminUsers/getAdminUsers';
$route['hr/admin/edit-admin-user/:num'] = 'admin/hr/adminUsers/editAdminUser';
$route['hr/admin/delete-admin-user'] = 'admin/hr/adminUsers/deleteAdminUser';
$route['hr/admin/users'] = 'admin/hr/users/index';
$route['hr/admin/add-user'] = 'admin/hr/users/addUser';
$route['hr/admin/get-users'] = 'admin/hr/users/getUsers';
$route['hr/admin/edit-user/:num'] = 'admin/hr/users/editUser';
$route['hr/admin/delete-user'] = 'admin/hr/users/deleteUser';
$route['hr/admin/time-cards'] = 'admin/hr/timeCards/index';
$route['hr/admin/add-time-card'] = 'admin/hr/timeCards/addTimeCard';
$route['hr/admin/save-time-cards'] = 'admin/hr/timeCards/saveTimeCards';
$route['hr/admin/get-time-cards'] = 'admin/hr/timeCards/getTimeCards';
$route['hr/admin/edit-time-card/:num'] = 'admin/hr/timeCards/editTimeCard';
$route['hr/admin/time-sheets'] = 'admin/hr/timeSheets/index';
$route['hr/admin/get-time-sheets'] = 'admin/hr/timeSheets/getTimesheets';
$route['hr/admin/delete-time-card'] = 'admin/hr/timeCards/deleteTimeCard';
$route['hr/admin/vacation-requests'] = 'admin/hr/vacationRequests/index';
$route['hr/admin/add-vacation-request'] = 'admin/hr/vacationRequests/addVacationRequest';
$route['hr/admin/save-vacation-requests'] = 'admin/hr/vacationRequests/saveVacationRequests';
$route['hr/admin/get-vacation-requests'] = 'admin/hr/vacationRequests/getVacationRequests';
$route['hr/admin/edit-time-card/:num'] = 'admin/hr/vacationRequests/editVacationRequest';
$route['hr/admin/delete-time-card'] = 'admin/hr/vacationRequests/deleteVacationRequest';
$route['hr/admin/incident-reports'] = 'admin/hr/incidentReports/index';
$route['hr/admin/add-incident-report'] = 'admin/hr/incidentReports/addIncident';
$route['hr/admin/save-incident-reports'] = 'admin/hr/incidentReports/saveIncidentReports';
$route['hr/admin/get-incident-reports'] = 'admin/hr/incidentReports/getIncidentReports';
$route['hr/admin/approve-deny-request'] = 'admin/hr/hrCommon/approveDenyRequest';
$route['hr/admin/user-types'] = 'admin/hr/userTypes/index';
$route['hr/admin/add-user-type'] = 'admin/hr/userTypes/addUserType';
$route['hr/admin/get-user-types'] = 'admin/hr/userTypes/getUserTypes';
$route['hr/admin/edit-user-type/:num'] = 'admin/hr/userTypes/editUserType';
$route['hr/admin/delete-user-type'] = 'admin/hr/userTypes/deleteUserType';
$route['hr/admin/departments'] = 'admin/hr/departments/index';
$route['hr/admin/add-department'] = 'admin/hr/departments/addDepartment';
$route['hr/admin/get-departments'] = 'admin/hr/departments/getDepartments';
$route['hr/admin/edit-department/:num'] = 'admin/hr/departments/editDepartment';
$route['hr/admin/delete-department'] = 'admin/hr/departments/deleteDepartment';
$route['hr/admin/positions'] = 'admin/hr/positions/index';
$route['hr/admin/add-position'] = 'admin/hr/positions/addPosition';
$route['hr/admin/get-positions'] = 'admin/hr/positions/getPositions';
$route['hr/admin/edit-position/:num'] = 'admin/hr/positions/editPosition';
$route['hr/admin/delete-position'] = 'admin/hr/positions/deletePosition';
$route['hr/admin/task-category'] = 'admin/hr/taskList/category';
$route['hr/admin/get-task-category'] = 'admin/hr/taskList/getCategory';
$route['hr/admin/add-task-category'] = 'admin/hr/taskList/addCategory';
$route['hr/admin/edit-task-category/(:num)'] = 'admin/hr/taskList/editCategory/$1';
$route['hr/admin/delete-task-category'] = 'admin/hr/taskList/deleteCategory';
$route['hr/admin/task-list'] = 'admin/hr/taskList/index';
$route['hr/admin/get-task-list'] = 'admin/hr/taskList/getTask';
$route['hr/admin/add-task-list'] = 'admin/hr/taskList/addTask';
$route['hr/admin/edit-task-list/(:num)'] = 'admin/hr/taskList/editTask/$1';
$route['hr/admin/delete-task-list'] = 'admin/hr/taskList/deleteTask';
$route['hr/admin/users-tasks/(:num)'] = 'admin/hr/users/getTask/$1';
$route['hr/admin/training'] = 'admin/hr/training/index';
$route['hr/admin/get-training'] = 'admin/hr/training/getTraining';
$route['hr/admin/add-training'] = 'admin/hr/training/addTraining';
$route['hr/admin/edit-training/(:num)'] = 'admin/hr/training/editTraining/$1';
$route['hr/admin/delete-training'] = 'admin/hr/training/deleteTraining';
$route['hr/admin/training-status'] = 'admin/hr/training/trainingStatus';
$route['hr/admin/get-training-status'] = 'admin/hr/training/getTrainingStatus';
$route['hr/admin/view-trainings-docs/:num'] = 'admin/hr/training/viewTrainingsDocs';
$route['hr/admin/complete-training/(:num)'] = 'admin/hr/training/completeTraining/$1';
$route['hr/admin/delete-training-material/(:num)'] = 'admin/hr/training/deleteTrainingMaterial/$1';

$route['hr/admin/memos'] = 'admin/hr/memos/index';
$route['hr/admin/add-memo'] = 'admin/hr/memos/addMemo';
$route['hr/admin/get-memos'] = 'admin/hr/memos/getMemos';
$route['hr/admin/edit-memo/:num'] = 'admin/hr/memos/editMemo';
$route['hr/admin/delete-memo'] = 'admin/hr/memos/deleteMemo';
$route['hr/admin/memos-status'] = 'admin/hr/memos/memosStatus';
$route['hr/admin/get-memos-status'] = 'admin/hr/memos/getMemosStatus';
$route['hr/admin/notifications'] = 'admin/hr/notifications/index';
$route['hr/admin/get-notifications'] = 'admin/hr/notifications/getNotifications';
$route['hr/admin/mark-as-read'] = 'admin/hr/hrCommon/markAsRead';
$route['hr/admin/branches'] = 'admin/hr/branches/index';
$route['hr/admin/add-branch'] = 'admin/hr/branches/addBranch';
$route['hr/admin/get-branches'] = 'admin/hr/branches/getBranches';
$route['hr/admin/edit-branch/(:num)'] = 'admin/hr/branches/editBranch/$1';
$route['hr/admin/delete-branch'] = 'admin/hr/branches/deleteBranch';
$route['hr/admin/get-memo-info'] = 'admin/hr/memos/getMemoInfo';
$route['hr/admin/accept-memo'] = 'admin/hr/memos/acceptMemo';
$route['hr/admin/trainings-branch-manager'] = 'admin/hr/training/trainingsBranchManager';
$route['hr/admin/get-branch-manager-trainings'] = 'admin/hr/training/getBranchManagerTrainings';
$route['hr/admin/send-password/(:num)'] = 'admin/hr/users/sendPassword/$1';
$route['hr/admin/get-dashboard-count'] = 'admin/hr/dashboard/getDashboardCount';
$route['hr/admin/ot-hours'] = 'admin/hr/timeSheets/viewOtHours';
$route['hr/admin/get-ot-hours'] = 'admin/hr/timeSheets/getOtHours';
$route['hr/admin/add-ot-request'] = 'admin/hr/timeSheets/addOtRequest';
$route['hr/admin/view-time-sheet/(:any)/(:num)'] = 'admin/hr/timeSheets/viewTimeSheet/$1/$2';
$route['hr/admin/orders'] = 'admin/hr/orders/index';
$route['hr/admin/get-orders'] = 'admin/hr/orders/getOrders';
$route['hr/admin/order-tasks/(:num)'] = 'admin/hr/orders/orderTasks/$1';
$route['hr/admin/upload-documet-resware/(:any)'] = 'admin/hr/orders/uploadBorrowerDocumentResware/$1';
$route['hr/admin/tasks'] = 'admin/hr/tasks/index';
$route['hr/admin/get-tasks'] = 'admin/hr/tasks/getTasks';
$route['hr/admin/add-task'] = 'admin/hr/tasks/addTask';
$route['hr/admin/edit-task/(:num)'] = 'admin/hr/tasks/editTask/$1';
$route['hr/admin/delete-task'] = 'admin/hr/tasks/deleteTask';
$route['hr/admin/loan-tasks-position'] = 'admin/hr/tasks/loanTasksPosition';
$route['hr/admin/sale-tasks-position'] = 'admin/hr/tasks/saleTasksPosition';
$route['hr/admin/save-loan-tasks-position'] = 'admin/hr/tasks/saveLoanTasksPosition';
$route['hr/admin/save-sale-tasks-position'] = 'admin/hr/tasks/saveSaleTasksPosition';
$route['hr/admin/save-loan-tasks-position'] = 'admin/hr/tasks/saveLoanTasksPosition';
$route['hr/admin/create-note'] = 'admin/hr/orders/create_note';
$route['hr/admin/add-borrower-on-order'] = 'admin/hr/orders/addBorrowerOnOrder';
$route['hr/admin/add-borrower-on-order-for-payoff'] = 'admin/hr/orders/addBorrowerOnOrderForPayoff';
$route['hr/admin/add-lender-on-order'] = 'admin/hr/orders/addLenderOnOrder';
$route['hr/admin/send-request-docs'] = 'admin/hr/orders/sendRequestDocs';
$route['hr/admin/add-buyer-on-order'] = 'admin/hr/orders/addBuyerOnOrder';
$route['hr/admin/add-seller-on-order'] = 'admin/hr/orders/addSellerOnOrder';
$route['hr/admin/task-documents'] = 'admin/hr/orders/taskDocuments';
$route['hr/admin/escrow-instruction'] = 'admin/hr/escrowInstruction/index';
$route['hr/admin/escrow-instruction-import'] = 'admin/hr/escrowInstruction/import';
$route['hr/admin/add-escrow-instruction'] = 'admin/hr/escrowInstruction/addEscrowInstruction';
$route['hr/admin/edit-escrow-instruction/:num'] = 'admin/hr/escrowInstruction/editEscrowInstruction';
$route['hr/admin/delete-escrow-instruction'] = 'admin/hr/escrowInstruction/deleteEscrowInstruction';
$route['hr/admin/logout'] = 'admin/hr/dashboard/logout';
/* End Route for HR-Center backend */

$route['calculator'] = 'frontend/calc/welcome/index';
$route['calculator/signup'] = 'frontend/calc/welcome/signup';
$route['calculator/dashboard'] = 'frontend/calc/welcome/dashboard';
$route['calculator/logout'] = 'frontend/calc/welcome/logout';
$route['calculator/view_quote/:num'] = 'frontend/calc/welcome/view_quote';
$route['calculator/admin_login'] = 'frontend/calc/welcome/admin_login';
$route['calculator/admin_dashboard'] = 'admin/calc/admin/admin_dashboard';
$route['calculator/admin/title_rates'] = 'admin/calc/admin/title_rates';
$route['calculator/admin_dashboard_submit'] = 'admin/calc/admin/admin_dashboard_submit';
$route['calculator/admin/import_title_rates'] = 'admin/calc/admin/import_title_rates';
$route['calculator/admin/edit_title_rates/:num'] = 'admin/calc/admin/edit_title_rates';
$route['calculator/admin/resale_rates'] = 'admin/calc/admin/resale_rates';
$route['calculator/admin/add_resale_rates'] = 'admin/calc/admin/add_resale_rates';
$route['calculator/admin/edit_resale_rates/:num'] = 'admin/calc/admin/edit_resale_rates';
$route['calculator/admin/refinance_rates'] = 'admin/calc/admin/refinance_rates';
$route['calculator/admin/add_refinance_rates'] = 'admin/calc/admin/add_refinance_rates';
$route['calculator/admin/edit_refinance_rates/:num'] = 'admin/calc/admin/edit_refinance_rates';
$route['calculator/admin/fees'] = 'admin/calc/admin/fees';
$route['calculator/admin/add_fees'] = 'admin/calc/admin/add_fees';
$route['calculator/admin/edit_fees/:num'] = 'admin/calc/admin/edit_fees';
$route['calculator/admin_logout'] = 'admin/calc/admin/admin_logout';

$route['reports'] = "frontend/report";
$route['reports/(.+)'] = "frontend/report/$1";
$route['pmas'] = "frontend/pma";
$route['labels'] = "frontend/label";
$route['labels/(.+)'] = "frontend/label/$1";
$route['download-label-pdf'] = "frontend/label/downloadPdf";
$route['pmas/(.+)'] = "frontend/pma/$1";
$route['send_invite'] = 'frontend/order/home/send_invite';
$route['sales-snap-shot'] = "frontend/salesSnapShot";
$route['send-sales-snap-shot-email'] = "frontend/salesSnapShot/sendEmailToSalesRep";

$route['sales-snap-shot/(.+)'] = "frontend/salesSnapShot/$1";
$route['sales-activity-report'] = "frontend/salesActivityReport";
$route['sales-activity-report/(.+)'] = "frontend/salesActivityReport/$1";

$route['api/get_file_details(/:num)?'] = 'frontend/api/pctCalculator/get_file_details$1';
$route['api/add_calc_details'] = 'frontend/api/pctCalculator/add_calc_details';
$route['api/send_title_doc_to_resware'] = 'frontend/api/pctCalculator/send_title_rates_document_to_resware';
$route['order/admin/regenerate-tax-document'] = 'admin/order/home/regenerateTaxDocument';
$route['order/admin/generate-tax-document'] = 'admin/order/home/generateTaxDocument';
$route['check-document'] = 'frontend/order/home/checkDocument';

$route['404_override'] = '';
$route['translate_uri_dashes'] = false;
