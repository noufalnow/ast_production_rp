  <?php echo ('</div>

  <!-- BACK-TO-TOP -->
  <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

<!-- JQUERY JS -->
  <script src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/js/jquery.min.js"></script>

  <!-- BOOTSTRAP JS -->
  <script src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
  <script src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/plugins/bootstrap/js/popper.min.js"></script>
  <script src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

  <!-- STICKY JS -->
  <script src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/js/sticky.js"></script>

  <!-- SIDEBAR JS -->
  <script src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/plugins/sidebar/sidebar.js"></script>

  

  <!-- SPARKLINE JS-->
  <script src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/js/jquery.sparkline.min.js"></script>

  <script src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/js/circle-progress.min.js"></script>
  <script src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/plugins/chart/utils.js"></script>
  <!-- PIETY CHART JS-->
  <script src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/plugins/peitychart/jquery.peity.min.js"></script>
  <!-- APEXCHART JS -->

  <!-- COLOR THEME JS -->
  <script src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/js/themeColors.js"></script>

  <!-- SWITCHER STYLES JS -->
  <script src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/js/swither-styles.js"></script>

  <!-- CUSTOM JS -->
  <script src="http://' . $_SERVER['HTTP_HOST'] . '/2024/assets/js/custom.js"></script>
  
 
		<script src="https://code.jquery.com/ui/1.12.0-rc.2/jquery-ui.js" integrity="sha256-6HSLgn6Ao3PKc5ci8rwZfb//5QUu3ge2/Sw9KfLuvr8=" crossorigin="anonymous"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/lightbox.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/chosen.jquery.min.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/jquery.numeric.min.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/MonthPicker.js"></script>

		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/sweetalert.js"></script>
        <script src="http://' . $_SERVER['HTTP_HOST'] . '/js/ui.multiselect.js"></script>
		<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/js.printElement.min.js"></script>'); ?>

  
  
<?php echo ('<script src="http://' . $_SERVER['HTTP_HOST'] . '/js/application.js"></script>'); ?>
  
<?php echo("						
		 	<div style='width: 100%;margin-top: 16px;padding-top: 10px;display: flex;margin-left: 25%;'><p  style='
    padding: 10px 0px;
    text-align: center;
    color: #999; padding-top: 0px;'>Copyright: &#169; CreativeSol  Management Information System,All rights reserverd </p> </div>
		</div>
	</div>
	<!-- Menu Toggle Script -->
	<script>
    $('#menu-toggle').click(function(e) {
        e.preventDefault();
        $('#wrapper').toggleClass('toggled');
    });
    </script>
    <script>
	/*$('.fancyselect').selectpicker({
	  });*/

$(function() {
	$('a.lightbox').each(function () { $(this).lightBox(); });
});


var winobjM;
function getOpener(option,link)
{
	sw = (screen.width*80)/100;
	sh = (screen.height*75)/100;
	if (typeof(winobjM) !== 'undefined')
		winobjM.close();
	if(option=='report')
		winobjM=window.open(link,'childwindow1','width=600,height=660,top=35,left=200,resizable=yes,toolbar=no,menubar=no,resizable=yes,location=no,directories=no,status=no');
	if(option=='wide')
		winobjM=window.open(link,'childwindow2','width='+sw+',height='+sh+',top=100,left=200,resizable=yes,toolbar=no,menubar=no,resizable=yes,location=no,directories=no,status=no');
	
}

$(window).keyup(function(event) {
	  if(event.ctrlKey && event.keyCode == 96 || event.ctrlKey && event.keyCode == 48) { 
		  event.stopImmediatePropagation();
		addRow('multi_table');
	    event.preventDefault(); 
	  }
	});

</script>

<div class='modal fade' id='myModal' role='dialog' data-bs-backdrop='static' tabindex='-1' data-bs-keyboard='false'>
	<div class='modal-dialog modal-md'>
		<div class='modal-content' id='modal-target'>
		</div>
	</div>
</div>
</body>
</html>");






