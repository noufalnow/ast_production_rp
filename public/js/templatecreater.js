function resetmname(){
	$('.ftypes_hfields').remove();
	$.each($('#milestone-0-tab').find(':input.mfield'), function(index) {
		var name = $(this).attr('name'); 		
		if (name !== undefined) {
					//var arr = name.split('[');
					var order = index;
					$(this).attr('id', 'flabel['+order+']')
					//.attr('index',"_"+index)
					.attr('name',  'flabel['+order+']')
					.after('<input class="ftypes_hfields" type=hidden name="ftypes['+order+']" value="'+$(this).attr('mtype')+'">' )
					.after('<input class="ftypes_hfields" type=hidden name="hfield_id['+order+']" id="hfield_id['+order+']" value="'+$(this).attr("field_id")+'">' );
		
					var field_attr = $(this).attr('field_id');

					if (typeof field_attr === typeof undefined || field_attr === false) { //template_id
					    
						var fieldArray = '{"type":'+$(this).attr('mtype')+',	"label":"'+$(this).val()+'",	"index":'+order+',	"template":"'+$('#template_id').val()+'"}';
						
						console.log(fieldArray);
						
						customPost(appurl+"manage/template/addfield",fieldArray);
					}
		
		}
			});
}

function setparam(field){
	
	//console.log($(field).closest('form'));
	
	getContent(appurl+"manage/template/param/ref/"+$(field).parents('.col-dr-dp_items').find(':input.mfield').attr('field_id'));
	
}

function delField(field){
	
	getContent(appurl+"manage/template/fieldremove/ref/"+$(field).parents('.col-dr-dp_items').find(':input.mfield').attr('field_id'));
	
}

function addFieldId(response){


	
	if(response.addfield==true){
		
		//$('#flabel['+response.field_order+']').attr('field_id',response.field_id);
		document.getElementById('flabel['+response.field_order+']').setAttribute("field_id", response.field_id);
		labelinput = document.getElementById('hfield_id['+response.field_order+']');
		labelinput.value = response.field_id;
	}
	
	if(response.removefield==true){
		
		$("input[field_id='"+response.field_id+"']").closest('.col-dr-dp_items').remove();
		console.log(response);
		
	}
	
	//console.log($('#flabel[0]').html());
	
}


$( document ).ready(function() {
		var $scrollingDiv = $("#activity-list-box");
		var $scrollingDivTop = $scrollingDiv.offset().top;
		
		$(window).scroll(function(){
			$windowScrollTop = $(window).scrollTop();
			if($windowScrollTop > $scrollingDivTop)
			{
				//$newTop =  $windowScrollTop - $scrollingDivTop;
				//$scrollingDiv
					//.stop()
					//.animate({"marginTop": ($newTop+50) + "px"}, "slow" );
			}	
		});
	});

function hideMilestonetab(tabId)
{
	if(tabId == '' || tabId == null || tabId == undefined  )
	{
		$('.milestone-tab').hide();		
	}
}

function setMilestoneToggleIcon()
{
	$('.milestone-tab-toggle').click(function()
			{
				$(this).parent().parent().find('.milestone-tab').slideToggle('fast');
			}
		);
}

function setCreateTemplate()
{
	$('#create_template').click(function()
			{
				saveTemplate();
			}
		);
}

