<?php
include("connect_mysql.php");//連結資料庫

$settime=strtotime('+7 hours');
$gettime=date('YmdHis',$settime);

/*
function check_inputsa($value)
{
	//去除斜槓
	if (get_magic_quotes_gpc())
	{
		$value = stripslashes($value);
	}
	
	// 如果不是數字则加引號
	if (!is_numeric($value))
	{
		$value = "'" . mysql_real_escape_string($value) . "'";// 過濾亂輸入的逃脫字元
	}
	return $value;
}
*/


if($_SERVER["REQUEST_METHOD"]=="POST")
{
	$NAME = $_POST['username_'];
	//$NAME = mysql_real_escape_string($NAME);
	
	$ACC = $_POST['account_'];
	//$ACC = mysql_real_escape_string($ACC);
	
	$PW = $_POST['password_'];
	//$PW = mysql_real_escape_string($PW);
	$PW = password_hash($PW,PASSWORD_DEFAULT);// hash 嘿咻 划龍舟
	
	$PW2 = $_POST['password2_'];
	//$PW2 = mysql_real_escape_string($PW2);
	
	$EMAIL = $_POST['email_'];
	//$EMAIL= mysql_real_escape_string($EMAIL);
	
	$PHONENUMBER = $_POST['phonenumber_'];
	//$PHONENUMBER = mysql_real_escape_string($PHONENUMBER);

	
	/*
	$NAME = mysql_real_escape_string($_POST['username_']);
	$ACC = mysql_real_escape_string($_POST['account_']);
	$PW = mysql_real_escape_string($_POST['password_']);
	$EMAIL= mysql_real_escape_string($_POST['email_']);
	$PHONENUMBER = mysql_real_escape_string($_POST['phonenumber_']);
	------------------------------------------------------------------
	$NAME = check_inputsa($_POST['username_']);//同參考 u_nick 暱稱
	
	
	$ACC = check_inputsa($_POST['account_']);
	$PW = check_inputsa($_POST['password_']);
	$PW = password_hash($PW,PASSWORD_DEFAULT);
	$PW2 = check_inputsa($_POST['password2_']);
	$EMAIL = check_inputsa($_POST['email_']);
	$PHONENUMBER = check_inputsa($_POST['phonenumber_']);
	*/
	
	$IDENTITY = $_POST['identity_'];  //同 lv    
	
	$check_Redundant="SELECT * FROM member_table WHERE account='".$ACC."' && phonenumber='".$PHONENUMBER."' ";
	$P_name=$_FILES['my_photo']['name'];  // 檔案名稱
	$P_tmmp=$_FILES['my_photo']['tmp_name'];  // 檔案位置
	$P_size=$_FILES['my_photo']['size'];  // 檔案大小
	$P_type=$_FILES['my_photo']['type'];  // 檔案類別
/*	暫不用的註解
	
	if (($P_type == "image/jpeg") || ($P_type == "image/png")) 
	{	
		$PhoTO=$gettime.".".substr(strrchr($p_name, '.'), 1);
	}
	else
	{
		echo "檔案格式不合規定! 請重新挑選~";
		unlink($P_tmmp);
		exit();
	}
	
	copy($P_tmmp,"pic/" . $PhoTO);
	unlink($P_tmmp);
*/	
	$PhoTO=$ACC . "-" . $P_name;
	

/////////////////////////上傳大頭照
	$allow_type_img= array("png","jpg","jpeg");
	$type_img= explode(".",$P_name);
	
	$imgg= end($type_img);

//限制上傳檔案類型 僅照片
if (!in_array($imgg,$allow_type_img))
{
	echo "<script>alert('請勿上傳圖片以外的檔案..!');</script>";
}
else
{
	

# 檢查檔案是否上傳成功
	if ($_FILES['my_photo']['error'] === UPLOAD_ERR_OK)
	{/*
		echo '檔案名稱: ' . $_FILES['my_photo']['name'] . '<br/>';
		echo '檔案類型: ' . $_FILES['my_photo']['type'] . '<br/>';
		echo '檔案大小: ' . ($_FILES['my_photo']['size'] / 1024) . ' KB<br/>';
		echo '暫存名稱: ' . $_FILES['my_photo']['tmp_name'] . '<br/>';
	*/
		# 檢查檔案是否已經存在
		if (file_exists("C:\AppServ\www\AI_assisted_System\UPLOAD_FILE_member_photo\\" . $ACC . "-" . $P_name))
		{
			echo '檔案已存在。<br/>';
		} 
		else 
		{
			$file = $_FILES['my_photo']['tmp_name'];
			
			//$dest = 'upload/' . $_FILES['my_photo']['name'];
			$dest = "C:\AppServ\www\AI_assisted_System\UPLOAD_FILE_member_photo\\" . $ACC . "-" . $P_name;
			
			# 將檔案移至指定位置
			move_uploaded_file($file, $dest);
			//echo "<script>alert('檔案上傳成功! ');</script>";
		}
	} 
	else 
	{
		echo '錯誤代碼：' . $_FILES['my_photo']['error'] . '<br/>';
		exit();
	}
	
	
	if (mysqli_num_rows(mysqli_query($link,$check_Redundant))==0)
	{ //檢查帳號是否重複
		$INSERT_INTO="INSERT INTO member_table(`member_id`,`username`, `account`, `password`, `photo`, `email`, `phonenumber`, `identity`) VALUES (NULL,\"$NAME\", \"$ACC\", \"$PW\", \"$PhoTO\", \"$EMAIL\", \"$PHONENUMBER\", \"$IDENTITY\")";//向資料庫插入表單傳來的值的sql
		//mysqli_query($link, 'SET NAMES utf8'); // 設定UTF8編碼
		
		if (mysqli_query($link,$INSERT_INTO))
		{
			echo"<script>alert('註冊成功!~ 可以進行登入囉! ');</script>";
			echo '<meta http-equiv=REFRESH CONTENT=1;url=homepage.php>'; //重新導向首頁
			exit();
		}
		else
		{
			echo "Error creating table: " . mysqli_error($link);
		}
	}
	else
	{
		echo"<script>alert('該帳號已有人使用! 請重新註冊，謝謝!');</script>";
        echo '<meta http-equiv=REFRESH CONTENT=1;url=register.php>'; //重新導向首頁
		exit();
	}
}
	
}


		
	
	
	//mysql_close($link);//關閉資料庫
