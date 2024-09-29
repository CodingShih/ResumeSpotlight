<?php
	include("connect_mysql.php");//連結資料庫
	session_start();  //很重要，可以用的變數存在session裡
	$username=$_SESSION["username"];
	$USER_identity=$_SESSION["identity"];
	
	if (!isset($_SESSION['username']))
	{
			echo"<script>alert('目前為遊客瀏覽模式，進階功能請登入。');</script>";
			header("refresh:0;url=login.php"); 
			//用上面那行會等一秒，用下面瞬間跳轉
			//header('location:login.php');  
	}
	else
	{
		if ($USER_identity == 3)  // 只有學生可以用 開始分析 的功能
		{
?>

<html>

	<head>
	  <meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/>
	  <title>書審Spotlight-開始分析</title>
	  <link rel="icon" type="image/x-icon" href="./photos/_spotlight_web_icon.ico" />
	</head> 
	
	<body class="bg-success">
	  <div style="font-family: UDDig;">
		<header class="main-header">
			<div class="containerleft">
				<h3><a href="homepage.php" style="color:#ECF5FF; text-decoration:none;">書審Spotlight</a></h3>	
				
				
<?php
				
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
?>
				
				
				
				
				<h5><a href="member.php">個人檔案</a> </h5>
				<nav>    

					<ul class="main-menu" >
	
						<li><a href style=" color:#FFD900"="StartAnalyze.php"> <b>開始分析</b> </a></li>
						<li><a href="outputReport.php"> <b>評估報告</b> </a></li>
						<li><a href="#"> <b>FAQ 常見問題</b> </a></li>					
	
						<a href="https://zh-tw.facebook.com/"><img src="./photos/fblogo.png" alt="" width="50" height="50" ></a>
						<a href="/"><img src="./photos/linelogo.png" alt="" width="50" height="50" ></a>
	
					<!--		list-style: none;   /* 移除項目符號 */         -->
					</ul>
				</nav>				
			</div>
		</header>
		
	
	<!--    置中表格  暫時取名 ANA_processing.php -->
	<form name="StartAnalyzeForm" action="outputReport.php" method="post" onsubmit="return pdfORnotForm()" enctype="multipart/form-data" >
		<br/><br/><br/><br/><br/>	
		
		<div class="centerrr">
			<span style="font-size:30px;">
				&emsp;&ensp;&emsp;&ensp;請上傳 <span style='color:#FF8000;'><b>學習歷程 pdf檔案</b></span> 讓 AI 為你分析學習狀況，<br/>判斷結果將會結合面試過程，分析完畢後將顯示在「<b>評估報告</b>」。				
			</span>
			
		</div>
		
		

		
		<div class="boxin">
			<!--網頁表單中如果包含檔案的上傳，就要把 enctype 設定為 "multipart/form-data"。-->
			<input type="file" name="my_file" id="my_file" multiple>
			
					
			<input type="submit" onclick="checkFile()" style="width:200px; height:50px; font-size:18px; border-radius:8px;" name="button" value="確認上傳">
			
			<br/><br/><br/>
			
			
			<!--
			<progress id="progressbar" value="0" max="100" style="width:300px;">
			</progress><span id="percentage"></span>
			-->
			
			<progress>
		
		</div>
		
		
		
		
	
		
	</form>
	
	
	
			
	
	
	
	
	
	
	
	
	
	
	
	
		<style>
			@import url(header.css);
			@font-face
			{
				font-family: UDDig;
				src: url(./org-unpack-20211227/UDDigiKyokashoN-R-01.ttf);
			}
			
			
			.centerrr
			{
				height: 10em;
				display: flex;
				align-items: center;
				justify-content: center;
			}
			
			.boxin
			{
				margin: auto;
				position: absolute;
				top:70%;
				left: 50%;
				transform: translate(-50%, -50%);
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
		
		<script>
			function pdfORnotForm()  // 表單判斷是否有選擇檔案，是否為pdf檔案
			{
				var PDFFILE = document.forms["StartAnalyzeForm"]["my_file"].value;
				
				if (PDFFILE == "")
				{
					alert("請選擇檔案..!");
					return false;
				}
				
			}
		/*	
			var fileSize=0;
			var SizeLimit=1024;
			
			function checkFile()
			{
				 var f = document.getElementById("my_file");
				//FOR IE
				if ($.browser.msie) 
				{
					var img = new Image();
					img.onload = checkSize;
					img.src = f.value;
				}
				//FOR Firefox,Chrome
				else 
				{
					fileSize = f.files.item(0).size;
					checkSize();
				}
			}
			
			//檢查檔案大小
			function checkSize() 
			{
				//FOR IE FIX
				if ($.browser.msie) 
				{
					fileSize = this.fileSize;
				}

				if (fileSize > SizeLimit) 
				{
					Message((fileSize / 1024).toPrecision(4), (SizeLimit / 1024).toPrecision(2));
				} 
				else 
				{
					document.FileForm.submit();
				}
			}

			function Message(file, limit) 
			{
				var msg = "您所選擇的檔案大小為 " + file + " kB\n已超過上傳上限 " + limit + " kB\n不允許上傳！"
				alert(msg);
			} 
		*/	
			//-------------------------------------
			
			
			
		</script>
		
		
		
	  </div>
	</body>
</html>
<?php

		}
		else
		{
			echo"<script>alert('不好意思，您的身份不適用於此功能。');</script>";
			header("refresh:0;url=homepage.php"); 
			
		}
	}
	
	
?>