function setDragDrop()
{
	 $( "#milestone-0-tabcontent, #milestone-1-tabcontent, #milestone-2-tabcontent, #milestone-3-tabcontent, #milestone-4-tabcontent, #milestone-5-tabcontent, #milestone-6-tabcontent, #milestone-7-tabcontent" ).sortable({
		 revert: true,
		 placeholder: "col-dr-dp_items_dropable",
		 forcePlaceholderSize: true,
		 start: function( event, ui ) {
			 	$(this).addClass('sortStart');
			 },
		 stop: function( event, ui ) {
			 	resetmname();
			 	//Have to find if the event is drop or sort
				if(ui.item.hasClass('draggable'))
				{
				 	if(checkMilestoneDropValid(this,ui.item))
				 	{
				 		currentMileStoneTabHeight = $(this).height();
						adjustedheight = currentMileStoneTabHeight+85;
						$(this).height(adjustedheight);
						
						droppedItem = $(ui.item);
						//fieldname = droppedItem.find('input.mfield');
						//console.log(fieldname.attr('index'));
						
						$(".chosen-select").chosen({
							no_results_text : "Oops, nothing found!"
						});
						//http://localhost:5050/manage/template/param/ref/'+fieldname.attr("name")+'
						
						if(droppedItem.children('div.field_param').length==0){
							var addBtn = '<div class="field_param col-sm-1" style="padding-left: 0px;"><a href="javascript:void(0)" onclick="setparam(this);">Options</a></div>'
										+'<div class="field_param col-sm-1" style="padding-left: 0px;"><a href="javascript:void(0)" onclick="delField(this);">X</a></div>';
							droppedItem.append(addBtn);
							

							
						}
						
						//Create the new min item with the details from the current item
						//miniActivityBoxHtml = createActivityMinBox(ui);
						//ui.item.replaceWith(miniActivityBoxHtml);
						
						
						
				 	}
				 	else
				 	{
					 	//revert drag is delete the dragged element with a message;
				 		ui.item.remove();
					 	return;
				 	}
				}

			 }
	 });
	 
	 $( ".draggable" ).draggable({
			 connectToSortable: "#milestone-0-tabcontent, #milestone-1-tabcontent, #milestone-2-tabcontent, #milestone-3-tabcontent, #milestone-4-tabcontent, #milestone-5-tabcontent, #milestone-6-tabcontent, #milestone-7-tabcontent",
			 helper: "clone",
			 revert: "invalid",
			 scroll: true,
			 distance: 10,
			 containment: "#milestone-container",
			 helper: function(){
				 $(this).find('select').each(function(i, v) {
						$(v).chosen('destroy'); 
				    });
				 return $(this).clone().width($(this).width());
				 },
			 start: function( event, ui ) {
				 	element = $(this);
				 	if( !checkMilestoneDroppableDivOpen(element))
				 	{
					 	return false;	//cancel drag;
				 	}
					 	
				 }
		 });
}

function checkMilestoneDroppableDivOpen(element)
{
	return true;
	
	validMilestones = $(element).attr('milestones');
	
	if(validMilestones != '' || validMilestones != null || validMilestones != undefined)
	{
		mileStoneArray = validMilestones.split(',');
		for(var i=0;i<mileStoneArray.length;i++)
		{
			validMileStoneId = 'milestone-'+mileStoneArray[i]+'-tab';
	        if($('#' + validMileStoneId + ' .milestone-tab').is(' :visible'))
	        	return true;
	    }
	}

	//alert('Not open');
	
	
}

function checkMilestoneDropValid(droppedTo, droppedItem)
{
	return true;
	
	validMilestones = $(droppedItem).attr('milestones');
	droppedToMilestone = droppedTo.id.slice(10,11);
	
	if(validMilestones != '' || validMilestones != null || validMilestones != undefined)
	{
		mileStoneArray = validMilestones.split(',');
		if(jQuery.inArray(droppedToMilestone, mileStoneArray) > -1)
		{
			//one item should not be dropped more than one time in a single milestone
			droppedItemId = droppedItem.find("[name='activity[]']").attr('id').slice(9);
			
			/*
			 * if($(droppedTo).find('#selected_activity_'+droppedItemId).length == 0)
			{
				return true;
			}*/
			
			if($('#milestone-container').find('#selected_activity_'+droppedItemId).length == 0)
			{
				return true;
			}
			else
			{
				_templateError('Activity is already added');
				return false;
			}
		}
		else
		{
			return false;
		}	
	}

	return false;
	
}

function createActivityMinBox(ui)
{
	droppedItem = $(ui.item);

	activityName = droppedItem.find('.activity_name').html();
	activityId = droppedItem.find("[name='activity[]']");
	activityWeightage = droppedItem.find("[name='weightage[]']");
	activityDays = droppedItem.find("[name='days[]']");
	
	html = "<div class='col-dr-dp_items hidenseek'>"+
				"<div class='col-dr-dp_items_content'>"+
					"<table cellspacing='0' cellpadding='2' border='0' width='100%'>"+
						"<tbody>"+
							"<tr>"+
								"<td colspan='5'><strong>"+activityName+"</strong><input type='hidden' id='selected_"+activityId.attr('id')+"' name='activitySelected[]' value='"+activityId.attr('value')+"'></td>"+
							"</tr>"+
							
							"<tr>"+
								"<td width='19%'>Weightage</td>"+
								"<td width='14%'><input type='text' size='5' class='numberonly' id='selected_"+activityWeightage.attr('id')+"' name='weightageSelected[]' value='"+activityWeightage.attr('value')+"'></td>"+
								"<td width='11%'>Days</td>"+
								"<td width='15%'><input type='text' size='5' class='integeronly' id='selected_"+activityDays.attr('id')+"' name='daysSelected[]' value='"+activityDays.attr('value')+"'></td>"+
								"<td width='41%'>"+
									"<div class='col-dr-dp_items_controls hidenseek_hide'>"+
										"<table cellspacing='0' cellpadding='0' border='0' align='right' width='30'>"+
											"<tbody>"+
												"<tr>"+
													"<td align='center' valign='middle'><i class='icon-16 icon-delete float-left hyperlink remove-col-dr-dp_items'></i></td>"+
												"</tr>"+
											"</tbody>"+
										"</table>"+
									"</div>"+
								"</td>"+
							"</tr>"+
							
						"</tbody>"+
					"</table>"+
				"</div>"+
			"</div>";

	return html;
}

