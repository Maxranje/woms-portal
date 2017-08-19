<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboarts extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('func');
		$this->data = array('state'=>'success', 'reson'=>'Mission Complete');
	}

    /***
        *******************************************
        *          show info page                 *
        *******************************************
    ***/
	public function index (){
		$sql = "select count(*) oluser from user where state = '1' and valid = '1' and cid =?";
		$res = $this->db->query($sql, array($_SESSION['cid']));
		$result = $res->row_array();
		$this->data['oluser'] = $result['oluser'];

		$sql = "select count(*) alluser from user  where cid =?";
		$res = $this->db->query($sql, array($_SESSION['cid']));
		$result = $res->row_array();
		$this->data['alluser'] = $result['alluser'];

		$sql = "select count(*) apcount from ap  where cid =?";
		$res = $this->db->query($sql, array($_SESSION['cid']));
		$result = $res->row_array();
		$this->data['apcount'] = $result['apcount'];

		$sql = "select count(*) funscount from wxfuns  where cid =?";
		$res = $this->db->query($sql, array($_SESSION['cid']));
		$result = $res->row_array();
		$this->data['funscount'] = $result['funscount'];

		$this->load_view('dashboarts', $this->data);
	}



    /***
        *******************************************
        *          get ajax data                  *
        *******************************************
    ***/
	public function getchartsdata (){
		try{
			$sql = "select count(*) count, left(FROM_UNIXTIME(createtime),10) time 
				from user  where cid = ? GROUP BY left(FROM_UNIXTIME(createtime),10)";
			$res = $this->db->query($sql, array($_SESSION['cid']));
			$result = $res->result_array();
			if($res->num_rows()==1 && $result[0]['count'] == 0){
				throw new Exception ();
			}
			$this->data['xAxis'] = array();
			$this->data['yAxis'] = array();			
			foreach ($result as $row) {
				$this->data['xAxis'][] = $row['time'];
				$this->data['yAxis'][] = $row['count'];
			}
			$this->data['xAxis'] = empty($this->data['xAxis'])?date('Y-m-d', time()):implode(",", $this->data['xAxis']);
			$this->data['yAxis'] = empty($this->data['yAxis'])?"0":implode(",", $this->data['yAxis']);

			$query = "select location, count(apid) value from ap  where cid = ? group by location";
			$res = $this->db->query ($query, array($_SESSION['cid']));
			$result = $res->result_array();
			$this->data['location'] = array();
			foreach ($result as $row) {
				$array = array();
				$array['name'] = $row['location'];
				$array['value'] = $row['value'];
				$this->data['location'][] = $array;
			}
		}
		catch(Exception $ec){
			$this->data['xAxis'] = "";
			$this->data['yAxis'] = "";
			$this->data['location'] = array();
		}
		echo json_encode($this->data);
		exit();
	}

	public function getaplist (){
		try{
			$sql = "select apname, createtime from ap where cid = ? ";
			$res = $this->db->query($sql , array($_SESSION['cid']));
			$result = $res->result_array();
			$total = $res->num_rows();
			$this->data['rows'] = array();
			foreach ($result as $row) {
				$ap = array();
				$ap['apname'] = $row['apname'];
				$ap['time'] = $this->func->sec2time($row['createtime']);
				$this->data['rows'][] = $ap;
			}
			$this->data['total'] = $total;
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();
	}

	public function getuserlist (){
		try{
			$time = time() - 900;
			$sql = "select a.apname, u.uname from ap a, user u where a.cid = ? and u.apid = a.apid and u.state='1'";
			$res = $this->db->query($sql , array($_SESSION['cid']));
			$result = $res->result_array();
			$total = $res->num_rows();
			$this->data['rows'] = array();
			foreach ($result as $row) {
				$ap = array();
				$ap['apname'] = $row['apname'];
				$ap['uname'] = $row['uname'];
				$this->data['rows'][] = $ap;
			}
			$this->data['total'] = $total;
		}
		catch (Exception $ec) {
			$this->data["state"] = "failed";
			$this->data["reson"] = $ec->getMessage();
		}
		echo json_encode($this->data);
		exit();
	}	

}
?>