<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<head>
		<title>書審Spotlight-成員清單-刪除頁面</title>
	</head>
	
	<?php
		include("connect_mysql.php");
		$SEL="SELECT * FROM member_table";
        $RESULT = mysqli_query($link,$SEL);
	?>
	
	<body bgcolor="#ECECFF">
		<div style="font-family: UDDig;">
		<!--用表格框起來 查出來的成員表 -->
		<header class="main-header">
			<div class="containerleft">
				<h3><a href="homepage.php" style="color:#ECF5FF;">書審Spotlight</a></h3>
				<nav>    
					<ul class="main-menu" >
						<li> <b>成員清單-刪除頁面</b> </li>
					</ul>	
				</nav>    
			</div>
		</header>
		
		
		<div align="left">	
			<a href="member.php" style="font-size:20px; ">回上頁 成員中心</a>
		</div>
		
		
		<div align="center">
				<table border="1"  style= "border:10px #FFF0D4 outset;" width="" height="" cellpadding="10" >
					<thead>
						<caption>
							<div style="text-align:left;">
								<h2>
									(原) - 成員表
								</h2>
							</div>	
						</caption>
					</thead>
					<tbody>
						<tr>
							<?php 
								while( $META= mysqli_fetch_field($RESULT))
									echo "<th><span style=font-size:20px;>" . $META->name . "</span></th>";
							?>	
						</tr>
				
						<?php
							$total_FIELDS=mysqli_num_fields($RESULT);
							while ($ROW = mysqli_fetch_row($RESULT))
							{
								echo "<tr align='center'>";  // 顯示每一筆紀錄的欄位值
								echo $ROW['username'];
								for ($i=0;$i <= $total_FIELDS-1;$i++)
								{
									echo "<td><span style=font-size:20px;>" . $ROW[$i] . "</span></td>";
								}
								echo "</tr>";
								
							}	
						?>	
					</tbody>
				</table>

			
			<form name="form" method="post" action="save_delete_member.php">
				<table border="3" style="border:8px #FF5959 inset;" width="500" Height="300" cell padding="10">	
					<caption><h2><span style="color:red;">欲刪除的成員</span></h2></caption>
		
					<th align="center" >
						<span style="font-size:20px;">
							<div>
								欲刪除的成員名稱: <input type="text" name="wantdelete_username" size="10" placeholder="username" /> 
							</div><br/>
							<input type="submit" name="submit" value="確定刪除" />&nbsp;&nbsp;&nbsp;
							<input type="reset" value="清除"/>
						</span>
					</th>
				</table>
			</form>
		
		</div>
		
			<style>
					@import url(memberpage.css);
					@font-face
					{
						font-family: UDDig;
						src: url(./org-unpack-20211227/UDDigiKyokashoN-R-01.ttf);
					}
			
			</style>
			
		</div>
	</body>
</html>