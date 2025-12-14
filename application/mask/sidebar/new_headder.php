<?php
$viewbase = new viewbase();
$encUserId = $viewbase->encode($_SESSION['user_id']);


require_once __DIR__ . '/../../admin/!model/documents.php';
$docs = new documets();
$empImage = $docs->getTopDocumentsByRef(array(
    'doc_ref_type' => DOC_IMG_EMP,
    'doc_ref_id' => $_SESSION['user_emp_id']
));
$empProfileImage= $empImage['0'];


function mainMenu($encUserId)
{
    $toggleText = '';

    if (@$_COOKIE['menu'] == 'show')
        $toggleText = '';
    elseif (@$_COOKIE['menu'] == 'hide')
        $toggleText = 'class="toggled"';

    $active[_REQUEST] = 'active';
    if (_REQUEST == 'erp_employee/master/view' ||
        _REQUEST == 'erp_employee/salary/list')    
        $active['erp_employee/master/list'] = 'active';

    if (_REQUEST == 'erp_property/master/view' || 
        _REQUEST == 'erp_property/tenants/list' || 
        _REQUEST == 'erp_masters/building/list' ||
        _REQUEST == 'erp_property/pservice/list' ||
        _REQUEST == 'erp_masters/building/dash')
        $active['erp_property/master/list'] = 'active';

    if (_REQUEST == 'erp_vehicle/master/view')
        $active['erp_vehicle/master/list'] = 'active';

    if (_REQUEST == 'erp_masters/vendor/view' ||
        _REQUEST == 'erp_masters/vendor/list' ||
        _REQUEST == 'erp_expense/payments/list')
        $active['erp_expense/expense/list'] = 'active';
        

    if (_REQUEST == 'erp_masters/customer/view' ||
        _REQUEST == 'erp_masters/customer/list' ||
        _REQUEST == 'erp_invoice/collection/list')
        $active['erp_invoice/bill/list'] = 'active';
        

    if (_REQUEST == 'erp_vehicle/master/list' ||
        _REQUEST == 'erp_masters/item/list')
        $active['erp_vehicle/master/list'] = 'active';
        

        if (_REQUEST == 'admin/users/index' ||
            _REQUEST == 'acl/config/setactions' ||
            _REQUEST == 'acl/config/setmodules')
            $active['erp_manage/updates/list'] = 'active';
        
    $menuHtml = '';
    
    
    $menuHtml .= '<li class="slide">
    	<a class="side-menu__item ' . @$active['default/default/dashboard'] . ' is-expanded" data-bs-toggle="slide" href="javascript:void(0);">
                  <i class="side-menu__icon fa-solid fa-house"></i><span class="side-menu__label">Dashboard</span></a>
    	<ul class="slide-menu open">';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'default/default/dashboard',
        'label' => 'Dashboard'
    )) . '</li>';
     
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'default/default/dashboardgraph',
        'label' => 'Dashboard - Graph',
        array("param" => 'wide_opener')
    )) . '</li>';
    
    $menuHtml .= '</ul>
      </li>';
    
    
    
    $menuHtml .= '<li class="slide">
    	<a class="side-menu__item ' . @$active['erp_employee/master/list'] . ' is-expanded" data-bs-toggle="slide" href="javascript:void(0);">
                  <i class="side-menu__icon fa-solid fa-users"></i><span class="side-menu__label">Employees</span></a>
    	<ul class="slide-menu open">';

    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_employee/master/list',
        'label' => 'Employees'
    )) . '</li>';

    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_employee/salary/list',
        'label' => 'Salary'
    )) . '</li>

            <li class="sub-slide">
            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Reports</span><i class="sub-angle fa fa-angle-right"></i></a>
            <ul class="sub-slide-menu">
              <li class="sub-slide-item">'.                          
              x(array('link' => 'erp_report/employee/employee','label' => 'Employees',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/employee/empdocument','label' => 'Documents',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/employee/empdocument','label' => 'Document Expiry',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/employee/empcontract','label' => 'Operator Status',array("param" => 'wide_opener'))).'
              </li>
            </ul>
            </li>
            ';
    $menuHtml .= '</ul>
      </li>';

    $menuHtml .= '<li class="slide">
    	<a class="side-menu__item ' . @$active['erp_property/master/list'] . ' is-expanded" data-bs-toggle="slide" href="javascript:void(0);">
                  <i class="side-menu__icon fa-solid fa-building"></i><span class="side-menu__label">Properties</span></a>
    	<ul class="slide-menu open">';
    
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_masters/building/dash',
        'label' => 'Building Status Board'
    )) . '</li>';

    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_property/master/list',
        'label' => 'Property'
    )) . '</li>';

    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_property/tenants/list',
        'label' => 'Tenants'
    )) . '</li>';
    


    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_masters/building/list',
        'label' => 'Buildings'
    )) . '</li>';
    

    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_property/rent/propertypay',
        'label' => 'Rent Schedule')). '</li>';
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_property/pservice/list',
        'label' => 'Maintanance'
    )) . '</li>';
    
    $menuHtml .= '
            <li class="sub-slide">
            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Reports</span><i class="sub-angle fa fa-angle-right"></i></a>
            <ul class="sub-slide-menu">
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/property/property','label' => 'Properties',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/property/propdocument','label' => 'Property Document',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/property/propdocument','ref' => array('ref' => 'exp'),'label' => 'Document Expiry',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/property/propvacant','label' => 'Property Status',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/property/propertymeter','label' => 'Property Meter',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/property/tenantagreements','label' => 'Tenant Agreements',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/property/pservicerpt','label' => 'Maintanance Report',array("param" => 'wide_opener'))).'
              </li>
            </ul>
            </li>
            ';
    
    $menuHtml .= '</ul>
      </li>';
    
    $menuHtml .= '<li class="slide">
    	<a class="side-menu__item ' . @$active['erp_vehicle/master/list'] . ' is-expanded" data-bs-toggle="slide" href="javascript:void(0);">
                  <i class="side-menu__icon fa-solid fa-truck-monster"></i><span class="side-menu__label">Vehicles</span></a>
    	<ul class="slide-menu open">';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_vehicle/master/list',
        'label' => 'Vehicles'
    )) . '</li>';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_masters/item/list',
        'label' => 'Items'
    )) . '</li>';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_masters/vhtype/list',
        'label' => 'Vehicle Type'
    )) . '</li>';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_masters/vman/list',
        'label' => 'Vehicle Manufacturer'
    )) . '</li>';
    
    
    $menuHtml .= '
            <li class="sub-slide">
            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Reports</span><i class="sub-angle fa fa-angle-right"></i></a>
            <ul class="sub-slide-menu">
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/vehicle/vehicle','label' => 'Vehicles',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/vehicle/vhldocument','label' => 'Vehicle Document',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/vehicle/vhldocument','ref' => array('ref' => 'exp'),'label' => 'Vehicle Expiry',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/vehicle/vhlexpense','label' => 'Vehicle Expense',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/vehicle/commveh','label' => 'Commercial',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/vehicle/vehiclecontract','label' => 'Vehicle Contract',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/vehicle/vehicleservice','label' => 'Vehicle Service',array("param" => 'wide_opener'))).'
              </li>
            </ul>
            </li>
            ';
    
    
    $menuHtml .= '</ul>
      </li>';
    
    

    $menuHtml .= '<li class="slide">
    	<a class="side-menu__item ' . @$active['erp_expense/expense/list'] . ' is-expanded" data-bs-toggle="slide" href="javascript:void(0);">
                  <i class="side-menu__icon fa-solid fa-money-bill-1"></i><span class="side-menu__label">Expenses</span></a>
    	<ul class="slide-menu open">';

    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_expense/expense/list',
        'label' => 'Expenses'
    )) . '</li>';

    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_masters/vendor/list',
        'label' => 'Vendor'
    )) . '</li>';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_expense/payments/list',
        'label' => 'Payments'
    )) . '</li>';
    
    
    $menuHtml .= '
            <li class="sub-slide">
            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Reports</span><i class="sub-angle fa fa-angle-right"></i></a>
            <ul class="sub-slide-menu">
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/expense/expense','label' => 'Expense',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/expense/expensevat','label' => 'Expense VAT',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/expense/expvensummary','label' => 'Vender Summary',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/expense/expensecategorywise','label' => 'Expense Category wise',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/expense/payments','ref' => array('ref' => 'exp'),'label' => 'Credit Payments',array("param" => 'wide_opener'))).'
              </li>
            </ul>
            </li>
            ';
    

    $menuHtml .= '</ul>
      </li>';
    
    
    $menuHtml .= '<li class="slide">
    	<a class="side-menu__item ' . @$active['erp_invoice/bill/list'] . ' is-expanded" data-bs-toggle="slide" href="javascript:void(0);">
                  <i class="side-menu__icon fa-solid fa-file-text"></i><span class="side-menu__label">Invoices</span></a>
    	<ul class="slide-menu open">';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_invoice/bill/list',
        'label' => 'Invoice'
    )) . '</li>';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_masters/customer/list',
        'label' => 'Customer'
    )) . '</li>';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_invoice/collection/list',
        'label' => 'Collections'
    )) . '</li>';
    
    
    $menuHtml .= '
            <li class="sub-slide">
            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span class="sub-side-menu__label">Reports</span><i class="sub-angle fa fa-angle-right"></i></a>
            <ul class="sub-slide-menu">
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/invoice/billbycustomer','label' => 'Outstanding Summary',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/invoice/billoutstanding','label' => 'Outstanding In Detail',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/invoice/billlisting','label' => 'Bill Listing',array("param" => 'wide_opener'))).'
              </li>
              <li class="sub-slide-item">'.
              x(array('link' => 'erp_report/property/paymentcollection','ref' => array('ref' => 'exp'),'label' => 'Payment Collection',array("param" => 'wide_opener'))).'
              </li>
            </ul>
            </li>
            ';
    
    
    $menuHtml .= '</ul>
      </li>';
    
    
    $menuHtml .= x(array(
        'link' => 'erp_fund/cashflow/list',
        'label' => '<div class="side-menu__item ' . @$active['erp_fund/cashflow/list'] . '">
                  <i class="side-menu__icon fa-solid fa-house"></i><span class="side-menu__label">Cash Flow</span></div>'
    ));

    
    $menuHtml .= '<li class="slide">
    	<a class="side-menu__item ' . @$active['erp_manage/company/list'] . ' is-expanded" data-bs-toggle="slide" href="javascript:void(0);">
                  <i class="side-menu__icon fa-solid fa-house"></i><span class="side-menu__label">Company</span></a>
    	<ul class="slide-menu open">';
    
    
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array('link' => 'erp_manage/company/list','label' => 'Company Documents',array())) . '</li>';
        
    $menuHtml .= '<li class="sub-slide-item">' . x(array('link' => 'erp_report/company/statement','label' => 'Monthly Statement',array("param" => 'wide_opener'))) . '</li>';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array('link' => 'erp_manage/calllog/list','label' => 'Business Call Log',array())) . '</li>';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array('link' => 'erp_manage/legalcase/list','label' => 'Manage Legal Cases',array())) . '</li>';
    
    
    $menuHtml .= '</ul>
      </li>';
    
    $menuHtml .= '<li class="slide">
    	<a class="side-menu__item ' . @$active['erp_manage/updates/list'] . ' is-expanded" data-bs-toggle="slide" href="javascript:void(0);">
                  <i class="side-menu__icon fa-solid fa-building"></i><span class="side-menu__label">Others</span></a>
    	<ul class="slide-menu open">';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'erp_manage/updates/list',
        'label' => 'Notifications'
    )) . '</li>';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'admin/users/index',
        'label' => 'Users'
    )) . '</li>';
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'acl/config/setmodules',
        'label' => 'Permissions'
    )) . '</li>';
    
        
    if($_SESSION['user_type']==1)
        $menuHtml .= '<li class="sub-slide-item"> <a href="javascript:void(0);" Onclick="triggerSync();"> Cloud Transfer</a> </li>';
    
    
    $menuHtml .= '<li class="sub-slide-item">' . x(array(
        'link' => 'default/default/backup',
        'ref' => array(
            'ref' => $encUserId
        ),
        'label' => 'Backup',
        array(
            "param" => 'class="facebox"'
        )
    )) . '</li>';
    
    
    $menuHtml .= '</ul>
      </li>';
  
    
    
    $menuHtml .= x(array(
        'link' => 'admin/users/changepwd',
        'ref' => array(
            'ref' => $encUserId
        ),
        'label' => '<div class="side-menu__item ' . @$active['admin/users/changepwd'] . '">
                  <i class="side-menu__icon fa-solid fa-key"></i><span class="side-menu__label">Change Password</span></div>',
        array(
            "param" => 'class="facebox"'
        )
    ));


    return $menuHtml;
}


