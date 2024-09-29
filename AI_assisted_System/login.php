<?php // index 登入頁面，session的確認與登入表單 
	//後面接著 loginConfirm.php 處理
	//include("connect_mysql.php");//連結資料庫
	session_start();  // 啟用交談期
	//-------------
	if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
	{
		header("location:homepage.php");
		exit; // 跳出以免重複轉址多次
	}
?>
<html>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css"/>
<link rel="icon" type="image/x-icon" href="./photos/_spotlight_web_icon.ico" />
	<head>
	  <meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/>
	  <title>書審Spotlight-登入頁面</title>
	</head> 
	
	<body class="bg-success">
	  <div style="font-family: UDDig;">
		<header class="main-header">
			<div class="containerleft">
				<b><a href="homepage.php" 
				style="color:#ECF5FF; text-decoration:none; font-size:35px;">書審Spotlight</a></b>
		
			</div>
		</header>
		
	
	<!--    置中表格  -->
	<form method="post" action="loginConfirm.php" name="LOGINForm"
	onsubmit="return VAForm()"style="height:100%; width:100%;">

				<table class="signin" style="width:650px; height:400px; border:8px #46A3FF dashed ;" border="3" cell padding="10">	
					<!--<caption>-->
						<br/>
						<h2 class="h2 mb-2 fw-normal" align='center' >
						<b>	<!--<span style="font-size:28px">  -->
								登入
						</b>	<!--</span>  -->
						</h2>
					<!--</caption>-->
				
					<tr>
						<th>
							<span style="font-size:20px">
							&emsp;&emsp;帳號:  
							</span>
							<div class="form-floating">
								<input type="text" id="floatingAccount" name="account_" class="form-control" style="width:550px; left:40px;" placeholder=" " maxlength="20" /> 
								<label for="floatingAccount" style="font-size:18px; color:#8E8E8E;">輸入帳號</label>
							</div>

							<br/>
							<span style="font-size:20px">
								&emsp;&emsp;密碼: 
							</span>
							
							<div class="form-floating">
								<input type="password" id="pwd" name="password_" class="form-control" style="width:550px; left:40px;" placeholder=" " maxlength="20" />							
								<label for="pwd" style="font-size:18px; color:#8E8E8E;">輸入密碼</label>
								<i id="checkEye" class="fas fa-eye-slash" style="left:550px;"></i>
							</div>
							<!-- 眼睛查看密碼./photos/eye_close.png	  //border-radius:8px; -->
							

								&emsp;&emsp;&emsp;&emsp;
							<div class="leftAlittle">
								<a href="register.php" style="font-size:20px;" >還沒註冊嗎?</a>
							</div>
								<br/><br/>
	
								<input class="w-40 btn btn-lg btn-primary" type="submit" 
								style="left:200px; width:200px; height:50px; font-size:18px; border-radius:10px;" name="button" value="確定登入" />
						</th>
					</tr>

				</table>
	</form>


		<style>
			@import url(loginpage.css);@import url ( loginpage . css );
			@font-face
			{
				font-family: UDDig;
				src: url(./org-unpack-20211227/UDDigiKyokashoN-R-01.ttf);
			}
			
			.leftAlittle
			{
				text-align: justify;
				position:relative;
				left:470px;
			}
			.signin label
			{
				left:40px;
			}
			
			.bg-success 
			{
			  --bs-bg-opacity: 1;
			  background-color: #ECECFF !important;
			}

			
		</style>

		<script>
			var checkEye = document.getElementById('checkEye');
			var pwd = document.getElementById('pwd');
		
			//2.註冊事件	
			 checkEye.addEventListener("click", function(e)
			 {
				if(e.target.classList.contains('fa-eye-slash'))
				{
					pwd.setAttribute('type','text');
					e.target.classList.remove('fa-eye-slash');
					e.target.classList.add('fa-eye');
					
				}
				else
				{
					e.target.classList.remove('fa-eye');
					e.target.classList.add('fa-eye-slash');
					pwd.setAttribute('type','password');
				}
			});
			
			function VAForm()  // 註冊表單防呆
			{
				var ACCO = document.forms["LOGINForm"]["account_"].value;
				var PASS = document.forms["LOGINForm"]["password_"].value;
				
				if(ACCO == "")
				{
					alert("沒有輸入帳號哦..!");
					return false;
				}
				if(PASS == "")
				{
					alert("沒有輸入密碼餒...!");
					return false;
				}
			}
						  
		</script>	
	  </div>
	</body>
</html>


