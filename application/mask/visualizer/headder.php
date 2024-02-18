<?php 
$viewbase = new viewbase;
$encUserId = $viewbase->encode($_SESSION['user_id']);


?>
<?php echo('
<!doctype html><html itemscope="" itemtype="http://schema.org/SearchResultsPage" lang="en">
<head>
<title>Document Management System</title>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1" name="viewport">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/jquery-ui.min.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/bootstrap.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/chosen.min.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/MonthPicker.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/simple-sidebar.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/lightbox.css" rel="stylesheet">	
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/ui.multiselect.css" rel="stylesheet">	
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/general.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/Treant.css" rel="stylesheet">
<link href="http://'.$_SERVER ['HTTP_HOST'].'/css/hover.css" rel="stylesheet">	
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/jquery-1.12.3.min.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/jquery-ui.min.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/lightbox.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/bootstrap.min.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/bootstrap-select.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/chosen.jquery.min.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/ui.multiselect.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/tree1.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/trean.js"></script>
<script src="http://'.$_SERVER ['HTTP_HOST'].'/js/application.js"></script>
<script type="text/javascript" language="javascript">
var appurl = "'.APPURL.'";
</script>

</head>
<body>
');
?>
<?php 
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
?>