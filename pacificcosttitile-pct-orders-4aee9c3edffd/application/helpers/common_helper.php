<?php
if (!function_exists('checkRemoteFile')) {
    function checkRemoteFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        if ($result !== false) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('separateZipRoute')) {
    function separateZipRoute($mixedVal, $zip)
    {
        $mixedVal = trim($mixedVal);
        $zip = trim($zip);
        $position = strpos($mixedVal, $zip);
        $returned_str = $mixedVal;
        if ($position !== false) {
            $returned_str = substr_replace($mixedVal, '-', strlen($zip), 0);
        } elseif (strlen($mixedVal) > 5) {
            $returned_str = substr_replace($mixedVal, '-', -4, 0);
        }
        return $returned_str;

    }
}
if (!function_exists('convertTimezone')) {
    function convertTimezone($dateTime, $format = 'm/d/Y h:i:s A')
    {
        $default_timezone = $to_timezone = 'America/Los_Angeles';
        $to_timezone = 'America/Los_Angele';
        if (!empty($_COOKIE['user_timezone'])) {
            $to_timezone = $_COOKIE['user_timezone'];
        }
        $date = new DateTime($dateTime);
        try {
            $date->setTimezone(new DateTimeZone($to_timezone));
        } catch (\Throwable $th) {
            $date->setTimezone(new DateTimeZone($default_timezone));
        }
        return $date->format($format);
    }
}
if (!function_exists('getUserName')) {
    function getUserName($id)
    {

        $CI = get_instance();
        $CI->load->model('admin/order/customer_basic_details_model');
        $user = $CI->customer_basic_details_model->get($id);
        if ($user) {
            return $user->first_name . ' ' . $user->last_name;
        } else {
            return '';
        }
    }
}
if (!function_exists('getExtraCommission')) {
    function getExtraCommission($per_array, $conditon)
    {
        $commission_array = [
            'loan' => 0,
            'sale' => 0,
            'escrow' => ['loan' => 0, 'sale' => 0],
        ];
        $loan_total = $sale_total = $loan_escrow_total = $sale_escrow_total = 0;
        $CI = get_instance();
        $CI->load->model('admin/order/user_monthly_commission_model');
        $commission_obj = $CI->user_monthly_commission_model->get_by($conditon);
        if ($commission_obj) {
            $prod_array = PRODUCT_TYPE;
            $underwriter_array = UNDERWRITERS;
            $underwriter_array['escrow'] = 'Escrow';
            foreach ($prod_array as $prod) {
                foreach ($underwriter_array as $und_key => $underwriter) {
                    $details_arr[$prod][$und_key] = 0;
                }
            }

            $details_json = $commission_obj->commission_details;

            if (!empty($details_json) && json_decode($details_json)) {

                $details = json_decode($details_json);
                foreach ($details as $detail_json) {
                    $detail = json_decode($detail_json);
                    $prod_type = $detail->prod_type;
                    $underwriter = $detail->underwriter;
                    if (in_array($prod_type, $prod_array)) {

                        if ($underwriter == 'escrow' && $prod_type == 'loan') {
                            $loan_escrow_total += $detail->commisison;
                        } elseif ($underwriter == 'escrow' && $prod_type == 'sale') {
                            $sale_escrow_total += $detail->commisison;
                        } elseif ($prod_type == 'sale') {
                            $sale_total += $detail->commisison;
                        } elseif ($prod_type == 'loan') {
                            $loan_total += $detail->commisison;
                        }
                    }

                }
            }
        }

        if ($loan_escrow_total > 0 && $per_array['escrow'] > 0) {
            $commission = ($loan_escrow_total * $per_array['escrow']) / 100;
            $commission_array['escrow']['loan'] = $commission;
        }
        if ($sale_escrow_total > 0 && $per_array['escrow'] > 0) {
            $commission = ($sale_escrow_total * $per_array['escrow']) / 100;
            $commission_array['escrow']['sale'] = $commission;
        }
        if ($loan_total > 0 && $per_array['loan'] > 0) {
            $commission = ($loan_total * $per_array['loan']) / 100;
            $commission_array['loan'] = $commission;
        }
        if ($sale_total > 0 && $per_array['sale'] > 0) {
            $commission = ($sale_total * $per_array['sale']) / 100;
            $commission_array['sale'] = $commission;
        }

        return $commission_array;

    }

    if (!function_exists('removeMultipleSpace')) {
        function removeMultipleSpace($str)
        {
            return preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $str);
        }
    }
}
