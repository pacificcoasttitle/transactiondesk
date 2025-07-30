<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template
{
    private $data;
    private $js_file;
    private $css_file;
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
        $this->addJS( base_url('assets/libs/jquery-1.12.4.min.js') );
        $this->addJS( base_url('assets/libs/jquery-migrate-1.2.1.js') );
        $this->addJS( base_url('assets/libs/bootstrap/bootstrap.min.js') );
        $this->addJS( base_url('assets/plugins/bootstrap-select/js/bootstrap-select.js') );
        $this->addJS( base_url('assets/plugins/owl-carousel/owl.carousel.min.js') );
        $this->addJS( base_url('assets/plugins/magnific-popup/jquery.magnific-popup.min.js') );
        $this->addJS( base_url('assets/plugins/headers/slidebar.js') );
        $this->addJS( base_url('assets/plugins/headers/header.js') );
        $this->addJS( base_url('assets/plugins/jqBootstrapValidation.js') );
        $this->addJS( base_url('assets/plugins/flowplayer/flowplayer.min.js') );
        $this->addJS( base_url('assets/plugins/isotope/isotope.pkgd.min.js') );
        $this->addJS( base_url('assets/plugins/isotope/imagesLoaded.js') );
        $this->addJS( base_url('assets/plugins/rendro-easy-pie-chart/jquery.easypiechart.min.js') );
        $this->addJS( base_url('assets/plugins/rendro-easy-pie-chart/waypoints.min.js') );
        $this->addJS( base_url('assets/plugins/scrollreveal/scrollreveal.min.js') );
        $this->addJS( base_url('assets/plugins/revealer/js/anime.min.js') );
        $this->addJS( base_url('assets/plugins/revealer/js/scrollMonitor.js') );
        $this->addJS( base_url('assets/plugins/revealer/js/main.js') );
        $this->addJS( base_url('assets/plugins/animate/wow.min.js') );
        $this->addJS( base_url('assets/plugins/animate/jquery.shuffleLetters.js') );
        $this->addJS( base_url('assets/plugins/animate/jquery.scrollme.min.js') );
        $this->addJS( base_url('assets/js/custom.js') );
        $this->addJS( base_url('assets/frontend/js/jquery.form.min.js'));
        $this->addJS( base_url('assets/frontend/js/jquery.validate.min.js'));
        $this->addJS( base_url('assets/frontend/js/jquery-ui.min.js'));
        $this->addJS( base_url('assets/vendor/datatables/jquery.dataTables.min.js'));
        $this->addJS( base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'));

        if ($this->CI->uri->segment(1) == 'order' && empty($this->CI->uri->segment(2))) {
            $this->addCSS( base_url('assets/frontend/css/custom.css'));
        }
        $this->addCSS( base_url('assets/css/master.css') );
        $this->addCSS( base_url('assets/frontend/css/smart-forms.css?v=smart_02'));
        $this->addCSS( base_url('assets/frontend/css/font-awesome.min.css'));
        $this->addCSS( base_url('assets/frontend/css/jquery-ui.css'));
        $this->addCSS( base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css'));
    }

    public function show($folder, $page, $data=null)
    {
        if ( ! file_exists('application/modules/frontend/views/'.$folder.'/'.$page.'.php' ) ) {
            show_404();
        } else {
            $this->load_JS_and_css();
            $data['notifications'] = $this->getNotifications(5);
            $data['unreadNotificationCount'] = $this->getUnreadNotificationCount(5);
            $this->data['header'] = $this->CI->load->view('order/layout/header.php', $data, true);
            $this->data['content'] = $this->CI->load->view($folder.'/'.$page.'.php', $data, true);
            $this->data['footer'] = $this->CI->load->view('order/layout/footer.php', $data, true);
            $this->CI->load->view('order/template.php', $this->data);
        }
    }

    public function addJS( $name )
    {
        $js = new stdClass();
        $js->file = $name;
        $this->js_file[] = $js;
    }

    public function addCSS( $name )
    {
        $css = new stdClass();
        $css->file = $name;
        $this->css_file[] = $css;
    }

    private function load_JS_and_css()
    {
        $this->data['css_files'] = '';
        $this->data['js_files'] = '';

        if ( $this->css_file ) {
            foreach( $this->css_file as $css ) {
                $this->data['css_files'] .= "<link rel='stylesheet' type='text/css' href=".$css->file.">". "\n";
            }
        }

        if ( $this->js_file ) {
            foreach( $this->js_file as $js ) {
                $this->data['js_files'] .= "<script type='text/javascript' src=".$js->file."></script>". "\n";
            }
        }
    }

    public function getNotifications($limit) 
    {
        $userdata = $this->CI->session->userdata('user');
        $this->CI->db->select('*');
        $this->CI->db->where('sent_user_id', $userdata['id']);
        $this->CI->db->where('is_read', 0);
        $query = $this->CI->db->get('pct_order_notifications');
        $this->CI->db->order_by('pct_order_notifications.id', 'desc');
        //$this->CI->db->limit($limit);  
        if ($query->num_rows() > 0)  {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getUnreadNotificationCount() 
    {
        $userdata = $this->CI->session->userdata('user');
        $this->CI->db->select('count(*) as total_unread_count');
        $this->CI->db->where('sent_user_id', $userdata['id']);
        $this->CI->db->where('is_read', 0);
        $query = $this->CI->db->get('pct_order_notifications');
        if ($query->num_rows() > 0)  {
            return $query->row_array();
        } else {
            return array();
        }
    }
}
