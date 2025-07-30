<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends MX_Controller
{
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *     http://example.com/index.php/welcome
     * - or -
     *     http://example.com/index.php/welcome/index
     * - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('email');
        $this->load->library('session');
        $this->load->model('calc/welcome_model');
    }
    
    
    public function index()
    {
        $data['active']      = 'homepage';
        $data['state_list']  = $this->welcome_model->get_state_list();
        $data['county_list'] = $this->welcome_model->get_county_list();
        $data['city_list']   = $this->welcome_model->get_city_list();
        $data['title']       = 'Rate Calculator Report | Pacific Coast Title Company';
        $this->load->view('layout/head_calc', $data);
        $this->load->view('calc/index', $data);
    }
    
    
    public function calculate_title_rate($post)
    {
        $row                    = $this->welcome_model->get_title_rate($post);
        $residential_owner_rate = $row->owner_rate;
        $home_owner_rate        = $row->home_owner_rate;
        $alta_lenders_rate      = $row->con_loan_rate;
        $residential_loan       = $row->resi_loan_rate;
        $purchase_rate          = 0;
        $lender                 = 0;
        if ($post['transaction_type'] == 'Resale') {
            
            if ($post['policy_type'] == 'Regular') {
                $purchase_rate = $residential_owner_rate;
                $lender        = $alta_lenders_rate;
            } else if ($post['policy_type'] == 'Extended') {
                $purchase_rate = $home_owner_rate;
                $lender        = $alta_lenders_rate;
            }
        } else if ($post['transaction_type'] == 'Re-Finance') {
            $purchase_rate = $residential_loan;
        }
        if ($post['escrow-rates'] == 1) {
            $this->calculate_escrow_fee($post);
        }
    }
    
    public function endorsement_options($txn_type)
    {
        $endo = $this->welcome_model->get_endorsement_options($txn_type);
        $html = "";
        foreach ($endo as $key) {
            if ($key->is_default == "Y") {
                
                $html .= '<div class="clearfix endo_' . $key->txn_type . '">
                <input class="checkbox-custom check_box' . $key->txn_type . ' default" name="endo_sel[]" checked="true" type="checkbox" value="' . $key->endorse_fee_id_fk . '" onclick="return false;">
                <label class="checkbox-custom-label">' . $key->endorse_name . '</label>
            </div>';
            } else {
                $html .= '<div class="clearfix endo_' . $key->txn_type . '">
                <input class="checkbox-custom check_box' . $key->txn_type . '" name="endo_sel[]" type="checkbox" value="' . $key->endorse_fee_id_fk . '" >
                <label class="checkbox-custom-label">' . $key->endorse_name . '</label>
            </div>';
            }
        }   
        print_r($html);
    }
    
    public function calculate_escrow_fee($post)
    {
        if ($post['transaction_type'] == 'Resale') {
            $this->calculate_escrow_fee_resale($post);
        } else if ($post['transaction_type'] == 'Re-Finance') {
            $this->calculate_escrow_fee_refinance($post);
        }
    }
    
    public function calculate_escrow_fee_resale($post)
    {
        
        $rate = $this->welcome_model->calculate_escrow_fee_resale($post);
        $pay = $rate->base_rate + $post['transaction_amount'] * $rate->rate_per_1k;
        if ($rate->multi_factor == 1) {
            if ($pay < $rate->min_rate) {
                $pay_up = $rate->min_rate;
            } else {
                $pay_up = $pay;
            }
        } else {
            $pay    = str_replace('loan_amount', $post['transaction_amount'], $rate->formula);
            $Cal    = new Field_calculate();
            $pay_up = $Cal->calculate($pay);
            
        }
    }
    
    
    public function calculate_escrow_fee_refinance($post)
    {
        $rate = $this->welcome_model->calculate_escrow_fee_refinance($post);
    }

    public function receipt()
    {
        if ($_POST['region']) {
            $quote_id = $this->welcome_model->generate_quote();
        }
        redirect('calculator/view_quote/' . $quote_id, 'refresh');
    }
    
    public function view_quote()
    {   
        if(isset($_POST) && !empty($_POST)) {
            redirect('calculator/view_quote/' . $_POST['quote_id'], 'refresh');
        } else if ($this->uri->segment(3)) {
            $quote_id = $this->uri->segment(3);
            $data['quote_detail']   = $this->welcome_model->get_quote_detail($quote_id);
            $data['closing_detail'] = $this->welcome_model->get_county_detail($data['quote_detail']->closing_county);
            $data['user_detail']    = $this->welcome_model->get_user_detail($this->session->userdata('mpuserid'));
            $data['title']       = 'Rate Calculator Report | Pacific Coast Title Company';
            $this->load->view('layout/head_calc', $data);
            $this->load->view('calc/recipt', $data); 
        } else  {
            redirect('/', 'refresh');
        }
        
    }
    
    
    
    
    public function email_quote()
    {
        //print_r($_REQUEST);
        $this->load->library('email');
        $config = array(
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'priority' => '1'
        );
        $this->email->initialize($config);
        $this->email->from('sales@PCT247.com', 'Name');
        $this->email->to($_REQUEST['email']);
        
        $this->email->subject('Title & Escrow Rate Calculator');
        $this->email->message($_REQUEST['quote']);
        
        $this->email->send();
        
    }
    
    public function error()
    {
        
        $data['active'] = 'error';
        $this->load->view('front/header', $data);
        $this->load->view('front/error', $data);
        $this->load->view('front/sidebar', $data);
        $this->load->view('front/footer', $data);
    }
    
    
    
    
    public function signup($value = '')
    {
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            
            $checkemail = $this->welcome_model->check_email_exist($this->input->post('email'));
            if ($checkemail) {
                $data['msg'] = "This email id is already registered with us.";
            } else {
                
                $unique = uniqid();
                $insert = array(
                    'membership_id_fk' => "1",
                    'email' => $this->input->post('email'),
                    'p_word' => md5($this->input->post('password')),
                    'first_name' => $this->input->post('fname'),
                    'last_name' => $this->input->post('lname'),
                    'rep_key' => $this->input->post('rep'),
                    'created' => date("Y-m-d h:i:s"),
                    'modified' => date("Y-m-d h:i:s"),
                    'is_active' => 1,
                    'is_email_confirmed' => 1,
                    'verification' => $unique
                );
                $id     = $this->welcome_model->insert_user($insert);
                
                if ($id) {
                    
                    $config = array(
                        'mailtype' => 'html',
                        'charset' => 'utf-8',
                        'priority' => '1'
                    );
                    $this->email->initialize($config);
                    $this->email->from('info@PCT247.com', 'Pacific Coast Title');
                    $this->email->to($_POST['email']);
                    $this->email->subject('Welcome to Pacific Coast Title!');
                    $message = '
          <!doctype html>
          <html>
            <head>
              <meta charset="utf-8">
              <title>Pacific Coast Title</title>
            </head>
            <body>
              <table width="800" border="0" style="margin:0 auto;">
                
                <tr>
                  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <img src="' . base_url() . 'assets/front/images/logo.png" height="45"><br>
                    <p style="font-size:15px; color:#5b5a5a;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Thank you for your interest in Pacific Coast Title.com<br>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Please <a  href="' . base_url() . 'index.php/?welcome/verify/' . $id . '/' . $unique . '" target="_blank">VERIFY </a> your registration</b></p></td>
                      
                    </tr>
                    <tr style="border:none;">
                      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<p style="margin-top: -17px;font-size:15px; color:#5b5a5a;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In case the verify tab does not work,<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;please copy and paste this URL into your browser<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="font-size:12px;" href="' . base_url() . 'index.php/?welcome/verify/' . $id . '/' . $unique . '" target="_blank">' . base_url() . 'index.php/welcome/verify/' . $id . '/' . $unique . '</a></p>
                      </td>
                    </tr>
                    <tr style="border:none;">
                      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<p style="font-size:15px; color:#333">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Pacific Coast Title Team</strong><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="font-size:15px; " href="http://www.pacificcoasttitle.com" target="_blank">www.pacificcoasttitle.com</a></p><br>
                        <p style="font-size:10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is an automatically generated email. Please do not reply</p>
                      </td>
                    </tr>
                  </table>
                </body>
              </html>
              ';
                    $this->email->message($message);
                    $this->email->send();
                }
                
                
                $this->session->set_flashdata('msg', 'You are registered Successfully,Please login with your Email and password.');
                redirect('calculator', 'refresh');
            }
        }
        
        
        $data['rep'] = $this->welcome_model->get_reps_data();
        $data['title']       = 'Rate Calculator Report | Pacific Coast Title Company';
        $this->load->view('layout/head_calc', $data);
        $this->load->view('calc/signup', $data); 
    }
    
    public function check_availability_email()
    {
        if ($this->welcome_model->check_email_exist($_GET['email'])) {
            print("0");
        } else {
            print("1");
        }
    }
    
    
    public function verify($user_id = "", $unique = "")
    {
        $data['msg'] = "";
        $user_type   = "user";
        $verify      = $this->welcome_model->verify_registration($user_id, $unique);
        if ($verify->is_active == "1") {
            $this->session->set_flashdata('msg', "Your account has already been verified. Please Log in to continue.");
        } else {
            $verified = $this->welcome_model->update_verfication($user_id, $unique);
            if ($verified) {
                $this->session->set_flashdata('msg', "Thanks for registering at Pacific Coast Title. Your account has been activated.");
                $message2 = '<!doctype html>
  <html>
    <head>
      <meta charset="utf-8">
      <title>Pacific Coast Title</title>
    </head>
    <body>
      <table width="800" border="0" style="padding-left: 30px;margin:0 auto; border:2px groove #333333;">
        
        <tr>
          <td >  <img src="' . base_url() . 'assets/front/images/logo.png" height="60">
            <p style="font-size:24px; color:#5b5a5a;"> Dear ' . ucfirst($verify->first_name) . ' ' . ucfirst($verify->last_name) . '<br>Thank you for your interest in Pacific Coast Title. Your account has been verified.
              
            </p>
            
          </tr>
          <tr style="border:none;">
            <td><p style="font-size:24px; color:#5b5a5a;">We thank you for your participation. <br><br>
            Pacific Coast Title Team</p>
          </td>
        </tr>
        <tr style="border:none;">
          <td><p style="font-size:18px; color:#5b5a5a;">Got queries?  Write to  us at info@PCT247.com
          </p>
        </td>
      </tr>
      <tr style="border:none;">
        <td><p style="font-size:10px; color:#333">This message (including any attachments) may contain confidential, proprietary, privileged and/or private information. The information is intended to be for the use of the individual or entity designated above. If you are not the intended recipient of this message, please notify the sender immediately, and delete the  message and any attachments. Any disclosure, reproduction, distribution or other use of this message or any attachments by an individual or entity other than the intended recipient is prohibited.
        </p>
      </td>
    </tr>
  </table>
</body>
</html>
';
                $config   = array(
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'priority' => '1'
                );
                $this->email->initialize($config);
                $this->email->from('info@PCT247.com', 'Pacific Coast Title');
                $this->email->to($verify->email);
                $this->email->subject('Account Activated.');
                $this->email->message($message2);
                $this->email->send();
                redirect('welcome/signup', 'refresh');
            }
        }
        redirect('welcome/signup', 'refresh');
    }
    
    
    public function login_user()
    {
        $data['msg'] = "";
        if (isset($_POST['email'])) {
            $email    = $this->input->post('email');
            $password = $this->input->post('password');
            $user     = $this->welcome_model->get_login_user($email, $password);
            //print_r($user);
            if ($user) {
                
                if ($user->is_active == '1') {
                    $userSessionData = array(
                        "mpuserid" => $user->user_id_pk,
                        "mpusername" => $user->first_name,
                        "mpuseremail" => $user->email,
                        "mpusertype" => $user->membership_id_fk,
                        "mpusermembership" => $user->membership
                    );
                    $this->session->set_userdata($userSessionData);
                    if ($this->input->is_ajax_request()) {
                        print_r("1,user");
                        die;
                    } else {
                        redirect('welcome/dashboard');
                    }
                } elseif ($user->status == '0' && $user->email_verification == '1') {
                    if ($this->input->is_ajax_request()) {
                        print_r("##");
                        die;
                    } else {
                        $data['msg'] = "Email not verified";
                    }
                } elseif ($user->status == '0') {
                    if ($this->input->is_ajax_request()) {
                        print_r("#");
                        die;
                    } else {
                        $data['msg'] = "Email not verified";
                    }
                }
            } else {
                if ($this->input->is_ajax_request()) {
                    print_r("0");
                    die;
                } else {
                    $data['msg'] = "Incorrect email or password";
                }
            }
        }
        //redirect('welcome/dashboard');
    }
    
    
    public function forget_password()
    {
        
        if (isset($_POST['email'])) {
            if ($this->welcome_model->check_email_exist($_POST['email'])) {
                $pass  = mt_rand(111111, 999999);
                $email = $_POST['email'];
                //$this->welcome_model->reset_password($_POST['email'],$pass);
                $this->welcome_model->reset_verification($_POST['email'], $pass);
                $this->email->from('info@PCT247.com', 'PCT Password reset');
                $this->email->to($email);
                $this->email->subject('Welcome to PCT');
                $this->email->message('Hi ' . "\n" . "\n" . 'You have requested for new password on PCT.' . "\n" . "\n" . 'Here is your login details' . "\n" . 'your email id : ' . $_POST['email'] . "\n" . 'Verification Code : ' . $pass . "\n" . "\n" . 'PCT Team' . "\n" . 'http://www.pacificcoasttitle.com' . "\n" . "\n" . "\n" . 'This is an automatically generated email. Please do not reply');
                $this->email->send();
                print_r(0);
            } else {
                print_r(1);
                
            }
        }
    }
    
    
    
    public function reset_password()
    {
        
        if (isset($_POST['email'])) {
            if ($this->welcome_model->check_email_exist($_POST['email'])) {
                $pass  = $_POST['password'];
                $email = $_POST['email'];
                $stat  = $this->welcome_model->reset_password($_POST['email'], $pass);
                if ($stat) {
                    $this->email->from('info@PCT247.com', 'PCT Password reset');
                    $this->email->to($email);
                    $this->email->subject('Welcome to PCT');
                    $this->email->message('Hi ' . "\n" . "\n" . 'You have requested for new password on PCT.' . "\n" . "\n" . 'Here is your New login details' . "\n" . 'your email id : ' . $_POST['email'] . "\n" . 'Password : ' . $pass . "\n" . "\n" . 'PCT Team' . "\n" . 'http://www.pacificcoasttitle.com' . "\n" . "\n" . "\n" . 'This is an automatically generated email. Please do not reply');
                    $this->email->send();
                    print_r(0);
                } else {
                    print_r(2);
                }
                
            } else {
                print_r(1);
                
            }
        }
    }
    
    
    
    
    public function logout()
    {
        $this->session->unset_userdata('mpuserid');
        $this->session->unset_userdata('mpuseremail');
        $this->session->unset_userdata('mpusername');
        $this->session->unset_userdata('mpusertype');
        session_destroy();
        redirect(base_url()."calculator");
    }
    
    
    
    public function admin_login()
    {
        $data['title']       = 'Rate Calculator Report | Pacific Coast Title Company';
        $this->load->view('layout/head_calc', $data);
        $this->load->view('calc/admin_login', $data);
    }
    
    function get_cities()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        $county_id = $this->input->post('zone_id');
        $this->db->where('zone_name', $county_id);
        $cities = $this->welcome_model->get_city_list();
        $out    = '  <option value="" class="city_null">Select City</option>';
        foreach ($cities as $city) {
            $out .= '<option value="' . $city->county_id_pk . '" class="region' . str_replace(' ', '_', $city->zone_name) . '">' . $city->county_name . '</option>';
        }
        echo $out;
        die;
    }
}