function saveTemplate()
{
	var error = false;
	
	$('div .error').remove();
	templateName = $('#name').val();
	templateDesc = $('#details').val();

	if(templateName == '')
	{
		setErrorMessage($('#name'), 'Please specify the template name');
		error = true;
	}
	if(templateDesc == '')
	{
		setErrorMessage($('#details'), 'Please specify the template details');
		error = true;
	}

	if(!error)
	{
		dataArray = [];
		var milestoneDetail = [];

		jQuery.each(milestoneArray, function(i, val) {
				elementId = '#milestone-'+val+'-tabcontent';
				tempobj  = {};
				tempobj['milestone'+val] = getSelectedMilestone($(elementId));
				milestoneDetail.push(tempobj);
			});
		
		dataArray.push({
			name: templateName,
			desc: templateDesc,
			activity: milestoneDetail
		  });

		//If the reference element is there then its a template edit
		if($('#reference').val() != '' && $('#reference').val() != undefined && $('#reference').val() != null)
		{
			var edit = true;
			dataArray.push({
				reference: $('#reference').val()
			});	
		}
		
		 var dataString = {};
		 dataString['data'] = JSON.stringify(dataArray);
		 
		 $.ajax({
	            type: 'POST',
	            url: absoluteurl +'/apanel/activity/savetemplate',
	            data: dataString,
	            success: function(response)
	            {
	            	if(response.length == 0)
	            	{
	            		if(edit == true)
            			{
	            			//redirect to template view page
	            			window.location= absoluteurl + '/apanel/activity/viewtemplate/reference/'+ $('#reference').val();
            			}
	            		else
            			{
	            			//redirect to template listing page
	            			window.location= absoluteurl + '/apanel/activity/listtemplate';
            			}
	            	}
	            	else
	            	{
		            	//show error messsage
	            	}
				},
				error: function(request,error)
				{
					console.log(" Can't do because: " + error);
				},
				dataType: 'json'
			});
		
	}		
}

function getSelectedMilestone(tab)
{
	var milestoneDetailArray = []; 
	tab.find('.col-dr-dp_items').each(function()
		{
			currentItem = $(this);
			activityId = currentItem.find("[name='activitySelected[]']");
			activityWeightage = currentItem.find("[name='weightageSelected[]']");
			activityDays = currentItem.find("[name='daysSelected[]']");
	
			key = (milestoneDetailArray.length)+1;
			milestoneDetailArray.push({
				activity: activityId.val(),
				weightage: activityWeightage.val(),
				days: activityDays.val()
			  });
		}
	);

	return milestoneDetailArray;
}

function setErrorMessage(element, message)
{
	if(!(element instanceof jQuery))
		element = $('#'+element);
	
	errorHtml = '<div class="error">'+
					'<ul id="errors-name">'+
						'<li>'+
							'<i class="float_left icon_error2 right_5 icon_size_16"></i>'+
							message+
						'</li>'+
					'</ul>'+
				'</div>';

	element.after(errorHtml);
}

function setTemplateSelect()
{
	$('#template').change(function()
			{
				setTemplateToMilestone(this);
			}
		);
}

