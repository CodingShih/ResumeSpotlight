<!DOCTYPE html>

<?php
/*
		在首頁先判斷連線者是否為成員，所以先讀入SESSION中的資料，如果有
值則判斷為成員並允許登入，接者再判斷等級，決定要顯示的內容，管理者、老師、學生分別的等
級，頁面上可使用的功能也不相同，
學生能上傳資料、進行分析、分析結果、查看建議、Q&A
老師能查看學生列表、查看學生能力表現．
系統管理員則能夠進行所有功能。
	*/

include("connect_mysql.php");//連結資料庫
session_start();  //很重要，可以用的變數存在session裡


//$USER=$_SESSION['username'];
$username=$_SESSION["username"];
$USER_identity=$_SESSION["identity"];
echo $USER_identity;


/*
$SELECT="SELECT * FROM member_table WHERE account='$ACC' && password='$PW'";
$RESULT=mysqli_query($link,$SELECT);
$_SESSION['account']=mysqli_fetch_assoc($RESULT)["account"];		
$_SESSION['username']=mysqli_fetch_assoc($RESULT)["username"];


*/
			

$identity_list=array("無","系統管理員","老師","學生");



if (!isset($_SESSION['username'])) // 沒有人登入
	{
			//echo"<script>alert('目前為遊客瀏覽模式，進階功能請登入。');</script>";
			//header("refresh:1;url=login.php"); 
			//用上面那行會等一秒，用下面瞬間跳轉
			//header('location:login.php');  
	}

?>


<html>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css"/>
<link rel="icon" type="image/x-icon" href="./photos/_spotlight_web_icon.ico" />
	<head>
	  <meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/>
	  <title>書審Spotlight輔助分析資訊系統</title>
	</head> 
	
	
	<body class="bg-success">
	
	
		
	<header class="main-header">
		<div style="font-family: UDDig;">
			<div class="containerleft">
				<b>
					<a href="homepage.php" style="color:#ECF5FF; text-decoration:none; font-size:35px;">書審Spotlight</a> 
				</b>
				<?php  if (!isset($_SESSION['username']))
						{ //沒有人登入
				?>
							<h5><a href="login.php">登入</a> </h5>
							<h5><a href="register.php">註冊帳號 </a> </h5>
							
							<nav>    
								<ul class="main-menu" >
				
									<li><a href="StartAnalyze.php"> <b>開始分析</b> </a></li>
									<li><a href="outputReport.php"> <b>評估報告</b> </a></li>
									<li><a href="#"> <b>FAQ 常見問題</b> </a></li>					
				
									<a href="https://zh-tw.facebook.com/"><img src="./photos/fblogo.png" alt="" width="50" height="50" ></a>
									<a href="/"><img src="./photos/linelogo.png" alt="" width="50" height="50" ></a>
				
								<!--		list-style: none;   /* 移除項目符號 */         -->
								</ul>		
							</nav>    
							
				<?php
						}
						else  // 有會員登入成功的話，就將登入位置改成 您好! 某某某 !
						{
							//echo "<span style='color:#F0C000;'>" . "您好 " . $USER . "  !</span>"; 
							//登入後不管身分，都有登出可按
							echo "<h5>"."<a href='logout.php'>" . "登出" . "</a></h5>";
			
					 
							
							switch ($USER_identity)
							{
								case 1:
									echo "<span style='color:#F0C000;'><h5>您好!  " . $username . "  系統管理員</h5></span>";
									break;
								case 2:
									echo "<span style='color:#F0C000;'><h5>您好!  " . $username . "  老師</h5></span>";
									break;
								case 3:
									echo "<span style='color:#F0C000;'><h5>歡迎!  " . $username . "  同學</h5></span>";
									break;
								
							}
							
							
							if ($USER_identity == "TE")   
							{  // 如果登入身分是 2(老師)、3(學生)，看到的是個人檔案
								// 老師可會有 學生一覽、學生評估報告 的功能
				?>			
								<h5><a href="member.php">個人檔案</a> </h5>
								<nav>    
									<ul class="main-menu" >
										<li><a href="allstudent.php"> <b>學生一覽</b> </a></li>
										<li><a href="outputReport.php"> <b>學生評估報告</b> </a></li>
										<li><a href="#"> <b>FAQ 常見問題</b> </a></li>					
					
										<a href="https://zh-tw.facebook.com/"><img src="./photos/fblogo.png" alt="" width="50" height="50" ></a>
										<a href="/"><img src="./photos/linelogo.png" alt="" width="50" height="50" ></a>
					
									<!--		list-style: none;   /* 移除項目符號 */         -->
									</ul>
								</nav>    
				<?php	
							}
							else if ($USER_identity == 3)  //學生
							{	// 學生會有 開始分析(上傳檔案)、評估報告 的功能
				?>
								<h5><a href="member.php">個人檔案</a> </h5>
								<nav>    

									<ul class="main-menu" >
					
										<li><a href="StartAnalyze.php"> <b>開始分析</b> </a></li>
										<li><a href="outputReport.php"> <b>評估報告</b> </a></li>
										<li><a href="#"> <b>FAQ 常見問題</b> </a></li>					
					
										<a href="https://zh-tw.facebook.com/"><img src="./photos/fblogo.png" alt="" width="50" height="50" ></a>
										<a href="/"><img src="./photos/linelogo.png" alt="" width="50" height="50" ></a>
					
									<!--		list-style: none;   /* 移除項目符號 */         -->
									</ul>
								</nav>    
			
				<?php			
							}
							else if ($USER_identity == 1) //只有系統管理員，看到的是成員中心 
							{
				?>
								<h5><a href="member.php">成員中心</a> </h5>
				<?php			
							}
								
						}// <span style="color:#F0C000;"> 你好的顏色
				?>
				
				
			<!--	
				<span style="font-size:10px;">
					<a href="member.php">成員中心</a>
				</span>
		-->