?>





<html>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css"/>
<link rel="icon" type="image/x-icon" href="./photos/_spotlight_web_icon.ico" />
	<head>
	  <meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/>
	  <title>書審Spotlight-註冊頁面</title>
	</head> 
	
	<body class="bg-success" >
	  <div style="font-family: UDDig;">
		<header class="main-header">
			<div class="containerleft">	
			<b>	
			<a href="homepage.php"
				style="color:#ECF5FF; text-decoration:none; font-size:35px;">書審Spotlight</a> 
			</b>
			</div>
		</header>
		
	
		<!--    置中表格  -->
		<form name="registerForm" action="register.php" method="post" onsubmit="return validateForm()"
		 style="background-color:#ECECFF ;" enctype="multipart/form-data">
			
			<table class="signin" style="border:8px #46A3FF dashed;" border="3" cell padding="10">	
					<br/>
					<h2 class="h2 mb-2 fw-normal" align='center' >
					<b>	<!---<span style="font-size:28px"> -->	
							註冊
					</b>	<!--</span> -->
					</h2>
				
				
				<tr>
					<th>
						<br/><br/>
						<span style="font-size:20px" autofocus>
							&emsp;請輸入使用者名稱:  
						</span>
						<div class="form-floating">      <!-- placeholder要有內容才會浮動，空格也行  -->
							<input type="text" id="floatingUsername" name="username_" class="form-control" style="" placeholder=" " /> 
							<label for="floatingUsername" style="font-size:18px; color:#8E8E8E;">您的姓名</label>
						</div>
						<br/>
						
						
						<span style="font-size:20px">
							&emsp;您的帳號:  
						</span>
						<div class="form-floating">
							<input type="text" id="floatingAccount" name="account_" class="form-control" style="" placeholder=" " maxlength="20" /> 
							<label for="floatingAccount" style="font-size:18px; color:#8E8E8E;">登入時將會使用(最多20字)</label>
						</div>
						<br/>
								
								
						<span style="font-size:20px">
							&emsp;您的密碼: 
						</span>
						<div class="form-floating">
							<input type="password" id="pwd" name="password_" class="form-control" style="" placeholder=" " maxlength="20" />							
							<label for="pwd" style="font-size:18px; color:#8E8E8E;">登入時將會使用(建議英文+數字，最多20字)</label>
							<i id="checkEye" class="fas fa-eye-slash" style=""></i>
						</div>
				
						
					<!-- <div align="left">-->
						
						<!--  &ensp;  半形空格 ，  &emsp; 全形空格，  &thinsp; 窄空格 ，&nbsp; 不換行空格  -->
						<span style="font-size:20px">
							&emsp;再次輸入您的密碼: 
						</span>
						<div class="form-floating">
							<input type="password" id="pwd2" name="password2_" class="form-control" style="" placeholder=" " maxlength="20"  /> 
							<label for="pwd2" style="font-size:18px; color:#8E8E8E;">二次確認，請和上方的密碼一樣</label>
						</div>
						
						
						<br/>
						<span style="font-size:20px">
							&emsp;您的電子信箱: 
						</span>
						<div class="form-floating">
							<input type="email" id="floatingEmail" name="email_" class="form-control"  style="" placeholder=" "  /> 
							<label for="floatingEmail" style="font-size:18px; color:#8E8E8E;">Name@example.com</label>
						</div>
						
						
						<br/>
						<span style="font-size:20px">
							&emsp;您的聯絡電話: 
						</span>
						<div class="form-floating">
							<input type="text" id="floatingPhone" name="phonenumber_" class="form-control" style="" placeholder=" " maxlength="10"	 /> 
							<label for="floatingPhone" style="font-size:18px; color:#8E8E8E;">09XX-XXX-XXX</label>
						</div>
						
						
						<br/>
						<span style="font-size:20px">
							&emsp;您的身份: 
						</span>
						
						
						<br/>
						<select class="alittleLeft" name="identity_" id="identity" style="" >
							<optgroup label="請選擇您的身份">
								<option value="2" <?php echo ($IDENTITY==2)?"selected":"";; ?>>老師</option>
								<option value="3" <?php echo ($IDENTITY==3)?"selected":"";; ?>>學生</option>
							</optgroup>
						</select> <!-- <label for="identity"></label> -->
						
						
						<br/>
						<span style="font-size:20px">
							&emsp;大頭照:  
						</span>
						<!--
						<input type="file" value="" name="PHOTO_" style="width:250px; font-size:18px; border-radius:2px;"/> 
						-->
						
						<input name="my_photo" id="photo_inp" value="<?php  echo $_SELF['photo']; ?>" type="file" onchange="readURL(this)" 
										accept="image/gif, image/jpeg, image/png" targetID="preview_new_photo" style="width:250px; font-size:18px; border-radius:2px;" /> 
										
						<img id="preview_new_photo" src="#" alt="圖片預覽" style="" />
						
						
						
						<br/><br/><br/>
						


						
						

						
						
					<!-- </div>  -->
						  
						
						&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
						<a href="login.php" style="">有帳號了嗎? 快來登入</a>
						
						<br/><br/><br/>
						<input class="w-40 btn btn-lg btn-primary" type="submit" 
						style="width:200px; height:50px; font-size:18px; border-radius:10px;" name="button" value="確定註冊" />
								 <!--不換行空格--> 	 	
					
						<input class="w-40 btn btn-lg btn-danger" type="reset" style="width:200px; height:50px; font-size:18px; color:white; border-radius:10px;" value="清除"/>	
						<br/><br/>
						
						
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
	
		.alittleLeft
		{
			text-align: justify;
			position:relative;
			padding:1px;
			left:20px;
			width:220px; height:60px; font-size:20px; 
		}
		
		.signin img
		{
			position:relative; 
			left:20px; 
			width:320px; 
			height:280px;
		}
		.signin label
		{
			left:20px;
		}
				
		.bg-success 
		{
		  --bs-bg-opacity: 1;
		  background-color: #ECECFF !important;
		}

			
			
		</style>
		
		<script>
			//1.獲取元素，圖片和密碼
			var checkEye = document.getElementById('checkEye');
			var pwd = document.getElementById('pwd');
			var pwd2 = document.getElementById('pwd2');
			//2.註冊事件	
			 checkEye.addEventListener("click", function(e)
			 {
				if(e.target.classList.contains('fa-eye'))
				{
					e.target.classList.remove('fa-eye');
					e.target.classList.add('fa-eye-slash');
					pwd.setAttribute('type','password');
					pwd2.setAttribute('type','password')
					
				}
				else
				{
					pwd.setAttribute('type','text');
					pwd2.setAttribute('type','text');
					e.target.classList.remove('fa-eye-slash');
					e.target.classList.add('fa-eye');
				}
			});
			
			
			function validateForm()  // 註冊表單防呆
			{
				var USNAME = document.forms["registerForm"]["username_"].value;
				var ACCO = document.forms["registerForm"]["account_"].value;
				var PASS = document.forms["registerForm"]["password_"].value;
				var PASS2 = document.forms["registerForm"]["password2_"].value;
				var EMA = document.forms["registerForm"]["email_"].value;
				var PHONE = document.forms["registerForm"]["phonenumber_"].value;
				if(USNAME == "")
				{
					alert("請輸入本帳號的使用者名稱!");
					return false;
				}
				if(ACCO == "")
				{
					alert("沒有帳號沒辦法登入哦..!");
					return false;
				}
				if(PASS.length<6)
				{
					alert("密碼長度不足，最少需要6個字。");
					return false;
				}
				if(EMA == "")
				{
					alert("請填寫您的電子信箱~ ");
					return false;
				}
				if(PHONE == "")
				{
					alert("別忘了填寫連絡電話~ ");
					return false;
				}
				
				if (PASS != PASS2) 
				{
					alert("請確認密碼是否輸入一致!");
					return false;
				}
			}
			
			function readURL(input)
			{
			  if(input.files && input.files[0])
			  {
				var imgTagID=input.getAttribute("targetID");
				var reader = new FileReader();
				reader.onload = function (e) {
					var img=document.getElementById(imgTagID);
					img.setAttribute("src",e.target.result)
				   //$("#preview_nㄌew_photo").attr('src', e.target.result);
				}
				reader.readAsDataURL(input.files[0]);
			  }
			}
						  	  
		</script>

	  
	  </div>
	</body>
</html>

