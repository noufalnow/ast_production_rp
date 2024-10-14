
$(document).ready(function($) {
	$('#feedback_container').slideUp(1500);
	
    $("#menu-toggle").click(function(e) {
    	toggleMenu();
    });
    
    $(".ip_err:first").focus();
    initDatePicker();
    

	
	$('body').on('keyup', 'input.numonly', function(e) {
		$(this).numeric();
		if (!this.value.match(/^([0-9]){1,12}$/))
			$(this).val('');
	});
	
	$('body').on('keyup', 'input.floatonly', function(e) {
		 $(this).numeric();
	});
	

	
	
	$('input:text.numberonly, input:text.numorfloatonly').attr('maxlength',12);
})

$(document).ready(function($) {

	$('body').on('click', '.facebox', function(e) {
		e.preventDefault();
		getContent($(this).attr('href'));
	});

	$(document).on('click', '.livepost', function() {
		/*$('#myModal').modal('hide');*/

  		displayOverlay('Loading...');

		var ptarget = $(this).attr('ptarget');
		if(ptarget!='')
			$('#page_'+ptarget).val($(this).val());
		
		if($(this).hasClass("no-confirm"))
			livePost(this,ptarget);
		else {
		Swal.fire({
			  title: 'Do you want to save the changes?',
			  showDenyButton: false,
			  showCancelButton: true,
			  confirmButtonText: 'Save',
			  denyButtonText: `Don't save`,
			  icon: 'info',
			}).then((result) => {
			  /* Read more about isConfirmed, isDenied below */
			  if (result.isConfirmed) 
		    	  livePost(this,ptarget);
			  else
				  removeOverlay();  	 	 	
			})
		}
		
	});

	$('#tel').keyup(function(e) {
		if (/\D/g.test(this.value)) {
			// Filter non-digits from input value.
			this.value = this.value.replace(/\D/g, '');
		}
	});
	$("#myModal").on("hide.bs.modal", function() {
		$('#modal-target').html('');
		//$("#myModal").removeData('bs.modal')
	});
	
	/*$('.fancyselect').selectpicker({
	  });*/
	
	$(".chosen-select").chosen({no_results_text: "Oops, nothing found!",
	    search_contains: true,
	    enable_split_word_search: true	
	});
	
	/*$('#myModal').on('show.bs.modal', function () {
	    $.fn.modal.Constructor.prototype.enforceFocus = function () { };	
	});*/
})

function getContent(postlink,refId,target) {
	
	
	
	$.ajax({
		type : 'GET',
		url : postlink,
		data: {
			"ref": refId,
		},
		success : function(response) {
		var data = $.parseJSON(response);
		
		
		if(target!= null ) {
			$('#'+target).html(data.view);
			
		}
		else if(data.view==null) {
			
			location.reload();
		}
		else {
			
			
			$('#modal-target').html(data.view);
			$('#myModal').modal('show');
			/*$('.fancyselect').selectpicker({
			  });*/
		}
		$(".chosen-select").chosen({no_results_text: "Oops, nothing found!",
		    search_contains: true,
		    enable_split_word_search: true
		});
		initDatePicker();
		},
		error : function(request, error) {
		},
	//dataType: 'json'
	});
}

