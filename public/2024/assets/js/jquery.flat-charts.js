

(function($) {
		
	//'use strict';	
		
	$.fn.flatCharts = function (setup) {

		//if there's flatCharts object associated with the current element, return it
		if (this.data('flatCharts')) {
			return this.data('flatCharts');
		}
		
		var fn       = this,
			flats    = {},
			flatIds  = [],
			legend,
			settings = {
				animate : false, //requires jQuery UI
				naming  : {
					top    : true,
					left   : true,
					getId  : function(character, row, column) {
						return row + '_' + column;
					},
					getLabel : function (character, row, column) {
						return column;
					}
					
				},
				legend : {
					node   : null,
					items  : []
				},
				click   : function() {

					if (this.status() == 'available') {
						return 'selected';
					} else if (this.status() == 'selected') {
						return 'available';
					} else {
						return this.style();
					}
					
				},
				focus  : function() {

					if (this.status() == 'available') {
						return 'focused';
					} else  {
						return this.style();
					}
				},
				blur   : function() {
					return this.status();
				},
				flats   : {}
			
			},
			//flat will be basically a flat object which we'll when generating the map
			flat = (function(flatCharts, flatChartsSettings) {
				return function (setup) {
					var fn = this;
					
					fn.settings = $.extend({
						status : 'available', //available, unavailable, selected
						style  : 'available',
						//make sure there's an empty hash if user doesn't pass anything
						data   : flatChartsSettings.flats[setup.character] || {}
						//anything goes here?
					}, setup);

					fn.settings.$node = $('<div></div>');
					
					fn.settings.$node
						.attr({
							id             : fn.settings.id,
							role           : 'checkbox',
							'aria-checked' : false,
							focusable      : true,
							tabIndex       : -1 //manual focus
						})
						.text(fn.settings.label)
						.addClass(['flatCharts-flat', 'flatCharts-cell', 'available'].concat(
							//let's merge custom user defined classes with standard JSC ones
							fn.settings.classes, 
							typeof flatChartsSettings.flats[fn.settings.character] == "undefined" ? 
								[] : flatChartsSettings.flats[fn.settings.character].classes
							).join(' '));
					
					//basically a wrapper function
					fn.data = function() {
						return fn.settings.data;
					};
					
					fn.char = function() {
						return fn.settings.character;
					};
					
					fn.node = function() {
						return fn.settings.$node;						
					};

					/*
					 * Can either set or return status depending on arguments.
					 *
					 * If there's no argument, it will return the current style.
					 *
					 * If you pass an argument, it will update flat's style
					 */
					fn.style = function() {

						return arguments.length == 1 ?
							(function(newStyle) {
								var oldStyle = fn.settings.style;

								//if nothing changes, do nothing
								if (newStyle == oldStyle) {
									return oldStyle;
								}
								
								//focused is a special style which is not associated with status
								fn.settings.status = newStyle != 'focused' ? newStyle : fn.settings.status;
								fn.settings.$node
									.attr('aria-checked', newStyle == 'selected');

								//if user wants to animate status changes, let him do this
								flatChartsSettings.animate ?
									fn.settings.$node.switchClass(oldStyle, newStyle, 200) :
									fn.settings.$node.removeClass(oldStyle).addClass(newStyle);
									
								return fn.settings.style = newStyle;
							})(arguments[0]) : fn.settings.style;
					};
					
					//either set or retrieve
					fn.status = function() {
	
						return fn.settings.status = arguments.length == 1 ? 
							fn.style(arguments[0]) : fn.settings.status;
					};
					
					//using immediate function to convienietly get shortcut variables
					(function(flatSettings, character, flat) {
						//attach event handlers
						$.each(['click', 'focus', 'blur'], function(index, callback) {
						
							//we want to be able to call the functions for each flat object
							fn[callback] = function() {
								if (callback == 'focus') {
									//if there's already a focused element, we have to remove focus from it first
									if (flatCharts.attr('aria-activedescendant') !== undefined) {
										flats[flatCharts.attr('aria-activedescendant')].blur();
									}
									flatCharts.attr('aria-activedescendant', flat.settings.id);
									flat.node().focus();
								}
							
								/*
								 * User can pass his own callback function, so we have to first check if it exists
								 * and if not, use our default callback.
								 *
								 * Each callback function is executed in the current flat context.
								 */
								return fn.style(typeof flatSettings[character][callback] === 'function' ?
									flatSettings[character][callback].apply(flat) : flatChartsSettings[callback].apply(flat));
							};
							
						});
					//the below will become flatSettings, character, flat thanks to the immediate function		
					})(flatChartsSettings.flats, fn.settings.character, fn);
							
					fn.node()
						//the first three mouse events are simple
						.on('click',      fn.click)
						.on('mouseenter', fn.focus)
						.on('mouseleave', fn.blur)
						
						//keydown requires quite a lot of logic, because we have to know where to move the focus
						.on('keydown',    (function(flat, $flat) {
						
							return function (e) {
								
								var $newSeat;
								
								//everything depends on the pressed key
								switch (e.which) {
									//spacebar will just trigger the same event mouse click does
									case 32:
										e.preventDefault();
										flat.click();
										break;
									//UP & DOWN
									case 40:
									case 38:
										e.preventDefault();
										
										/*
										 * This is a recursive, immediate function which searches for the first "focusable" row.
										 * 
										 * We're using immediate function because we want a convenient access to some DOM elements
										 * We're using recursion because sometimes we may hit an empty space rather than a flat.
										 *
										 */
										$newSeat = (function findAvailable($rows, $flats, $currentRow) {
											var $newRow;
											
											//let's determine which row should we move to
											
											if (!$rows.index($currentRow) && e.which == 38) {
												//if this is the first row and user has pressed up arrow, move to the last row
												$newRow = $rows.last();
											} else if ($rows.index($currentRow) == $rows.length-1 && e.which == 40) {
												//if this is the last row and user has pressed down arrow, move to the first row
												$newRow = $rows.first();
											} else {
												//using eq to get an element at the desired index position
												$newRow = $rows.eq(
													//if up arrow, then decrement the index, if down increment it
													$rows.index($currentRow) + (e.which == 38 ? (-1) : (+1))
												);
											}												
											
											//now that we know the row, let's get the flat using the current column position
											$newSeat = $newRow.find('.flatCharts-flat,.flatCharts-space').eq($flats.index($flat));
											
											//if the flat we found is a space, keep looking further
											return $newSeat.hasClass('flatCharts-space') ?
												findAvailable($rows, $flats, $newRow) : $newSeat;
											
										})($flat
											//get a reference to the parent container and then select all rows but the header
												.parents('.flatCharts-container')
												.find('.flatCharts-row:not(.flatCharts-header)'),
											$flat
											//get a reference to the parent row and then find all flat cells (both flats & spaces)
												.parents('.flatCharts-row:first')
												.find('.flatCharts-flat,.flatCharts-space'),
											//get a reference to the current row
											$flat.parents('.flatCharts-row:not(.flatCharts-header)')
										);
										
										//we couldn't determine the new flat, so we better give up
										if (!$newSeat.length) {
											return;
										}
										
										//remove focus from the old flat and put it on the new one
										flat.blur();
										flats[$newSeat.attr('id')].focus();
										$newSeat.focus();
										
										//update our "aria" reference with the new flat id
										flatCharts.attr('aria-activedescendant', $newSeat.attr('id'));
																			
										break;										
									//LEFT & RIGHT
									case 37:
									case 39:
										e.preventDefault();
										/*
										 * The logic here is slightly different from the one for up/down arrows.
										 * User will be able to browse the whole map using just left/right arrow, because
										 * it will move to the next row when we reach the right/left-most flat.
										 */
										$newSeat = (function($flats) {
										
											if (!$flats.index($flat) && e.which == 37) {
												//user has pressed left arrow and we're currently on the left-most flat
												return $flats.last();
											} else if ($flats.index($flat) == $flats.length -1 && e.which == 39) {
												//user has pressed right arrow and we're currently on the right-most flat
												return $flats.first();
											} else {
												//simply move one flat left or right depending on the key
												return $flats.eq($flats.index($flat) + (e.which == 37 ? (-1) : (+1)));
											}

										})($flat
											.parents('.flatCharts-container:first')
											.find('.flatCharts-flat:not(.flatCharts-space)'));
										
										if (!$newSeat.length) {
											return;
										}
											
										//handle focus
										flat.blur();	
										flats[$newSeat.attr('id')].focus();
										$newSeat.focus();
										
										//update our "aria" reference with the new flat id
										flatCharts.attr('aria-activedescendant', $newSeat.attr('id'));
										break;	
									default:
										break;
								
								}
							};
								
						})(fn, fn.node()));
						//.appendTo(flatCharts.find('.' + row));

				}
			})(fn, settings);
			
		fn.addClass('flatCharts-container');
		
		//true -> deep copy!
		$.extend(true, settings, setup);		
		
		//Generate default row ids unless user passed his own
		settings.naming.rows = settings.naming.rows || (function(length) {
			var rows = [];
			for (var i = 1; i <= length; i++) {
				rows.push(i);
			}
			return rows;
		})(settings.map.length);
		
		//Generate default column ids unless user passed his own
		settings.naming.columns = settings.naming.columns || (function(length) {
			var columns = [];
			for (var i = 1; i <= length; i++) {
				columns.push(i);
			}
			return columns;
		})(settings.map[0].split('').length);
		
		if (settings.naming.top) {
			var $headerRow = $('<div></div>')
				.addClass('flatCharts-row flatCharts-header');
			
			if (settings.naming.left) {
				$headerRow.append($('<div></div>').addClass('flatCharts-cell'));
			}
			
				
			$.each(settings.naming.columns, function(index, value) {
				$headerRow.append(
					$('<div></div>')
						.addClass('flatCharts-cell')
						.text(value)
				);
			});
		}
		
		fn.append($headerRow);
		
		//do this for each map row
		$.each(settings.map, function(row, characters) {

			var $row = $('<div></div>').addClass('flatCharts-row');
				
			if (settings.naming.left) {
				$row.append(
					$('<div></div>')
						.addClass('flatCharts-cell flatCharts-space')
						.text(settings.naming.rows[row])
				);
			}

			/*
			 * Do this for each flat (letter)
			 *
			 * Now users will be able to pass custom ID and label which overwrite the one that flat would be assigned by getId and
			 * getLabel
			 *
			 * New format is like this:
			 * a[ID,label]a[ID]aaaaa
			 *
			 * So you can overwrite the ID or label (or both) even for just one flat.
			 * Basically ID should be first, so if you want to overwrite just label write it as follows:
			 * a[,LABEL]
			 *
			 * Allowed characters in IDs areL 0-9, a-z, A-Z, _
			 * Allowed characters in labels are: 0-9, a-z, A-Z, _, ' ' (space)
			 *
			 */
			 
			$.each(characters.match(/[a-z_]{1}(\[[0-9a-z_]{0,}(,[0-9a-z_ ]+)?\])?/gi), function (column, characterParams) { 
				var matches         = characterParams.match(/([a-z_]{1})(\[([0-9a-z_ ,]+)\])?/i),
					//no matter if user specifies [] params, the character should be in the second element
					character       = matches[1],
					//check if user has passed some additional params to override id or label
					params          = typeof matches[3] !== 'undefined' ? matches[3].split(',') : [],
					//id param should be first
					overrideId      = params.length ? params[0] : null,
					//label param should be second
					overrideLabel   = params.length === 2 ? params[1] : null;
								
				$row.append(character != '_' ?
					//if the character is not an underscore (empty space)
					(function(naming) {
	
						//so users don't have to specify empty objects
						settings.flats[character] = character in settings.flats ? settings.flats[character] : {};
	
						var id = overrideId ? overrideId : naming.getId(character, naming.rows[row], naming.columns[column]);
						flats[id] = new flat({
							id        : id,
							label     : overrideLabel ?
								overrideLabel : naming.getLabel(character, naming.rows[row], naming.columns[column]),
							row       : row,
							column    : column,
							character : character
						});

						flatIds.push(id);
						return flats[id].node();
						
					})(settings.naming) :
					//this is just an empty space (_)
					$('<div></div>').addClass('flatCharts-cell flatCharts-space')	
				);
			});
			
			fn.append($row);
		});
	
		//if there're any legend items to be rendered
		settings.legend.items.length ? (function(legend) {
			//either use user-defined container or create our own and insert it right after the flat chart div
			var $container = (legend.node || $('<div></div').insertAfter(fn))
				.addClass('flatCharts-legend');
				
			var $ul = $('<ul></ul>')
				.addClass('flatCharts-legendList')
				.appendTo($container);
			
			$.each(legend.items, function(index, item) {
				$ul.append(
					$('<li></li>')
						.addClass('flatCharts-legendItem')
						.append(
							$('<div></div>')
								//merge user defined classes with our standard ones
								.addClass(['flatCharts-flat', 'flatCharts-cell', item[1]].concat(
									settings.classes, 
									typeof settings.flats[item[0]] == "undefined" ? [] : settings.flats[item[0]].classes).join(' ')
								)
						)
						.append(
							$('<span></span>')
								.addClass('flatCharts-legendDescription')
								.text(item[2])
						)
				);
			});
			
			return $container;
		})(settings.legend) : null;
	
		fn.attr({
			tabIndex : 0
		});
		
		
		//when container's focused, move focus to the first flat
		fn.focus(function() {
			if (fn.attr('aria-activedescendant')) {
				flats[fn.attr('aria-activedescendant')].blur();
			}
				
			fn.find('.flatCharts-flat:not(.flatCharts-space):first').focus();
			flats[flatIds[0]].focus();

		});
	
		//public methods of flatCharts
		fn.data('flatCharts', {
			flats   : flats,
			flatIds : flatIds,
			//set for one, set for many, get for one
			status: function() {
				var fn = this;
			
				return arguments.length == 1 ? fn.flats[arguments[0]].status() : (function(flatsIds, newStatus) {
				
					return typeof flatsIds == 'string' ? fn.flats[flatsIds].status(newStatus) : (function() {
						$.each(flatsIds, function(index, flatId) {
							fn.flats[flatId].status(newStatus);
						});
					})();
				})(arguments[0], arguments[1]);
			},
			each  : function(callback) {
				var fn = this;
			
				for (var flatId in fn.flats) {
					if (false === callback.call(fn.flats[flatId], flatId)) {
						return flatId;//return last checked
					}
				}
				
				return true;
			},
			node       : function() {
				var fn = this;
				//basically create a CSS query to get all flats by their DOM ids
				return $('#' + fn.flatIds.join(',#'));
			},

			find       : function(query) {//D, a.available, unavailable
				var fn = this;
			
				var flatSet = fn.set();
			
				//user searches just for a particual character
				return query.length == 1 ? (function(character) {
					fn.each(function() {
						if (this.char() == character) {
							flatSet.push(this.settings.id, this);
						}
					});
					
					return flatSet;
				})(query) : (function() {
					//user runs a more sophisticated query, so let's see if there's a dot
					return query.indexOf('.') > -1 ? (function() {
						//there's a dot which separates character and the status
						var parts = query.split('.');
						
						fn.each(function(flatId) {
							if (this.char() == parts[0] && this.status() == parts[1]) {
								flatSet.push(this.settings.id, this);
							}
						});
						
						return flatSet;
					})() : (function() {
						fn.each(function() {

							if (this.status() == query) {
								flatSet.push(this.settings.id, this);
							}
						});
						
						return flatSet;
					})();
				})();
				
			},
			set        : function set() {//inherits some methods
				var fn = this;
				
				return {
					flats      : [],
					flatIds    : [],
					length     : 0,
					status     : function() {
						var args = arguments,
							that = this;
						//if there's just one flat in the set and user didn't pass any params, return current status
						return this.length == 1 && args.length == 0 ? this.flats[0].status() : (function() {
							//otherwise call status function for each of the flats in the set
							$.each(that.flats, function() {
								this.status.apply(this, args);
							});
						})();
					},
					node       : function() {
						return fn.node.call(this);
					},
					each       : function() {
						return fn.each.call(this, arguments[0]);
					},
					get        : function() {
						return fn.get.call(this, arguments[0]);
					},
					find       : function() {
						return fn.find.call(this, arguments[0]);
					},
					set       : function() {
						return set.call(fn);
					},
					push       : function(id, flat) {
						this.flats.push(flat);
						this.flatIds.push(id);
						++this.length;
					}
				};
			},
			//get one object or a set of objects
			get   : function(flatsIds) {
				var fn = this;

				return typeof flatsIds == 'string' ? 
					fn.flats[flatsIds] : (function() {
						
						var flatSet = fn.set();
						
						$.each(flatsIds, function(index, flatId) {
							if (typeof fn.flats[flatId] === 'object') {
								flatSet.push(flatId, fn.flats[flatId]);
							}
						});
						
						return flatSet;
					})();
			}
		});
		
		return fn.data('flatCharts');
	}
	
	
})(jQuery);
