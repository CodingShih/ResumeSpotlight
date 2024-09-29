<?php 

	session_start(); 
	//$_SESSION=array();
	session_destroy();
/*	echo '登出中......謝謝您的使用!';
	echo '<meta http-equiv=REFRESH CONTENT=1;url=login.php>';
	*/
	
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php

//  課本教的
//將session清空

 
$_SESSION['account']=NULL;
$_SESSION['username']=NULL;
$_SESSION['password']=NULL;
$_SESSION['email']=NULL;
$_SESSION['phonenumber']=NULL;
$_SESSION['identity']=NULL;
$_SESSION['photo']=NULL;
 
unset($_SESSION['account']);
unset($_SESSION['username']);
unset($_SESSION['password']);
unset($_SESSION['email']);
unset($_SESSION['phonenumber']);
unset($_SESSION['identity']);
unset($_SESSION['photo']);
 
echo '登出中......';
echo '<meta http-equiv=REFRESH CONTENT=1;url=login.php>';




?>