function notify(){
    
    require_once __DIR__ . '/../../admin/!model/updates.php';
    
    $updateObj = new updates();
    $updList = $updateObj->getPendingUpdatesByUser(array(
        'upd_assign' => USER_ID
    ));
    
    //a($updList);
    
    
    
    $notif .='<div class="drop-heading border-bottom">
        <div class="d-flex">
        <h6 class="mt-1 mb-0 fs-16 fw-semibold">You have
        Notification</h6>
        <div class="ms-auto">
        <span class="badge bg-success rounded-pill">'.count($updList).'</span>
        </div>
        </div>
        </div>
    <div class="notifications-menu">';
    
    
    if(is_array($updList) && count($updList)>0)
        foreach ($updList as $row):
    
    $notif .= '

    <a class="dropdown-item d-flex" href="#">
    	<div
    		class="me-3 notifyimg bg-primary-gradient brround box-shadow-primary">
    		<i class="fe fe-message-square"></i>
    	</div>
    	<div class="mt-1 wd-80p">
    		<h5 class="notification-label mb-1">'.ucwords(strtolower($row['upd_note'])).'</h5>
    		<span class="notification-subtext">'.$row['upd_enddttime'].'</span>
    	</div>
    </a>';
    endforeach;
    
    
    $notif .='</div>';
    
    return $notif;
}

