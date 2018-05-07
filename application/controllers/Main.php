<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends FRONT_Controller {

    public $meta_description;
    public $meta_keywords;

    public function __construct() {
        parent::__construct();
        $this->meta_description = SITE_NAME;
        $this->meta_keywords = SITE_NAME;
    }
    
    public function index() {
        $page_data                  =   $this->global_model->page_data('home');
        $data                       =   [
            'title'                     =>  $page_data->title,
            'meta_description'          =>  $page_data->description,
            'meta_keywords'             =>  $page_data->keywords,
            'heading'                   =>  $page_data->heading,
            'content'                   =>  $page_data->page_content,
            'banners'                   =>  $this->setting_model->get_banners_cache(['where' => ['status' => STATUS_ACTIVE]]),
            'show_application_link'     =>  TRUE,
            'right_bar'                 =>  TRUE
        ];
        $this->load->view('templates/front.tpl', array_merge($this->data, $data));
    }
    
    public function page($slug = 'home') {
        $page_data                  =   $this->global_model->page_data($slug);
        if (empty($page_data)) {
            redirect(base_url());
        }
        $data                       =   [
            'title'                     =>  $page_data->title,
            'meta_description'          =>  $page_data->description,
            'meta_keywords'             =>  $page_data->keywords,
            'heading'                   =>  $page_data->heading,
            'content'                   =>  $page_data->page_content,
            'banners'                   =>  $this->setting_model->get_banners_cache(['where' => ['status' => STATUS_ACTIVE]]),
            'show_application_link'     =>  TRUE,
            'right_bar'                 =>  TRUE,
            'slug'                      =>  $slug
        ];
        $this->load->view('templates/front.tpl', array_merge($this->data, $data));
    }
    
    public function contact_us() {
        $data                       =   [
            'title'                     =>  'Contact For Indian Tourist VISA',
            'meta_description'          =>  $this->meta_description,
            'meta_keywords'             =>  $this->meta_keywords,
            'heading'                   =>  'Contact For Indian Tourist VISA',
            'banners'                   =>  $this->setting_model->get_banners_cache(['where' => ['status' => STATUS_ACTIVE]]),
            'show_application_link'     =>  TRUE,
            'right_bar'                 =>  TRUE,
        ];
        $this->load->view('templates/front.tpl', array_merge($this->data, $data));
    }

    function apply_visa() {
        if (!$this->input->post('step1') == "") {
            if ($this->input->post('v_code') != $this->session->userdata('captcha')) {
                echo json_encode(['sts' => STATUS_ERROR, 'msg' => 'Please Enter Correct verification code']);
            } else { 
                $n  = $this->setting_model->get_country_cache(['where' => ['id' => $this->input->post('nationality')]]);
                $nationality        =   $n[0]->name;
                $nationality_code   =   $n[0]->code;
                
                $application_id             =   $this->operation_model->app_step1($nationality_code);
                $this->session->set_userdata('application_id', $application_id);

                /**
                 * Send Mail : - 
                 */
                //Mail to User :- 
                $name = $this->input->post('fname') . ((!empty($this->input->post('mname'))) ? (' ' . $this->input->post('mname')) : '') . ' ' . $this->input->post('lname');
                $email = $this->input->post('email');
                $arvp  = $this->setting_model->get_arrival_port_cache(['where' => ['id' => $this->input->post('portofarrival')]]);
                $arvp_name  =   $arvp[0]->name;
                   
                $udata = [
                    'site_name'             =>  SITE_NAME,
                    'logo'                  =>  base_url('public/img/logo.png'),
                    'heading'               =>  'Visa Application Generated',
                    'subheading'            =>  'Thanks for using ' . SITE_NAME,
                    'app_id'                =>  $application_id,
                    'fname'                 =>  $this->input->post('fname'),
                    'mname'                 =>  $this->input->post('mname'),
                    'lname'                 =>  $this->input->post('lname'),
                    'pass_type'             =>  $this->input->post('passportType'),
                    'nationality'           =>  $nationality,
                    'portofarrival'         =>  $arvp_name,
                    'dob'                   =>  $this->input->post('dob'),
                    'email'                 =>  $this->input->post('email'),
                    'date_of_arrival'       =>  $this->input->post('date_of_arrival'),
                    'phone'                 =>  $this->input->post('phone'),
                    'apply_link'            =>  base_url('serach_app')
                ];
                $mail_html                  =   $this->load->view('email/visa_aply', $udata, TRUE);
                $this->load->library('Ops_Email');
                $this->ops_email->content = $mail_html;
                $this->ops_email->subject = SITE_NAME . ' - Complete Pending Registration';
                $this->ops_email->__toEmail = $this->input->post('email');
                $this->ops_email->__toName = $name;
                $this->ops_email->__send_mail();

                //Mail To Admin 
                $adata                  =   [
                    'name'                  =>  $name,
                    'site_name'             =>  SITE_NAME,
                    'logo'                  =>  base_url('public/img/logo.png'),
                    'heading'               =>  'A New Visa Application Registered',
                    'app_id'                =>  $application_id,
                    'fname'                 =>  $this->input->post('fname'),
                    'mname'                 =>  $this->input->post('mname'),
                    'lname'                 =>  $this->input->post('lname'),
                    'pass_type'             =>  $this->input->post('passportType'),
                    'nationality'           =>  $nationality,
                    'portofarrival'         =>  $arvp_name,
                    'dob'                   =>  $this->input->post('dob'),
                    'email'                 =>  $this->input->post('email'),
                    'date_of_arrival'       =>  $this->input->post('date_of_arrival'),
                    'phone'                 =>  $this->input->post('phone'),
                    'apply_link'            =>  base_url('admin')
                ];
                $mail_admin_html            =   $this->load->view('email/admin_visa_aply', $adata, TRUE);
                $this->load->library('Ops_Email');
                $this->ops_email->content   =   $mail_admin_html;
                $this->ops_email->subject   =   'New Visa Application Registration - ' . $application_id;
                $this->ops_email->__toEmail =   [ADMIN_EMAIL1, ADMIN_EMAIL2];
//                $this->ops_email->__send_mail(); 
                
                echo json_encode([
                    'sts' => STATUS_SUCCESS,
                    'title' => 'Congratulation!',
                    'msg' => 'Your e-Tourist Visa (eTV) Application submitted successfully.<br/>Your Application Id is : <b>' . $application_id . '</b><br/>Please complete Step2.',
                    'url' => base_url('visa_reg')
                ]);
            }
            exit();
        }
        $data = [
            'title'                     => 'Apply Online For Indian VISA',
            'show_application_link'     =>  FALSE,
            'right_bar'                 =>  FALSE,
            'meta_description'          =>  $this->meta_description,
            'meta_keywords'             =>  $this->meta_keywords,
            'heading'                   =>  'e-Tourist Visa (eTV) Application',
            'getCounrty'                =>  $this->setting_model->get_country_cache(['where'=> ['status' => STATUS_ACTIVE]]), 
            'ports'                     =>  $this->setting_model->get_arrival_port_cache(['where'=> ['status' => STATUS_ACTIVE]]),
            'application_type'          =>  $this->setting_model->get_application_type_cache(['where' => ['status' => STATUS_ACTIVE]]),
            'form_banner'               =>  $this->setting_model->get_setting_value(SETTING_FORM_BANNER)
        ];
        $this->load->view('templates/only_form.tpl', array_merge($this->data, $data));
    }
    
    function visa_reg() {
        if (empty($this->session->userdata('application_id'))) {
            redirect(base_url('apply_visa'));
        }
        if (!$this->input->post('step1') == "") {
            $result                     =   $this->operation_model->set_visa_reg();
            if ($result) {
                redirect(base_url('visa_step3'));
            }
        }
        $data                       =   [
            'title'                     => 'Apply Online For Indian VISA',
//            'meta_description' => $this->meta_description,
//            'meta_keywords' => $this->meta_keywords,
            'show_application_link'     =>  FALSE,
            'right_bar'                 =>  FALSE,
            'heading' => 'Complete Partially Filled Form',
            'apply_details' => $this->operation_model->get_application_details(),
            'getCounrty' => $this->setting_model->get_country_cache(),
            'getAllCounrty'     => $this->setting_model->get_country_cache(),
            'form_banner'       =>  $this->setting_model->get_setting_value(SETTING_FORM_BANNER)
        ];
        $this->load->view('templates/only_form.tpl', array_merge($this->data, $data));
    }
    
    function visa_step3() {
        if (!empty($this->session->userdata('application_id'))) {
            if (!$this->input->post('visa_step3') == "") {
                if ($this->operation_model->set_visareg3()) {
                    redirect(base_url('visa_step4'));
                }
            }
            if (!$this->input->post('visa_step3_exit') == "") {
                if ($this->operation_model->set_visareg3()) {
                    $this->session->unset_userdata('application_id');
                    redirect(base_url());
                }
            }
            $data = [
                'title' => 'Apply Online for Indian VISA',
                'heading' => 'Complete Partially Filled Form',
//                'meta_description' => $this->meta_description,
//                'meta_keywords' => $this->meta_keywords,
                'show_application_link'     =>  FALSE,
                'right_bar'                 =>  FALSE,
                'getCounrty'                =>  $this->setting_model->get_country_cache(),
                'getAllCounrty'             =>  $this->setting_model->get_country_cache(),
                'apply_details'             =>  $this->operation_model->get_application_details(),
                'form_banner'               =>  $this->setting_model->get_setting_value(SETTING_FORM_BANNER)
            ];
            $this->load->view('templates/only_form.tpl', array_merge($this->data, $data));
        } else {
            redirect(base_url('apply_visa'));
        }
    }
    
    public function visa_step4() {
        if (empty($this->session->userdata('application_id'))) {
            redirect(base_url('apply_visa'));
        }
        if (!$this->input->post('step4') == "") {
            $return                 =   [];
            $app_id                 =   $this->session->userdata('application_id');
            $img                    =   $this->util->fileUpload(APPLICATION_IMG, 'image', $app_id, 'jpeg|jpg|png');
            if (!empty($img['error']) && ($_FILES['image']['size'] != 0)) {
                $return             =   ['sts' => STATUS_ERROR, 'msg' => $img['error']];
                unset($img);
            }
            if(empty($img['error'])) {
                $old_img            =   $this->input->post('old_img');
                if (empty($img)) {
                    $img            =   $old_img;
                } else {
                    $old_img_path   =   APPLICATION_IMG . $old_img;
                    if (file_exists($old_img_path)) {
                        @unlink($old_img_path);
                    }
                }
                $result             =   $this->operation_model->app_step4($img);
                if ($result) {
                    $return         =   ['sts' => STATUS_SUCCESS, 'url' => base_url('main/uploadPassport')];
                } else {
                    $return         =   ['sts' => STATUS_ERROR, 'msg' => 'Unable to complete step4. Please contact our support team.'];
                }
            }
            echo json_encode($return);
            exit();
        }
        $data = [
            'title'                     =>  'Apply Online For Indian VISA',
//            'meta_description'        =>  $this->meta_description,
//            'meta_keywords'           =>  $this->meta_keywords,
            'show_application_link'     =>  FALSE,
            'right_bar'                 =>  FALSE,
            'heading'                   =>  'e-Tourist Visa (eTV) Application',
            'apply_details'             =>  $this->operation_model->get_application_details(),
            'getCounrty'                =>  $this->setting_model->get_country_cache(),
            'ports'                     =>  $this->setting_model->get_arrival_port_cache(['where'=> ['status' => STATUS_ACTIVE]]),
            'application_type'          =>  $this->setting_model->get_application_type_cache(['where' => ['status' => STATUS_ACTIVE]]),
            'form_banner'               =>  $this->setting_model->get_setting_value(SETTING_FORM_BANNER)
        ];
        $this->load->view('templates/only_form.tpl', array_merge($this->data, $data));
    }
    
    public function uploadPassport() {
        if (empty($this->session->userdata('application_id'))) {
            redirect(base_url('apply_visa'));
        }
        if (!$this->input->post('uploadpassport') == "") {
            $return                 =   [];
            $app_id                 =   $this->session->userdata('application_id');
            $img                    =   $this->util->fileUpload(PASSPORT_IMG, 'passport', $app_id, 'jpeg|jpg|png');
            
            if (!empty($img['error']) && ($_FILES['passport']['size'] != 0)) {
                $return             =   ['sts' => STATUS_ERROR, 'msg' => $img['error']];
                unset($img);
            }
            
            $old_img                =   $this->input->post('old_passport');
            if (empty($img)) {
                $img                =   $old_img;
            } else {
                $old_img_path       =   PASSPORT_IMG . $old_img;
                if (file_exists($old_img_path)) {
                    @unlink($old_img_path);
                }
            }
            $result                 =   $this->operation_model->uploadpassport($img);
            if ($result) {
                $return             =   ['sts' => STATUS_SUCCESS, 'url' => base_url('main/reviewform')];
            } else {
                $return             =   ['sts' => STATUS_ERROR, 'msg' => 'Unable to upload passport. Please contact our support team.'];
            }
            echo json_encode($return);
            exit();
        }
        $data = [
            'title'                     =>  'Apply Online For Indian VISA',
//            'meta_description'          =>  $this->meta_description,
//            'meta_keywords'             =>  $this->meta_keywords,
            'show_application_link'     =>  FALSE,
            'right_bar'                 =>  FALSE,
            'heading'                   =>  'e-Tourist Visa (eTV) Application',
            'apply_details'             =>  $this->operation_model->get_application_details(),
            'form_banner'               =>  $this->setting_model->get_setting_value(SETTING_FORM_BANNER)
        ];
        $this->load->view('templates/only_form.tpl', array_merge($this->data, $data));
    }
    
    
    public function reviewform() {
        if (empty($this->session->userdata('application_id'))) {
            redirect(base_url('serach_app'));
        }
        $data = [
            'title' => 'Apply Online For Indian VISA',
//            'meta_description' => $this->meta_description,
//            'meta_keywords' => $this->meta_keywords,
            'heading' => 'Conform Details',
            'apply_details' => $this->operation_model->get_application_details(),
            'form_banner'               =>  $this->setting_model->get_setting_value(SETTING_FORM_BANNER)
        ];
        $this->load->view('templates/only_form.tpl', array_merge($this->data, $data));
    }
    
    
    public function feepay() {
        if (empty($this->session->userdata('application_id'))) {
            redirect(base_url('serach_app'));
        }
        $data = [
            'title' => 'Apply Online For Indian VISA',
//            'meta_description' => $this->meta_description,
//            'meta_keywords' => $this->meta_keywords,
            'heading' => 'e-Tourist Visa (eTV) Application',
            'apply_details' => $this->operation_model->get_application_details(),
            'form_banner'               =>  $this->setting_model->get_setting_value(SETTING_FORM_BANNER)
        ];
        $this->load->view('templates/only_form.tpl', array_merge($this->data, $data));
    }
    
    
    public function payment() {
        if (empty($this->session->userdata('application_id'))) {
            redirect(base_url('serach_app'));
        }
        $data = [
            'title' => 'Apply Online For Indian VISA',
//            'meta_description' => $this->meta_description,
//            'meta_keywords' => $this->meta_keywords,
            'heading' => 'e-Tourist Visa (eTV) Application',
            'apply_details' => $this->operation_model->get_application_details(),
            'form_banner'               =>  $this->setting_model->get_setting_value(SETTING_FORM_BANNER)
        ];
        $this->load->view('templates/only_form.tpl', array_merge($this->data, $data));
    }
    
     function payment_success() {
        if (empty($this->session->userdata('application_id'))) {
            redirect(base_url('serach_app'));
        }
        $this->operation_model->payment_status(1);
        $data = [
            'title' => 'Apply Online For Indian VISA',
//            'meta_description' => $this->meta_description,
//            'meta_keywords' => $this->meta_keywords,
            'heading' => 'e-Tourist Visa (eTV) Application',
            'apply_details' => $this->operation_model->get_application_details(),
            'form_banner'               =>  $this->setting_model->get_setting_value(SETTING_FORM_BANNER)
        ];
        $this->load->view('templates/only_form.tpl', array_merge($this->data, $data));
    }

    function payment_faild() {
        if (empty($this->session->userdata('application_id'))) {
            redirect(base_url('serach_app'));
        }
        $this->operation_model->payment_status(0);
        $data = [
            'title' => 'Apply Online For Indian VISA',
//            'meta_description' => $this->meta_description,
//            'meta_keywords' => $this->meta_keywords,
            'heading' => 'e-Tourist Visa (eTV) Application',
            'apply_details' => $this->operation_model->get_application_details()
        ];
        $this->load->view('templates/front.tpl', array_merge($this->data, $data));
    }
     
    public function serach_app() {
        if (!$this->input->post('search') == "") {
            if ($this->input->post('v_code') != $this->session->userdata('captcha')) {
                echo json_encode(['sts' => STATUS_ERROR, 'msg' => 'Please Enter Correct verification code']);
            } else {
                $application_data = $this->operation_model->search_app();
                if (empty($application_data)) {
                    echo json_encode([
                        'sts' => STATUS_ERROR,
                        'msg' => 'Please enter correcty Temporary Application ID.',
                    ]);
                } else {
                    $url = base_url('visa_reg');
                    if ($application_data[0]->application_status == 1) {
                        if ($application_data[0]->status == 1) {
                            $url = base_url('visa_reg');
                        }
                        if ($application_data[0]->status == 2) {
                            $url = base_url('visa_step3');
                        }
                        if ($application_data[0]->status == 3) {
                            $url = base_url('visa_step4');
                        }
                        if ($application_data[0]->status == 4) {
                            $url = base_url('uploadPassport');
                        }
                        if ($application_data[0]->status == 5) {
                            $url = base_url('reviewform');
                        }
                        if (in_array($application_data[0]->status, [1, 2, 3, 4, 5])) {
                            $this->session->set_userdata('application_id', $application_data[0]->app_id);
                            echo json_encode([
                                'sts' => STATUS_SUCCESS,
                                'title' => 'Hi! ' . $application_data[0]->fname,
                                'msg' => 'Please Your Application is not completed. please continue now.',
                                'url' => $url
                            ]);
                        }
                    } elseif ($application_data[0]->application_status == 2) {
                        if ($application_data[0]->payment_status == 1) {
                            echo json_encode([
                                'sts' => STATUS_PENDING,
                                'title' => 'Hi ! ' . $application_data[0]->fname,
                                'msg' => 'Your Application Form is completed, payment is done and currently Visa is under process.',
                            ]);
                        } else {
                            $this->session->set_userdata('application_id', $application_data[0]->app_id);
                            echo json_encode([
                                'sts' => STATUS_SUCCESS,
                                'title' => 'Hi ! ' . $application_data[0]->fname,
                                'msg' => 'Your Application Form is completed, but due to some reason your payment was not successfull, Please pay your Application charge.',
                                'url' => base_url('main/feepay')
                            ]);
                        }
                    } elseif ($application_data[0]->application_status == 3) {
                        echo json_encode([
                            'sts' => STATUS_COMPLETE,
                            'title' => 'Hi ! ' . $application_data[0]->fname,
                            'msg' => 'Your Visa is completed.',
                        ]);
                    }
                }
            }
            exit();
        }
        $data = [
            'title' => 'Apply Online For Indian VISA',
//            'meta_description' => $this->meta_description,
//            'meta_keywords' => $this->meta_keywords,
            'heading' => 'e-Tourist Visa (eTV) Application',
            'apply_details' => $this->operation_model->get_application_details()
        ];
        $this->load->view('templates/front.tpl', array_merge($this->data, $data));
    }

    
    public function app_status() {
        if (!$this->input->post('search') == "") {
            if ($this->input->post('v_code') != $this->session->userdata('captcha')) {
                echo json_encode(['sts' => STATUS_ERROR, 'msg' => 'Please Enter Correct verification code']);
            } else {
                $application_data = $this->operation_model->search_app();
                if (empty($application_data)) {
                    echo json_encode([
                        'sts' => STATUS_ERROR,
                        'msg' => 'Please enter correcty Temporary Application ID.',
                    ]);
                } else {
                    $url = base_url('visa_reg');
                    if ($application_data[0]->application_status == 1) {
                        if ($application_data[0]->status == 1) {
                            $url = base_url('visa_reg');
                        }
                        if ($application_data[0]->status == 2) {
                            $url = base_url('visa_step3');
                        }
                        if ($application_data[0]->status == 3) {
                            $url = base_url('visa_step4');
                        }
                        if ($application_data[0]->status == 4) {
                            $url = base_url('uploadPassport');
                        }
                        if ($application_data[0]->status == 5) {
                            $url = base_url('reviewform');
                        }
                        if (in_array($application_data[0]->status, [1, 2, 3, 4, 5])) {
                            $this->session->set_userdata('application_id', $application_data[0]->app_id);
                            echo json_encode([
                                'sts' => STATUS_SUCCESS,
                                'title' => 'Hi! ' . $application_data[0]->fname,
                                'msg' => 'Please Your Application is not completed. please continue now.',
                                'url' => $url
                            ]);
                        }
                    } elseif ($application_data[0]->application_status == 2) {
                        if ($application_data[0]->payment_status == 1) {
                            echo json_encode([
                                'sts' => STATUS_PENDING,
                                'title' => 'Hi ! ' . $application_data[0]->fname,
                                'msg' => 'Your Application Form is completed, payment is done and currently Visa is under process.',
                            ]);
                        } else {
                            $this->session->set_userdata('application_id', $application_data[0]->app_id);
                            echo json_encode([
                                'sts' => STATUS_SUCCESS,
                                'title' => 'Hi ! ' . $application_data[0]->fname,
                                'msg' => 'Your Application Form is completed, but due to some reason your payment was not successfull, Please pay your Application charge.',
                                'url' => base_url('main/feepay')
                            ]);
                        }
                    } elseif ($application_data[0]->application_status == 3) {
                        echo json_encode([
                            'sts' => STATUS_COMPLETE,
                            'title' => 'Hi ! ' . $application_data[0]->fname,
                            'msg' => 'Your Visa is completed.',
                        ]);
                    }
                }
            }
            exit();
        }
        $data = [
            'title' => 'Apply Online For Indian VISA',
//            'meta_description' => $this->meta_description,
//            'meta_keywords' => $this->meta_keywords,
            'heading' => 'Check Applications Status',
        ];
        $this->load->view('templates/front.tpl', array_merge($this->data, $data));
    }
        
    public function free_assessment() {
        $data                       =   [
            'title'                     =>  'Free Assessment',
            'meta_description'          =>  $this->meta_description,
            'meta_keywords'             =>  $this->meta_keywords,
            'heading'                   =>  'Free Assessment',
            'banners'                   =>  $this->setting_model->get_banners_cache(['where' => ['status' => STATUS_ACTIVE]]),
            'show_application_link'     =>  TRUE,
            'right_bar'                 =>  TRUE,
        ];
        $this->load->view('templates/front.tpl', array_merge($this->data, $data));
    }

  
}