function formSubmit(source, target) {
	var form = $(source).parents('form');
	var fileCount = 0;
	if ($("input[name='my_files']").length > 0) {
		var dataArray = new FormData(form[0]);
		dataArray.append("my_files", $('input[name="my_files"]')[0].files[0]);
		fileCount = 1;
	}
	else if ($("input[name='my_files2']").length > 0) {
		var dataArray = new FormData(form[0]);
		dataArray.append("my_files2", $('input[name="my_files2"]')[0].files[0]);
		fileCount = 1;
	} 
	else if($("input[name='photo']").length > 0) {
		var dataArray = new FormData(form[0]);
		dataArray.append("photo", $('input[name="photo"]')[0].files[0]);
		fileCount = 1;
	}
	else {
		dataArray = [];
		dataArray = $(form).serializeArray();
	}
	var act_url = baseurl + $(form).attr('action');
	$(form).attr('action', '##');
	//displayOverlay('Loading...');
	$.ajax({
		type: "POST",
		cache: false,
		async: true,
		processData: (fileCount == 0) ? true : false,
		contentType: (fileCount == 0) ? 'application/x-www-form-urlencoded; charset=UTF-8' : false,
		url: act_url,
		data: dataArray,
		//beforeSend: function () { displayOverlay('Loading...'); },
		success: function(response) {
			removeOverlay();
			
			var data = $.parseJSON(response);

			if (data.status == 11) { 
				
				if (data.feedback!=null) 
					Swal.fire('Saved!', data.feedback, 'success');

				if(data.target==null)
				{
					location.reload();
					return;
				}
				$('#myModal').modal('hide');
				$("#modal-target").html('')
				//expecting reaload
				getContent(data.url,data.ref,data.target);
			}
			else if (target != null) {
				$("#"+target).html(data.view)
				$(".chosen-select").chosen({no_results_text: "Oops, nothing found!",
				    search_contains: true,
				    enable_split_word_search: true});
				initDatePicker();
			}
			else if (data.target != null) {
				
				if (data.feedback!=null) { 	
				Swal.fire({
				       title: "Saved", 
				       text: data.feedback,
				       type: "success",
				       icon: 'success',
				}).then((result) => {
					  /* Read more about isConfirmed, isDenied below */
					  if (result.isConfirmed) 
							$("#"+data.target).html(data.view);
				});
				}else {
					$("#"+data.target).html(data.view);
				}
				

				$(".chosen-select").chosen({no_results_text: "Oops, nothing found!",
				    search_contains: true,
				    enable_split_word_search: true});
				initDatePicker();
			}
			else if (data.view!=null) {
				$("#modal-target").html(data.view)
				$('#myModal').modal('show');
				$(".chosen-select").chosen({no_results_text: "Oops, nothing found!",
				    search_contains: true,
				    enable_split_word_search: true});
				initDatePicker();
			}
			else {
				$("#modal-target").html('')
				$('#myModal').modal('hide');


				if (data.feedback!=null) 	
				Swal.fire({
				       title: "Saved", 
				       text: data.feedback,
				       type: "success",
				       icon: 'success',
				}).then((result) => {
					  /* Read more about isConfirmed, isDenied below */
					  if (result.isConfirmed) 
						  location.reload();
				});

				
				
				//location.reload();
				
				//show_widget(data.target,'',data.refId);
			}
			
		}
	});
}


function livePost(element,ptarget) {
	targetform = $(element).closest('form');
	
	formSubmit(element, ptarget);
	return;
	
	action = targetform.attr("action");
	dataArray = targetform.serializeArray();
	var postlink = action;
	$.ajax({
		type : 'POST',
		url : postlink,
		data : dataArray,
		success : function(response) {
			var parsed;
			try {
				parsed = $.parseJSON(response);
				
				callback = parsed.callback;
				if (callback) {
					addFieldId(parsed);
				}
				
				if(parsed.mtarget) // after open popup 
				{
					getOpener ('wide',parsed.mtarget);
					location.reload();					
				}
				
				if(parsed.norefresh==true)
					$('#myModal').modal('hide');
				else 
					location.reload();
				
			} catch (e) {
				
				//console.log(ptarget);
				if(ptarget=='' || ptarget==undefined)
					$('#dynamic-popup').replaceWith(response);
				else
					$('#'+ptarget).replaceWith(response);
					
			}
			initDatePicker();
			$(".chosen-select").chosen({no_results_text: "Oops, nothing found!",
			    search_contains: true,
			    enable_split_word_search: true});
		},
		error : function(request, error) {
			//$('#content_target').html(request+error);
		},
	//dataType: 'json'
	});

}
function customPost(action,dataArray) {
	var postlink = action;
	$.ajax({
		type : 'POST',
		url : postlink,
		data    : { result :$.parseJSON(dataArray)},
		
		success : function(response) {
			
			addFieldId(response);
			//return(response.feedback);
			

		},
		error : function(request, error) {
			//$('#content_target').html(request+error);
		},
	dataType: 'json'
	});

}