?>


<?php

echo ('
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
<!-- META DATA -->
<meta charset="UTF-8" />
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, user-scalable=0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="description" content="AST Global" />
<meta name="author" content="AST Global" />
<meta name="keywords" content="" />

<!-- FAVICON -->
<link rel="shortcut icon" type="image/x-icon" href="" />

<!-- TITLE -->
<title>AST Global Dashboard</title>

<!-- BOOTSTRAP CSS -->
<link id="style"
	href="//' . $_SERVER['HTTP_HOST'] . '/2024/assets/plugins/bootstrap/css/bootstrap.min.css"
	rel="stylesheet" />

<!-- STYLE CSS -->
<link href="//' . $_SERVER['HTTP_HOST'] . '/2024/assets/css/style.css" rel="stylesheet" />
<link href="//' . $_SERVER['HTTP_HOST'] . '/2024/assets/css/plugins.css"
	rel="stylesheet" />

<!--- FONT-ICONS CSS -->
<link href="//' . $_SERVER['HTTP_HOST'] . '/2024/assets/css/icons.css" rel="stylesheet" />
		
<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/jquery-ui.min.css" rel="stylesheet">
<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/chosen.min.css" rel="stylesheet">
<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/MonthPicker.css" rel="stylesheet">
<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/general.css" rel="stylesheet">
<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/lightbox.css" rel="stylesheet">
<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/sweetalert.css" rel="stylesheet">
<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/hover.css" rel="stylesheet">
<link href="http://' . $_SERVER['HTTP_HOST'] . '/css/ui.multiselect.css" rel="stylesheet">
<link href="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/css/custom.css" rel="stylesheet">
<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/jquery-1.12.3.min.js"></script>
<script> var baseurl = "' . APPURL . '"</script>



</head>

<body
	class="app ltr horizontal horizontal-hover light-mode sidebar-gone">
	<!--  GLOBAL-LOADER -->
	<div id="global-loader">
		<div class="dimmer active">
			<div class="spinner"></div>
		</div>
	</div>
	<!-- /GLOBAL-LOADER -->

	<!-- PAGE -->
	<div class="page" id="ipage">
		<div class="page-main">
			<!-- APP-HEADER -->
			<div class="app-header header sticky">
				<div class="container-fluid main-container">
					<div class="d-flex align-items-center">
						<a aria-label="Hide Sidebar" class="app-sidebar__toggle"
							data-bs-toggle="sidebar" href="javascript:void(0);"></a>
						<div class="responsive-logo">
							<a href="index.html" class="header-logo"> <img
								src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/images/brand/logo-3.png"
								class="mobile-logo logo-1" alt="logo" /> <img
								src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/images/brand/logo-3.png"
								class="mobile-logo dark-logo-1" alt="logo" />
							</a>
						</div>
						<!-- sidebar-toggle-->
						<a class="logo-horizontal" href="index.html"> <img
							src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/images/brand/logo-3.png"
							class="header-brand-img desktop-logo" alt="logo" /> <img
							src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/images/brand/logo-3.png"
							class="header-brand-img light-logo1" alt="logo" />
						</a>
						<!-- LOGO -->
						<div class="main-header-center ms-3 d-none d-lg-block">
							<input class="form-control" placeholder="Search for anything..."
								type="search" />
							<button class="btn">
								<i class="fa fa-search" aria-hidden="true"></i>
							</button>
						</div>
						<div class="d-flex order-lg-2 ms-auto header-right-icons">
							<!-- SEARCH -->
							<button
								class="navbar-toggler navresponsive-toggler d-lg-none ms-auto"
								type="button" data-bs-toggle="collapse"
								data-bs-target="#navbarSupportedContent-4"
								aria-controls="navbarSupportedContent-4" aria-expanded="false"
								aria-label="Toggle navigation">
								<span class="navbar-toggler-icon fe fe-more-vertical text-dark"></span>
							</button>
							<div class="navbar navbar-collapse responsive-navbar p-0">
								<div class="collapse navbar-collapse"
									id="navbarSupportedContent-4">
									<div class="d-flex order-lg-2">
										<div class="dropdown d-block d-lg-none">
											<a href="javascript:void(0);" class="nav-link icon"
												data-bs-toggle="dropdown"> <i class="fe fe-search"></i>
											</a>
											<div class="dropdown-menu header-search dropdown-menu-start">
												<div class="input-group w-100 p-2">
													<input type="text" class="form-control"
														placeholder="Search...." />
													<div class="input-group-text btn btn-primary">
														<i class="fa fa-search" aria-hidden="true"></i>
													</div>
												</div>
											</div>
										</div>
										<div class="dropdown d-md-flex">
											<a
												class="nav-link icon theme-layout nav-link-bg layout-setting">
												<span class="dark-layout"><i class="fe fe-moon"></i></span>
												<span class="light-layout"><i class="fe fe-sun"></i></span>
											</a>
										</div>
										<!-- Theme-Layout -->
										<div class="dropdown d-md-flex">
											<a class="nav-link icon full-screen-link nav-link-bg"> <i
												class="fe fe-minimize fullscreen-button"></i>
											</a>
										</div>
										<!-- FULL-SCREEN -->
										<div class="dropdown d-md-flex notifications">
											<a class="nav-link icon" data-bs-toggle="dropdown"><i
												class="fe fe-bell"></i><span class="pulse"></span> </a>
											<div
												class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
													'.
                                                        notify()
													.'
												<div class="dropdown-divider m-0"></div>
                                                '.
                                                lx(array('link'=>'erp_manage/updates/list','ref'=>'','label'=>'View all Notifications',array("param"=>'class="dropdown-item text-center p-3 text-muted"' ))).
                                                '</div>
										</div>
										<!-- NOTIFICATIONS -->
										<div class="dropdown d-md-flex message">
											<a class="nav-link icon text-center"
												data-bs-toggle="dropdown"> <i class="fe fe-message-square"></i><span
												class="pulse-danger"></span>
											</a>
											<div
												class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
												<div class="drop-heading border-bottom">
													<div class="d-flex">
														<h6 class="mt-1 mb-0 fs-16 fw-semibold">You have Messages
														</h6>
														<div class="ms-auto">
															<span class="badge bg-danger rounded-pill">4</span>
														</div>
													</div>
												</div>
												<div class="message-menu">

													<a class="dropdown-item d-flex" href="#"> <span
														class="avatar avatar-md brround me-3 align-self-center cover-image"data-bs-image-http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/images/users/12.jpg"></span>
														<div class="wd-90p">
															<div class="d-flex">
																<h5 class="mb-1">Antony</h5>
																<small class="text-muted ms-auto text-end"> 5 hour ago </small>
															</div>
															<span>New Machine Imported...</span>
														</div>
													</a> <a class="dropdown-item d-flex" href="#"> <span
														class="avatar avatar-md brround me-3 align-self-center cover-image"data-bs-image-http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/images/users/12.jpg"></span>
														<div class="wd-90p">
															<div class="d-flex">
																<h5 class="mb-1">Antony</h5>
																<small class="text-muted ms-auto text-end"> 5 hour ago </small>
															</div>
															<span>New Machine Imported...</span>
														</div>
													</a> <a class="dropdown-item d-flex" href="#"> <span
														class="avatar avatar-md brround me-3 align-self-center cover-image"data-bs-image-http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/images/users/12.jpg"></span>
														<div class="wd-90p">
															<div class="d-flex">
																<h5 class="mb-1">Antony</h5>
																<small class="text-muted ms-auto text-end"> 5 hour ago </small>
															</div>
															<span>New Machine Imported...</span>
														</div>
													</a> <a class="dropdown-item d-flex" href="#"> <span
														class="avatar avatar-md brround me-3 align-self-center cover-image"data-bs-image-http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/images/users/12.jpg"></span>
														<div class="wd-90p">
															<div class="d-flex">
																<h5 class="mb-1">Antony</h5>
																<small class="text-muted ms-auto text-end"> 5 hour ago </small>
															</div>
															<span>New Machine Imported...</span>
														</div>
													</a>
												</div>
												<div class="dropdown-divider m-0"></div>
												<a href="#" class="dropdown-item text-center p-3 text-muted">See
													all Messages</a>
											</div>
										</div>
										<!-- MESSAGE-BOX -->
										<div class="dropdown d-md-flex profile-1">
											<a href="javascript:void(0);" data-bs-toggle="dropdown"
												class="nav-link leading-none d-flex px-1"> <span> <img
													src="'.IMAGEURL.$this->encode($empProfileImage['file_id']).'"
													alt="profile-user"
													class="avatar profile-user brround cover-image" />
											</span>
											</a>
											<div
												class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
												<div class="drop-heading">
													<div class="text-center">
														<h5 class="text-dark mb-0">'.ucwords(strtolower($_SESSION['user_dip_name'])).'</h5>
														<small class="text-muted">'.$_SESSION['user_role'].'</small>
													</div>
												</div>
												<div class="dropdown-divider m-0"></div>
												<a class="dropdown-item" href=""> <i
													class="dropdown-icon fe fe-user"></i> Profile
												</a> <a class="dropdown-item" href="'.APPURL.'default/default/logout'.'"> <i
													class="dropdown-icon fe fe-alert-circle"></i> Sign out
												</a>
											</div>
										</div>
										<!-- SIDE-MENU -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /APP-HEADER -->

			<!--APP-SIDEBAR-->
			<div class="sticky">
				<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
				<aside class="app-sidebar">
					<div class="side-header">
						<a class="header-brand1" href="index.html"> <img
							src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/images/brand/logo-3.png"
							class="header-brand-img desktop-logo" alt="logo" /> <img
							src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/images/brand/logo-1.png"
							class="header-brand-img toggle-logo" alt="logo" /> <img
							src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/images/brand/logo-2.png"
							class="header-brand-img light-logo" alt="logo" /> <img
							src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/images/brand/logo-3.png"
							class="header-brand-img light-logo1" alt="logo" />
						</a>
						<!-- LOGO -->
					</div>
					<div class="main-sidemenu" style="max-width: 85% !important;">
						<div class="slide-left disabled" id="slide-left">
							<svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
								height="24" viewBox="0 0 24 24">
                <path
									d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
              </svg>
						</div>
						<ul class="side-menu">
							<li class="sub-category">
								<h3>Main</h3>
							</li>
              				'.mainMenu($encUserId).'
						</ul>
						<div class="slide-right" id="slide-right">
							<svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24"
								height="24" viewBox="0 0 24 24">
                <path
									d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
              </svg>
						</div>
					</div>
				</aside>
			</div>
			<!--/APP-SIDEBAR-->


		</div>');?>
		
		

		