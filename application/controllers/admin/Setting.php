<?php

class setting extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->append_jc(['js' => [
                'public/js/admin/setting.js'
            ],
            'css' => []
        ]);
    }
    
    public function banner() {
        $data = [
            'heading' => 'Manage Front Page Banner',
            'sub_heading' => '',
            'breadcrumb' => [base_url('admin') => '<i class="fa fa-dashboard"></i> Home', 'Banners'],
            'banners' => $this->setting_model->get_banners()
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }
     
    public function banner_ae($b_id = NULL) {
        if (!empty($this->input->post('submit'))) {
            $post = $this->input->post();
            $img = $this->util->fileUpload(BANNER_PATH, 'img', SITE_NAME, 'jpeg|jpg|png', $resize = ['h' => 400]);
            if ($post['submit'] == 'add') {
                unset($post['submit']);
                if (!empty($img))
                    $post['img'] = base_url(BANNER_PATH.$img);
                if ($this->setting_model->save_banner($post)) {
                    $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Banner Save successfully']);
                    redirect('admin/setting/banner');
                }
            } elseif ($post['submit'] == 'update') {
                $post['status'] = ($post['status'] == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_IN_ACTIVE;
                unset($post['submit']);
                if ($img) {
                    $post['img'] = base_url(BANNER_PATH.$img);
                    //Remove the old Image
                    $old_img = $post['old_img'];
                    //Get the old IMGAE :-
                    $old_img_path   =   ltrim($old_img,base_url());
                    if (file_exists($old_img_path)){
                        @unlink($old_img_path);
                    }
                }
                unset($post['old_img']);
                if ($this->setting_model->update_banner($post, $b_id)) {
                    $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Banner Update successfully']);
                    redirect('admin/setting/banner');
                }
            }
        }
        if (!empty($b_id)) {
            $heading = 'Update Banner';
            $banner = $this->setting_model->get_banners(['where' => ['id' => $b_id]]);
        } else {
            $heading = 'Add New Banner';
            $banner = '';
        }
        $data = [
            'heading' => $heading,
            'sub_heading' => '',
            'breadcrumb' => [
                base_url('admin') => '<i class="fa fa-dashboard"></i> Home',
                base_url('admin/setting/banner') => 'Banners',
                $heading
            ],
            'banner' => $banner
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }
    
    public function form_banner() {
        $old_img        =   $this->setting_model->get_setting_value(SETTING_FORM_BANNER);
        if (!empty($this->input->post('submit'))) {
            $return             =   [];
            $img                =   $this->util->fileUpload(BANNER_FORM_PATH, 'img', SITE_NAME, 'jpeg|jpg|png', ['h' => 125]);    
            if (!empty($img['error']) && ($_FILES['img']['size'] != 0)) {
                $return         =   ['sts' => STATUS_ERROR, 'msg' => $img['error']];
                unset($img);
            }
            if(empty($img['error'])) {
                //Get the old IMGAE :-
                $old_img_path   =   ltrim($old_img,base_url());
                if (file_exists($old_img_path)){
                    @unlink($old_img_path);
                }
                //Update Imgae :-
                if($this->setting_model->update_setting_value(SETTING_FORM_BANNER,  base_url(BANNER_FORM_PATH.$img))) {
                    $this->session->set_flashdata(SUCCESS_MSG, 'Banner Save successfully.');
                    $return     =   ['sts' => STATUS_SUCCESS, 'msg' => 'Banner Save successfully.'];
                }
            }
            echo json_encode($return);
            exit();
        }
        $data               = [
            'heading'           =>  'Manage Form Page Banner',
            'sub_heading'       =>  '',
            'breadcrumb'        =>  [base_url('admin') => '<i class="fa fa-dashboard"></i> Home', 'Form Banner'],
            'old_img'           =>  $old_img
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }
    
    public function del_banner($b_id = NULL) {
        if (empty($b_id)) {
            $this->session->set_flashdata(WARNING_MSG, ['Warning!', 'Bad URL']);
            redirect('admin/setting/banner');
        }
        $banner = $this->setting_model->get_banners(['where' => ['id' => $b_id]]);
        if (empty($banner)) {
            $this->session->set_flashdata(WARNING_MSG, ['Warning!', 'Previous URL was Bad.']);
            redirect('admin/setting/banner');
        }
        if ($this->setting_model->delete_banner($b_id)) {
            if ($banner[0]->img) {
                //Remove the old Image
                $old_img_path   =   ltrim($banner[0]->img,base_url());
                if (file_exists($old_img_path)) {
                    @unlink($old_img_path);
                }
            }
            $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Banner Deleted successfully']);
            redirect('admin/setting/banner');
        }
    }
    
    public function page($page = NULL) {
        $this->load->library('paginator');
        $this->paginator->initialize([
            'base_url' => base_url('admin/setting/page'),
            'total_items' => (int) $this->setting_model->getPages(NULL, NULL, 'count'),
            'current_page' => $page
        ]);
        $limit = $this->paginator->limit_end;
        $offset = $this->paginator->limit_start;
        $data = [
            'heading' => '<i class="fa fa-television"></i> Manage Page\'s Data',
            'sub_heading' => '',
            'page' => $offset,
            'breadcrumb' => [base_url('admin') => '<i class="fa fa-dashboard"></i> Home', 'Pages'],
            'pages' => $this->setting_model->getPages($offset, $limit, NULL)
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }
    
    public function page_ae($pid = NULL) {
        $this->append_jc(['js' => ['public/plugins/ckeditor/ckeditor.js']]);
        if (!empty($pid)) {
            $heading = '<i class="fa fa-television"></i> Update Page Detail';
            $page_data = $this->setting_model->page_data($pid);
        } else {
            $heading = '<i class="fa fa-television"></i> Add Page Detail';
            $page_data = '';
        }
        $this->load->library('pages');
        if (!empty($this->input->post('save_page'))) {
            $post = $this->input->post();
            $post['menu_location'] = json_encode($post['menu_location']);
            if ($post['save_page'] == 'add') {
                unset($post['save_page']);
                if ($this->setting_model->save_page($post)) {
                    $this->pages->savefile();
                    $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Page Added Successfully']);
                    redirect('admin/setting/page');
                    exit();
                }
            } else if ($post['save_page'] == 'update') {
                unset($post['save_page']);
                if ($this->setting_model->update_page($post, $pid)) {
                    $this->pages->savefile();
                    $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Page Update Successfully']);
                    redirect('admin/setting/page');
                    exit();
                }
            }
        }
        $data = [
            'heading' => $heading,
            'sub_heading' => '',
            'breadcrumb' => [
                base_url('admin') => '<i class="fa fa-dashboard"></i> Home',
                $heading
            ],
            'pid' => $pid,
            'all_pages' => $this->setting_model->all_available_pages(),
            'all_saved_page' => $this->setting_model->all_saved_page(),
            'page_data' => $page_data
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }

    public function trash_page($id = NULL) {
        if (empty($id)) {
            $this->session->set_flashdata(WARNING_MSG, ['Warning!', 'Bad URL']);
            redirect('admin/setting/page');
        }
        if ($this->setting_model->delete_page($id)) {
            $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Page Deleted successfully']);
            redirect('admin/setting/page');
        }
    }
    
    public function application_type() {
        $data = [
            'heading' => '<i class="fa fa-television"></i> Application Type',
            'sub_heading' => '',
            'application_type' => $this->setting_model->get_application_type(),
            'breadcrumb' => [base_url('admin') => '<i class="fa fa-dashboard"></i> Home', 'Application Type']
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }
    
    public function app_type_ae($a_id = NULL) {
        if (!empty($this->input->post('submit'))) {
            $post           =   $this->input->post();
            if ($post['submit'] == 'add') {
                unset($post['submit']);
                if ($this->setting_model->save_application_type($post)) {
                    $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Application Type Save successfully']);
                    redirect('admin/setting/application_type');
                }
            } elseif ($post['submit'] == 'update') {
                $post['status'] = ($post['status'] == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_IN_ACTIVE;
                unset($post['submit']);
                if ($this->setting_model->update_application_type($post, $a_id)) {
                    $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Application Type Update successfully']);
                    redirect('admin/setting/application_type');
                }
            }
        }
        if (!empty($a_id)) {
            $heading            =   'Update Application Type';
            $app_type           =   $this->setting_model->get_application_type(['where' => ['id' => $a_id]]);
        } else {
            $heading            =   'Add New Application Type';
            $app_type           =   '';
        }
        $data                   =   [
            'heading'               =>  $heading,
            'sub_heading'           =>  '',
            'breadcrumb'            =>  [
                base_url('admin')                               => '<i class="fa fa-dashboard"></i> Home',
                base_url('admin/setting/application_type')      => 'Application Types',
                $heading
            ],
            'app_type'          =>  $app_type
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }
    
    
    public function country() {
         $data                  =   [
            'heading'               =>  '<i class="fa fa-map"></i> List of countries',
            'sub_heading'           =>  '',
            'country'               =>  $this->setting_model->get_country(),
            'breadcrumb'            =>  [base_url('admin') => '<i class="fa fa-dashboard"></i> Home', 'Country']
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }

    public function change_country_sts() {
        if ($this->setting_model->change_country_status($this->input->post('sts'), $this->input->post('id'))) {
            $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Your Country changre successfully.']);
            echo json_encode(['sts' => STATUS_SUCCESS, 'msg' => 'Your Country status changre successfully.']);
        } else {
            echo json_encode(['sts' => STATUS_ERROR, 'msg' => 'Unable to change Country status.']);
        }
        exit();
    }
    
    public function arrival_port() {
        $data                   =   [
            'heading'               =>  '<i class="fa fa-plane"></i> Port Of Arrival',
            'sub_heading'           =>  '',
            'ports'                 =>  $this->setting_model->get_arrival_port(),
            'breadcrumb'            =>  [base_url('admin') => '<i class="fa fa-dashboard"></i> Home', 'Port Of Arrival']
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }
    
    public function arrival_port_ae($id=NULL) {
        if (!empty($this->input->post('submit'))) {
            $post           =   $this->input->post();
            if ($post['submit'] == 'add') {
                unset($post['submit']);
                if ($this->setting_model->save_arrival_port($post)) {
                    $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Port Of Arrival Save successfully']);
                    redirect('admin/setting/arrival_port');
                }
            } elseif ($post['submit'] == 'update') {
                $post['status'] = ($post['status'] == STATUS_ACTIVE) ? STATUS_ACTIVE : STATUS_IN_ACTIVE;
                unset($post['submit']);
                if ($this->setting_model->update_arrival_port($post, $id)) {
                    $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Port Of Arrival Update successfully']);
                    redirect('admin/setting/arrival_port');
                }
            }
        }
        if (!empty($id)) {
            $heading            =   'Update Port Of Arrival';
            $port               =   $this->setting_model->get_arrival_port(['where' => ['id' => $id]]);
        } else {
            $heading            =   'Add New Port Of Arrival';
            $port               =   '';
        }
        $data                   =   [
            'heading'               =>  $heading,
            'sub_heading'           =>  '',
            'breadcrumb'            =>  [
                base_url('admin')                               => '<i class="fa fa-dashboard"></i> Home',
                base_url('admin/setting/arrival_port')          => 'Arrival Ports',
                $heading
            ],
            'port'              =>  $port
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }








    //    public function change_sts() {
//        if ($this->global_model->change_status($this->input->post('sts'), $this->input->post('id'))) {
//            $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Your Enquiry status changre successfully.']);
//            echo json_encode(['sts' => STATUS_SUCCESS, 'msg' => 'Your Enquiry status changre successfully.']);
//        } else {
//            echo json_encode(['sts' => STATUS_ERROR, 'msg' => 'Unable to change Enquiry status.']);
//        }
//        exit();
//    }




//    public function index() {
//        $data = [
//            'heading' => 'Setting',
//            'sub_heading' => '',
//            'breadcrumb' => [base_url('admin') => '<i class="fa fa-dashboard"></i> Home', 'Dashborad'],
//        ];
//        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
//    }












//    function edit_application_type() {
//        if ($this->setting_model->save_application_type()) {
//            echo json_encode(['sts' => STATUS_SUCCESS, 'msg' => 'Application Types change successfully.']);
//        } else {
//            echo json_encode(['sts' => STATUS_ERROR, 'msg' => 'Unable to change Application Types.']);
//        }
//        exit();
//    }

}
