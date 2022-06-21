<!-- Top menu-->
  <ul class="topnav -fixed" id="Topnav">
     <li>
        <a class="brand" href="https://location.com.pl">
			<div>
				<img src="view/images/logo.png" />
				<span style="margin-left:10px;">Location App</span>
			</div>
		</a>
     </li>
	 
     <li>
        <a href="index.php?page=devices" <? echo ($_GET['page']=='devices')? 'class="active"' : ''; ?>>
			Moje urządzenia
		</a>
     </li>
     <li>
        <a href="index.php?page=account" <? echo ($_GET['page']=='account')? 'class="active"' : ''; ?>>
			Moje konto
		</a>
     </li>
	 <li>
        <a  onclick="openmodal('helpModal');" style="cursor: help;">
			Pomoc
		</a>
     </li>
     <li class="-icon">
        <a href="javascript:void(0);" onclick="topnav('Topnav')"><img style="height:20px;" src="view/images/hamburger.png"></a>
     </li>
     <li class="brand" style="float:right; display:inline!important">
        <a href="index.php?a=log_out">
			<img src="view/images/logout.png">
			<span>Wyloguj</span>
		</a>
     </li>
  </ul>
	
	<div class="container _force-full-width" style= "padding:5%; padding-top:0px; padding-bottom:0px; background: white; background: linear-gradient(#c3f6c3, white); margin-top:56px;">
			<div id="main" style="background-color:#FFF; padding-top:40px;">
