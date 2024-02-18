<?php
$viewbase = new viewbase();
$encUserId = $viewbase->encode($_SESSION['user_id']);

?>
<?php

echo ('<!doctype html><html itemscope="" itemtype="http://schema.org/SearchResultsPage" lang="en">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" name="viewport">
		<title>CreativeSol MIS V2</title>		
		<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/jquery-ui.min.css" rel="stylesheet">
		<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/bootstrap.css" rel="stylesheet">
		<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/chosen.min.css" rel="stylesheet">
		<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/MonthPicker.css" rel="stylesheet">
		<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/general.css" rel="stylesheet">
		<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/simple-sidebar.css" rel="stylesheet">
		<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/lightbox.css" rel="stylesheet">
		<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/sweetalert.css" rel="stylesheet">
		<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/hover.css" rel="stylesheet">
        <link href="http://' . $_SERVER['HTTP_HOST'] . '/css/ui.multiselect.css" rel="stylesheet">	
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/jquery-1.12.3.min.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/jquery-ui.min.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/lightbox.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/bootstrap.min.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/bootstrap-select.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/chosen.jquery.min.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/jquery.numeric.min.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/MonthPicker.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/highcharts.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/sweetalert.js"></script>
        <script src="http://' . $_SERVER['HTTP_HOST'] . '/js/ui.multiselect.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/js.printElement.min.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/application.js"></script>
        <script> var baseurl = "' . APPURL . '"</script>
        <script>
            $().ready(function() {
                //if ($(\'li.active\').length ) 
            	   //$(\'li.active\')[0].scrollIntoView();
            });
        </script>
		<style>
		div.my-image { width:150px;
		height:150px;
		background-repeat: no-repeat;
		background-size: 150px 150px;
		}
        </style>
</head>
<body>
<nav class="navbar navbar-inverse topbar">
	<div class="container-fluid">' . showNotification($_SESSION['upd_count']) . '<div class="navbar-header">
			<h3 style="margin-top: 0px; margin-bottom:0px" class="color-whiter">CreativeSol MIS</h3>
		</div>
<ul class="nav navbar-nav navbar-right">
<li class="dropdown"><a href="#" data-toggle="dropdown"
		class="dropdown-toggle" style="padding-top: 12px;">' . $_SESSION['user_dip_name'] . '<span class="caret"></span></a>
			<ul class="dropdown-menu">
				' . barMenu($encUserId) . '
				<li><a href="http://' . $_SERVER['HTTP_HOST'] . '/default/default/logout" >Logout</a></li>
			</ul></li>
		</ul>
		</div>
		</nav>');

function barMenu($encUserId)
{
    $active[_REQUEST] = 'active';
    // $menuHtml = '<li class="<!--sidebar-brand-->" style="background-color: papayawhip;"></li>';
    $menuHtml = '<li class="' . @$active['default/default/dashboard'] . '">' . x(array(
        'link' => 'default/default/dashboard',
        'label' => 'Dashboard'
    )) . '</li>';
    $menuHtml .= '<li class="' . @$active['acl/config/setmodules'] . '">' . x(array(
        'link' => 'acl/config/setmodules',
        'label' => 'Permissions'
    )) . '</li>';
    $menuHtml .= '<li class="' . @$active['admin/users/changepwd'] . '">' . x(array(
        'link' => 'admin/users/changepwd',
        'ref' => array(
            'ref' => $encUserId
        ),
        'label' => 'Change Password',
        array(
            "param" => 'class="facebox"'
        )
    )) . '</li>';
    $menuHtml .= '<li class="' . @$active['default/default/backup'] . '">' . x(array(
        'link' => 'default/default/backup',
        'ref' => array(
            'ref' => $encUserId
        ),
        'label' => 'Babkup',
        array(
            "param" => 'class="facebox"'
        )
    )) . '</li>';

    return $menuHtml;
}

function showNotification($count)
{
    if ($count)
        x(array(
            'link' => 'erp_manage/updates/list',
            'label' => '<span style="float: right;border-radius: 28px;width: 2.9em;padding-top: 3px;padding-left: 5px;border: 1px white;background-color: whitesmoke;">
			<a style="color: red;padding-top: 1px;padding-bottom: 0px;float: right;margin-top: 1px;margin-bottom: 1px;padding-left: 1px;padding-right: 1px;font-size: 15px;"
			href="' . APPURL . 'erp_manage/updates/list" class="btn btn-lg glyphicon-refresh-animate" >
			<span class="glyphicon glyphicon-bell"></span></a> ' . $count . '</span>'
        ));
}

function mainMenu($encUserId)
{
    $toggleText = '';

    if (@$_COOKIE['menu'] == 'show')
        $toggleText = '';
    elseif (@$_COOKIE['menu'] == 'hide')
        $toggleText = 'class="toggled"';

    $active[_REQUEST] = 'active';
    if (_REQUEST == 'erp_employee/master/view')
        $active['erp_employee/master/list'] = 'active';
    if (_REQUEST == 'erp_property/master/view' || _REQUEST == 'erp_masters/building/list')

        $active['erp_property/master/list'] = 'active';
    if (_REQUEST == 'erp_vehicle/master/view')
        $active['erp_vehicle/master/list'] = 'active';
    if (_REQUEST == 'erp_masters/customer/view')
        $active['erp_masters/customer/list'] = 'active';
    if (_REQUEST == 'erp_masters/vendor/view')
        $active['erp_masters/vendor/list'] = 'active';

    if (_REQUEST == 'acl/config/setcontrollers')
        $active['acl/config/setmodules'] = 'active';
    if (_REQUEST == 'acl/config/setactions')
        $active['acl/config/setmodules'] = 'active';

    $menuHtml = '<div class="">
					<div id="wrapper" ' . @$toggleText . '>
						<!-- Sidebar -->
						<div id="sidebar-wrapper">
							<ul class="sidebar-nav">
								<li class="<!--sidebar-brand-->"  style="border: none;padding-bottom: 7px;margin-bottom: 2px;margin-top: -5px;width: 103%;margin-left: -3px;">
									<p style="margin-top: 5px; margin-bottom: 5px;">
										<img width="150" src="http://' . $_SERVER['HTTP_HOST'] . '/css/images/ast_g.png" alt="ast" style="padding-top: 6px;  padding-left: 0;">
									</p>
								</li>';
    $menuHtml .= x(array(
        'link' => 'default/default/dashboard',
        'label' => '<li class="' . @$active['default/default/dashboard'] . '">' . 'DASHBOARD' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_employee/master/list',
        'label' => '<li class="' . @$active['erp_employee/master/list'] . '">' . 'EMPLOYEES' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_property/master/list',
        'label' => '<li class="' . @$active['erp_property/master/list'] . '">' . 'PROPERTY' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_property/tenants/list',
        'label' => '<li class="' . @$active['erp_property/tenants/list'] . '">' . 'TENANTS' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_vehicle/master/list',
        'label' => '<li class="' . @$active['erp_vehicle/master/list'] . '">' . 'VEHICLES' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_expense/expense/list',
        'label' => '<li class="' . @$active['erp_expense/expense/list'] . '">' . 'EXPENSE' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_expense/payments/list',
        'label' => '<li class="' . @$active['erp_expense/payments/list'] . '">' . 'PAYMENTS' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_invoice/bill/list',
        'label' => '<li class="' . @$active['erp_invoice/bill/list'] . '">' . 'INVOICE' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_invoice/collection/list',
        'label' => '<li class="' . @$active['erp_invoice/collection/list'] . '">' . 'COLLECTIONS' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_fund/cashbook/list',
        'label' => '<li class="' . @$active['erp_fund/cashbook/list'] . '">' . 'CASH BOOK' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_fund/cashflow/list',
        'label' => '<li class="' . @$active['erp_fund/cashflow/list'] . '">' . 'CASH FLOW' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_employee/salary/list',
        'label' => '<li class="' . @$active['erp_employee/salary/list'] . '">' . 'SALARY' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_manage/company/list',
        'label' => '<li class="' . @$active['erp_manage/company/list'] . '">' . 'COMPANY' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_manage/updates/list',
        'label' => '<li class="' . @$active['erp_manage/updates/list'] . '">' . 'UPDATES' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_masters/customer/list',
        'label' => '<li class="' . @$active['erp_masters/customer/list'] . '">' . 'CUSTOMER' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_masters/vendor/list',
        'label' => '<li class="' . @$active['erp_masters/vendor/list'] . '">' . 'VENDOR' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'erp_masters/item/list',
        'label' => '<li class="' . @$active['erp_masters/item/list'] . '">' . 'ITEMS' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'admin/users/index',
        'label' => '<li class="' . @$active['admin/users/index'] . '">' . 'USERS' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'acl/config/setmodules',
        'label' => '<li class="' . @$active['acl/config/setmodules'] . '">' . 'PERMISSIONS' . '</li>'
    ));
    $menuHtml .= x(array(
        'link' => 'admin/users/changepwd',
        'ref' => array(
            'ref' => $encUserId
        ),
        'label' => '<li class="' . @$active['admin/users/changepwd'] . '">' . 'CHANGE PASSWORD' . '</li>',
        array(
            "param" => 'class="facebox"'
        )
    ));
    $menuHtml .= x(array(
        'link' => 'default/default/dashboard',
        'label' => '<li class="' . @$active['default/default/about'] . '">' . 'ABOUT' . '</li>'
    ));
    $menuHtml .= '</ul>
			</div>';

    echo $menuHtml;
}
mainMenu($encUserId);
echo ('<div id="page-content-wrapper" style="padding-top: 2px;">
	<a id="menu-toggle" class="" href="#menu-toggle"><i
		class="glyphicon glyphicon-resize-horizontal"></i></a>
	<div class="container-fluid">
		<div class="row" style="margin-left: -4%; margin-right: -4%;">');
/*
 * if(@$_SESSION['feedback']):?>
 * <?php echo('
 * <div class="card-block" id="feedback_container">
 * <div role="alert" class="alert alert-success">
 * <strong>Success! </strong>'
 * .$_SESSION['feedback'].'</div>
 * </div>
 * ');
 * $_SESSION['feedback'] = '';
 * endif;
 */
/*
 * if(@$_SESSION['error']):?>
 * <?php echo('
 * <div class="card-block" id="feedback_container">
 * <div role="alert" class="alert alert-danger">
 * <strong>Error! </strong>'
 * .$_SESSION['error'].'</div>
 * </div>
 * ');
 * unset($_SESSION['error']);
 * endif;
 */
?>