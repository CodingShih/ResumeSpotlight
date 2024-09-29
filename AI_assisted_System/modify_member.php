<html>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css"/>
<link rel="icon" type="image/x-icon" href="./photos/_spotlight_web_icon.ico" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php 
		include("connect_mysql.php");//連結資料庫
		session_start(); 
		
		$username=$_SESSION["username"];
		$USER_identity=$_SESSION["identity"];
		$account=$_SESSION["account"];
		$S_member_id=$_SESSION["member_id"];
		
		$ori_username=$_SESSION["username"];
		$ori_account=$_SESSION["account"];
		$ori_password=$_SESSION["password"];
		$ori_email=$_SESSION["email"];
		$ori_phonenumber=$_SESSION["phonenumber"];
		$ACC=$_POST['account'];
		
		//echo $ori_username."-".$ori_account."-".$ori_password."-".$ori_email."-".$ori_phonenumber;
		
		
		
		
		
		
		$check_Redundant="SELECT * FROM member_table WHERE account='".$ACC."' ";
		
		
		
		if (!isset($_SESSION['username']))  // 沒有人登入的話，抓不到使用者名稱
		{
			echo"<script>alert('請登入再來做修改哦!');</script>";
			echo '<meta http-equiv=REFRESH CONTENT=1;url=login.php>'; //重新導向登入頁
		}
		else
		{	
			if ($USER_identity==1)  // 系統管理員
			{
				$SEL_self="SELECT * FROM member_table WHERE account = '".$account."'";
				$RESULT_self = mysqli_query($link,$SEL_self);//執行
				$_SELF=mysqli_fetch_assoc($RESULT_self);
			
				if($_SERVER["REQUEST_METHOD"]=="POST") // 要點下去確認修改才會收到post
				{
					
					$P_name=$_FILES['new_photo']['name'];  // 檔案名稱
					$P_tmmp=$_FILES['new_photo']['tmp_name'];  // 檔案位置
					$P_size=$_FILES['new_photo']['size'];  // 檔案大小
					$P_type=$_FILES['new_photo']['type'];  // 檔案類別
					
	
	
					/////////////////////////上傳大頭照
					$allow_type_img= array("png","jpg","jpeg");
					$type_img= explode(".",$P_name);
					
					$imgg= end($type_img);

					
					# 檢查檔案是否上傳成功
					if ($_FILES['new_photo']['error'] === UPLOAD_ERR_OK)
					{	
						//限制上傳檔案類型 僅照片
						if (!in_array($imgg,$allow_type_img))
						{
							echo "<script>alert('請勿上傳圖片以外的檔案..!');</script>";
						}
						else
						{
							$PhoTO=$ACC . "-" . $P_name;
							# 檢查檔案是否已經存在
							if (file_exists("C:\AppServ\www\AI_assisted_System\UPLOAD_FILE_member_photo\\" . $ACC . "-" . $P_name))
							{
								echo '檔案已存在。<br/>';
							} 
							else 
							{
								$file = $_FILES['new_photo']['tmp_name'];
								
								//$dest = 'upload/' . $_FILES['my_photo']['name'];
								$dest = "C:\AppServ\www\AI_assisted_System\UPLOAD_FILE_member_photo\\" . $ACC . "-" . $P_name;
								
								# 將檔案移至指定位置
								move_uploaded_file($file, $dest);
								//echo "<script>alert('檔案上傳成功! ');</script>";
								
								
								//$UPdate_PHOTO= "UPDATE member_table SET `photo`=\"$PhoTO\" WHERE `member_id`=\"$S_member_id\" ";
								
								
							}
						}
					} 
					else 
					{
						//照片重複寫原本的名稱回去
						$PhoTO=$_SELF['photo'];
						//echo '錯誤代碼：' . $_FILES['new_photo']['error'] . '<br/>';
						//exit();
						//echo '<meta http-equiv=REFRESH CONTENT=1;url=member.php>';
					}
					
					
				
					if ($_POST['username']=="") // 為空字串 就拿原本的填
					{
						$G_username=$ori_username;  // SELF不行 就拿預先存起來的前SESSION
					}
					else
					{
						$G_username=$_POST['username'];
					}
				
				
					//--------------// 帳號
					//if (!empty($_POST['account']) || $_POST['account']!="")  
					if ($_POST['account']=="")
					{
						$G_account=$ori_account;
					}
					else
					{
						$G_account=$_POST['account'];
					}
				
					
					//--------------// Email
					//if (!empty($_POST['email']) || $_POST['email']!="")
					if ($_POST['email']=="")
					{
						$G_email=$ori_email;
					}
					else  
					{
						$G_email=$_POST['email'];
					}
					
					//--------------// 連絡電話
					//if (!empty($_POST['phonenumber']) || $_POST['phonenumber']!="")
					if ($_POST['phonenumber']=="")
					{
						$G_phonenumber=$ori_phonenumber;
					}
					else 
					{
						$G_phonenumber=$_POST['phonenumber'];
					}
					
					//--------------// 新密碼
					
					if (isset($_POST['OldPassword']) && $_POST['OldPassword'] != "" && password_verify($_POST['OldPassword'] ,$_SESSION['password'])) 
					{
						if ($_POST['NewPassword'] == "") // 密碼
						{
							$G_NewPassword=$ori_password;
						
							echo"<script>alert('密碼是空字串...');</script>";	
						}
						else 
						{
							$G_NewPassword=$_POST['NewPassword'];
							/* 待用勿刪	 
							require('connectMySQL.php');
							$hash_password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
							$query = "UPDATE nativeUsers SET name = '" . $_POST['name'] . "' ,password = '" . $hash_password . "' ,phone = '" . $_POST['phone'] . "' WHERE id = " . $_SESSION['id'];

							$result = mysqli_query($db_link, $query);
							$_SESSION['password'] = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
							unset($_POST['currentPassword']);
							header('Location: welcome.php');	
							*/
						}
					
					}
					
				
					$UPdate= "UPDATE member_table SET `username`=\"$G_username\",
														`account`=\"$G_account\",
														`password`=\"$G_NewPassword\",
														`photo`=\"$PhoTO\",
														`email`=\"$G_email\",
														`phonenumber`=\"$G_phonenumber\" 
														WHERE `member_id`=\"$S_member_id\" ";
														
														
					
					//mysqli_query($link, 'SET NAMES utf8'); // 設定UTF8編碼
				
				//}
					//變數內容等於 post內容
				
					
					
					if (mysqli_query($link,$UPdate))
					{
						/*
						unset($_SESSION['account']);
						unset($_SESSION['username']);
						unset($_SESSION['password']);
						unset($_SESSION['email']);
						unset($_SESSION['phonenumber']);  */
						//unset($_SESSION['photo']);
						
						$_SESSION['username']=$_POST['username'];
						$_SESSION['account']=$_POST['account'];
						$_SESSION['password']=$_POST['NewPassword'];
						$_SESSION['email']=$_POST['email'];
						$_SESSION['phonenumber']=$_POST['phonenumber'];
						$_SESSION['photo']=$_POST['photo'];
						
						echo"<script>alert('修改完成..!');</script>";
						echo '<meta http-equiv=REFRESH CONTENT=1;url=member.php>'; //重新導向首頁
						exit();
					
					
					}
					else
					{
						echo "Error creating table: " . mysqli_error($link);
						echo"<script>alert('修改失敗......');</script>";
					}
			
					
				}
				
	?>
				<head>
					<title>書審Spotlight-成員清單-編輯頁面</title>
				</head>
				
				<body class="bg-success">
					<div style="font-family: UDDig;">
					<!--用表格框起來 查出來的成員表 -->
					<header class="main-header">
						<div class="containerleft">
							<b>
								<a href="homepage.php" style="color:#ECF5FF; text-decoration:none; font-size:35px;">書審Spotlight</a> 
							</b>
							<nav>    
								<ul class="main-menu" >
									<li> <b>成員清單-編輯頁面</b> </li>
								</ul>	
							</nav>    
						</div>
					</header>
	
					
					
			
					<div align="center">			
						<form name="modifyForm" action="modify_member.php" method="post" 
						style="background-color:#ECECFF;"enctype="multipart/form-data" onsubmit="return MODI_COMFIRM_Form()" >
							
						
							<div align="left">	
								<a href="member.php" style="font-size:25px; position:relative; left:5px; ">回上頁 成員中心</a>
							</div>
						 <table border="1"  style= "border:10px #EA0000 outset;" width="850" height="450" cellpadding="10" >
							
							<thead>
									<div style="text-align:center;">
										<h2>
											修改中<br/><progress></progress>
										</h2>
									</div>	
							</thead>
							
							<tbody>
								<tr>
									<td rowspan="6" align='center' valign="middle">
										
										大頭照：<span style="font-size:24px;"><b></b></span>
										<input name="new_photo" id="photo_inp" value="<?php  echo $_SELF['photo']; ?>" type="file" onchange="readURL(this)" 
										accept="image/gif, image/jpeg, image/png" targetID="preview_new_photo" multiple /> 
										
										<img id="preview_new_photo" src="#" alt="圖片預覽" width="260px" height="400px" />
									<!--	<input name="E_upload" type="hidden" value=""  />-->
										<!--<img src="./UPLOAD_FILE_member_photo/<?php  echo $_SELF['photo']; ?>" width="260px" height="400px" /> -->
									</td>
									
									
									<td>名稱：
										<span style="font-size:24px;">
											<b><input name="username" type="text" value="<?php echo $_SELF['username'];?>" placeholder="<?php echo $_SELF['username'];?>" /></b>
												<input name="member_id" type="hidden" value="<?php echo $_SELF['member_id'];?>" />
										</span>
									</td>
								<!--	<td >編號：<b><?php echo $_SELF['member_id'];?></b></td> -->			  
									<td rowspan="6" align='center' valign="middle">
										<input name="modify_OK" type="submit" value="確認修改" />
									</td>
								</tr>
								
								<tr>
									<td >帳號：<span style="font-size:24px;"><b><input name="account" type="text" value="<?php echo $_SELF['account'];?>" placeholder="<?php echo $_SELF['account'];?>" /></b></span></td>
								</tr>
								<tr>
									<td >新密碼：<span style="font-size:24px;"><b><input name="NewPassword" type="password" value="<?php echo $_SELF['password'];?>" placeholder="<?php echo $_SELF['password'];?>" /></b></span></td>
								</tr>
								<tr>
									<td>確認新密碼:<span style="font-size:24px;"><b><input name="OKNewPassword" type="password" value="" placeholder="" /></b></span></td>
								</tr>
								<tr>
									<td>Email：<span style="font-size:24px;"><b><input name="email" type="text" value="<?php echo $_SELF['email'];?>" placeholder="<?php echo $_SELF['email'];?>" /></b></span>
								</tr>
								<tr>
									<td>
										連絡電話：<span style="font-size:24px;"><b><input name="phonenumber" type="text" value="<?php echo $_SELF['phonenumber'];?>" placeholder="<?php echo $_SELF['phonenumber'];?>" /></b></span></td>	
									</td>
								</tr>
					
							</tbody>
						  </table>
						</form>
						
						
					</div>
					
					</div>
				</body>

	<?php
			}		
			if ($USER_identity==2 || $USER_identity==3) // 老師 2 和 學生 3
			{	
				$SEL_self="SELECT * FROM member_table WHERE account = '".$account."'";
				$RESULT_self = mysqli_query($link,$SEL_self);//執行
				$_SELF=mysqli_fetch_assoc($RESULT_self);
			
				if($_SERVER["REQUEST_METHOD"]=="POST") // 要點下去確認修改才會收到post
				{
					
					$P_name=$_FILES['new_photo']['name'];  // 檔案名稱
					$P_tmmp=$_FILES['new_photo']['tmp_name'];  // 檔案位置
					$P_size=$_FILES['new_photo']['size'];  // 檔案大小
					$P_type=$_FILES['new_photo']['type'];  // 檔案類別
					
	
	
					/////////////////////////上傳大頭照
					$allow_type_img= array("png","jpg","jpeg");
					$type_img= explode(".",$P_name);
					
					$imgg= end($type_img);

					
					# 檢查檔案是否上傳成功
					if ($_FILES['new_photo']['error'] === UPLOAD_ERR_OK)
					{	
						//限制上傳檔案類型 僅照片
						if (!in_array($imgg,$allow_type_img))
						{
							echo "<script>alert('請勿上傳圖片以外的檔案..!');</script>";
						}
						else
						{
							$PhoTO=$ACC . "-" . $P_name;
							# 檢查檔案是否已經存在
							if (file_exists("C:\AppServ\www\AI_assisted_System\UPLOAD_FILE_member_photo\\" . $ACC . "-" . $P_name))
							{
								echo '檔案已存在。<br/>';
							} 
							else 
							{
								$file = $_FILES['new_photo']['tmp_name'];
								
								//$dest = 'upload/' . $_FILES['my_photo']['name'];
								$dest = "C:\AppServ\www\AI_assisted_System\UPLOAD_FILE_member_photo\\" . $ACC . "-" . $P_name;
								
								# 將檔案移至指定位置
								move_uploaded_file($file, $dest);
								//echo "<script>alert('檔案上傳成功! ');</script>";
								
								
								//$UPdate_PHOTO= "UPDATE member_table SET `photo`=\"$PhoTO\" WHERE `member_id`=\"$S_member_id\" ";
								
								
							}
						}
					} 
					else 
					{
						//照片重複寫原本的名稱回去
						$PhoTO=$_SELF['photo'];
						//echo '錯誤代碼：' . $_FILES['new_photo']['error'] . '<br/>';
						//exit();
						//echo '<meta http-equiv=REFRESH CONTENT=1;url=member.php>';
					}
					
					
				
					if ($_POST['username']=="") // 為空字串 就拿原本的填
					{
						$G_username=$ori_username;  // SELF不行 就拿預先存起來的前SESSION
					}
					else
					{
						$G_username=$_POST['username'];
					}
				
				
					//--------------// 帳號
					//if (!empty($_POST['account']) || $_POST['account']!="")  
					if ($_POST['account']=="")
					{
						$G_account=$ori_account;
					}
					else
					{
						$G_account=$_POST['account'];
					}
				
					
					//--------------// Email
					//if (!empty($_POST['email']) || $_POST['email']!="")
					if ($_POST['email']=="")
					{
						$G_email=$ori_email;
					}
					else  
					{
						$G_email=$_POST['email'];
					}
					
					//--------------// 連絡電話
					//if (!empty($_POST['phonenumber']) || $_POST['phonenumber']!="")
					if ($_POST['phonenumber']=="")
					{
						$G_phonenumber=$ori_phonenumber;
					}
					else 
					{
						$G_phonenumber=$_POST['phonenumber'];
					}
					
					
					if (($_POST['NewPassword']!="") && ($_POST['OldPassword']!="")) 
					{	
						if (($_POST['OldPassword'] != "") && password_verify($_POST['OldPassword'] ,$_SESSION['password']))
						{	// 密碼驗證
							$hash_NewPassword = password_hash($_POST['NewPassword'], PASSWORD_DEFAULT);
						}
						else
						{
							echo"<script>alert('舊密碼錯誤.');</script>";
							return false;
						}
						
					}
					else
					{
						echo"<script>alert('密碼為空字串....WHY..');</script>";
						return false;
					}
					
					
					/* 
					//--------------// 新密碼
					//if (isset($_POST['OldPassword']) && $_POST['OldPassword'] != "" )) 
					//{//&& password_verify($_POST['OldPassword'] ,$_SESSION['password']
						if ($_POST['NewPassword'] == "") // 密碼
						{
							$G_NewPassword=$ori_password;
						
							echo"<script>alert('密碼是空字串...');</script>";	
						}
						else 
						{
							$G_NewPassword=$_POST['NewPassword'];
							$G_NewPassword = password_hash($G_NewPassword,PASSWORD_DEFAULT);
							待用勿刪	 
							require('connectMySQL.php');
							$hash_password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
							$query = "UPDATE nativeUsers SET name = '" . $_POST['name'] . "' ,password = '" . $hash_password . "' ,phone = '" . $_POST['phone'] . "' WHERE id = " . $_SESSION['id'];

							$result = mysqli_query($db_link, $query);
							$_SESSION['password'] = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
							unset($_POST['currentPassword']);
							header('Location: welcome.php');	
							
						}
					
					//}
					else
					{
						echo "密碼錯誤";
						return false;
						
					}
					*/
				
					$UPdate= "UPDATE member_table SET `username`=\"$G_username\",
														`account`=\"$G_account\",
														`password`=\"$hash_NewPassword\",
														`photo`=\"$PhoTO\",
														`email`=\"$G_email\",
														`phonenumber`=\"$G_phonenumber\" 
														WHERE `member_id`=\"$S_member_id\" ";
					//mysqli_query($link, 'SET NAMES utf8'); // 設定UTF8編碼
				
				//}
					//變數內容等於 post內容
				
					
					
					if (mysqli_query($link,$UPdate))
					{
						/*
						unset($_SESSION['account']);
						unset($_SESSION['username']);
						unset($_SESSION['password']);
						unset($_SESSION['email']);
						unset($_SESSION['phonenumber']);  */
						//unset($_SESSION['photo']);
						
						$_SESSION['username']=$_POST['username'];
						$_SESSION['account']=$_POST['account'];
						$_SESSION['password']=password_hash($_POST['NewPassword'], PASSWORD_DEFAULT);
						unset($_POST['OldPassword']);
						$_SESSION['email']=$_POST['email'];
						$_SESSION['phonenumber']=$_POST['phonenumber'];
						$_SESSION['photo']=$_POST['photo'];
						
						echo"<script>alert('修改完成..!');</script>";
						echo '<meta http-equiv=REFRESH CONTENT=1;url=member.php>'; //重新導向首頁
						exit();
					
					
					}
					else
					{
						echo "Error creating table: " . mysqli_error($link);
						echo"<script>alert('修改失敗......');</script>";
					}
			
					
				}
				
		
	?>
				<head>
					<title>書審Spotlight-個人檔案-編輯頁面</title>
				</head>
					
				<body class="bg-success">
					<div style="font-family: UDDig;">
					<!--用表格框起來 查出來的成員表 -->
					<header class="main-header">
						<div class="containerleft">
							<b>
								<a href="homepage.php" style="color:#ECF5FF; text-decoration:none; font-size:35px;">書審Spotlight</a> 
							</b>
							<nav>    
								<ul class="main-menu" >
									<li> <b>個人檔案-編輯頁面</b> </li>
								</ul>	
							</nav>    
						</div>
					</header>
					
					<div align="center">			
						<form name="modifyForm" action="modify_member.php" method="post" enctype="multipart/form-data" 
						style="background-color:#ECECFF ;" onsubmit="return MODI_COMFIRM_Form()" >
							
						
							<div align="left">	
								<a href="member.php" style="font-size:25px; position:relative; left:5px; ">回上頁 成員中心</a>
							</div>
						  
						  <table border="1"  style= "border:10px #EA0000 outset;" width="850" height="450" cellpadding="10" >
							
							<thead>
									<div style="text-align:center;">
										<h2>
											修改中<br/><progress></progress>
										</h2>
									</div>	
							</thead>
							
							<tbody>
								<tr>
									<td rowspan="6" align='center' valign="middle">
										
										大頭照：<span style="font-size:24px;"><b></b></span>
										<input name="new_photo" id="photo_inp" value="<?php  echo $_SELF['photo']; ?>" type="file" onchange="readURL(this)" 
										accept="image/gif, image/jpeg, image/png" targetID="preview_new_photo" multiple /> 
										
										<img id="preview_new_photo" src="#" alt="圖片預覽，不選擇將維持原大頭照" width="260px" height="400px" />
									<!--	<input name="E_upload" type="hidden" value=""  />-->
										<!--<img src="./UPLOAD_FILE_member_photo/<?php  echo $_SELF['photo']; ?>" width="260px" height="400px" /> -->
									</td>
									
									
									<td>名稱：
										<span style="font-size:24px;">
											<b><input name="username" type="text" value="<?php echo $_SELF['username'];?>" placeholder="<?php echo $_SELF['username'];?>" /></b>
												<input name="member_id" type="hidden" value="<?php echo $_SELF['member_id'];?>" />
										</span>
									</td>
									
								<!--	<td >編號：<b><?php echo $_SELF['member_id'];?></b></td> -->			  
									<td rowspan="6" align='center' valign="middle">
										<input class="w-40 btn btn-lg btn-primary" name="modify_OK" type="submit" value="確認修改" />
									</td>
									
								</tr>
								
								<tr>
									<td>帳號：<br/><span style="font-size:24px;"><b><input name="account" type="text" value="<?php echo $_SELF['account'];?>" placeholder="<?php echo $_SELF['account'];?>" /></b></span></td>
								</tr>
								<tr>
									<td>舊密碼：<span style="font-size:24px;"><b><input name="OldPassword" type="password" value="" placeholder=""/></b></span></td>
								</tr>
								<tr>
									<td>新密碼：<span style="font-size:24px;"><b><input name="NewPassword" type="password" value="" /></b></span>
									<br/><br/>確認新密碼:<span style="font-size:24px;"><b><input name="OKNewPassword" type="password" value="" placeholder="" /></b></span></td>
								</tr>
								<tr>
									<td>Email：<span style="font-size:24px;"><b><input name="email" type="text" value="<?php echo $_SELF['email'];?>" placeholder="<?php echo $_SELF['email'];?>" /></b></span>
								</tr>
								<tr>
									<td>
										連絡電話：<span style="font-size:24px;"><b><input name="phonenumber" type="text" value="<?php echo $_SELF['phonenumber'];?>" placeholder="<?php echo $_SELF['phonenumber'];?>" /></b></span></td>	
									</td>
								</tr>
							
							</tbody>
						  </table>
						</form>
						
						
					</div>
					
					</div>
				</body>
				
				
				
				
	<?php		
			}
	?>
	
	
					<script>
						function MODI_COMFIRM_Form()
						{
							var USNAME = document.forms["modifyForm"]["username"].value;
							var ACCO = document.forms["modifyForm"]["account"].value;
							var OLDPASS = document.forms["modifyForm"]["OldPassword"].value;
							var PASS = document.forms["modifyForm"]["NewPassword"].value;
							var PASS2 = document.forms["modifyForm"]["OKNewPassword"].value;
							var EMA = document.forms["modifyForm"]["email"].value;
							var PHONE = document.forms["modifyForm"]["phonenumber"].value;
							
							
							if(USNAME == "")
							{
								alert("如不需修改，請填寫原姓名，謝謝。");
								return false;
							}
							if(ACCO == "")
							{
								alert("如不需修改，請填寫原帳號，謝謝。");
								return false;
							}
							
							if (OLDPASS == "")
							{
								alert("請填寫原密碼，謝謝。");
								return false;
							}
							
							if(PASS.length<6)
							{
								alert("新密碼長度不足，最少需要6個字。");
								return false;
								
							}
							else if (PASS == "")
							{
								alert("如不需修改，請填寫原密碼，謝謝。");
							}
							
							if (PASS != PASS2) 
							{
								alert("請確認新密碼是否輸入一致!");
								return false;
							}
							
							if(EMA == "")
							{
								alert("如不須修改，請填寫原電子信箱，謝謝。");
								return false;
							}
							if(PHONE == "")
							{
								alert("如不須修改，請填寫原連絡電話，謝謝。");
								return false;
							}
						
						
						}
						
						function readURL(input){
						  if(input.files && input.files[0])
						  {
							var imgTagID=input.getAttribute("targetID");
							var reader = new FileReader();
							reader.onload = function (e) {
								var img=document.getElementById(imgTagID);
								img.setAttribute("src",e.target.result)
							   //$("#preview_new_photo").attr('src', e.target.result);
							}
							reader.readAsDataURL(input.files[0]);
						  }
						  else
						  {
							  var Nophoto=$("<p>目前沒有圖片</p>");
							  $("#preview_new_photo").append(Nophoto);
						  }
						}
						/*
						//預覽圖片
						$("#photo_inp").change(function(){
							//當檔案改變後，做一些事 
							readURL(this);   //this代表<input id="imgInp">
						});
						*/
						
					
					</script>
	
	<?php		
		}
	?>
	
	
		

		
			<style>
					@import url(memberpage.css);
					@font-face
					{
						font-family: UDDig;
						src: url(./org-unpack-20211227/UDDigiKyokashoN-R-01.ttf);
					}
					
					a
					{
						text-decoration: none;
						display: inline-block;
					}
					
					a:link, a:visited 
					{
						text-align: center;
						 
					}

					a:hover { 
					  color: hotpink;
					  text-decoration: none;
					} 
					
					.bg-success {
					  --bs-bg-opacity: 1;
					  background-color: #ECECFF !important;
					}
					
					
					
			
			</style>
			
</html>