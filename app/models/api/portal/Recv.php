<?php
ignore_user_abort(true);
set_time_limit(0);

try{
	
	if(($socket = stream_socket_server("udp://127.0.0.1:50100", $errno, $errstr, STREAM_SERVER_BIND)) == false){
		throw new Exception("[".date("y-m-d H:i:s", time())."] - stream_socket_server errno={$errno} errstr={$errstr}");
	}
	echo "start listing 50100 port \r\n";
	
	do{
		try{
			$package = stream_socket_recvfrom($socket, 1024, 0, $address);
			$key_wlanip = substr($address, 0, strpos($address, ":"));
			$packarray = unpack("C*", $package);
			$key_userip = implode(".",array($packarray[9], $packarray[10], $packarray[11], $packarray[12]));
			
			if($packarray[2] == 0x08){
				if(($fp = fsockopen("127.0.0.1", 80, $errno, $errstr)) == false){
					throw new Exception("无法连接服务器, 通知平台用户下线失败");
				}
				fwrite($fp, "GET /portal/corp/logout.php?from=A&userip=".$key_userip."&wlanip={$key_wlanip} \r\n");
				fclose($fp);
			}
			
		}catch (Exception $e){
			error_log(date("[y-m-d H:i:s]", time())."- {$e->getMessage()}", 3, "error.log");
		}
	} while($package !== false);
	
}catch(Exception $e){
	$globalerrormsg = date("[y-m-d H:i:s]", time())."- {$e->getMessage()}";
	error_log($globalerrormsg, 3, "error.log");
}
?>