<?php //----------------------

?>			
			</div>
		</div>
	</header>
	
	<div class="robotp">
		<img src="./photos/robot.png" alt="" width="150" height="180" />
	</div>
	<!--CSS not working

	-->
	
	
	
	
	<div class="pazzlep">
		<img src="./photos/pazzle.png" alt="" width="380" height="200" />
	</div>
	
	<div style="font-family: UDDig;">
	
		
		<b>
		
			<div class="text1p">
				您. . . 是否還在迷茫面試時要問甚麼問題?
			</div>
		
			<div class="text2p">
				還在對著辦公桌上一大疊的備審資料苦苦發愁?
			</div>
		
			<div class="text3p">
				是否覺得招進來的學生卻對這個科系沒有那麼大的興趣?
			</div>
		
		
	
		
			<div class="textcenterp">
				<br/>
				別擔心，在此系統，
				<br/>
				AI可以成為您的超級助手，並且給出最客觀的分析!
			</div>
		</b>
		</b>
	</div>
	
	
	

	<!--
			<table border="1" style="border:1px  groove;" width="1518" height="550" >
				<tr style="background-color:#ECECFF	">
						

				&nbsp;&nbsp;
					<img src="./photos/pazzle.png" alt="" width="300" height="175" />
					
					
					
				</tr>
			</table>	
	-->
		
		<style>
			@import url(header.css);
			@font-face
			{
				font-family: UDDig;
				src: url(./org-unpack-20211227/UDDigiKyokashoN-R-01.ttf);
			}
			
			.bg-success 
			{
			  --bs-bg-opacity: 1;
			  background-color: #ECECFF !important;
			}
			
			a:hover
			{ /* #500001 酒紅*/
				color:	#FFD900; /*橘黃 */ 
			}
	
		</style>

	
	
	
	
	
	
	 
	</body>
</html>
