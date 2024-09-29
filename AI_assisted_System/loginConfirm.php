<?php  // 做個單純的php，負責處理登入的動作
	include("connect_mysql.php");//連結資料庫
	

	$ACC = $_POST["account_"];
	$PW =$_POST["password_"];  
	
	// Processing form data when form is submitted
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$SELECT="SELECT * FROM member_table WHERE account='$ACC'";
		//$SELECT="SELECT * FROM member_table WHERE account='$ACC' && password='$PW'";
		
		$RESULT=mysqli_query($link,$SELECT);


		$sql_findID = "SELECT * FROM member_table WHERE account = '".$_POST['account_']."'";
		$data = mysqli_query($link, $sql_findID);
		$user = mysqli_fetch_assoc($data);
		
		
		//if (mysqli_num_rows($RESULT)==1 && $PW==mysqli_fetch_assoc($RESULT)["password"])
			
		if (password_verify($PW,mysqli_fetch_assoc($RESULT)["password"])) //解密hash
		//if (mysqli_num_rows($RESULT)==1 && $PW==mysqli_fetch_assoc($RESULT)["password"])
		{
			session_start();  // 啟用交談期
			//$ROW=mysqli_fetch_assoc($RESULT);
			
			$_SESSION["loggedin"]=true;  // 成功登入的session 變數
			
				
			$_SESSION['member_id']=$user['member_id'];
			$_SESSION['username']=$user['username'];
			$_SESSION['account']=$user['account'];
			$_SESSION['password']=$user['password'];
			$_SESSION['email']=$user['email'];
			$_SESSION['phonenumber']=$user['phonenumber'];
			$_SESSION['identity']=$user['identity'];
			$_SESSION['photo']=$user['photo'];
			
			//去member.php 把 查詢成員資料那邊 放過來這  存進 session 試試看
			/*		
			$_SESSION['username']=mysqli_fetch_assoc($RESULT)["username"];
			$_SESSION['account']=mysqli_fetch_assoc($RESULT)["account"];
			$_SESSION['password']=mysqli_fetch_assoc($RESULT)["password"];
			$_SESSION['email']=mysqli_fetch_assoc($RESULT)["email"];
			$_SESSION['phonenumber']=mysqli_fetch_assoc($RESULT)["phonenumber"];
			$_SESSION['identity']=mysqli_fetch_assoc($RESULT)["identity"];
		*/
				
			//$_SESSION['photo']=mysqli_fetch_assoc($RESULT)["photo"];
			echo"<script>alert('登入成功! 歡迎使用書審Spotlight!');</script>";
			header("refresh:1;url=homepage.php");
			
		}
		else
		{
			echo"<script>alert('帳號或密碼錯誤.. 請重新登入!');</script>";
			echo '<meta http-equiv=REFRESH CONTENT=1;url=login.php>'; //重新導向	
			//$_SESSION["loggedin"]=false;
		}
	}
	else
	{
		echo"<script>alert('OH NO Something wrong  TAT');</script>";
		echo '<meta http-equiv=REFRESH CONTENT=1;url=login.php>'; //重新導向	
	}
	 mysqli_close($link);
	
	
	
	
	
	/* 在php裡面寫function 酷酷  
	
	function function_alert($message) 
	{ 
      
    // Display the alert box  
    echo "<script>alert('$message');
     window.location.href='index.php';
    </script>"; 
    return false;
	} 
	
	function_alert("帳號或密碼錯誤.. 請重新登入!");
	*/
	
				
				
	//////////////////////  下面的PHP 暫時不用
		
	//$ROW = @mysql_fetch_row($RESULT);
	
	//$ROW=mysqli_fetch_assoc($RESULT);		
	//$total_records = mysqli_num_rows($RESULT);	
	/*
	if ($ACC != null && $PW != null && $ROW[3]==$ACC && $ROW[4]==$PW)
	{ //參考 https://dreamtails.pixnet.net/blog/post/23583385 
		//將帳號寫入session，方便驗證使用者身份
		$_SESSION['account']=$ACC;
		echo"<script>alert('登入成功! 歡迎使用書審Spotlight!');</script>";
		echo '<meta http-equiv=REFRESH CONTENT=1;url=homepage.php>'; //重新導向	
	}
	else
	{
        echo"<script>alert('登入失敗... 請重新再登入');</script>";
        echo '<meta http-equiv=REFRESH CONTENT=1;url=login.php>';
	}
	
	
	*/
	//echo $_SESSION['username'];
			/*
	
			if (!empty($_POST["account_"])
			{
				$ACC = $_POST["account_"];
				$PW =($_POST["password_"]);  //md5 雜湊加密函數
				
				$SELECT="SELECT * FROM member_table WHERE account='$ACC' && password='$PW'";
				
				$RESULT=mysqli_query($link,$SELECT);//執行
				
				$ROW=mysqli_fetch_assoc($RESULT);
				
				$total_records = mysqli_num_rows($RESULT);
				
				if ($total_records == 1)
				{
					echo"登入成功!";
					
					$_SESSION['username']=$ROW['username'];
					$_SESSION['account']=$ROW['account'];
					$_SESSION['password']=$ROW['password'];
					$_SESSION['email']=$ROW['email'];
					$_SESSION['phonenumber']=$ROW['phonenumber'];
					$_SESSION['identity']=$ROW['identity'];				
				
					$_SESSION['photo']=$ROW['photo'];
					header('location:homepage.php');
				}
				else
				{
					echo "帳號或密碼輸入錯誤!";
				}
				
			}	
			
			
			*/
?>
<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/>
					<title>書審Spotlight-登入處理中-逼逼逼</title>
				</head> 
	
				<body>
					<h5>

					</h5>
				</body>
			</html>

