<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Weixin extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library('Func');
		$this->data = array('state'=>'success', 'reson'=>'Mission Complete');
	}

	public function index (){
		try{

			$cid = $this->input->get('cid');
			$token = $this->input->get('token');
			if(!$cid || !$token){
				throw new AppException("Error Processing Request", "cid=".$cid." | token=".$token);
			}

			$res = $this->db->query("select * from cooperater where cid = ?", array($cid));
			if($res->num_rows() == 0){
				throw new Exception ('请求失败, 商户不存在');
			}
			$corp = $res->row_array();
			$wxkeyword 		= "wifi";
			$wxlinktitle 	= empty($corp['wxlinktitle']) ? "欢迎使用无线上网热点" : $corp['wxlinktitle'];
			$wxlinkcontent  = empty($corp['wxlinkcontent']) ? "您可以向微信公众号发送自定义的关键字获取登录上网的图文链接，只要轻轻一点就可以轻松上网，告别繁琐的微信登录方式；新关注公众号用户还可以自动获取上网链接。还等什么呢，赶紧拿起手机关注微信公众号，点击上网吧!!" : $corp['wxlinkcontent'];
			$wxlinklogo		= empty($corp['wxlinklogo']) ? "" : $corp['wxlinklogo'];
			$wxlinkurl 		= empty($corp['wxlinkurl']) ? "http://www.baidu.com" : $corp['wxlinkurl'];

			if($token != $corp['wxtoken']){
				throw new Exception ('请求失败, token不正确');
			}
			if(isset($_GET['echostr']) && !empty($_GET['echostr'])){
				$this->checksignature ();
			}

			$request = file_get_contents("php://input");
			if($request == false){
				throw new Exception ('请求失败, 无法获取xml 数据');
			}
			$xml = simplexml_load_string($request, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromuser = $xml->FromUserName;
			$touser = $xml->ToUserName;
			$key = $xml->Content;

            $picTpl = "
            <xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                    <item>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    <PicUrl><![CDATA[%s]]></PicUrl>
                    <Url><![CDATA[%s]]></Url>
                    </item>
                    </Articles>
                    <FuncFlag>1</FuncFlag>
            </xml> ";



			$keytip = '
			<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%d</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<MsgId>1234567890123456</MsgId>
			</xml>';


			// 关注 或者是回复关键字
			$this->db->trans_start();
			if(($xml->MsgType == "event" && $xml->Event == "subscribe") || ($key == $wxkeyword) ){
				$token = $this->func->getToken (16);
				$query = "insert into wxtoken (`cid`, `openid`,`type`, `token`, `issuetime`, `expiretime`, `state`) value (?,?,?,?,?,?,?)";
				$res = $this->db->query ($query, array($cid, $fromuser, 'w', $token, time(), time() + 6000, '1'));
				if($this->db->affected_rows() == 0){
					throw new AppException ("添加微信用户令牌失败", "openid=".$fromuser);
				}
				$query = "select id from wxfuns where cid = ? and openid = ?";
				$res = $this->db->query ($query, array($cid, $fromuser));
				if($res->num_rows () == 0 ){
					$query = "insert into wxfuns (`cid`, `openid`,`type`, `status`, `createtime`, `authtime`) value (?,?,?,?,?,?)";
					$this->db->query ($query, array($cid, $fromuser, 'w', '1', time(), time()));				
				}else {
					$this->db->query("update wxfuns set authtime = ?", array(time()));
				}
				$this->db->trans_commit();
				$wxlinkurl = $wxlinkurl."?wxcode=".$token;
				$response = sprintf($picTpl, $fromuser, $touser, time(), 'news',$wxlinktitle, $wxlinkcontent, $wxlinklogo, $wxlinkurl);

				echo $response;
				exit();
			} else if($xml->MsgType == "event" && $xml->Event == "unsubscribe")  {
				// 取消关注， 删除funs 内容
				$query = "delete from wxfuns where openid = ? and cid = ?";
				$res = $this->db->query ($query, array($cid, $fromuser));
				$this->db->trans_commit();
			} else {
				$ret = "回复".$wxkeyword.", 获取上网连接";
				$response = sprintf($keytip, $fromuser, $touser, time(), 'text', $ret);
				echo $response;
				exit;				
			}

		}catch (AppException $ec){
			$this->db->trans_rollback();
			$ec->log_message();
		}catch (Exception $ee){
			$this->db->trans_rollback();
			echo $ee->getMessage();
		}
		echo "";
		exit;
	}


	public function checksignature (){
		$signature = $_GET['signature'];
		$timestamp = $_GET['timestamp'];
		$nonce = $_GET['nonce'];
		$token = $_GET['token'];

		$array = array($token, $timestamp, $nonce);
		sort($array, SORT_STRING );
		$sh = sha1(implode($array, ""));
		if($sh == $signature){
			echo $_GET['echostr'];
		}else{
			echo "";
		}
		exit();
	}
}
?>