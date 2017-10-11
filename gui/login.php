<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>
<meta charset="utf-8" />
<title>Web Application</title>
<meta name="renderer" content="webkit" />
<meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link rel="shortcut icon" type="image/x-icon" href="/res/images/favicon.ico" media="screen" />
<link rel="stylesheet" href="/res/css/app.v2.css" type="text/css" />
<link rel="stylesheet" href="/res/css/font.css" type="text/css" cache="false" />
<!--[if lt IE 9]> <script src="js/ie/html5shiv.js" cache="false"></script> <script src="js/ie/respond.min.js" cache="false"></script> <script src="js/ie/excanvas.js" cache="false"></script> <![endif]-->
</head>
<body>
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <div class="container aside-xxl"> <a class="navbar-brand block">RAISECOM</a>
        <section class="panel panel-default bg-white m-t-lg">
            <header class="panel-heading text-center"> <strong class="infomsg">
                <?php if(isset($reson)) echo '<span class="text-danger">'.$reson.'</span>'; else echo '用户登录'; ?> 
            </strong> </header>
            <form action="/corp/login" method="post" class="panel-body wrapper-lg m-b-n-lg" data-validate="parsley">
                <div class="form-group">
                    <label class="control-label">帐号</label>
                    <input type="text" class="form-control parsley-validated" data-required="true" id='un' name="username" placeholder="">
                </div>
                <div class="form-group">
                    <label class="control-label">密码</label>
                    <input type="password" class="form-control parsley-validated" data-required="true" id="pwd" name="password" placeholder="">
                </div>
                <div class="form-group"><input type="hidden" name="secret" value="<?=$secret ?>"></div>                
                <button type="submit" class="btn btn-primary">登录</button>
                <div class="line line-dashed"></div>
                 <p class="text-center"> <small>如忘记账户或密码请联系管理员</small> </p> 
            </form>
        </section>
    </div>
</section>
<!-- footer -->
<footer id="footer">
  <div class="text-center padder">
    <p> <small>©2017 RAISECOM. All rights reserved | Tech Support</small> </p>
  </div>
</footer>
<script src="/res/js/jquery/jquery.min.js"></script>
<script src="/res/js/app.v2.js"></script>
<script src="/res/js/parsley/parsley.min.js" cache="false"></script>
<script src="/res/js/parsley/parsley.extend.js" cache="false"></script>
<script type="text/javascript">
var Chrome = window.navigator.userAgent.indexOf('Chrome/');
var Firefox = window.navigator.userAgent.indexOf('Firefox/');
var Ie = window.navigator.userAgent.indexOf('MSIE');
if(Chrome != -1){
    var v = window.navigator.userAgent.split('Chrome/');
    if(parseFloat(v[1].substring(0,3)) < 49){
        alert("浏览器版本过低，为了避免使用上的困扰，请升级该到最新版本");
    }
}

if(Firefox != -1){
    var v = window.navigator.userAgent.split('Firefox/');
    if(parseFloat(v[1].substring(0,3)) < 54){
        alert("浏览器版本过低，为了避免使用上的困扰，请升级该到最新版本");
    }
}
if(Ie != -1){
    var v = window.navigator.userAgent.split('MSIE');
    if(parseFloat(v[1].substring(0,2)) < 11){
        alert("浏览器版本过低，为了避免使用上的困扰，系统推荐使用Chrome, Firefox, IE11+, 或者浏览器切换到极速模式");
    }
}
</script>
</body>
</html>