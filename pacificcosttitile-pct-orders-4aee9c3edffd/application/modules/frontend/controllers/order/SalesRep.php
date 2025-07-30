<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

class SalesRep extends MX_Controller
{
    private $version = '09';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url', 'form')
        );
        $this->version = strtotime(date('Y-m-d'));
        $this->load->library('order/salesDashboardTemplate');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('order/template');
        $this->load->model('order/orderRecording');
        $this->load->library('order/order');
        $this->load->model('order/apiLogs');
        $this->load->model('order/reviewPrelimData');
        $this->load->model('order/titleOfficer');
        $this->load->model('order/home_model');
        $this->load->model('order/fees_model');
        $this->load->library('order/resware');
        $this->load->library('order/common');
        $this->load->model('order/salesRep_model');
        $this->common->is_sales_user();
    }

    public function index()
    {
        $userdata = $this->session->userdata('user');
        $name = isset($userdata['name']) && !empty($userdata['name']) ? $userdata['name'] : '';
        $data['name'] = $name;
        $data['is_sales_rep_manager'] = $userdata['is_sales_rep_manager'];
        $userId = $this->uri->segment(2);
        $data['user_id'] = $userId;
        if ($userdata['is_sales_rep_manager'] == 1) {
            $salesUser = $this->home_model->get_user(array('id' => $userdata['id']));
            if (!empty($salesUser['sales_rep_users'])) {
                $salesRepUsers = explode(',', $salesUser['sales_rep_users']);
                if (!in_array($userdata['id'], $salesRepUsers)) {
                    $salesRepUsers[] = $userdata['id'];
                }
                if (!in_array($userId, $salesRepUsers)) {
                    redirect(base_url() . 'sales-dashboard/' . $userdata['id']);
                }
                $data['salesUsers'] = $this->order->get_sales_users($salesRepUsers);
            } else {
                $data['salesUsers'] = $this->order->get_sales_users();
            }
        } else {
            if ($userId != $userdata['id']) {
                redirect(base_url() . 'sales-dashboard/' . $userdata['id']);
            }
            $data['salesUsers'] = array();
        }
        $data['user_email'] = $userdata['email'];
        $data['order_lists'] = $this->order->get_recent_orders();
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $con = array('id' => $userdata['id']);
        $sales_rep_info = $this->order->getSalesRep($con);
        $data['sales_rep_info'] = $sales_rep_info;
        $workedDays = $this->order->countWorkedDaysOfMonth();
        $workingDaysRemaining = $this->order->countWokingsDaysLeftOfMonth();
        $openRefiResult = $this->order->getOpenOrdersCountForRefiProducts(date('m'), $userId);
        $data['refi_open_count'] = !empty($openRefiResult['refi_count']) ? $openRefiResult['refi_count'] : 0;
        $openSaleResult = $this->order->getOpenOrdersCountForSaleProducts(date('m'), $userId);
        $data['sale_open_count'] = !empty($openSaleResult['sale_count']) ? $openSaleResult['sale_count'] : 0;
        $data['total_open_count'] = $data['sale_open_count'] + $data['refi_open_count'];

        if ($data['total_open_count'] > 0) {
            $numOfOpenOrderPerWorkedDays = $data['total_open_count'] / $workedDays;
            $data['projected_open_count'] = (round($numOfOpenOrderPerWorkedDays * $workingDaysRemaining)) + $data['total_open_count'];
        } else {
            $numOfOpenOrderPerWorkedDays = 0;
            $data['projected_open_count'] = 0;
        }

        $closeRefiResult = $this->order->getClosedOrdersCountForRefiProducts(date('m'), $userId);
        $data['refi_close_count'] = !empty($closeRefiResult['refi_count']) ? $closeRefiResult['refi_count'] : 0;
        $closeSaleResult = $this->order->getClosedOrdersCountForSaleProducts(date('m'), $userId);

        // get commission value from monthly commission
        $sales_commission = 0;
        $this->load->model('admin/order/user_monthly_commission_model');
        $monthly_commission_arr = [
            'user_id' => $userId,
            'commission_year' => date('Y'),
            'commission_month' => date('m'),
        ];
        $commission_obj = $this->user_monthly_commission_model->get_by($monthly_commission_arr);
        if ($commission_obj) {
            $sales_commission = $commission_obj->commission;
            $details_json = $commission_obj->commission_details;
            $first_in_threshold = $draw_amount = 0;

            if (!empty($details_json) && json_decode($details_json)) {

                $details = json_decode($details_json);
                foreach ($details as $detail_json) {
                    if (!empty($detail_json) && json_decode($detail_json)) {
                        $detail = json_decode($detail_json);
                        $prod_type = $detail->prod_type;
                        if ($prod_type == 'override_add') {
                            $override_add_user = getUserName($detail->user_id);
                            if ($detail->loan > 0) {
                                $override_add_per['loan'] = $detail->loan;
                            }
                            if ($detail->sale > 0) {
                                $override_add_per['sale'] = $detail->sale;
                            }
                            if ($detail->escrow > 0) {
                                $override_add_per['escrow'] = $detail->escrow;
                            }
                            if (count($override_add_per)) {
                                $condition = [
                                    'user_id' => $detail->user_id,
                                    'commission_month' => date('m'),
                                    'commission_year' => date('Y'),
                                ];
                                $override_add_val = getExtraCommission($override_add_per, $condition);
                            }
                            if ($override_add_user):
                                foreach ($override_add_val as $override_add_key => $override_add_comm):
                                    if ($override_add_key == 'escrow' && is_array($override_add_comm)):
                                        $override_commission_val = array_sum($override_add_comm);
                                    else:
                                        $override_commission_val = $override_add_comm;
                                    endif;

                                    $sales_commission += $override_commission_val;

                                endforeach;
                            endif;

                        } elseif ($prod_type == 'draw') {
                            $draw_amount = $detail->commisison;
                        } elseif ($prod_type == 'first_threshold') {
                            $first_in_threshold = $detail->commisison;
                        }

                    }
                }
                if ($sales_commission < 0 && abs($draw_amount) == 0 && abs($first_in_threshold) > 0) {
                    $sales_commission = 0;
                }
            }
        }
        $data['sales_commission'] = $sales_commission;
        //Commission Logic Ends

        $data['sale_close_count'] = !empty($closeSaleResult['sale_count']) ? $closeSaleResult['sale_count'] : 0;
        $data['total_close_count'] = $data['refi_close_count'] + $data['sale_close_count'];

        if ($data['total_close_count'] > 0) {
            $numOfCloseOrderPerWorkedDays = $data['total_close_count'] / $workedDays;
            $data['projected_close_count'] = (round($numOfCloseOrderPerWorkedDays * $workingDaysRemaining)) + $data['total_close_count'];
        } else {
            $numOfCloseOrderPerWorkedDays = 0;
            $data['projected_close_count'] = 0;
        }

        $openOrderRefiTotalPremium = !empty($openRefiResult['total_premium_for_refi_open_orders']) ? $openRefiResult['total_premium_for_refi_open_orders'] : 0;
        $closeOrderRefiTotalPremium = !empty($closeRefiResult['total_premium_for_refi_close_orders']) ? $closeRefiResult['total_premium_for_refi_close_orders'] : 0;
        //$data['refi_total_premium'] = $openOrderRefiTotalPremium + $closeOrderRefiTotalPremium;
        $data['refi_total_premium'] = $closeOrderRefiTotalPremium;
        $openOrderSaleTotalPremium = !empty($openSaleResult['total_premium_for_sale_open_orders']) ? $openSaleResult['total_premium_for_sale_open_orders'] : 0;
        $closeOrderSaleTotalPremium = !empty($closeSaleResult['total_premium_for_sale_close_orders']) ? $closeSaleResult['total_premium_for_sale_close_orders'] : 0;
        //$data['sale_total_premium'] = $openOrderSaleTotalPremium + $closeOrderSaleTotalPremium;
        $data['sale_total_premium'] = $closeOrderSaleTotalPremium;
        $data['total_premium'] = $data['sale_total_premium'] + $data['refi_total_premium'];
        if ($data['total_premium'] > 0) {
            $premiumWorkedDays = $data['total_premium'] / $workedDays;
            $data['projected_revenue'] = (round($premiumWorkedDays * $workingDaysRemaining)) + $data['total_premium'];
        } else {
            $premiumWorkedDays = 0;
            $data['projected_revenue'] = 0;
        }
        $totalCount = $data['sale_close_count'] + $data['refi_close_count'] + $data['sale_open_count'] + $data['refi_open_count'];
        if ($totalCount > 0) {
            // $data['refi_close_order_percetage'] = round(($data['refi_close_count']*100)/$totalCount);
            // $data['sale_close_order_percetage'] = round(($data['sale_close_count']*100)/$totalCount);
            // $data['close_order_percetage'] = $data['refi_close_order_percetage'] + $data['sale_close_order_percetage'];

            /** Current month calculation only */
            // $data['refi_close_order_percetage'] = round(($data['refi_close_count']*100)/$data['refi_open_count']);
            // $data['sale_close_order_percetage'] = round(($data['sale_close_count']*100)/$data['sale_open_count']);
            // $data['close_order_percetage'] = round((($data['sale_close_count'] + $data['refi_close_count'])*100)/($data['refi_open_count'] + $data['sale_open_count']));

            /** For last 4 months calculations */
            $clseRefiResult = $this->order->getClosedOrdersCountForRefiProducts(date('m'), $userId, 0, 0, 1);
            $refiClsCount = !empty($clseRefiResult['refi_count']) ? $clseRefiResult['refi_count'] : 0;
            $clsSaleResult = $this->order->getClosedOrdersCountForSaleProducts(date('m'), $userId, 0, 0, 1);
            $saleClsCount = !empty($clsSaleResult['sale_count']) ? $clsSaleResult['sale_count'] : 0;

            $opnRefiResult = $this->order->getOpenOrdersCountForRefiProducts(date('m'), $userId, 0, 0, 1);
            $refiOpnCount = !empty($opnRefiResult['refi_count']) ? $opnRefiResult['refi_count'] : 0;
            $opnSaleResult = $this->order->getOpenOrdersCountForSaleProducts(date('m'), $userId, 0, 0, 1);
            $saleOpnCount = !empty($opnSaleResult['sale_count']) ? $opnSaleResult['sale_count'] : 0;

            $data['refi_close_order_percetage'] = round(($refiClsCount * 100) / $refiOpnCount);
            $data['sale_close_order_percetage'] = round(($saleClsCount * 100) / $saleOpnCount);
            $data['close_order_percetage'] = round((($saleClsCount + $refiClsCount) * 100) / ($saleOpnCount + $refiOpnCount));
            /** End last 4 month calculations */

        } else {
            $data['refi_close_order_percetage'] = 0;
            $data['sale_close_order_percetage'] = 0;
            $data['close_order_percetage'] = 0;
        }
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/sales_dashboard.js?v=' . $this->version));
        $this->salesdashboardtemplate->addCss(base_url('assets/frontend/css/sales-dashboard.css?v=' . $this->version));

        $this->salesdashboardtemplate->show("order", "sales_dashboard", $data);
        // $this->salesdashboardtemplate->addCSS(base_url('assets/css/theme.css'));
        // echo "<pre>";
        // var_dump($data);die;
        // $this->template->show("order", "sales_dashboard", $data);
    }

    public function get_sales_orders()
    {
        $params = array();
        $data = array();
        $lp_alerts = $this->home_model->get_lp_alert_list();
        $lp_alerts = array_values($lp_alerts);
        $lpAlertRange = [];
        $numItems = count($lp_alerts);
        $k = 0;
        foreach ($lp_alerts as $key => $alert) {
            ++$k;
            $lpAlertRange[$k]['color_code'] = $alert['color_code'];
            $lpAlertRange[$k]['text_color'] = $alert['text_color'];
            $lpAlertRange[$k]['regular_order_color_code'] = isset($alert['regular_order_color_code']) && !empty($alert['regular_order_color_code']) ? $alert['regular_order_color_code'] : $alert['color_code'];
            if ($k != $numItems) {
                $lpAlertRange[$k]['range'] = range((int) $lp_alerts[$key]['days'], ((int) $lp_alerts[$key + 1]['days'] - 1));
                // $lpAlertRange[$k]['delete'] = $alert['delete'];
            } else {
                $lpAlertRange[$k]['range'] = range((int) $lp_alerts[$key]['days'], ((int) $lp_alerts[$key]['days']));
                // $lpAlertRange[$k]['color_code'] = $alert['color_code'];
                // $lpAlertRange[$k]['text_color'] = $alert['text_color'];
                // $lpAlertRange[$k]['regular_order_color_code'] = $alert['regular_order_color_code'];
                // $lpAlertRange[$k]['delete'] = $alert['delete'];
            }
        }

        $userdata = $this->session->userdata('user');
        $status = $this->input->post('status');
        $month = $this->input->post('month') ? $this->input->post('month') : '';
        $salesUser = $this->input->post('sales_user') ? $this->input->post('sales_user') : '';
        $order_type = $this->input->post('order_type');
        $sales_rep_manager_flag = $this->input->post('sales_rep_manager_flag');
        $params['salesUser'] = $salesUser;
        $params['salesFlag'] = 1;
        //$params['status'] = isset($status) && !empty($status) ? $status : 'open';
        //$params['month'] = isset($month) && !empty($month) ? $month : date('m');
        $params['order_type'] = isset($order_type) && !empty($order_type) ? $order_type : '';
        $params['sales_rep_manager_flag'] = isset($sales_rep_manager_flag) && !empty($sales_rep_manager_flag) ? $sales_rep_manager_flag : false;

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 2;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $pageno = ($params['start'] / $params['length']) + 1;
            $order_lists = $this->order->get_orders($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $order_lists = $this->order->get_orders($params);
        }

        if (isset($order_lists['data']) && !empty($order_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($order_lists['data'] as $order) {

                $nestedData = array();
                $nestedData[] = (empty($order['file_number']) ? $order['lp_file_number'] : ((empty($order['lp_file_number'])) ? $order['file_number'] : $order['file_number'] . '&nbsp; <i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">It\'s LP order ' . $order['lp_file_number'] . ' and it\'s converted into normal order </span>'));
                if ($userdata['is_sales_rep_manager'] == 1) {
                    $nestedData[] = $order['sales_first_name'] . " " . $order['sales_last_name'];
                }
                $nestedData[] = date("m/d/Y", strtotime($order['created_at']));
                $nestedData[] = $order['full_address'];
                if ($order['file_number'] == 0 && !empty($order['lp_file_number'])) {
                    $nestedData[] = ucfirst($order['lp_report_status']);
                } else {
                    $nestedData[] = ucfirst($order['resware_status']);
                }

                // $action = '<select style="width:auto;margin-left:10px;" name="order_type_filter" id="order_type_filter" class="custom-select custom-select-sm form-control form-control-sm">';
                // if ($order['prelim_summary_id'] != 0) {
                //     $action .= "<option value='REVIEW FILE' ><a href='".base_url()."review-file/".$order['file_id']."'><button class='btn btn-grad-2a button-color' type='button'>REVIEW FILE</button></a></option>";
                // } else {
                //     $action .= "<option value='Not Ready'><a href='javascript:void(0);'><button class='btn btn-grad-2a' style='background: #d35411;' type='button'>Not Ready</button></a></option>";
                // }

                // if (!empty($order['file_number'])) {
                //     $action .= "<option value='VIEW Partners' ><a href='javascript:void(0);'><button class='btn btn-grad-2a button-color' type='button' onclick='getPartners(".$order['file_id'].");'>VIEW Partners</button></a></option>";
                // }

                // if ($order['file_number'] == 0 && !empty($order['lp_file_number']) && $order['lp_report_status'] == 'approved') {
                //     $documentUrl = env('AWS_PATH')."pre-listing-doc/".$order['lp_file_number'].'.pdf';
                //     $reportDocumentUrl = env('AWS_PATH')."pre-listing-doc/pre_listing_report_".$order['lp_file_number'].'.pdf';
                //     $action .= "<option value='View Pre List Doc'><a target='_blank' href='$documentUrl'><button class='btn btn-grad-2a button-color' type='button' style='margin-top:10px;'>View Pre List Doc</button></a><a target='_blank href='$reportDocumentUrl'><button class='btn btn-grad-2a button-color' type='button' style='margin-top:10px;'>View LP Report</button></a></option>";
                // }
                // $action .= "</select>";
                $action = '<div class="dropdown"><a class="btn dropdown-toggle click-action-type" type="button" data-toggle="dropdown" href="#">Click Action Type <span class="caret"></span></a><ul class="dropdown-menu">';
                if ($order['prelim_summary_id'] != 0) {
                    $action .= "<li><a href='" . base_url() . "review-file/" . $order['file_id'] . "'><button class='btn btn-grad-2a button-color' type='button'>REVIEW FILE</button></a></li>";
                } else {
                    $action .= "<li><a href='javascript:void(0);'><button class='btn btn-grad-2a' style='background: #d35411;' type='button'>Not Ready</button></a></li>";
                }

                if (!empty($order['file_number'])) {
                    $action .= "<li><a href='javascript:void(0);'><button class='btn btn-grad-2a button-color' type='button' onclick='getPartners(" . $order['file_id'] . ");'>VIEW Partners</button></a></li>";
                }

                if ($order['file_number'] == 0 && !empty($order['lp_file_number']) && $order['lp_report_status'] == 'approved') {
                    $documentUrl = env('AWS_PATH') . "pre-listing-doc/" . $order['lp_file_number'] . '.pdf';
                    $reportDocumentUrl = env('AWS_PATH') . "pre-listing-doc/pre_listing_report_" . $order['lp_file_number'] . '.pdf';
                    $action .= "<li><a target='_blank' href='$documentUrl'><button class='btn btn-grad-2a button-color' type='button' style='margin-top:10px;'>View Pre List Doc</button></a></li><li><a target='_blank' href='$reportDocumentUrl'><button class='btn btn-grad-2a button-color' type='button' style='margin-top:10px;'>View LP Report</button></a></li>";
                }
                $action .= "</ul></div>";
                $nestedData[] = $action;
                $now = time();
                $your_date = strtotime($order['created_at']);
                $new_date = date('Y-m-d', $your_date);
                $nowDate = date('Y-m-d');
                $datetime1 = new DateTime($new_date);
                $datetime2 = new DateTime($nowDate);

                $datediff = $datetime1->diff($datetime2)->format("%a");

                if ((!empty($order['lp_file_number']) && empty($order['file_number'])) || (!empty($order['file_number']) && ($order['prelim_summary_id'] == 0 && strtolower($order['resware_status']) == 'open'))) {
                    foreach ($lpAlertRange as $key => $val) {
                        if (((count($val['range']) == 1) && $datediff >= $val['range'][0]) || in_array($datediff, $val['range'])) {
                            if ((!empty($order['lp_file_number']) && empty($order['file_number']))) {
                                $nestedData[] = "color~" . $val['color_code'] . "|text_color~" . $val['text_color'];
                            } else {
                                $regularColorCode = $val['regular_order_color_code'];
                                $nestedData[] = "color~" . $regularColorCode . "|text_color~" . $val['text_color'];
                            }
                            break;
                        }
                    }
                }
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        $json_data['lpAlertRange'] = $lpAlertRange;

        echo json_encode($json_data);
    }

    public function salesProductionHistory()
    {
        $userdata = $this->session->userdata('user');
        $userId = $this->uri->segment(2);
        $data['sales_user_id'] = $userId;
        $data['is_sales_rep_manager'] = $userdata['is_sales_rep_manager'];
        if ($userdata['is_sales_rep_manager'] == 1) {
            $salesUser = $this->home_model->get_user(array('id' => $userdata['id']));
            if (!empty($salesUser['sales_rep_users'])) {
                $salesRepUsers = explode(',', $salesUser['sales_rep_users']);
                if (!in_array($userdata['id'], $salesRepUsers)) {
                    $salesRepUsers[] = $userdata['id'];
                }
                if (!in_array($userId, $salesRepUsers)) {
                    redirect(base_url() . 'sales-production-history/' . $userdata['id']);
                }
                $data['salesUsers'] = $this->order->get_sales_users($salesRepUsers);
            } else {
                $data['salesUsers'] = $this->order->get_sales_users();
            }
        } else {
            if ($userId != $userdata['id']) {
                redirect(base_url() . 'sales-production-history/' . $userdata['id']);
            }
            $data['salesUsers'] = array();
        }
        $data['title'] = 'Sales Production History | Pacific Coast Title Company';
        $salesHistory = array();
        for ($iM = 1; $iM <= (int) date('m'); $iM++) {
            $month = date("m", strtotime("$iM/12/10"));
            $dateObj = DateTime::createFromFormat('!m', $iM);
            $monthName = $dateObj->format('F');
            $salesHistory[$iM - 1]['month'] = $monthName;
            $salesHistory[$iM - 1]['month_val'] = $month;

            $openRefiResult = $this->order->getOpenOrdersCountForRefiProducts($month, $userId);
            $refi_open_count = !empty($openRefiResult['refi_count']) ? $openRefiResult['refi_count'] : 0;
            $openSaleResult = $this->order->getOpenOrdersCountForSaleProducts($month, $userId);
            $sale_open_count = !empty($openSaleResult['sale_count']) ? $openSaleResult['sale_count'] : 0;
            $salesHistory[$iM - 1]['total_open_count'] = $sale_open_count + $refi_open_count;

            $closeRefiResult = $this->order->getClosedOrdersCountForRefiProducts($month, $userId);
            $refi_close_count = !empty($closeRefiResult['refi_count']) ? $closeRefiResult['refi_count'] : 0;
            $closeSaleResult = $this->order->getClosedOrdersCountForSaleProducts($month, $userId);
            $sale_close_count = !empty($closeSaleResult['sale_count']) ? $closeSaleResult['sale_count'] : 0;
            $salesHistory[$iM - 1]['total_close_count'] = $refi_close_count + $sale_close_count;

            $openOrderRefiTotalPremium = !empty($openRefiResult['total_premium_for_refi_open_orders']) ? $openRefiResult['total_premium_for_refi_open_orders'] : 0;
            $closeOrderRefiTotalPremium = !empty($closeRefiResult['total_premium_for_refi_close_orders']) ? $closeRefiResult['total_premium_for_refi_close_orders'] : 0;
            //$refi_total_premium = $openOrderRefiTotalPremium + $closeOrderRefiTotalPremium;
            $refi_total_premium = $closeOrderRefiTotalPremium;
            $openOrderSaleTotalPremium = !empty($openSaleResult['total_premium_for_sale_open_orders']) ? $openSaleResult['total_premium_for_sale_open_orders'] : 0;
            $closeOrderSaleTotalPremium = !empty($closeSaleResult['total_premium_for_sale_close_orders']) ? $closeSaleResult['total_premium_for_sale_close_orders'] : 0;
            //$sale_total_premium = $openOrderSaleTotalPremium + $closeOrderSaleTotalPremium;
            $sale_total_premium = $closeOrderSaleTotalPremium;
            $salesHistory[$iM - 1]['total_premium'] = $sale_total_premium + $refi_total_premium;

            $totalCount = $sale_close_count + $refi_close_count + $sale_open_count + $refi_open_count;
            if ($totalCount > 0) {
                $refi_close_order_percetage = round(($refi_close_count * 100) / $totalCount);
                $sale_close_order_percetage = round(($sale_close_count * 100) / $totalCount);
                $salesHistory[$iM - 1]['close_order_percetage'] = $refi_close_order_percetage + $sale_close_order_percetage;
            } else {
                $refi_close_order_percetage = 0;
                $sale_close_order_percetage = 0;
                $salesHistory[$iM - 1]['close_order_percetage'] = 0;
            }
            if ($month == date('m')) {
                if ($month == '01') {
                    $previousCount = $this->order->getCountBasedOnCurrentDayForPreviousMonthForPreviousYear($userId);
                } else {
                    $previousCount = $this->order->getCountBasedOnCurrentDayForPreviousMonth($userId);
                }
                $salesHistory[$iM - 1]['trending'] = $previousCount['total_count'] >= $salesHistory[$iM - 1]['total_open_count'] ? '<span style="color: red;font-weight:bold;"><i class="fa fa-arrow-down"></i></span>' : '<span style="color: limegreen;font-weight:bold;"><i class="fa fa-arrow-up"></i></span>';
            } else {
                if ($month == '01') {
                    $previousCount = $this->order->getOpenOrdersCountForLastMonthOfPreviousYear($userId);
                    $previousCount = $previousCount['total_count'];
                } else {
                    $previousCount = $salesHistory[$iM - 2]['total_open_count'];
                }
                $salesHistory[$iM - 1]['trending'] = $previousCount >= $salesHistory[$iM - 1]['total_open_count'] ? '<span style="color: red;font-weight:bold;"><i class="fa fa-arrow-down"></i></span>' : '<span style="color: limegreen;font-weight:bold;"><i class="fa fa-arrow-up"></i></span>';
            }

        }
        $data['salesHistory'] = $salesHistory;
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/sales_dashboard.js?v=' . $this->version));
        $this->salesdashboardtemplate->addCss(base_url('assets/frontend/css/sales-production-history.css?v=' . $this->version));
        //$this->template->show("order", "sales_production_history", $data);
        $this->salesdashboardtemplate->show("order", "sales_production_history", $data);
    }

    public function trends()
    {
        $userdata = $this->session->userdata('user');
        $userId = $this->uri->segment(2);
        $data['sales_user_id'] = $userId;
        $data['is_sales_rep_manager'] = $userdata['is_sales_rep_manager'];

        if ($userdata['is_sales_rep_manager'] == 1) {
            $salesUser = $this->home_model->get_user(array('id' => $userdata['id']));
            if (!empty($salesUser['sales_rep_users'])) {
                $salesRepUsers = explode(',', $salesUser['sales_rep_users']);
                if (!in_array($userdata['id'], $salesRepUsers)) {
                    $salesRepUsers[] = $userdata['id'];
                }
                if (!in_array($userId, $salesRepUsers)) {
                    redirect(base_url() . 'trends/' . $userdata['id']);
                }
                $data['salesUsers'] = $this->order->get_sales_users($salesRepUsers);
            } else {
                $data['salesUsers'] = $this->order->get_sales_users();
            }
        } else {
            if ($userId != $userdata['id']) {
                redirect(base_url() . 'trends/' . $userdata['id']);
            }
            $data['salesUsers'] = array();
        }
        $data['title'] = 'Trends | Pacific Coast Title Company';
        $salesHistory = array();

        for ($year = (int) date('Y'); $year > (int) date('Y') - 2; $year--) {
            $monthLimit = ($year == (int) date('Y')) ? (int) date('m') : 12;

            for ($iM = 1; $iM <= $monthLimit; $iM++) {
                $month = date("m", strtotime("$iM/12/10"));
                $dateObj = DateTime::createFromFormat('!m', $iM);
                $monthName = $dateObj->format('F');
                $salesHistory[$year][$iM - 1]['month'] = $monthName;

                $openRefiResult = $this->order->getOpenOrdersCountForRefiProducts($month, $userId, strval($year));
                $refi_open_count = !empty($openRefiResult['refi_count']) ? $openRefiResult['refi_count'] : 0;
                $openSaleResult = $this->order->getOpenOrdersCountForSaleProducts($month, $userId, strval($year));
                $sale_open_count = !empty($openSaleResult['sale_count']) ? $openSaleResult['sale_count'] : 0;
                $salesHistory[$year][$iM - 1]['total_open_count'] = $sale_open_count + $refi_open_count;

                $closeRefiResult = $this->order->getClosedOrdersCountForRefiProducts($month, $userId, strval($year));
                $refi_close_count = !empty($closeRefiResult['refi_count']) ? $closeRefiResult['refi_count'] : 0;
                $closeSaleResult = $this->order->getClosedOrdersCountForSaleProducts($month, $userId, strval($year));
                $sale_close_count = !empty($closeSaleResult['sale_count']) ? $closeSaleResult['sale_count'] : 0;
                $salesHistory[$year][$iM - 1]['total_close_count'] = $refi_close_count + $sale_close_count;

                $closeOrderRefiTotalPremium = !empty($closeRefiResult['total_premium_for_refi_close_orders']) ? $closeRefiResult['total_premium_for_refi_close_orders'] : 0;
                $refi_total_premium = $closeOrderRefiTotalPremium;
                $closeOrderSaleTotalPremium = !empty($closeSaleResult['total_premium_for_sale_close_orders']) ? $closeSaleResult['total_premium_for_sale_close_orders'] : 0;
                $sale_total_premium = $closeOrderSaleTotalPremium;
                $salesHistory[$year][$iM - 1]['total_premium'] = $sale_total_premium + $refi_total_premium;
            }
        }
        $data['salesHistory'] = $salesHistory;
        $this->salesdashboardtemplate->addJS(base_url('assets/plugins/chart/Chart.min.js'));
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/sales_dashboard.js?v=' . $this->version));
        $this->salesdashboardtemplate->addCss(base_url('assets/frontend/css/sales-trends.css?v=' . $this->version));
        //$this->template->show("order", "sales_trends", $data);
        $this->salesdashboardtemplate->show("order", "sales_trends", $data);
    }

    public function summary()
    {
        $userdata = $this->session->userdata('user');
        $userId = $this->uri->segment(2);
        $data['sales_user_id'] = $userId;
        $data['is_sales_rep_manager'] = $userdata['is_sales_rep_manager'];

        if ($userdata['is_sales_rep_manager'] == 1) {
            $salesUser = $this->home_model->get_user(array('id' => $userdata['id']));
            if (!empty($salesUser['sales_rep_users'])) {
                $salesRepUsers = explode(',', $salesUser['sales_rep_users']);
                if (!in_array($userdata['id'], $salesRepUsers)) {
                    $salesRepUsers[] = $userdata['id'];
                }
                if (!in_array($userId, $salesRepUsers)) {
                    redirect(base_url() . 'sales-summary/' . $userdata['id']);
                }
                $data['salesUsers'] = $this->order->get_sales_users($salesRepUsers);
            } else {
                $data['salesUsers'] = $this->order->get_sales_users();
            }
        } else {
            if ($userId != $userdata['id']) {
                redirect(base_url() . 'sales-summary/' . $userdata['id']);
            }
            $data['salesUsers'] = array();
        }

        $result = $this->salesRep_model->getSummaryDetailsForSalesRep($userId);
        if (!empty($result)) {

            $data['summary_info'] = array();
            $i = 0;
            $j = 0;
            $companyName = '';
            $position = 0;
            $num_of_company_deals = 0;

            foreach ($result as $res) {
                if ($res['company_name'] == $companyName) {
                    $data['summary_info'][$i]['company_id'] = $j;
                    $data['summary_info'][$i]['sales_name'] = $res['sales_name'];
                    $data['summary_info'][$i]['company_name'] = $companyName;
                    $data['summary_info'][$i]['name'] = $res['name'];
                    $data['summary_info'][$i]['num_of_deals'] = $res['num_of_deals'];
                    $data['summary_info'][$i]['parent_id'] = $j;
                    $num_of_company_deals += $res['num_of_deals'];
                    $i++;
                } else {
                    $data['summary_info'][$position]['num_of_deals'] = $num_of_company_deals;
                    $num_of_company_deals = 0;
                    $j++;
                    $position = $i;
                    $companyName = $res['company_name'];
                    $data['summary_info'][$i]['company_id'] = $j;
                    $data['summary_info'][$i]['sales_name'] = $res['sales_name'];
                    $data['summary_info'][$i]['company_name'] = $companyName;
                    $data['summary_info'][$i]['name'] = $res['name'];
                    $data['summary_info'][$i]['num_of_deals'] = $res['num_of_deals'];
                    $data['summary_info'][$i]['parent_id'] = 0;
                    $i++;

                    $data['summary_info'][$i]['company_id'] = $j;
                    $data['summary_info'][$i]['sales_name'] = $res['sales_name'];
                    $data['summary_info'][$i]['company_name'] = $companyName;
                    $data['summary_info'][$i]['name'] = $res['name'];
                    $data['summary_info'][$i]['num_of_deals'] = $res['num_of_deals'];
                    $data['summary_info'][$i]['parent_id'] = $j;
                    $num_of_company_deals += $res['num_of_deals'];
                    $i++;
                }
            }
        }
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/sales_dashboard.js?v=' . $this->version));
        $this->salesdashboardtemplate->addCss(base_url('assets/frontend/css/escrow_tasks.css?v=' . $this->version));
        $this->salesdashboardtemplate->addCss(base_url('assets/frontend/css/sales-summary.css?v=' . $this->version));
        //$this->template->show("order", "sales_summary", $data);
        $this->salesdashboardtemplate->show("order", "sales_summary", $data);
    }

    public function commission($userId)
    {
        $userdata = $this->session->userdata('user');
        // $userId = $this->uri->segment(2);
        $data['sales_user_id'] = $userId;
        $data['is_sales_rep_manager'] = $userdata['is_sales_rep_manager'];
        // if ($userdata['is_sales_rep_manager'] == 1) {
        //     //echo "hehe";exit;
        //     $salesUser =  $this->home_model->get_user(array('id' => $userdata['id']));
        //     if (!empty($salesUser['sales_rep_users'])) {
        //         $salesRepUsers = explode(',', $salesUser['sales_rep_users']);
        //         if (!in_array($userdata['id'], $salesRepUsers)) {
        //             $salesRepUsers[] = $userdata['id'];
        //         }
        //         if (!in_array($userId, $salesRepUsers)) {
        //             redirect(base_url().'sales-commission/'.$userdata['id']);
        //         }
        //         $data['salesUsers'] = $this->order->get_sales_users($salesRepUsers);
        //     } else {
        //         $data['salesUsers'] = $this->order->get_sales_users();
        //     }
        // } else {
        //     if ($userId != $userdata['id']) {
        //         redirect(base_url().'sales-commission/'.$userdata['id']);
        //     }
        //     $data['salesUsers'] = array();
        // }
        $data['salesUsers'] = array();
        $data['title'] = 'Sales Production History | Pacific Coast Title Company';
        $commissionHistory = array();
        $current_year = date('Y');
        $this->load->model('admin/order/user_monthly_commission_model');
        for ($iM = 1; $iM <= (int) date('m'); $iM++) {
            $dateObj = DateTime::createFromFormat('!m', $iM);
            $monthName = $dateObj->format('F');
            $commissionHistory[$iM - 1]['month'] = $monthName;
            $commissionHistory[$iM - 1]['month_num'] = $iM;
            $get_month_conditon = [
                'user_id' => $userId,
                'commission_year' => $current_year,
                'commission_month' => $iM,
            ];
            $commisson_data = $this->user_monthly_commission_model->get_by($get_month_conditon);
            if ($iM == date('m') && (!($commisson_data) || empty($commisson_data->commission))) {
                //Call procedure
                $stored_pocedure = "CALL calculate_commission(?)";
                $this->user_monthly_commission_model->call_sp($stored_pocedure, array('id' => $userId));
                $commisson_data = $this->user_monthly_commission_model->get_by($get_month_conditon);
            }
            $commissionHistory[$iM - 1]['commission_data'] = $commisson_data;

        }
        $data['commissionHistory'] = $commissionHistory;
        $this->template->addJS(base_url('assets/frontend/js/order/sales_dashboard.js?v=' . $this->version));
        $this->template->show("order", "sales_commission_history", $data);
    }

    public function salesCurrentMonthSummary()
    {
        $userdata = $this->session->userdata('user');
        $data['title'] = 'Sales Production History | Pacific Coast Title Company';
        $data['salesHistory'] = array();
        if ($userdata['is_sales_rep_manager'] == 1) {

            $salesUser = $this->home_model->get_user(array('id' => $userdata['id'], 'status' => 1));
            if (!empty($salesUser['sales_rep_users'])) {
                $salesRepUsers = explode(',', $salesUser['sales_rep_users']);
                if (!in_array($userdata['id'], $salesRepUsers)) {
                    $salesRepUsers[] = $userdata['id'];
                }

                $salesUsers = $this->order->get_sales_users($salesRepUsers);
            } else {
                $salesUsers = $this->order->get_sales_users();
            }

            $i = 0;
            if (!empty($salesUsers)) {
                foreach ($salesUsers as $salesrep) {
                    $data['salesHistory'][$i]['sales_rep'] = $salesrep['first_name'] . " " . $salesrep['last_name'];
                    $openRefiResult = $this->order->getOpenOrdersCountForRefiProducts(date('m'), $salesrep['id']);
                    $refi_open_count = !empty($openRefiResult['refi_count']) ? $openRefiResult['refi_count'] : 0;
                    $openSaleResult = $this->order->getOpenOrdersCountForSaleProducts(date('m'), $salesrep['id']);
                    $sale_open_count = !empty($openSaleResult['sale_count']) ? $openSaleResult['sale_count'] : 0;
                    $data['salesHistory'][$i]['total_open_count'] = $sale_open_count + $refi_open_count;

                    $closeRefiResult = $this->order->getClosedOrdersCountForRefiProducts(date('m'), $salesrep['id']);
                    $refi_close_count = !empty($closeRefiResult['refi_count']) ? $closeRefiResult['refi_count'] : 0;
                    $closeSaleResult = $this->order->getClosedOrdersCountForSaleProducts(date('m'), $salesrep['id']);
                    $sale_close_count = !empty($closeSaleResult['sale_count']) ? $closeSaleResult['sale_count'] : 0;
                    $data['salesHistory'][$i]['total_close_count'] = $refi_close_count + $sale_close_count;

                    $openOrderRefiTotalPremium = !empty($openRefiResult['total_premium_for_refi_open_orders']) ? $openRefiResult['total_premium_for_refi_open_orders'] : 0;
                    $closeOrderRefiTotalPremium = !empty($closeRefiResult['total_premium_for_refi_close_orders']) ? $closeRefiResult['total_premium_for_refi_close_orders'] : 0;
                    //$refi_total_premium = $openOrderRefiTotalPremium + $closeOrderRefiTotalPremium;
                    $refi_total_premium = $closeOrderRefiTotalPremium;
                    $openOrderSaleTotalPremium = !empty($openSaleResult['total_premium_for_sale_open_orders']) ? $openSaleResult['total_premium_for_sale_open_orders'] : 0;
                    $closeOrderSaleTotalPremium = !empty($closeSaleResult['total_premium_for_sale_close_orders']) ? $closeSaleResult['total_premium_for_sale_close_orders'] : 0;
                    //$sale_total_premium = $openOrderSaleTotalPremium + $closeOrderSaleTotalPremium;
                    $sale_total_premium = $closeOrderSaleTotalPremium;
                    $data['salesHistory'][$i]['total_premium'] = $sale_total_premium + $refi_total_premium;
                    $i++;
                }
            }
            $this->salesdashboardtemplate->show("order", "sales_current_month_production_history", $data);
            // $this->template->show("order", "sales_current_month_production_history", $data);
        } else {
            redirect(base_url() . 'sales-dashboard/' . $userdata['id']);
        }
    }

    public function salesReports()
    {
        $this->load->model('salesReport_model');
        $userdata = $this->session->userdata('user');
        $userId = $this->uri->segment(2);

        $data['title'] = 'Sales Reports | Pacific Coast Title Company';
        if ($userdata['is_sales_rep'] == 1) {

            $data['sales_user_id'] = $userId;
            // $data['is_sales_rep'] = $userdata['is_sales_rep'];

            $report_condition = array(
                'sales_rep' => $userId,
            );

            $data['reports_data'] = $this->salesReport_model->getSalesAllReportData($report_condition);

            $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/report.js?v=' . $this->version));
            $this->salesdashboardtemplate->show("order", "sales_report", $data);
        } else {
            redirect(base_url() . 'sales-dashboard/' . $userId);
        }
    }
    public function getRevenueData()
    {
        $sales_rep_id = $this->input->post('sales_rep_id');
        $revenueData = $this->order->getRevenueData($this->input->post('month') ? $this->input->post('month') : date('m'), $sales_rep_id);
        $data = "<table class='table table-bordered' id='tbl-lp-orders-listing' width='100%' cellspacing='0'>
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>File Number</th>
                    <th>Address</th>
                    <th>Prod Type</th>
                    <th>Revenue</th>
                </tr>
            </thead>
        <tbody>";

        $i = 1;
        if (!empty($revenueData)) {
            foreach ($revenueData as $revenue) {
                $file_number = $revenue['file_number'];
                $full_address = $revenue['full_address'];
                $prod_type = $revenue['prod_type'];
                $revenue = '$' . number_format($revenue['premium']);
                $data .= "<tr>
                                <td width='12%'>$i</td>
                                <td width='12%'>$file_number</td>
                                <td width='52%'>$full_address</td>
                                <td width='12%'>$prod_type</td>
                                <td width='12%'>$revenue</td>
                            </tr>";
                $i++;
            }
        } else {
            $data .= "<tr><td colspan='5'>No records found.</td></tr>";
        }
        $data .= '</tbody></table>';
        if (!empty($data)) {
            $result = array('status' => 'success', 'data' => $data);
        } else {
            $result = array('status' => 'error', 'data' => $data);
        }
        echo json_encode($result);
        exit;
    }
}
