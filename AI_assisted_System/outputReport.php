<?php
	include("connect_mysql.php");//連結資料庫
	session_start();  //很重要，可以用的變數存在session裡
	$username=$_SESSION["username"];
	$acc=$_SESSION["account"];
	$USER_identity=$_SESSION["identity"];
	
	$S_member_id=$_SESSION["member_id"];
	
	


	$check_Redundant="SELECT * FROM member_table WHERE account='".$acc."' && phonenumber='".$PHONENUMBER."' ";
	$P_name=$_FILES['my_file']['name'];  // 檔案名稱
	$P_tmmp=$_FILES['my_file']['tmp_name'];  // 檔案位置
	$P_size=$_FILES['my_file']['size'];  // 檔案大小
	$P_type=$_FILES['my_file']['type'];  // 檔案類別
	
	
	$SEL_JSON="SELECT * FROM member_table WHERE account= '".$acc."' ";
				
	$RESULT_JSON = mysqli_query($link,$SEL_JSON);//執行
	$_SELF_JSON=mysqli_fetch_assoc($RESULT_JSON);
				
	
	
	
	
	

if($_SERVER["REQUEST_METHOD"]=="POST")
{
	
/*

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
*/
	
	# 限制上傳檔案類型 僅pdf
	if ($P_type == "application/pdf")
	{	# 檢查檔案是否上傳成功
		if ($_FILES['my_file']['error'] === UPLOAD_ERR_OK)
		{/*
			echo '檔案名稱: ' . $_FILES['my_file']['name'] . '<br/>';
			echo '檔案類型: ' . $_FILES['my_file']['type'] . '<br/>';
			echo '檔案大小: ' . ($_FILES['my_file']['size'] / 1024) . ' KB<br/>';
			echo '暫存名稱: ' . $_FILES['my_file']['tmp_name'] . '<br/>';
		*/
			# 檢查檔案是否已經存在
			if (file_exists("C:\AppServ\www\AI_assisted_System\UPLOAD_FILE\\" . $username . "-" . $acc . "-" . $P_name))
			{
				echo "<script>alert('檔案已存在!');</script>";
			} 
			else 
			{
				$file = $_FILES['my_file']['tmp_name'];
				
				//$dest = 'upload/' . $_FILES['my_file']['name'];
				$dest = "C:\AppServ\www\AI_assisted_System\UPLOAD_FILE\\" . $username . "-" . $acc . "-" . $P_name;
				
				# 將PDF檔案移至指定位置
				move_uploaded_file($file, $dest);
				
				//執行python檔 讀取學生的PDF歷程資料，進行斷詞，比對專業詞彙，
				exec("./sendpy/test.py"); // J個可以執行python檔

				//python會輸出json檔存入資料庫
				
				//PHP 讀取資料庫中的json檔，剖析資料 並製作成圖表
				
				
				//現在要來寫 json select 
				
				/*
				$UPJSON="UPDATE member_table SET `jsonreport`= 
				'[{\"Name1\":{\"value1\":\"22\", \"value2\":\"33\"}},{\"Name2\":{\"value1\":\"44\", \"value2\":\"66\"}}]' 
				WHERE `member_table`.`member_id`=\"$S_member_id\"  ";
				
				if (mysqli_query($link,$UPJSON))
				{
				}
				*/
				

				echo "<script>alert('檔案上傳成功!       請靜待幾分鐘讓系統為您分析，分析完畢將可以在<評估報告>頁面查看建議!');</script>"; 
			
			
			
			
			
			
			}
		} 
		else 
		{
			echo '錯誤代碼：' . $_FILES['my_file']['error'] . '<br/>';
			exit();
		}
	
	}
	else
	{
		echo "<script>alert('請勿上傳 pdf 以外類型的檔案!');</script>";
		//以防原檔案的資料還在，故使用重新整理頁面方式
		echo '<meta http-equiv=REFRESH CONTENT=0;url=StartAnalyze.php>';
		//header('location:StartAnalyze.php');  瞬間抵達 連上面的alert都跳不出來XD
	}
	
}	
	
	
	
	
	
	
	
	if (!isset($_SESSION['username']))
	{
			echo"<script>alert('目前為遊客瀏覽模式，進階功能請登入。');</script>";
			header("refresh:0;url=login.php"); 
			//用上面那行會等一秒，用下面瞬間跳轉
			//header('location:login.php');  
	}
	else
	{// https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js
?>


<html>
	<head>
	  <meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/>
	 
	  <title>書審Spotlight-評估報告</title>
	</head> 
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
	</script>
	<link rel="icon" type="image/x-icon" href="./photos/_spotlight_web_icon.ico" />
	
	<body bgcolor="#ECECFF">
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
	
						<li><a style=";" href ="StartAnalyze.php"> <b>開始分析</b> </a></li>
						<li><a style="color:#FFD900;" href="outputReport.php"> <b>評估報告</b> </a></li>
						<li><a href="#"> <b>FAQ 常見問題</b> </a></li>					
					</ul>	
				</nav>   
			</div>
		</header>
				
		<script src="https://cdn.highcharts.com.cn/highcharts/highcharts.js"></script>
        <script src="https://cdn.highcharts.com.cn/highcharts/modules/exporting.js"></script>
        <script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
        <script src="https://cdn.highcharts.com.cn/highcharts/themes/dark-unica.js"></script>
		
		<div class="centerGRA">
			<div id="container" style=""></div>
			
			<?php
				echo $_SELF_JSON['jsonreport'];
			
			?>
			
		</div>
		
		
		
		
		<!--
		<canvas id="myChart" style="width:100%;max-width:700px"></canvas>
		-->
		
	
	
	
	
	
		<style>
			@import url(header.css);
			@font-face
			{
				font-family: UDDig;
				src: url(./org-unpack-20211227/UDDigiKyokashoN-R-01.ttf);
			}
			
			
			canvas {
					padding-top: 50;
					padding-down: 0;
					padding-left: 0;
					padding-right: 0;
					margin-left: auto;
					margin-right: auto;
					display: block;
					width: 800px;
					}
			
			a:hover 
			{ <!-- #500001 酒紅 -->
				color:	#FFD900; <!--橘黃 --> 
			}
			
		</style>
	  </div>
	</body>
</html>

<script>
	var chart = Highcharts.chart('container',
	{
		chart: 
		{
			type: 'column'
		},
		title: 
		{
			text: '各領域關鍵字涉及程度'
		},
		subtitle: 
		{
			text: '數據來源:您的備審資料'
		},
		xAxis: 
		{
			categories: 
			[
				'一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'
			],
			crosshair: true
		},
		yAxis: 
		{
			min: 0,
			title: 
			{
				text: '降雨量 (mm)'
			}
		},
		tooltip: 
		{
			// head + 每个 point + footer 拼接成完整的 table
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
			'<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			useHTML: true
		},
		plotOptions: 
		{
			column: 
			{
				borderWidth: 0
			}
		},
		series: 
		[
			{
				name: '東京',
				data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
			}, 
			{
				name: '紐約',
				data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]
			}, 
			{
				name: '倫敦',
				data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]
			}, 
			{
				name: '柏林',
				data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]
			}
		]
	});
	
	
	
	
/*
	var xyValues = [
	  {x:50, y:7},
	  {x:60, y:8},
	  {x:70, y:8},
	  {x:80, y:9},
	  {x:90, y:9},
	  {x:100, y:9},
	  {x:110, y:10},
	  {x:120, y:11},
	  {x:130, y:14},
	  {x:140, y:14},
	  {x:150, y:15}
	];

	new Chart("myChart", {
	  type: "scatter",
	  data: {
		datasets: [{
		  pointRadius: 4,
		  pointBackgroundColor: "rgb(0,0,255)",
		  data: xyValues
		}]
	  },
	  options: {
		legend: {display: false},
		scales: {
		  xAxes: [{ticks: {min: 40, max:160}}],
		  yAxes: [{ticks: {min: 6, max:16}}],
		}
	  }
	});*/
</script>
		


	





<?php
		// 下括號
	}

?>