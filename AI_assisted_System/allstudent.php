<!DOCTYPE html>
<?php


include("connect_mysql.php");//連結資料庫
session_start();  //很重要，可以用的變數存在session裡


$username=$_SESSION["username"];
$USER_identity=$_SESSION["identity"];


		

$identity_list=array("無","系統管理員","老師","學生");



if (!isset($_SESSION['username']))
	{
			echo"<script>alert('目前為遊客瀏覽模式，進階功能請登入。');</script>";
			header("refresh:0;url=login.php"); 
			//用上面那行會等一秒，用下面瞬間跳轉
			//header('location:login.php');  
	}
	else
	{
		if ($USER_identity == 2)  // 只有老師可以有 學生一覽 的功能
		{
?>
<html>
	<head>
		<title>書審Spotlight-學生一覽表</title>
		<link href="table.css" rel="stylesheet">
	</head>
	
	<body bgcolor="#ECECFF">
	<div style="font-family: UDDig;">	
		<header class="main-header">
			<div class="containerleft">
				<h3><a href="homepage.php" style="color:#ECF5FF;">書審Spotlight</a></h3>
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
							<li><a href="allstudent.php"> <b>學生一覽</b> </a></li>
							<li><a href="studentsOutputReport.php"> <b>學生評估報告</b> </a></li>
							<li><a href="#"> <b>FAQ 常見問題</b> </a></li>					
		
							<a href="https://zh-tw.facebook.com/"><img src="./photos/fblogo.png" alt="" width="50" height="50" ></a>
							<a href="/"><img src="./photos/linelogo.png" alt="" width="50" height="50" ></a>
		
						<!--		list-style: none;   /* 移除項目符號 */         -->
						</ul>
					</nav>    
				
			
			</div>
		</header>
		
		<table border="1" align="center" style="min-width:80%; min-height:100%;">
			<caption>
				<h2>
					學生一覽表
				</h2>
			</caption>
		
		<tr style="color:#fff;" width="100%;" height="100%;">
			<th height="50px;">照片</th>
			<th>申請者姓名</th>
			<th>個人特色</th>
			<th>擅長領域</th>
			<th>學生個人報告</th>
		</tr>
<?php
//連線資料庫
  
$sql="SELECT * FROM member_table";
$result=mysqli_query($link,$sql);
//獲取資料表的資料條數     //-------------------------------------------------                 
$datanum=mysqli_num_rows($result);//take the num of data

//列印輸出所有資料


for($i=0;$i<$datanum;$i++){
	
	//while($result_arr=mysql_fetch_assoc($result)){
    $result_arr=mysqli_fetch_assoc($result);//抓取一行結果
	//echo $result_arr['sex'];
    $userid=$result_arr['member_id'];
    $name=$result_arr['username'];
    $account=$result_arr['account'];
	$sex=$result_arr['password'];
	$photo=$result_arr['photo'];
	$email=$result_arr['email'];
	$phone=$result_arr['phonenumber'];
	$ident=$result_arr['identity'];
	$json=$result_arr['jsonpath'];
	//echo $result_arr['name'];
    //print_r($result_arr);
	//echo "UPLOAD_FILE_member_photo/.$photo";
	if($ident==3)
	{
		
?>
		<tr align="center">
			<td height="50px;">
				<img src="./UPLOAD_FILE_member_photo/<?php echo $photo;?>"alt="找不到照片" 
				width="75" height="75"/>
			</td>
			
			<td ><?php echo $name;?></td>
			<td ><?php echo $userid;?></td>
			<td ><?php echo $account;?></td>
			<td >
				<a href="https://www.google.com" style="color:#000;  font-size:20px;">
					<b>
					詳情
					</b>
				</a>	
			</td>
		</tr>
	
<?php	
	}
}
mysqli_close($link);
?>

</table>
		<style>
			@import url(table.css);
			@font-face
			{
				font-family: UDDig;
				src: url(./org-unpack-20211227/UDDigiKyokashoN-R-01.ttf);
			}
			
		</style>
	
	
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