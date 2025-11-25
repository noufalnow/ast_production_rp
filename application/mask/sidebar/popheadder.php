<?php 
$viewbase = new viewbase;
$encUserId = $viewbase->encode($_SESSION['user_id']);


?>

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

<?php echo ('

<!-- BOOTSTRAP CSS -->
<link id="style"
	href="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/plugins/bootstrap/css/bootstrap.min.css"
	rel="stylesheet" />

<!-- STYLE CSS -->
<link href="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/css/style.css" rel="stylesheet" />
<link href="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/css/plugins.css"
	rel="stylesheet" />

<!--- FONT-ICONS CSS -->
<link href="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/css/icons.css" rel="stylesheet" />



		
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
<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/tableToExcel.js"></script>
<script> var baseurl = "' . APPURL . '"</script>'); ?>
</head>
<body>
<?php 
/*if(@$_SESSION['feedback']):?>
		<?php echo('
			<div class="card-block" id="feedback_container">
				<div role="alert" class="alert alert-success">
					<strong>Success! </strong>'
		.$_SESSION['feedback'].'</div>
			</div>
		');
$_SESSION['feedback'] = '';		
endif;*/
?>