function setTemplateToMilestone(element)
{
	selectedTemplate = $(element).val();
	if(selectedTemplate == '')
	{
		$('.milestone-tab').empty().height('75px');
	}
	else
	{
		$('.milestone-tab').empty().height('75px');
		
		//get activities in the milestone
		dataArray = [];
		dataArray.push({
			template: selectedTemplate
		  });
		
		$.ajax({
            type: 'GET',
            url: absoluteurl +'/apanel/activity/getactivities/template/'+selectedTemplate,
            success: function(response)
            {
            	if(response.length == 0)
            	{
            		//show error messsage
            	}
            	else
            	{
            		$.each(response, function (index, activity) {
            			html = "<div class='col-dr-dp_items hidenseek'>"+
		        				"<div class='col-dr-dp_items_content'>"+
		        					"<table cellspacing='0' cellpadding='2' border='0' width='100%'>"+
		        						"<tbody>"+
		        							"<tr>"+
		        								"<td colspan='5'><strong>"+activity.name+"</strong><input type='hidden' id='selected_activity_"+activity.id+"' name='activitySelected[]' value='"+activity.id+"'></td>"+
		        							"</tr>"+
		        							
		        							"<tr>"+
		        								"<td width='19%'>Weightage</td>"+
		        								"<td width='14%'><input type='text' size='5' class='numberonly' id='selected_activity_wgt_"+activity.id+"' name='weightageSelected[]' value='"+activity.weight+"'></td>"+
		        								"<td width='11%'>Days</td>"+
		        								"<td width='15%'><input type='text' size='5' class='integeronly' id='selected_activity_days_"+activity.id+"' name='daysSelected[]' value='"+activity.days+"'></td>"+
		        								"<td width='41%'>"+
		        									"<div class='col-dr-dp_items_controls hidenseek_hide'>"+
		        										"<table cellspacing='0' cellpadding='0' border='0' align='right' width='30'>"+
		        											"<tbody>"+
		        												"<tr>"+
		        													"<td align='center' valign='middle'><i class='icon-16 icon-delete float-left hyperlink remove-col-dr-dp_items'></i></td>"+
		        												"</tr>"+
		        											"</tbody>"+
		        										"</table>"+
		        									"</div>"+
		        								"</td>"+
		        							"</tr>"+
		        							
		        						"</tbody>"+
		        					"</table>"+
		        				"</div>"+
		        			"</div>";
            			
            			//get where to place it	-- milestone-0-tabcontent
            			var placeHolder = $('#milestone-'+activity.milestone+'-tabcontent');
            			
            			placeHolder.append(html);
            			
            			//increase the height
            			currentMileStoneTabHeight = placeHolder.height();
						adjustedheight = currentMileStoneTabHeight+85;
						placeHolder.height(adjustedheight);
						
            		});
            	}
			},
			error: function(request,error)
			{
				console.log(" Can't do because: " + error);
			},
			dataType: 'json'
		});
	}
}

function setSaveActivities()
{
	$('#save_activities').click(function()
			{
				saveActivities();
			}
		);

	$('#update_activities').click(function()
			{
				saveActivities(true);
			}
	);
}

function saveActivities(edit)
{
	var gpId = $('#reference').val();
	dataArray = [];
	var milestoneDetail = [];

	jQuery.each(milestoneArray, function(i, val) {
		elementId = '#milestone-'+val+'-tabcontent';
		tempobj  = {};
		tempobj['milestone'+val] = getSelectedMilestone($(elementId));
		milestoneDetail.push(tempobj);
	});
	
	dataArray.push({
		activity: milestoneDetail
	  });

	 var dataString = {};
	 dataString['data'] = JSON.stringify(dataArray);
	 
	 if(edit)
		 var postUrl = absoluteurl +'/apanel/gp/updatemilestone/reference/'+gpId;
	 else
		 var postUrl = absoluteurl +'/apanel/gp/addmilestone/reference/'+gpId;

	 
	 $.ajax({
            type: 'POST',
            url: postUrl,
            data: dataString,
            success: function(response)
            {
            	if(response.length == 0)
            	{
	            	//redirect to template listing page
            		window.location= absoluteurl + '/apanel/gp/milestonelist/reference/'+gpId;
            	}
            	else
            	{
	            	//show error messsage
            	}
			},
			error: function(request,error)
			{
				console.log(" Can't do because: " + error);
			},
			dataType: 'json'
		});
	 
}

/*$('.remove-col-dr-dp_items').live('click',function() {
	
	contentBox = $(this).closest('.col-dr-dp_items');
	parentMilestoneTab = $(this).closest('.milestone-tab');
	removedBoxHeight = contentBox.outerHeight(true);
	
	newHeight = parentMilestoneTab.height() - removedBoxHeight;
	
	contentBox.remove();
	parentMilestoneTab.height( newHeight+'px' );
});*/

function _templateError(error)
{
	console.log(error);
}