<?php
/**
 下线请求， U 用户主动下线， A 设备强制下线
 made by maxranje
**/
require_once("../inc/config.php");
require_once("../inc/function.php");
require_once("../inc/class.php");

$globalerormsg = "";
if(isset($_GET["from"]) && isset($_GET["userip"]) && isset($_GET['wlanip'])){
	$userip = mysql_escape_string($_GET["userip"]);
	$wlanip = mysql_escape_string($_GET["wlanip"]);
	$from = mysql_escape_string($_GET["from"]);
	
	try{
		
		if(strlen($userip) <= 0 && strlen($wlanip) <= 0 && strlen($from) <= 0){
			$globalerormsg = "请求参数不正确，或请求参数为空";
			throw new AppException($globalerormsg, "userip={$userip},wlanip={$wlanip},from={$from}", true, false);
		}
		
		$conn = dbconnection();
		$query = "update portal_userlog set validate = '0' where userip = {$userip}";
		$result = $conn -> query ($query);
		if( !$result && $from == "U" ){
			$globalerormsg = "下线失败, 请重试....";
			throw new AppException($globalerormsg, "query={$query}; from=".$from, true, false);
		}
				
		if($from == "U"){
			$query = "select baskey from portal_bas where wlanip = {$wlanip}";
			$result = $conn -> query($query);
			if(!$result || $result -> num_rows <= 0){
				$globalerormsg = "下线请求参数不正确， 请重试";
				throw new AppException($globalerormsg, "wlanip={$wlanip}", true, false);
			}
			$res = $result -> fetch_assoc();
			$result -> free_result();
			
			$host = "udp://".$res["wlanip"].":2000";
			$portalpacket = new PortalPacket(0x02, 0x00, $res["key"], $userip);
			$requestpacket = $portalpacket -> createpacket(0x05, "", "", 0x00);
			$portalsocket = new PortalSocket();
			$portalsocket -> createsocket($host);
			$portalsocket -> sendpacket($requestpacket);
			$globalerormsg = "请求下线成功";
		} 
		
	}catch(AppException $ae){
		$ae -> log();
	}catch(Exception $e){
		error_log(date("[y-m-d H:i:s]", time())."- {$e->getMessage()}", 3, "error.log");
	}	
}else{
	$globalerormsg = "请求下线参数不正确， 请重新尝试";
}
?>
<html>
	<head><style type="text/css">
	.errormsg{width:40%;height:150px;position:absolute;left:50%;top:30%;transform:translate(-50%, -50%);-webkit-transform:translate(-50%, -50%);
	-moz-transform:translate(-50%, -50%);-ms-transform:translate(-50%, -50%);background-color:#fff;box-shadow: 5px 5px 4px #888888;text-align:center;
	line-height:140px;border:1px solid #e2e2e2;padding:10px;}@media only screen and (max-width: 800px) {.errormsg{height:300px;}}p{font: 14.5px "Trebuchet MS", sans-serif; position:relative; top:30%;}
	</style></head>
	<body><div class="errormsg" ><p style="text-align:center"><?=$globalerormsg?></p></div></body>
</html>
