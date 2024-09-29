<DOCTYPE html>
<html>
	
	  <meta http-equiv="Content-Type" content="text/html"; charset="utf-8"/>
	
	
		<?php
			
		
			
			$link=mysqli_connect("localhost","root","nuuadmin","ai_assisted")
				or die ("無法開啟MySQL資料庫連接!<br/>");
			
			if (!$link)
			{
					echo "MySQL 資料庫連接錯誤~!<br/>";
				exit();
			}
			//else
			//{
			//	echo "MySQL 資料庫  連接成功!!!<br/>";
			//}
				
				
			// 指定開啟的資料庫名稱 ai_assisted
			$dbname="ai_assisted";
			// 開啟指定的資料庫
			if ( !mysqli_select_db($link, $dbname) )
				die("無法開啟 $dbname 資料庫!<br/>");
			//else
			//	echo "資料庫: $dbname 開啟成功!<br/>";
			
			mysqli_query($link, 'SET CHARACTER SET utf8'); // 送出Big5編碼的MySQL指令

			mysqli_query($link,  "SET collation_connection = 'utf8_general_ci'");
		
			
			
			/*
			$INSERT_INTO = "INSERT INTO member_table(`username`, `account`, `password`, `photo`, `email`, `phonenumber`, `identity`) VALUES (\"王大伯\",\"wang\",\"wangwang\",\"\",\"wang2@gmail.com\",\"0900021012\",\"伯伯\")"; // 指定SQL字串
			echo "INSERT_INTO字串: $INSERT_INTO <br/>";																									
			//送出UTF8編碼的MySQL指令
			mysqli_query($link, 'SET NAMES utf8'); 
			mysqli_query($link, $INSERT_INTO);	
			*/
			//	mysqli_close($link); // 關閉資料庫連接
			
			
		?>
	
	
	
</html>