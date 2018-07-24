<?php

class Setting_model extends CORE_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_banners($data = NULL) {
        $this->db->select();
        $this->db->from(TBL_BANNERS . ' as b');

        if (!empty($data['where']['id'])) {
            $this->db->where('id', $data['where']['id']);
        }
        if (!empty($data['where']['status'])) {
            $this->db->where('status', $data['where']['status']);
        }

        $this->db->order_by("order", "ASC");
        return $this->db->get()->result();
    }

    public function get_banners_cache($data = NULL) {
        $this->db->cache_on();
        $result = $this->get_banners($data);
        $this->db->cache_off();
        return $result;
    }

    public function save_banner($data) {
        $this->db->cache_delete_all();
        if ($this->db->insert(TBL_BANNERS, $data))
            return TRUE;
        return FALSE;
    }

    public function update_banner($data, $b_id) {
        $this->db->cache_delete_all();
        if ($this->db->update(TBL_BANNERS, $data, ['id' => $b_id]))
            return TRUE;
        return FALSE;
    }

    public function delete_banner($b_id) {
        $this->db->cache_delete_all();
        if ($this->db->delete(TBL_BANNERS, ['id' => $b_id]))
            return TRUE;
        return FALSE;
    }

    public function getPages($offset = NULL, $limit = NULL, $count = NULL) {
        $this->db->select();
        $this->db->from(TBL_PAGES);
        if (!empty($count)) {
            return $this->db->get()->num_rows();
        } else {
            $this->db->order_by('slug', 'DESC');
            $this->db->limit($limit, $offset);
            return $this->db->get()->result();
        }
    }

    public function save_page($data) {
        $this->db->cache_delete_all();
        if ($this->db->insert(TBL_PAGES, $data))
            return TRUE;
        return FALSE;
    }

    public function page_data($pid) {
        $sql = 'SELECT *, (SELECT GROUP_CONCAT(slug) FROM pages) as all_slugs FROM pages p WHERE p.id = "' . $pid . '"';
        if ($query = $this->db->query($sql)) {
            return $query->result();
        }
        return FALSE;
    }

    public function all_saved_page() {
        $sql = 'SELECT GROUP_CONCAT(slug) as all_slugs FROM pages';
        if ($query = $this->db->query($sql)) {
            return $query->result();
        }
        return FALSE;
    }

    public function all_available_pages() {
        if (file_exists($this->_page_file)) {
            $str = @file_get_contents($this->_page_file);
            return json_decode($str, true);
        }
    }

    public function page_name_by_slug($slug) {
        if (file_exists($this->_page_file)) {
            $str = @file_get_contents($this->_page_file);
            return json_decode($str, true)[$slug];
        }
    }

    public function update_page($data, $id) {
        $this->db->cache_delete_all();
        if ($this->db->update(TBL_PAGES, $data, ['id' => $id]))
            return TRUE;
        return FALSE;
    }

    public function delete_page($id) {
        $this->db->cache_delete_all();
        if ($this->db->delete(TBL_PAGES, ['id' => $id]))
            return TRUE;
        return FALSE;
    }

    public function get_application_type($data = NULL) {
        $this->db->select();
        $this->db->from(TBL_APPLICATION_TYPE . ' as at');

        if (!empty($data['where']['id'])) {
            $this->db->where('id', $data['where']['id']);
        }
        if (!empty($data['where']['status'])) {
            $this->db->where('status', $data['where']['status']);
        }
        $this->db->order_by("order", "ASC");
        return $this->db->get()->result();
    }

    public function get_application_type_cache($data = NULL) {
        $this->db->cache_on();
        $result = $this->get_application_type($data);
        $this->db->cache_off();
        return $result;
    }

    public function save_application_type($data) {
        $this->db->cache_delete_all();
        if ($this->db->insert(TBL_APPLICATION_TYPE, $data))
            return TRUE;
        return FALSE;
    }

    public function update_application_type($data, $b_id) {
        $this->db->cache_delete_all();
        if ($this->db->update(TBL_APPLICATION_TYPE, $data, ['id' => $b_id]))
            return TRUE;
        return FALSE;
    }

    public function get_country_cache($data = NULL) {
        $this->db->cache_on();
        $this->db->select();
        $this->db->from(TBL_COUNTRY);

        if (!empty($data['where']['id'])) {
            $this->db->where('id', $data['where']['id']);
        }
        if (!empty($data['where']['status'])) {
            $this->db->where('status', $data['where']['status']);
        }
        $result = $this->db->get()->result();
        $this->db->cache_off();
        return $result;
    }

    public function get_country($data = NULL) {
        $this->db->select();
        $this->db->from(TBL_COUNTRY);

        if (!empty($data['where']['id'])) {
            $this->db->where('id', $data['where']['id']);
        }
        if (!empty($data['where']['status'])) {
            $this->db->where('status', $data['where']['status']);
        }
        return $this->db->get()->result();
    }

    public function change_country_status($status, $id) {
        $this->db->cache_delete_all();
        if ($this->db->update(TBL_COUNTRY, ['status' => $status], ['id' => $id]))
            return TRUE;
        return FALSE;
    }

    public function get_arrival_port($data = NULL) {
        $this->db->select();
        $this->db->from(TBL_ARRIVAL_PORT);

        if (!empty($data['where']['id'])) {
            $this->db->where('id', $data['where']['id']);
        }
        if (!empty($data['where']['status'])) {
            $this->db->where('status', $data['where']['status']);
        }
        return $this->db->get()->result();
    }

    public function get_arrival_port_cache($data = NULL) {
        $this->db->cache_on();
        $result = $this->get_arrival_port($data);
        $this->db->cache_off();
        return $result;
    }

    public function save_arrival_port($data) {
        $this->db->cache_delete_all();
        if ($this->db->insert(TBL_ARRIVAL_PORT, $data))
            return TRUE;
        return FALSE;
    }

    public function update_arrival_port($data, $id) {
        $this->db->cache_delete_all();
        if ($this->db->update(TBL_ARRIVAL_PORT, $data, ['id' => $id]))
            return TRUE;
        return FALSE;
    }

    public function get_setting_value($key) {
        $this->db->select();
        $this->db->from(TBL_SETTING . ' as s');
        $this->db->where('key', $key);
        $data = $this->db->get()->row();
        if (!empty($data)) {
            return $data->value;
        }
        return NULL;
    }

    public function update_setting_value($key, $value) {
        if ($this->db->update(TBL_SETTING, ['value' => $value], ['key' => $key]))
            return TRUE;
        return FALSE;
    }
    
    public function save_blog($data) {
        $this->db->cache_delete_all();
        if ($this->db->insert(TBL_BLOG, $data))
            return $this->db->insert_id();
        return FALSE;
    }
    
    public function update_blog($data, $id) {
        $this->db->cache_delete_all();
        if ($this->db->update(TBL_BLOG, $data, ['id' => $id]))
            return TRUE;
        return FALSE;
    }
    
    public function check_blog($data,$blog_id=NULL) {
        $msg = '';
        $this->db->select();
        $this->db->from(TBL_BLOG);
        if (!empty($blog_id)) {
            $this->db->where('id !=', $blog_id);
        }
        $where_au = '(slug = "'.$data['slug'].'" OR heading = "'.$data['slug'].'")';
        $this->db->where($where_au);
        $data =  $this->db->get()->result();
        if (!empty($data)) {
            $msg    = 'Blog Url , Blog Title or Heading already exist';
        }
        if(empty($msg)) {
            return ['status' => STATUS_SUCCESS];
        } else {
            return ['status' => STATUS_ERROR, 'msg' => $msg];
        }
    }
    
    public function blog_data($bid) {
        $sql = 'SELECT * FROM '.TBL_BLOG.' WHERE id = "' . $bid . '"';
        if ($query = $this->db->query($sql)) {
            return $query->result();
        }
        return FALSE;
    }
    
    public function save_blog_content($bid,$contents) {
        $data   = [] ;
        foreach ($contents as $content) {
            $data[]     =   [
                'blog_id'   =>  $bid,
                'type'      =>  $content['type'],
                'content'   =>  $content['data'],
                'order'     =>  $content['order']
            ];
        }
        if(!empty($data)) {           
            $this->db->insert_batch(TBL_BLOG_CONTENT, $data);
        }
        return TRUE;
    }
    
    public function blog_content_data($bid) {
        $sql = 'SELECT * FROM '.TBL_BLOG_CONTENT.' WHERE blog_id = "' . $bid . '" ORDER BY `order`';
        if ($query = $this->db->query($sql)) {
            return $query->result();
        }
        return FALSE;
    }

