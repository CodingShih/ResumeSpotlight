<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"  />
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css"/>
	<link rel="icon" type="image/x-icon" href="./photos/_spotlight_web_icon.ico" />
	<?php 
	
		include("connect_mysql.php");//連結資料庫
		session_start();  
		
		$USER_identity=$_SESSION["identity"];
		$account=$_SESSION["account"];
		
		$identity_list=array("無","系統管理員","老師","學生");
		
		
	if (!isset($_SESSION['username'])) // 沒有人登入
	{
			echo"<script>alert('目前為遊客瀏覽模式，進階功能請登入。');</script>";
			header("refresh:1;url=login.php"); 
			//用上面那行會等一秒，用下面瞬間跳轉
			//header('location:login.php');  
	}
		
			
		if ($USER_identity==1) // 登入權限為 系統管理員 的話，
		{	
			//在成員中心看到的是各個成員的基本資料列，並可以進行維護
	?>
	<head>
		<title>書審Spotlight-成員中心</title>
	</head>
		
	<body class="bg-success">
	<div style="font-family: UDDig;">
		
		<header class="main-header">
			<div class="containerleft">
				<b>
					<a href="homepage.php" style="color:#ECF5FF; text-decoration:none; font-size:35px;">書審Spotlight</a> 
				</b>
				<nav>    
					<ul class="main-menu" >
						<li> <b>成員中心</b> </li>
					</ul>	
				</nav>    
			</div>
		</header>
		
		<?php
		$SEL="SELECT * FROM member_table";
		$RESULT = mysqli_query($link,$SEL);//執行
		$ROW=mysqli_fetch_assoc($RESULT);
		
	?>


		<br/><br/><br/><br/> <!--用表格框起來 查出來的成員表 -->
		<div align="center" style="background-color:#ECECFF ;">
	
	
			<table border="1"  style= "border:10px #FFF0D4 outset;" width="" height="" cellpadding="10" >
				<thead>
					<caption>
					
					</caption>
				</thead>
<?php
		do{
			$editstr="member_id=".$ROW['member_id'].
					"&account=".$ROW['account'].
					"&username".$ROW['username'].
					"&email".$ROW['email'].
					"&phonenumber".$ROW['phonenumber'].
					"&photo=".$ROW['photo'];
				
?>
<!--
echo "<tr>" . '<img src = "data:image/png;base64,' . base64_encode($ROW['photo']) . '" width = "60px" height = "60px"/>' . '</tr>';

						<img src="pic/<?php // echo base64_decode($ROW['photo']); ?>" width="100px" height="100px" />
	-->					
				<tr>
					<td rowspan="7" align='center' valign="middle">
						<img src="./UPLOAD_FILE_member_photo/<?php  echo ($ROW['photo']); ?>" style="width:150px; height:200px; border-radius:5%; " />
					</td>
					<td >編號：<b><?php echo $ROW['member_id'];?></b></td>
					
					<td rowspan="7" align='center' valign="middle">
						<a href="modify_member.php" >修改</a>
					</td>
													  
				</tr>
				<tr>
					<td >名稱：<b><?php echo $ROW['username']; ?></b></td>
				</tr>
				<tr>
					<td >帳號：<b><?php echo $ROW['account']; ?></b></td>
				</tr>
				<tr>
					<td >密碼：<b><?php echo $ROW['password']; ?></b></td>
				</tr>
				<tr>
					<td >Email：<b><?php echo $ROW['email']; ?></b></td>
				</tr>
				<tr>
					<td >連絡電話：<b><?php echo $ROW['phonenumber']; ?></b></td>
				</tr>
				<tr>
					<td >身分：<b><?php echo $identity_list[$ROW['identity']]; ?></b></td>
				</tr>
				
				
<?php
		}while($ROW=mysqli_fetch_assoc($RESULT)); // 每位成員的資料
		
		
?>
			</table>
		</div>
	</div>
	</body>
		
		
		
		
	<?php 
		} //------------------------------------------------------------
		if ($USER_identity==2) // 登入權限為 老師 的話，
		{	
			//$_SESSION['account']=$user['account'];
			$SEL_self="SELECT * FROM member_table WHERE account = '".$account."'";
			$RESULT_self = mysqli_query($link,$SEL_self);//執行
			$_SELF=mysqli_fetch_assoc($RESULT_self);
	?>
			<head>
				<title>書審Spotlight-個人檔案</title>
			</head>
			
			<body class="bg-success">
			<div style="font-family: UDDig;">
		
		<header class="main-header">
			<div class="containerleft">
				<b>
					<a href="homepage.php" style="color:#ECF5FF; text-decoration:none; font-size:35px;">書審Spotlight</a> 
				</b>
				<nav>    
					<ul class="main-menu" >
						<li> <b>個人檔案</b> </li>
					</ul>	
				</nav>    
			</div>
		</header>
		
		
		<a href="homepage.php" style="font-size:25px; position:relative; left:5px;">回首頁</a>
		<br/>
		
		
		<br/> <!--用表格框起來 查出來 老師 自己的個人資料 -->
		<div align="center" style="background-color:#ECECFF ;">
			<table class="signin" border="1" style="border:15px #84C1FF outset; font-size:18px;" width="800" height="500"  cellpadding="10">
				<thead>
					<caption>

					</caption>
				</thead>	
			
				<tr>
					<td rowspan="6" align='center' valign="middle">
						<img src="./UPLOAD_FILE_member_photo/<?php  echo $_SELF['photo']; ?>" style="width:280px; height:400px; border-radius:5%;"/>
					</td>
					
					<td>名稱：<span style="font-size:24px;"><b><?php echo $_SELF['username']; ?></b></span></td>
				<!--	<td >編號：<b><?php echo $_SELF['member_id'];?></b></td> -->					  
					<td rowspan="6" align='center' valign="middle">
						<a href="modify_member.php <?php echo $editstr;?> " style="font-size:20px;"><b>修改</b></a>
					</td>
				</tr>
				
				<tr>
					<td >帳號：<span style="font-size:24px;"><b><?php echo $_SELF['account']; ?></b></span></td>
				</tr>
		<!--		<tr>
					<td >密碼：<span style="font-size:24px;"><b><?php echo $_SELF['password']; ?></b></span></td>
				</tr>-->
				<tr>
					<td >Email：<span style="font-size:24px;"><b><?php echo $_SELF['email']; ?></b></span></td>
				</tr>
				<tr>
					<td >連絡電話：<span style="font-size:24px;"><b><?php echo $_SELF['phonenumber']; ?></b></span></td>
				</tr>
				<tr>
					<td >身分：<span style="font-size:24px;"><b><?php echo $identity_list[$_SELF['identity']]; ?></b></span></td>
				</tr>
			</table>
			
		</div>
			</div>
			</body>
	<?php
		}
		if ($USER_identity==3) // 登入權限為 學生 的話，
		{
			$SEL_self="SELECT * FROM member_table WHERE account = '".$account."'";
			$RESULT_self = mysqli_query($link,$SEL_self);//執行
			$_SELF=mysqli_fetch_assoc($RESULT_self);
	?>
			<head>
				<title>書審Spotlight-個人檔案</title>
			</head> 
			
			<body class="bg-success">
			<div style="font-family: UDDig;">
		
		<header class="main-header">
			<div class="containerleft">
				<b>
					<a href="homepage.php" style="color:#ECF5FF; text-decoration:none; font-size:35px;">書審Spotlight</a> 
				</b>
				<nav>    
					<ul class="main-menu" >
						<li> <b>個人檔案</b> </li>
					</ul>	
				</nav>    
			</div>
		</header>

		<a href="homepage.php" style="font-size:25px; position:relative; left:5px;">回首頁</a>
		<br/>
		
		<br/> <!--用表格框起來 查出來 學生 自己的個人資料 -->
		<div align="center" style="background-color:#ECECFF ;">
			<table class="signin" border="1" style="border:15px #005AB5 outset; font-size:18px;" width="800" height="500"  cellpadding="10">
				<thead>
					<caption>

					</caption>
				</thead>	
			
				<tr>
					<td rowspan="6" align='center' valign="middle">
						<img src="./UPLOAD_FILE_member_photo/<?php  echo $_SELF['photo']; ?>" style="width:260px; height:400px; border-radius:5%; " />
					</td>
					
					<td>名稱：<span style="font-size:24px;"><b><?php echo $_SELF['username']; ?></b></span></td>
				<!--	<td >編號：<b><?php echo $_SELF['member_id'];?></b></td> -->					  
					<td rowspan="6" align='center' valign="middle">
						<a href="modify_member.php <?php echo $editstr;?> " style="font-size:20px;"><b>修改</b></a>
					</td>
				</tr>
				
				<tr>
					<td >帳號：<span style="font-size:24px;"><b><?php echo $_SELF['account']; ?></b></span></td>
				</tr>
		<!--		<tr>
					<td >密碼：<span style="font-size:24px;"><b><?php echo $_SELF['password']; ?></b></span></td>
				</tr>-->
				<tr>
					<td >Email：<span style="font-size:24px;"><b><?php echo $_SELF['email']; ?></b></span></td>
				</tr>
				<tr>
					<td >連絡電話：<span style="font-size:24px;"><b><?php echo $_SELF['phonenumber']; ?></b></span></td>
				</tr>
				<tr>
					<td >身分：<span style="font-size:24px;"><b><?php echo $identity_list[$_SELF['identity']]; ?></b></span></td>
				</tr>
		
			</table>
		</div>
		
			</div>
			</body>
	<?php
		}
	///////////////	 下面是一開始測試的	//////////////////////
	?>
		
			<!--
			 <iframe
				src="https://www.youtube.com/embed/C4DTfP-7VyE"
				width="40%" height="40%"
				title="YouTube video player"
				frameborder="0"
				allowfullscreen="true"
				>
			</iframe>
			
			-->

	
	
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
    <!--

	
	 echo '<a href="logout.html">登出</a>  <br/><br/>'; 
	



		//此判斷為判定觀看此頁有沒有權限
		//說不定是路人或不相關的使用者，因此要給予排除
		if($_SESSION['account'] != null)
		{
				echo '<a href="register.html">新增</a> <br/><br/>   ';
				echo '<a href="modify_member.html">修改</a>  <br/><br/>  ';
				echo '<a href="delete_member.html">刪除</a>  <br/><br/>';
    
				//將資料庫裡的所有成員資料顯示在畫面上
		
			
			echo '<meta http-equiv=REFRESH CONTENT=2;url=homepage.html>';
		}
	-->	

