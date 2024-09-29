<html>

	<?php 
	//	session_start(); 
		include("connect_mysql.php");//連結資料庫
	?>


	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"  />
		
	<head>
	  <title>書審Spotlight-儲存新會員中...</title>
	</head> 
	
	<body bgcolor="#ECECFF">
	  <div style="font-family: UDDig;">
		
		<header class="main-header">
			<div class="containerleft">
				<h3><a href="homepage.php" style="color:#ECF5FF;">書審Spotlight</a></h3>
				<nav>    
					<ul class="main-menu" >
						<li> <b>...</b> </li>
					</ul>	
				</nav>    
			</div>
		</header>
		
	  <div>
	</body>



	<script>
		function on_submit()
			{
				var username=document.getElementById("username") ;
				
				if(pwd.value!=pwd2.value)
				{
					alert("兩次密碼不一致，請重新輸入!");
					pwd . value="" ;
					pwd2. value="" ;
					pwd.focus () ;
					return false;
				}
			}
	</script>

	<style>
			@import url(memberpage.css);@import url ( loginpage . css );
			@font-face
			{
				font-family: UDDig;
				src: url(./org-unpack-20211227/UDDigiKyokashoN-R-01.ttf);
			}
			
	</style>
<?php

	header("Content-Type: text/html; charset=utf8");

	//if(!isset($_POST['submit']))//判斷是否有submit操作
	//{	
	//		echo"<script>alert('註冊失敗，請重來!');</script>";
	//		echo '<meta http-equiv=REFRESH CONTENT=1;url=register.php>'; //重新導向註冊頁面
	//		exit("錯誤執行");
	//}	

	
		
		
	//$settime = strtotime('+7 hours');
	//$gettime = date('YmdHis',$settime);


	if(!empty($_POST['account_']))
	{
		$NAME = $_POST["username_"]; //同參考 u_nick 暱稱
		$ACC = $_POST["account_"];
		$PW = $_POST["password_"];
		$PW2 = $_POST['password2_'];
		$EMAIL = $_POST['email_'];
		$PHONENUMBER = $_POST['phonenumber_'];
		$IDENTITY = $_POST['identity_'];  //同 lv    
	
		$PHOTO_name=$_FILES['PHOTO_']['name'];   //檔案名稱
		$PHOTO_tmp=$_FILES['PHOTO_']['tmp_name']; //檔案位置
		$PHOTO_size=$_FILES['PHOTO_']['size'];   //檔案大小
		$PHOTO_type=$_FILES['PHOTO_']['type'];   //檔案類別
	
	/*
		if (($PHOTO_type == "image/jpeg") || ($PHOTO_type == "image/png"))
		{
			$PHOTO=$gettime.".".substr(strrchr($PHOTO_name, '.'), 1);	
		}
		else
		{
			echo "檔案格式不合規定! 請重新挑選~";
			unlink($PHOTO_tmp);
			exit();
		}
	*/
		copy($PHOTO_tmp,"pic/".$PHOTO);
		unlink($PHOTO_tmp);
	
	
	
	if($ACC != null && $PW != null && $PW2 != null && $PW == $PW2)
	{	//判斷帳號密碼是否為空值 
			//確認密碼輸入的正確性
	
			$INSERT_INTO="INSERT INTO member_table(`member_id`,`username`, `account`, `password`, `photo`, `email`, `phonenumber`, `identity`) 
			VALUES (NULL,\"$NAME\",\"$ACC\",\"$PW\",\"$PHOTO\",\"$EMAIL\",\"$PHONENUMBER\",\"$IDENTITY\")";//向資料庫插入表單傳來的值的sql
	
			mysqli_query($link, 'SET NAMES utf8'); // 設定UTF8編碼
		
			$RESULT=mysqli_query($link,$INSERT_INTO); 
			mysqli_query($link,$INSERT_INTO);// 插入資料
	
			if (!$RESULT)
			{	
				die('Error: ' . mysql_error());//如果sql執行失敗輸出錯誤
			}	
			else
			{
				echo"<script>alert('註冊成功!~ 可以回到首頁進行登入囉!');</script>";
				echo '<meta http-equiv=REFRESH CONTENT=1;url=homepage.php>'; //重新導向首頁
			}
	}
	else
	{
		echo"<script>alert('註冊表單填寫不完整，大俠請重來!');</script>";
		echo '<meta http-equiv=REFRESH CONTENT=1;url=register.php>'; //重新導向	
	}
	
	}
	mysql_close($link);//關閉資料庫
	

?>

















</html>