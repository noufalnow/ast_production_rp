<?php 
$viewbase = new viewbase;
$encUserId = $viewbase->encode($_SESSION['user_id']);


?>
<?php echo('
<!doctype html><html itemscope="" itemtype="http://schema.org/SearchResultsPage" lang="en">
<head>
<title>Document Management System</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="width=device-width, initial-scale=1" name="viewport">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/jquery-ui.min.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/bootstrap.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/chosen.min.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/MonthPicker.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/general.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/simple-sidebar.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/lightbox.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/sweetalert.css" rel="stylesheet">		
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/hover.css" rel="stylesheet">	
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/jquery-1.12.3.min.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/jquery-ui.min.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/lightbox.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/bootstrap.min.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/bootstrap-select.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/chosen.jquery.min.js"></script>	
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/jquery.numeric.min.js"></script>			
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/MonthPicker.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/highcharts.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/sweetalert.js"></script>		
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/js.printElement.min.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/application.js"></script>
<script type="text/javascript" language="javascript">
var baseurl = "'.APPURL.'";
</script>

</head>
<body>
');
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