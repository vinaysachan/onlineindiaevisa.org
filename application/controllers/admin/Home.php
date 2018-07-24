<?php

class Home extends Admin_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data = [
            'heading' => 'Dashboard',
            'sub_heading' => '',
            'breadcrumb' => [base_url('admin') => '<i class="fa fa-dashboard"></i> Home', 'Dashborad']
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }

    public function login() {
        if (!empty($this->session->userdata(SESSION_ADMIN))) {
            redirect(base_url('admin'));
            exit();
        }
        if (!empty($this->input->post('submit')) && ($this->input->post('submit') == 'login')) {
            $post = $this->input->post();
            unset($post['submit']);
            if ($this->operation_model->do_login($post)) {
                $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton! Login successfull', 'Congratulaton! Login successfull']);
                echo json_encode(['sts' => STATUS_SUCCESS, 'msg' => 'Congratulaton Login successfull.', 'url' => base_url('admin')]);
            } else {
                echo json_encode(['sts' => STATUS_ERROR, 'msg' => 'Password and username not match']);
            }
            exit();
        }
        $this->append_jc([
            'js' => ['public/js/admin/home/login.js'],
            'css' => ['public/css/admin/login.css']]);

        $data = [
            'title' => 'Admin Login',
            'heading' => 'Please sign in',
        ];
        $this->load->view('admin/admin_login_view', array_merge($this->data, $data));
    }

    public function logout() {
        $this->session->unset_userdata(SESSION_ADMIN);
        redirect(base_url('admin/login'));
        exit();
    }

    public function my_profile() {
        $this->append_jc(['js' => ['public/js/admin/profile.js']]);
        //Update Admin Profile
        if (!empty($this->input->post('update_profile'))) {
            $post = $this->input->post();
            unset($post['update_profile']);
            if ($this->operation_model->update_account($post, $this->session->userdata(SESSION_ADMIN)['id'])) {
                $this->session->set_flashdata(SUCCESS_MSG, 'You have successfully updated your profile.');
                redirect(base_url('admin/home/my_profile'));
            }
        }
        //Update Admin password
        if (!empty($this->input->post('update_password'))) {
            $post = $this->input->post();
            unset($post['update_password']);
            echo json_encode($this->operation_model->update_account_password($post, $this->session->userdata(SESSION_ADMIN)['id']));
            exit();
        }
        $data = [
            'heading' => 'Update Profile',
            'breadcrumb' => [
                base_url('ops-admin') => '<i class="fa fa-dashboard"></i> Home',
                'Update Profile'
            ],
            'profile' => $this->operation_model->getAdminById($this->session->userdata(SESSION_ADMIN)['id'])
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }

    public function enquiry($status = STATUS_IN_ACTIVE, $page = NULL) {
        if ($status == STATUS_IN_ACTIVE) {
            $heading = '<i class="fa fa-envelope-o mr10"></i> New Enquiries';
        } else {
            $status == STATUS_ACTIVE;
            $heading = '<i class="fa fa-envelope-open-o mr10"></i> Closed Enquiries';
        }
        $this->load->library('paginator');
        $this->paginator->initialize([
            'base_url' => base_url('admin/home/enquiry/' . $status),
            'total_items' => (int) $this->global_model->getEnqs(NULL, NULL, 'count', $status),
            'current_page' => $page
        ]);
        $limit = $this->paginator->limit_end;
        $offset = $this->paginator->limit_start;
        $data = [
            'heading' => $heading,
            'sub_heading' => '',
            'page' => $offset,
            'status' => $status,
            'breadcrumb' => [base_url('admin') => '<i class="fa fa-dashboard"></i> Home', 'Enquiry'],
            'enqs' => $this->global_model->getEnqs($offset, $limit, NULL, $status)
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }

    public function change_sts() {
        if ($this->global_model->change_status($this->input->post('sts'), $this->input->post('id'))) {
            $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Your Enquiry status change successfully.']);
            echo json_encode(['sts' => STATUS_SUCCESS, 'msg' => 'Your Enquiry status change successfully.']);
        } else {
            echo json_encode(['sts' => STATUS_ERROR, 'msg' => 'Unable to change Enquiry status.']);
        }
        exit();
    }

    function applicationDetails($page = NULL) {
        $heading = '<i class="fa fa-envelope-o mr10"></i> Visa Applications';

        $this->load->library('paginator');
        $this->paginator->initialize([
            'base_url' => base_url('admin/home/applicationDetails'),
            'total_items' => (int) $this->global_model->getApplication(NULL, NULL, 'count'),
            'current_page' => $page
        ]);
        $limit = $this->paginator->limit_end;
        $offset = $this->paginator->limit_start;
        $data = [
            'heading' => $heading,
            'page' => $offset,
            'sub_heading' => '',
            'getCounrty' => $this->setting_model->get_country_cache(['where' => ['status' => STATUS_ACTIVE]]),
            'breadcrumb' => [base_url('admin') => '<i class="fa fa-dashboard"></i> Home', 'Visa Applications'],
            'application' => $this->global_model->getApplication($offset, $limit, NULL),
            'application_type' => $this->setting_model->get_application_type_cache(['where' => ['status' => STATUS_ACTIVE]])
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }

    public function appDetails($app_id) {
        $heading = '<i class="fa fa-envelope-o mr10"></i> Application Detail : ' . $app_id;

        $data = [
            'heading' => $heading,
            'sub_heading' => '',
            'breadcrumb' => [base_url('admin') => '<i class="fa fa-dashboard"></i> Home', 'applicationDetails'],
            'application' => $this->global_model->getAppDetails($app_id)
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }

    public function close_sts() {
        if ($this->global_model->close_status($this->input->post('app_id'))) {
            $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', 'Your Application status change successfully.']);
            echo json_encode(['sts' => STATUS_SUCCESS, 'msg' => 'Your Application status change successfully.']);
        } else {
            echo json_encode(['sts' => STATUS_ERROR, 'msg' => 'Unable to change Application status.']);
        }
        exit();
    }

    public function blog($page = NULL) {
        $heading = '<i class="fa fa-envelope-o mr10"></i> Blog List';
        $this->load->library('paginator');
        $this->paginator->initialize([
            'base_url' => base_url('admin/home/blogs'),
//            'total_items' => (int) $this->global_model->getApplication(NULL, NULL, 'count'),
            'current_page' => $page
        ]);
        $limit = $this->paginator->limit_end;
        $offset = $this->paginator->limit_start;
        $data = [
            'heading' => $heading,
            'page' => $offset,
            'sub_heading' => '',
            'breadcrumb' => [base_url('admin') => '<i class="fa fa-dashboard"></i> Home', 'Blogs'],
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }

    public function blog_ae($blog_id = NULL) {
        $this->append_jc(['js' => ['public/plugins/ckeditor/ckeditor.js', 'public/js/admin/setting.js']]);
        if (!empty($blog_id)) {
            $heading                =   '<i class="fa fa-television"></i> Update Blog Detail';
            $blog_data              =   $this->setting_model->blog_data($blog_id);
            $blog_content_data      =   $this->setting_model->blog_content_data($blog_id);
        } else {
            $heading                =   '<i class="fa fa-television"></i> Add New Blog';
            $blog_data              =   '';
            $blog_content_data      =   '';
        }
        if (!empty($this->input->post('save_blog'))) {
            $post                       =   $this->input->post();
            if ($post['save_blog'] == 'add') {
                unset($post['save_blog'], $post['blog_id']);
                $check_data             =   $this->setting_model->check_blog($post);
                if ($check_data['status'] == STATUS_ERROR) {
                    echo json_encode(['sts' => STATUS_ERROR, 'msg' => $check_data['msg']]);
                    exit();
                } else {
                    if (!empty($_FILES['img']['name'])) {
                        $img            =   $this->util->fileUpload(BLOG_PATH, 'img', SITE_NAME, 'jpeg|jpg|png');
                    }
                    if (!empty($img['error'])) {
                        echo json_encode(['sts' => STATUS_ERROR, 'msg' => $img['error']]);
                        exit();
                    }
                    if (!empty($img)) {
                        $post['img']    =   base_url(BLOG_PATH . $img);
                    }
                    if ($bid = $this->setting_model->save_blog($post)) {
                        $msg            =   'Blog data save successfully. Now Please add blog content.';
                        $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', $msg]);
                        echo json_encode(['sts' => STATUS_SUCCESS, 'msg' => $msg, 'url' => base_url('admin/home/blog_ae/' . $bid)]);
                        exit();
                    }
                }
            } elseif ($post['save_blog'] == 'update') {
                unset($post['save_blog'], $post['blog_id']);
                $check_data             =   $this->setting_model->check_blog($post, $blog_id);
                if ($check_data['status'] == STATUS_ERROR) {
                    echo json_encode(['sts' => STATUS_ERROR, 'msg' => $check_data['msg']]);
                    exit();
                } else {
                    if (!empty($_FILES['img']['name'])) {
                        $img            =   $this->util->fileUpload(BLOG_PATH, 'img', SITE_NAME, 'jpeg|jpg|png');
                    }
                    if (!empty($img['error'])) {
                        echo json_encode(['sts' => STATUS_ERROR, 'msg' => $img['error']]);
                        exit();
                    }
                    if (!empty($img)) {
                        $post['img']    =   base_url(BLOG_PATH . $img);
                        //Remove the old Image
                        $old_img        =   $post['old_img'];
                        //Get the old IMGAE :-
                        $old_img_path   =   ltrim($old_img, base_url());
                        if (file_exists($old_img_path)) {
                            @unlink($old_img_path);
                        }
                    }
                    unset($post['old_img']);
                    if ($this->setting_model->update_blog($post, $blog_id)) {
                        $msg            =   'Blog data update successfully. Now Please check blog content.';
                        $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', $msg]);
                        echo json_encode(['sts' => STATUS_SUCCESS, 'msg' => $msg, 'url' => base_url('admin/home/blog_ae/' . $blog_id)]);
                        exit();
                    }
                }
            }
        }
        if (!empty($this->input->post('save_blog_content'))) {
            $content                    =   [];
            $post                       =   $this->input->post();
            if (!empty($_FILES["blog_img"])) {
                $num_of_images          =   count($_FILES["blog_img"]['name']);
                //if Images exist :- 
                if ($num_of_images > 0) {
                    foreach ($_FILES["blog_img"]['name'] as $key => $file_name) {
                        $size_of_image  = $_FILES["blog_img"]['size'][$key];
                        $error_of_image = $_FILES["blog_img"]['error'][$key];
                        if ($size_of_image > 0 && $error_of_image == 0) {
                            $filename   = underscore($blog_data[0]->blog_name . '_' . date('YmdHis') . $file_name);
                            move_uploaded_file($_FILES["blog_img"]['tmp_name'][$key], BLOG_PATH_CONTENT . $filename);
                            $content[]  = [
                                'type'      => 'image',
                                'order'     => $key,
                                'data'      => base_url(BLOG_PATH_CONTENT . $filename)
                            ];
                        }
                    }
                }
            }
            if (!empty($post['page_content'])) {
                foreach ($post['page_content'] as $key => $page_content) {
                    $content[]          =   [
                        'type'              =>  'content',
                        'order'             =>  $key,
                        'data'              =>  $page_content
                    ];
                }
            }
            if (empty($content)) {
                $msg                    =   'No Blog Content data to update.';
                echo json_encode(['sts' => STATUS_ERROR, 'msg' => $msg]);
                exit();
            } elseif ($this->setting_model->save_blog_content($blog_id, $content)) {
                $msg                    =   'Blog Content data update successfully.';
                $this->session->set_flashdata(SUCCESS_MSG, ['Congratulaton!', $msg]);
                echo json_encode(['sts' => STATUS_SUCCESS, 'msg' => $msg, 'url' => base_url('admin/home/blog_ae/' . $blog_id)]);
                exit();
            }
        }
        $data                       =   [
            'heading'                   =>  $heading,
            'sub_heading'               =>  '',
            'breadcrumb'                =>  [
                base_url('admin')           => '<i class="fa fa-dashboard"></i> Home',
                $heading
            ],
            'blog_id'                   =>  $blog_id,
            'blog_data'                 =>  $blog_data,
            'blog_content_data'         =>  $blog_content_data
        ];
        $this->load->view('templates/admin.tpl', array_merge($this->data, $data));
    }

}