function toggleMenu(){
	var menu = getCookie('menu');
	if(menu==''){
		setCookie('menu','show', 1);
	}
	if(menu=='show'){
		setCookie('menu','hide', 1);
	}
	else if(menu=='hide'){	
		setCookie('menu','show', 1);
	}	
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
} 
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires + ";path=/";
    //document.cookie = name+"="+value+expires+"; path=/"; 
    //$.cookie(cname, cvalue,expires,'/');
}

function initDatePicker()
{
	element = $( ".date_picker" );
	element.each(
			 		function(index)
			 		{
			 			setDatePicker($(this));
			 		}
			 	);
}
function setDatePicker(dateElement){
	dateElement.datepicker({ dateFormat: 'dd/mm/yy',changeMonth: true,changeYear: true,yearRange: '-70:+30',
		
	    beforeShow: function () {                
	        setTimeout(function () {
	        	if($('#myModal').hasClass('show'))
	       		 	$('#ui-datepicker-div').appendTo($('#myModal'));
	        	else
	        		$('#ui-datepicker-div').appendTo($('#ipage'));
	       	
        }, 0);
    }
		
	});
	if(dateElement.attr('maxdate')!= undefined && dateElement.attr('maxdate')!= null)
	{
		 var maxDate = dateElement.attr('maxdate');
		 dateElement.datepicker( "option", "maxDate", maxDate );
	}
	if(dateElement.attr('mindate')!= undefined && dateElement.attr('mindate')!= null)
	{
		 var minDate = dateElement.attr('mindate');
		 dateElement.datepicker( "option", "minDate", minDate );
	}
}

function getJaxData(refId, refElement,url,refParam,pType)
{
	if((refId!='')&&(refId!=null)&&(refId!=undefined))
	{
		$.ajax({
			  type: 'POST',
			  url: url,
			  data: {refId:refId,refParam:refParam,pType:pType },
			  beforeSend: function () { displayOverlay('Loading...'); },
			  success: function(response)
							  {
				  				
				  				fillJaxData(response, refElement);
							  },
			  dataType: 'json'
		});
	}
}
function fillJaxData(options, refElement)
{
	var refElementObj = $('#'+refElement);
	
    var selectedElementValue = refElementObj.val();
    refElementObj.empty();
    /*refElementObj.append($('<option>', { value : '' })
				    .text('--Select--'));*/
	$.each(options, function(key, value) {
		refElementObj
	          .append($('<option>', { value : value.key })
	          .text(value.value))
		});
	if(selectedElementValue != null && selectedElementValue != undefined && selectedElementValue != '')
		refElementObj.val(selectedElementValue);
	
	$('.chosen-select').chosen('destroy');  
	
	$(".chosen-select").chosen({
		no_results_text : "Oops, nothing found!",
	    search_contains: true,
	    enable_split_word_search: true
	});
	
	removeOverlay();
}


function customPost(action,dataArray) {
	var postlink = action;
	$.ajax({
		type : 'POST',
		url : postlink,
		data    : { result :$.parseJSON(dataArray)},
		success : function(response) {
			//addFieldId(response);
		},
		error : function(request, error) {
			//$('#content_target').html(request+error);
		},
	dataType: 'json'
	});

}


function displayOverlay(text) {
    $("<table id='overlay'><tbody><tr><td>" + text + "</td></tr></tbody></table>").css({
        "position": "fixed",
        "top": "0px",
        "left": "0px",
        "width": "100%",
        "height": "100%",
        "background-color": "rgba(0,0,0,.5)",
        "z-index": "2500",
        "vertical-align": "middle",
        "text-align": "center",
        "color": "#fff",
        "font-size": "40px",
        "font-weight": "bold",
        "cursor": "wait"
    }).appendTo("body");
}

function removeOverlay() {
    $("#overlay").remove();
}



$("form").bind("keypress", function (e) {
	
	
    if (e.keyCode == 13) {
    	
    	$('form').submit();

      
    }
});