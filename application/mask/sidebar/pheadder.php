<?php 
$viewbase = new viewbase;
$encUserId = $viewbase->encode($_SESSION['user_id']);
 echo('<!doctype html><html itemscope="" itemtype="http://schema.org/SearchResultsPage" lang="en">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" name="viewport">
		<title>cSol MIS2 | Abdullah Salem Trading Est.</title>		
		<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/jquery-ui.min.css" rel="stylesheet">
		<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/bootstrap.css" rel="stylesheet">
		<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/chosen.min.css" rel="stylesheet">
		<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/MonthPicker.css" rel="stylesheet">
		<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/general.css" rel="stylesheet">
		<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/simple-sidebar.css" rel="stylesheet">
		<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/lightbox.css" rel="stylesheet">
		<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/hover.css" rel="stylesheet">
        <link href="http://'.$_SERVER ['HTTP_HOST'].'/css/ui.multiselect.css" rel="stylesheet">	
		<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/jquery-1.12.3.min.js"></script>
		<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/jquery-ui.min.js"></script>
		<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/lightbox.js"></script>
		<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/bootstrap.min.js"></script>
		<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/bootstrap-select.js"></script>
		<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/chosen.jquery.min.js"></script>
		<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/jquery.numeric.min.js"></script>
		<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/MonthPicker.js"></script>
		<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/highcharts.js"></script>
        <script src="http://'.$_SERVER ['HTTP_HOST'].'/js/ui.multiselect.js"></script>
		<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/js.printElement.min.js"></script>
		<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/application.js"></script>
		<style>
		div.my-image { width:150px;
		height:150px;
		background-repeat: no-repeat;
		background-size: 150px 150px;
		}

/* dummy */ 


</style>
</head>
<body style="">
	<div class="">
		<div class="center">
			<h4 style="margin-top: 1px;">
			<div class="title-margin" style="margin-top: 0%; display:flex;">');
 //if(_REQUEST<>'default/default/dashboard') 
 echo('	<div style="margin-top: 0%; display:flex;" class= "quick_link_box">'.
				x ( array ('link' => 'accounts/sales/index','label' => '
				<i class="glyphicon glyphicon-signal " style="font-size: x-large;padding-left: 10%;margin-left: 4px;background-color: lavender;border-radius: 8px;"></i>
				','aatr'=>'title="Sales"' ), '' ).'
				'.x ( array ('link' => 'accounts/receivable/index','label' => '
				<i class="glyphicon glyphicon-save " style="font-size: x-large;padding-left: 10%;margin-left: 7px;background-color: lavender;border-radius: 8px;"></i>
				','aatr'=>'title="Cash"' ), '' ).'
				
				'.x ( array ('link' => 'accounts/expense/list','label' => '
				<i class="glyphicon glyphicon-new-window " style="font-size: x-large;padding-left: 10%;margin-left: 7px;background-color: yellow;border-radius: 8px;"></i>
				','aatr'=>'title="Expense"' ), '' ).'
				
				'.x ( array ('link' => 'accounts/purchase/index','label' => '
				<i class="glyphicon glyphicon-list-alt " style="font-size: x-large;padding-left: 10%;margin-left: 7px;background-color: navajowhite;border-radius: 8px;"></i>
				','aatr'=>'title="Purchase"' ), '' ).'
				'.x ( array ('link' => 'accounts/payable/index','label' => '
				<i class="glyphicon glyphicon-open " style="font-size: x-large;padding-left: 10%;margin-left: 7px;background-color: navajowhite;border-radius: 8px;"></i>
				','aatr'=>'title="Payments"' ), '' ).'
			</div>');
//else echo('<div style="margin-top: 0%; display:flex; 14%;width: 16%;"> </div>');
 /*echo('<div style="margin-top: 0%; display:flex; margin-left: 10%;width: 40%;">
				<img alt="" src="http://'.$_SERVER ['HTTP_HOST'].'/css/images/babana1.png" style="margin-top: 2px;margin-left: 1px;margin-right: 1px;padding-left: 30%;" height="60">
				<span style="margin-top: 1%;"> creativeSol   <p style="margin: 12px;">  --TITLE--'. @$_SESSION ['COMP_LABL2'].' </p></span>
				</div>');	*/
 echo('<div style="margin-top: 0%; display:flex; margin-left: 20%" class= "quick_link_box pull-right">
				'.x ( array ('link' => 'accounts/sales/add','label' => '
				<i class="glyphicon glyphicon-signal " style="font-size: x-large;padding-left: 10%;margin-left: 4px;background-color: lavender;color: green;border-radius: 8px;"></i>
				' ,array("param"=>'class="facebox"') ,'aatr'=>'title="Create Sales"' ), '' ).'
				'.x ( array ('link' => 'accounts/receivable/receipt','label' => '
				<i class="glyphicon glyphicon-save " style="font-size: x-large;padding-left: 10%;margin-left: 7px;background-color: lavender;color: green;border-radius: 8px;"></i>
				' ,array("param"=>'class="facebox"') ,'aatr'=>'title="Receive Cash"' ), '' ).'
				
				'.x ( array ('link' => 'accounts/expense/add','label' => '
				<i class="glyphicon glyphicon-new-window " style="font-size: x-large;padding-left: 10%;margin-left: 7px;background-color: yellow;color: green;border-radius: 8px;"></i>
				' ,array("param"=>'class="facebox"') ,'aatr'=>'title="Add Expense"' ), '' ).'
				
				'.x ( array ('link' => 'accounts/purchase/add','label' => '
				<i class="glyphicon glyphicon-list-alt " style="font-size: x-large;padding-left: 10%;margin-left: 7px;background-color: navajowhite;color: green;border-radius: 8px;"></i>
				' ,array("param"=>'class="facebox"') ,'aatr'=>'title="Make Purchase"' ), '' ).'
				'.x ( array ('link' => 'accounts/payable/payment','label' => '
				<i class="glyphicon glyphicon-open " style="font-size: x-large;padding-left: 10%;margin-left: 7px;background-color: navajowhite;color: green;border-radius: 8px;"></i>
				' ,array("param"=>'class="facebox"') ,'aatr'=>'title="Make Payment"' ), '' ).'
			</div>
		</div>
	</div>

<nav class="navbar navbar-inverse topbar">
<div class="container-fluid">
<ul class="nav navbar-nav navbar-right">
<li class="dropdown"><a href="#" data-toggle="dropdown"
		class="dropdown-toggle" style="padding-top: 12px;">'.$_SESSION ['user_dip_name'].'<span class="caret"></span></a>
			<ul class="dropdown-menu">
				'.barMenu($encUserId).'
				<li><a href="http://'.$_SERVER ['HTTP_HOST'].'/default/default/logout" >Logout</a></li>
			</ul></li>
		</ul>
		</div>
		</nav>');
	
function barMenu($encUserId)
{
	$active[_REQUEST] = 'active';
	
	//$menuHtml = '<li class="<!--sidebar-brand-->" style="background-color: papayawhip;"></li>';
	$menuHtml = '<li class="'.@$active['default/default/dashboard'].'">'.x(array('link'=>'default/default/dashboard','label'=>'Dashboard')).'</li>';
	$menuHtml .= '<li class="'.@$active['admin/item/list'].'">'.x(array('link'=>'admin/item/list','label'=>'Item')).'</li>';
	$menuHtml .= '<li class="'.@$active['admin/customer/list'].'">'.x(array('link'=>'admin/customer/list','label'=>'Customer')).'</li>';
	$menuHtml .= '<li class="'.@$active['accounts/sales/index'].'">'.x(array('link'=>'accounts/sales/index','label'=>'Sales')).'</li>';
	$menuHtml .= '<li class="'.@$active['accounts/receivable/index'].'">'.x(array('link'=>'accounts/receivable/index','label'=>'Payment Collection')).'</li>';
	$menuHtml .= '<li class="'.@$active['admin/vendor/list'].'">'.x(array('link'=>'admin/vendor/list','label'=>'Vendor')).'</li>';
	$menuHtml .= '<li class="'.@$active['admin/users/index'].'">'.x(array('link'=>'admin/users/index','label'=>'Users')).'</li>';
	$menuHtml .= '<li class="'.@$active['admin/users/changepwd'].'">'.x(array('link'=>'admin/users/changepwd','ref'=>array('ref'=>$encUserId),'label'=>'Change Password',array("param"=>'class="facebox"'))).'</li>';
	$menuHtml .= '<li class="'.@$active['default/default/backup'].'">'.x(array('link'=>'default/default/backup','ref'=>array('ref'=>$encUserId),'label'=>'Backup')).'</li>';
	
	return $menuHtml;
}


function mainMenu($encUserId)
{
	$toggleText = '';
	
	if(@$_COOKIE['menu']=='show')
		$toggleText = '';
		elseif(@$_COOKIE['menu']=='hide')
		$toggleText = 'class="toggled"';
		
		$active[_REQUEST] = 'active';
		
		
		$menuHtml = '<div class="">
					<div id="wrapper" '.@$toggleText.' style="padding-top: 1pt;">
						<!-- Sidebar -->
						<div id="sidebar-wrapper">
							<ul class="sidebar-nav">
								<li class="<!--sidebar-brand-->">
									<p style="margin-top: 5px; margin-bottom: 5px;">
										<img width="150" src="http://'.$_SERVER ['HTTP_HOST'].'/css/images/ast5.png" alt="LOGO" style="padding-top: 6px;  padding-left: 0;">
									</p>
								</li>';
		
			$menuHtml.= formSideMenu ('default/default/dashboard','Dashboard'); 
			$menuHtml.= formSideMenu ('branches/index/index','Home'); 
			$menuHtml.= formSideMenu ('branches/node/index','Branches'); 
			$menuHtml.= formSideMenu ('branches/member/index','C19 Contacts'); 
			
			$menuHtml.= formSideMenu ('manage/item/list','Items'); 
			$menuHtml.= formSideMenu ('manage/unit/list','Unit'); 
			$menuHtml.= formSideMenu ('manage/category/list','Category'); 
			$menuHtml.= formSideMenu ('manage/vendor/list','Vendor'); 
			
			$menuHtml.= formSideMenu ('admin/users/index','Users'); 
			//$menuHtml.= formSideMenu ('admin/users/changepwd','Password'); 	

			
			$menuHtml .='</ul>
			</div>';
			
			echo $menuHtml;
}
//mainMenu($encUserId);

function formSideMenu($link,$label){
	
	return x(array('link'=>$link,'label'=>'
							<div class="col-md-12">
								<div class="card" style="margin-bottom: 3px;">
										<div class="card-block">
										<div class="card-block">
											<h4 class="card-title">'.$label.'</h4>
											<p class="card-text">
												<small class="text-muted"></small>
											</p>
										</div>
									</div>
								</div>
							</div>
						'),'');
	
}




if(@$_SESSION['feedback']):?>
		<?php echo('
			<div class="card-block" id="feedback_container">
				<div role="alert" class="alert alert-success">
					<strong>Success! </strong>'
		.$_SESSION['feedback'].'</div>
			</div>
		');
$_SESSION['feedback'] = '';		
endif;
/*if(@$_SESSION['error']):?>
		<?php echo('
			<div class="card-block" id="feedback_container">
				<div role="alert" class="alert alert-danger">
					<strong>Error! </strong>'
		.$_SESSION['error'].'</div>
			</div>
		');
unset($_SESSION['error']);		
endif;*/
?>