	
		<font face="DFKai-sb">	
		<form name="form" method="post" action="connect.php">
			
		<!--  HTML  -->
		
	<div class="header">
		<div class="header__inner clearfix">
			<h1 class="header__logo pull-left">
				<a href="/">
					<img src="assisted_System_logo.jpg" alt="學習歷程輔助系統" class="img-responsive">
				</a>
			</h1>
		</div>
		
		
		
		
		
		
		
		<nav class="menu menu--fixed affix">
			<div class="menu__bg">
				<div class="menu__container clearfix">
					
					<ul class="list-unstyled menu__left">
						<li class="menu__item">
							<a href="https://ithelp.ithome.com.tw/questions" class="menu__item-link  menu__item-link--pl">技術問答</a>
						</li>
						<li class="menu__item">
							<a href="https://ithelp.ithome.com.tw/articles?tab=tech" class="menu__item-link  menu__item-link--active ">技術文章</a>
						</li>
                    
						<li class="menu__item">
							<a href="https://ithelp.ithome.com.tw/articles?tab=job" class="menu__item-link  hidden-xs">iT 徵才</a>
						</li>
                    
						<li class="menu__item">
							<a href="https://ithelp.ithome.com.tw/tags" class="menu__item-link  hidden-xs">Tag</a>
						</li>
						<li class="menu__item">
							<a href="https://ithelp.ithome.com.tw/talks" class="menu__item-link  hidden-xs">聊天室</a>
						</li>
						<li class="menu__item menu__item--ironman">
							<a href="/2021ironman?utm_source=ithelp&amp;utm_medium=navbar&amp;utm_campaign=ironman13" target="_blank" class="menu__item-link hidden-xs">鐵人賽</a>
						</li>
						<li class="menu__item menu__item--ironman">
							<a href="/2021ironman-dojo?utm_source=ithelp&amp;utm_medium=navbar&amp;utm_campaign=ironman13" target="_blank" class="menu__item-link hidden-xs">鐵人館</a>
						</li>
					</ul>
					
					
					<ul class="list-unstyled menu__right">
                        <li class="menu__item">
                            <div id="searchDropdown" class="menu__search-btn">
                                <span class="menu__search-toggle"></span>
                            </div>
                        </li>
                        <li class="menu__item">
                            <a href="https://ithelp.ithome.com.tw/users/login" class="menu__item-link">登入/註冊</a>
                        </li>
                    </ul>
            		
				</div>
			</div>
		</nav>
		<div class="menu__mask"></div>	
	</div>
		
		
		
		   <div class="wrap">
				<div class="header2">  <!--  標頭放 功能按鍵 -->
					
					<div class="left">
						<br/>
						<table border="1" style="border:5px #FFA1A1 groove;" bgcolor="#FFD4FF" width="110" height="60" align="center">
							<tbody>
									<td align="center"><span style="font-size:20px;"><a href="member.php">成員中心</a></span></td>	
							</tbody>
						</table> 	
					</div>
					
					
					
					<div class="right">
						<!-- <table border="1" style="border:5px #FFA1A1 groove;" width="565" height="100" >  -->
							<tbody>
									<td align="center"><span style="font-size:30px;"><a href="catalogue.php">目錄總覽</a></span></td>
									<td align="center"><span style="font-size:30px;"><a href="add_invest.php">新增項目</a></span></td>
									<td align="center"><span style="font-size:30px;"><a href="modify_invest.php">管理投資</a></span></td>	
							</tbody>
					<!--	</table>  -->
					</div>
				
				</div>
				
		
				<div class="clearfix"></div>
				
		   </div>
				 <!--	// CSS			
						//	背景模板的 CSS		-->
		   
			<style>	
				      
				.clearfix
				{
					clear:both;
				}
				
				
				.wrap
				{
					width: 1500px;
					margin: 0 auto;
				}
				
				

				.content,.header2
				{
					margin-bottom: 1px;  <?php // header、content表格之間的距離?>
					padding: 6px;
					font-size:18px;
					border:3px	#A86E00 groove;
				}

				
				
				.header2
				{   <?php // 目錄列表的背景藍色圖#FFFFB5 ?>
					height: 105px;
					background: ;
					background-image:url("titleblue2001500.jpg");
					background-repeat:repeat;
					color:black;
					padding: 3px;
				}

				.content
				{
					height: 1500px;
					background: ;
					background-image:url("3stage.png");
					background-repeat:repeat;  <!--  no-repeat; -->
					color:purple;
				}
			
				
				
				
				.left,.center,.right
				{
					
		
					float:left;
					margin:2px;
					padding:px;
					
					
				}

				.left
				{
					border:0 px;
					width: 300px;
					
				}
				.center
				{
					width: 200px;
				}
				.right
				{
					width: 565px;
					background: ;
					background-image:url("3stage3.jpeg");
					background-repeat:no-repeat;
				}


				
		   
		   </style>
	
	
			<style type="text/css" media="screen">  
		   
				a{
					text-decoration: none; 
					color: black; 
				}
				
				#expand      
				{
					background-color: black;
				}
				
				.description 
				{
					display: none;    
				}
				
				.entry       
				{
					margin: 0; 
					padding: 0px;
				}
			</style>
				
				
				
				
				





		</form>
		</font>
											
	 
	 
	 
	 <!--CSS
	 
	 
	 html { box-sizing: border-box; }
*, *::before, *::after { box-sizing: inherit; }

body {
    padding: 20px 0;
}

h1, h2 {
    margin: 0;
    padding: 34px 0 14px 40px;
}

nav > ul {
	background-image:url("titleblue2001500.jpg");
	width: 1500px;
	height: 150px;
  /*  background-color: rgb(120, 130, 230); */
    list-style: none;   /* 移除項目符號 */
    margin: 0 auto;
    padding: 0;
}


nav a {
    color: inherit; /* 移除超連結顏色 */
    display: block; /* 讓 <a> 填滿 <li> */
    font-size: 1.5rem;
    padding: 10px;
    text-decoration: none;  /* 移除超連結底線 */
}

/* 滑鼠移到 <a> 時變成深底淺色 */
nav li:hover {
    background-color: rgb(110, 110, 110);
    color: yellow;
}

.flex-nav {
    display: flex;
    justify-content: center;
}

.inline-block-nav 
{
    text-align: center;
    font-size: 0;   /* 移除 <li> 之間的空隙 */
}

.inline-block-nav > li 
{
    display: inline-block;
}

-->
	 
	 
	 
	 