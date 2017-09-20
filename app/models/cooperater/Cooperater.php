<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cooperater extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->data = array('state'=>'success', 'reson'=>'Mission Complete');
    }

    /***
        *******************************************
        *          show info page                 *
        *******************************************
    ***/
    public function login() {   
        try {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $secret = $this->input->post('secret');
            if(!$username || !$password || !$secret){
                $data['secret'] = $this->getSecretCode ();
                $_SESSION['secret'] = $data['secret'];
                $this->load_view('login', $data);
            }

            if ($secret != $_SESSION['secret']){
                throw new Exception ("校验参数不正确");
            }

            $sql = "select cid, logo from cooperater where binary cpun = ? and cppw = ?";
            $res = $this->db->query ($sql, array($username, $password));
            if(!$res || $res->num_rows() == 0) {
                throw new Exception ('用户名密码错误');
            }
            $row = $res->row_array();
            $_SESSION['cid'] = $row['cid'];
            $_SESSION['corpname'] = $username;
            $_SESSION['corplogo'] = $row['logo'];

            redirect("/corp", 'location', 302);
        }
        catch(Exception $tt) {
            $this->data['state'] = "failed";
            $this->data['reson'] = $tt->getMessage(); 
            $this->data['secret'] = $this->getSecretCode();
            $_SESSION['secret'] = $this->data['secret'];
            $this->load_view('login', $this->data);
        }
    }

    public function setting (){
        if(isset($_POST) && !empty($_POST)){
            try{
                $this->data['state'] = "up";
                $nickname = $this->input->post('nickname');
                $industry = $this->input->post('industry');
                $manage = $this->input->post('name_manager');
                $phone = $this->input->post('phone');
                $email = $this->input->post('email');
                $qq = $this->input->post('qq');

                if(!$nickname || !$industry || !$manage || !$phone){
                    throw new Exception ('传递参数不正确');
                }

                $config['upload_path']      = FCPATH.'./res/images/corplogo';
                $config['allowed_types']    = 'gif|jpg|png';
                $config['overwrite']        = true;
                $config['file_name']        = md5($_SESSION['cid']);
                
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('toppic')){
                    throw new Exception($this->upload->display_errors("<span>","</span>"));
                } 
                $filename = $this->upload->data('file_name');
                $this->db->trans_start();
                $sql = "update cooperater set nickname=?,industry=?,name_manager=?,email=?,qq=?, logo=?  where cid = ?";
                $res = $this->db->query ($sql, array($nickname, $industry, $manage, $email, $qq, $filename, $_SESSION['cid']));
                if(!$res) {
                    throw new Exception ('更新用户个人资料数据失败, 请重试');
                }
                $this->db->trans_commit();
                $_SESSION["corplogo"] = $filename;
                $this->data['reson'] = "更新用户个人资料数据成功";
               
            }catch(Exception $ec){
                $this->db->trans_rollback();
                $this->data['reson'] = $ec->getMessage();
                $this->data['state'] = "falied";
            }
        }

        $query = "select * from cooperater where cid = ?";
        $res = $this->db->query ($query, array($_SESSION['cid']));
        if(!$res || $res->num_rows() == 0){
            throw new Exception("用户竟然你妈不存在");
        }
        $cpt = $res->row_array();
        $this->data['cpt'] = $cpt;
        $this->load_view('setting', $this->data);
    }


    public function getsysnotice (){
        try {
            $res = $this->db->query ("select title, addtime, id, content from sysnotice order by addtime");
            $row = $res->result_array();
            foreach ($row as $t) {
                $array = array();
                $array['addtime']   = date('Y-m-d', $t['addtime']);
                $array['title']     = $t['title'];
                $array['id']        = $t['id'];
                $array['content']   = $t['content'];
                $this->data['notice'][] = $array;
            }
            
            $this->data['size'] = count($row);
        }
        catch(Exception $tt) {
            $this->data['state'] = "failed";
            $this->data['reson'] = $tt->getMessage(); 
        }      
        echo json_encode($this->data);
        exit();
    }


    private function getSecretCode () {
        return  strtoupper( md5(mt_rand(11111, 99999)) );
    }


    /***
        *******************************************
        *          get ajax data                  *
        *******************************************
    ***/
    
 
}
?>