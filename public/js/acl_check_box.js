$('#selectAll.create').click(function (e) {
    $(this).closest('table').find('td input:checkbox.create').prop('checked', this.checked);
});
$('#selectAll.update').click(function (e) {
    $(this).closest('table').find('td input:checkbox.update').prop('checked', this.checked);
});
$('#selectAll.view').click(function (e) {
    $(this).closest('table').find('td input:checkbox.view').prop('checked', this.checked);
});
$('#selectAll.delete').click(function (e) {
    $(this).closest('table').find('td input:checkbox.delete').prop('checked', this.checked);
});

$('input:checkbox.create').click(function (e) {
	var status = true;
    $("input:checkbox.create").each(function() {
        if(this.id !='selectAll'){
        	if(this.checked){}
        	else{
        		$('#selectAll.create').prop('checked', false);
        		 status = false;
        	}
        }
   });    
    if(status==true)
    	$('#selectAll.create').prop('checked', true);
});

$('input:checkbox.update').click(function (e) {
	var status = true;
    $("input:checkbox.update").each(function() {
        if(this.id !='selectAll'){
        	if(this.checked){}
        	else{
        		$('#selectAll.update').prop('checked', false);
        		 status = false;
        	}
        }
   });    
    if(status==true)
    	$('#selectAll.update').prop('checked', true);
});


$('input:checkbox.view').click(function (e) {
	var status = true;
    $("input:checkbox.view").each(function() {
        if(this.id !='selectAll'){
        	if(this.checked){}
        	else{
        		$('#selectAll.view').prop('checked', false);
        		 status = false;
        	}
        }
   });    
    if(status==true)
    	$('#selectAll.view').prop('checked', true);
});

$('input:checkbox.delete').click(function (e) {
	var status = true;
    $("input:checkbox.delete").each(function() {
        if(this.id !='selectAll'){
        	if(this.checked){}
        	else{
        		$('#selectAll.delete').prop('checked', false);
        		 status = false;
        	}
        }
   });    
    if(status==true)
    	$('#selectAll.delete').prop('checked', true);
});

$('#selectAll.All').click(function (e) {
    $(this).closest('table').find('td input:checkbox.All').prop('checked', this.checked);
});

$('input:checkbox.All').click(function (e) {
	var status = true;
    $("input:checkbox.All").each(function() {
        if(this.id !='selectAll'){
        	if(this.checked){}
        	else{
        		$('#selectAll.All').prop('checked', false);
        		 status = false;
        	}
        }
   });    
    if(status==true)
    	$('#selectAll.All').prop('checked', true);
});