//    public function do_Login($data) {
//        try {
//            $encrypt_pass = $this->util->hash_create('sha256', $data['password'], HASH_PASSWORD_KEY);
//            $key = HASH_MYSQL_KEY;
//            $sql = 'SELECT au.id,au.name,au.username,au.img,au.designation FROM ' . TBL_ADMIN_USER . ' au WHERE au.username = "' . $data['username'] . '" AND au.pass = AES_ENCRYPT("' . $encrypt_pass . '","' . $key . '")';
//            if ($query = $this->db->query($sql)) {
//                if (!empty($u = $query->result())) {
//                    $this->session->set_userdata(SESSION_ADMIN, [
//                        'id' => $u[0]->id,
//                        'name' => $u[0]->name,
//                        'username' => $u[0]->username,
//                        'designation' => $u[0]->designation,
//                        'img' => $u[0]->img,
//                    ]);
//                    return TRUE;
//                }
//                return FALSE;
//            } else {
//                throw new Exception($this->db->error()['message']);
//            }
//        } catch (Exception $e) {
//            $this->global_model->saveErrorLogs($e);
//            return FALSE;
//        }
//    }
//
//
//    public function save_application_type() {
//        $data = $this->input->post();
//        $at = [];
//        foreach ($data['app_type_key'] as $k => $d) {
//            $at[$d] = [
//                'type' => $d,
//                'text' => $data['app_type'][$k],
//                'price' => $data['app_price'][$k]
//            ];
//        }
//        $adata = "'" . json_encode($at) . "'";
//        if ($this->db->update('setting', ['mata_value' => json_encode($at)], ['meta_key' => 'app_type']))
//            return TRUE;
//        return FALSE;
//    }
}