class Field_calculate
{
    const PATTERN = '/(?:\-?\d+(?:\.?\d+)?[\+\-\*\/])+\-?\d+(?:\.?\d+)?/';
    
    const PARENTHESIS_DEPTH = 10;
    
    public function calculate($input)
    {
        if (strpos($input, '+') != null || strpos($input, '-') != null || strpos($input, '/') != null || strpos($input, '*') != null) {
            //  Remove white spaces and invalid math chars
            $input = str_replace(',', '.', $input);
            $input = preg_replace('[^0-9\.\+\-\*\/\(\)]', '', $input);
            
            //  Calculate each of the parenthesis from the top
            $i = 0;
            while (strpos($input, '(') || strpos($input, ')')) {
                $input = preg_replace_callback('/\(([^\(\)]+)\)/', 'self::callback', $input);
                
                $i++;
                if ($i > self::PARENTHESIS_DEPTH) {
                    break;
                }
            }
            
            //  Calculate the result
            if (preg_match(self::PATTERN, $input, $match)) {
                return $this->compute($match[0]);
            }
            
            return 0;
        }
        
        return $input;
    }
    
    private function compute($input)
    {
        $compute = create_function('', 'return ' . $input . ';');
        
        return 0 + $compute();
    }
    
    private function callback($input)
    {
        if (is_numeric($input[1])) {
            return $input[1];
        } elseif (preg_match(self::PATTERN, $input[1], $match)) {
            return $this->compute($match[0]);
        }
        
        return 0;
    }    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */