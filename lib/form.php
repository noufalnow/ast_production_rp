<?php


require 'vendor/autoload.php';

// @todo remove sanitize from post
class element {
	public $_validators;
	public $_name;
	public $_tname;
	public $_error;
	public $_value;
	public $_type;
	public $_options;
	public $_rules;
	public $_index;
	public $_attribs;
	function __construct($name = null, $tname= null, $type= null, $options = array(), $attribs = array(), $rules= null) {
		if (is_array ( $name )) {
			$this->_index = $name [1];
			$this->_name = $name [0] . "[$name[1]]";
		} else {
			$this->_name = $name;
		}
		$this->_tname = $tname;
		$this->_type = $type;
		if(is_array(@$options))
		  $this->_options = @$options ['options'];
		$this->_attribs = @$attribs;
		$this->_rules = $rules;
		return $this->_name;
	}
	public function show() {
		foreach ( $this->_attribs as $attr => $atrval ) {
			if ($attr == 'class')
				$class = $atrval;
			elseif ($attr == 'id')
				$atrid = $atrval;
			elseif ($attr == '')
				$plainAttr .= ' ' . $atrval . ' ';
			else
				$plainAttr .= ' ' . $attr . ' = ' . ' " ' . $atrval . ' " ';
		}
		
		$required = '';
		
		if (! is_array ( $this->_rules )) {
			if (strpos ( $this->_rules, 'required' ) !== false)
				$required = ' required ';
		} else {
			
			if (in_array ( 'required', $this->_rules ) !== false)
				$required = ' required ';
		}
		
		if (! $atrid)
			$atrid = $this->_name;
		
		switch ($this->_type) {
			case 'text' :
			    if($this->_disabled)	$disabled = ' readonly ';else $disabled = '';
				if ($this->_error) {
					$error_class = 'input_error ip_err';
					$error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
				}
				
				echo '<input type="text" ' . $disabled . ' ' . $required . ' class="form-control ' . @$error_class . ' ' . @$class . ' " ' . @$plainAttr . ' placeholder="' . $this->_tname . '" id="' . $atrid . '" name="' . $this->_name . '" value="' . $this->_value . '" autocomplete="off">' . @$error_msg;
				break;
			case 'amount' :
				if($this->_disabled)	$disabled = ' readonly ';else $disabled = '';
				if ($this->_error) {
					$error_class = 'input_error ip_err';
					$error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
				}
				
				echo '<input autocomplete="off" style="text-align: right;" type="text" ' . $disabled . ' ' . $required . ' class="form-control ' . @$error_class . ' ' . @$class . ' numonly" ' . @$plainAttr . ' placeholder="' . $this->_tname . '" id="' . $atrid . '" name="' . $this->_name . '" value="' . $this->_value . '">' . @$error_msg;
				break;
			case 'baisa' :
			    if($this->_disabled)	$disabled = ' readonly ';else $disabled = '';
			    if ($this->_error) {
			        $error_class = 'input_error ip_err';
			        $error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
			    }
			    
			    echo '<input '.$disabled.' autocomplete="off" style="text-align: right;" type="text"  class="form-control ' . @$error_class .' '. @$class .' numonly baisa" '.@$plainAttr.' placeholder="' . $this->_tname . '" id="' . $this->_name . '" name="' . $this->_name . '" value="' . $this->_value . '">' . @$error_msg;
			    break;
			case 'paisa' :
				if ($this->_error) {
					$error_class = 'input_error ip_err';
					$error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
				}
				
				echo '<input autocomplete="off" style="text-align: right;" type="text"  ' . $required . ' class="form-control ' . @$error_class . ' ' . @$class . ' numonly paisa" ' . @$plainAttr . ' placeholder="' . $this->_tname . '" id="' . $atrid . '" name="' . $this->_name . '" value="' . $this->_value . '">' . @$error_msg;
				break;
			case 'number' :
				if($this->_disabled)	$disabled = ' readonly ';else $disabled = '';
				if ($this->_error) {
					$error_class = 'input_error ip_err';
					$error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
				}
				
				echo '<input autocomplete="off" type="text" ' . $disabled . ' ' . $required . ' class="form-control ' . @$error_class . ' ' . @$class . ' numonly" ' . @$plainAttr . ' placeholder="' . $this->_tname . '" id="' . $atrid . '" name="' . $this->_name . '" value="' . $this->_value . '">' . @$error_msg;
				break;
			case 'float' :
				if($this->_disabled)	$disabled = ' readonly ';else $disabled = '';
				if ($this->_error) {
					$error_class = 'input_error ip_err';
					$error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
				}
				
				echo '<input autocomplete="off" type="text" ' . $disabled . ' ' . $required . ' class="form-control ' . @$error_class . ' ' . @$class . ' floatonly" ' . @$plainAttr . ' placeholder="' . $this->_tname . '" id="' . $atrid . '" name="' . $this->_name . '" value="' . $this->_value . '" >' . @$error_msg;
				break;
			case 'password' :
				if($this->_disabled)	$disabled = ' readonly ';else $disabled = '';
				if ($this->_error) {
					$error_class = 'input_error ip_err';
					$error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
				}
				echo '<input type="password" ' . $required . ' class="form-control ' . @$error_class . '" placeholder="' . $this->_tname . '" id="' . $atrid . '" name="' . $this->_name . '" value="' . $this->_value . '" autocomplete="off">' . @$error_msg;
				break;
			case 'textarea' :
				if ($this->_error) {
					$error_class = 'input_error ip_err';
					$error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
				}
				echo '<textarea class="form-control ' . @$error_class . '" placeholder="' . $this->_tname . '" id="' . $atrid . '" name="' . $this->_name . '" >' . $this->_value . '</textarea>' . @$error_msg;
				break;
			
			case 'select' :
				if ($this->_disabled)
					$disabled = ' disabled ';
				else
					$disabled = '';
				// $select = '<select id="' . $this->_name . '" class="' . $this->_name . ' chosen-select '.@$class.'" name="' . $this->_name . '" '.@$plainAttr.'>';
				$select = '<select id="' . $atrid . '" class="' . $this->_name . ' chosen-select ' . @$class . ' " name="' . $this->_name .'" ' . @$plainAttr . '>';
				if (! $this->_options [''])
					$select .= '<option value =""> --  ' . $this->_tname . ' -- </option>';
				foreach ( $this->_options as $key => $option ) {
					$selected = '';
					if ($key == $this->_value)
						$selected = 'selected ';
					else
						$selected .= $disabled;
					$select .= '<option ' . $selected . 'value ="' . $key . '">' . $option . '</option>';
				}
				$select .= '</select>';
				if ($this->_error) {
					$error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
				}
				if ($this->_error)
					echo '<div class="select_error ip_err">' . $select . '</div>';
				else
					echo $select;
				echo @$error_msg;
				break;
			
			case 'splselect' :
				if ($this->_disabled)
					$disabled = ' disabled ';
				else
					$disabled = '';
				
				// $select = '<select id="' . $this->_name . '" class="' . $this->_name . ' chosen-select '.@$class.'" name="' . $this->_name . '" '.@$plainAttr.'>';
				$select = '<select id="' . $atrid . '" class="' . $this->_name . ' ' .@$class . ' " name="' . $this->_name .'" ' . @$plainAttr . '>';
				if (! $this->_options [''])
					$select .= '<option value ="">Select ' . $this->_tname . '</option>';
				foreach ( $this->_options as $key => $option ) {
					$selected = '';
					if ($key == $this->_value)
						$selected = 'selected ';
					else
						$selected .= $disabled;
					$select .= '<option ' . $selected . 'value ="' . $key . '">' . $option . '</option>';
				}
				$select .= '</select>';
				if ($this->_error) {
					$error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
				}
				if ($this->_error)
					echo '<div class="select_error ip_err">' . $select . '</div>';
				else
					echo $select;
				echo @$error_msg;
				break;
			
			case 'radio' :
				if ($this->_error) {
					$error_class = 'input_error ip_err';
					$error_msg = '<br><small class="text-help error_text">' . $this->_error . '</small>';
				}
				foreach ( $this->_options as $key => $option ) {
					$checked = '';
					if (is_array ( @$this->_value )) {
						if (@in_array ( $key, $this->_value ))
							$checked = ' checked="checked"';
					} elseif ($key == $this->_value)
						$checked = ' checked="checked"';
					echo '<input class="' . @$class . '" type="radio" ' . $required . ' value="' . $key . '" id="' . $atrid . $key . '" name="' . $this->_name . '" ' . @$checked . '' . @$plainAttr . '> ' . $option . '&nbsp;&nbsp;';
				}
				echo @$error_msg;
				
				break;
			case 'checkbox' :
				
				if ($this->_error) {
					$error_class = 'input_error ip_err';
					$error_msg = '<br><small class="text-help error_text">' . $this->_error . '</small>';
				}
				
				$arrayNotation = '';
				if (count ( $this->_options ) > 1)
					$arrayNotation = '[]';
				
				foreach ( $this->_options as $key => $option ) {
					$checked = '';
					if ($arrayNotation) {
						if (@in_array ( $key, $this->_value ))
							$checked = 'checked';
					} else {
						if ($key == $this->_value)
							$checked = ' checked="checked"';
					}
					
					echo '<input type="checkbox" ' . $required . ' value="' . $key . '" id="' . $atrid . $key . '" name="' . $this->_name . '' . $arrayNotation . '" ' . @$checked . ' class="' . @$class . '" ' . @$plainAttr . '> ' . $option . '&nbsp;&nbsp;';
				}
				echo @$error_msg;
				
				break;
			
			case 'file' :
				
				$file = '<input class="form-control" type="file" id="' . $atrid . '" name="' . $this->_name . '" value="' . $this->_value . '" ' . $required . '>';
				if ($this->_error) {
					$error_class = 'input_error ip_err';
					$error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
				}
				
				if ($this->_error) {
					echo '<div class="select_error ip_err">' . $file . '</div>';
					echo $error_msg;
				} else
					echo $file;
				
				break;
			case 'multiselect' :
				// $select = '<select id="' . $atrid . '" class="' . $this->_name . '" name="' . $this->_name . '[]" multiple="">';
				$select = '<select id="' . $atrid . '" class="' . $this->_name . ' chosen-select ' . @$class . '" name="' . $this->_name . '[]" ' . @$plainAttr . ' multiple="">';
				
				foreach ( $this->_options as $key => $option ) {
					$selected = '';
					if (is_array($this->_value) && @in_array ( $key, $this->_value ))
						$selected = 'selected ';
					$select .= '<option ' . $selected . 'value ="' . $key . '">' . $option . '</option>';
				}
				$select .= '</select>';
				if ($this->_error) {
					$error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
				}
				if ($this->_error)
					echo '<div class="select_error ip_err">' . $select . '</div>';
				else
					echo $select;
				echo @$error_msg;
				break;
			
			case 'jsmultiselect' :
				// $select = '<select id="' . $atrid . '" class="' . $this->_name . '" name="' . $this->_name . '[]" multiple="">';
				$select = '<select id="' . $atrid . '" class="' . $this->_name . ' multiselect' . @$class . '" name="' . $this->_name . '[]" ' . @$plainAttr . ' multiple="" style="width: 92%;height:150px"> ';
				
				foreach ( $this->_options as $key => $option ) {
					$selected = '';
					if (@in_array ( $key, $this->_value ))
						$selected = 'selected ';
					$select .= '<option ' . $selected . 'value ="' . $key . '">' . $option . '</option>';
				}
				$select .= '</select>';
				if ($this->_error) {
					$error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
				}
				if ($this->_error)
					echo '<div class="select_error ip_err">' . $select . '</div>';
				else
					echo $select;
				echo @$error_msg;
				break;
			
			case 'hidden' :
				if ($this->_error) {
					$error_class = 'input_error ip_err';
					$error_msg = '<small class="text-help error_text">' . $this->_error . '</small>';
				}
				echo '<input type="hidden"  " id="' . $this->_name . '" name="' . $this->_name . '" value="' . $this->_value . '">' . @$error_msg;
				break;
		}
	}
	public function setValue($value) {
		$this->_value = $value;
	}
	public function resetValue() {
		$this->_value = '';
	}
	public function setError($value) {
		$this->_error = $value;
	}
	public function setAttr($value) {
		$this->_attribs = $value;
	}
	public function getTname() {
		return $this->_tname;
	}
	public function setDisabled() {
	    $this->_disabled= true;
	}
}
class form {
	public $_rules;
	public $_mrules;
	public $_elements = array ();
	public $_isValid = true;
	public $_files = array ();
	public function addElement($name, $tname, $type, $rules, $options = array(), $attr = array()) {
		// echo "func called";
		$this->{$name} = new element ( $name, $tname, $type, $options, $attr, $rules );
		$obj = $this->{$name};
		
		$this->_rules [$name] = $rules;
		$this->_elements [$name] = $obj;
		
		return $obj->_name;
	}
	public function addMultiElement($name= null, $tname= null, $type= null, $rules= null, $options = array(), $attr = array(), $count= null) {
		if (is_array ( $count )) {
			foreach ( $count as $key ) {
				$melement = new element ( array (
						$name,
						$key 
				), $tname, $type, $options, $attr, $rules );
				$this->_elements [$name] [$key] = $melement;
				$this->_mrules [$name] [$key] = $rules;
			}
		} else
			for($i = 0; $i < $count; $i ++) {
				$melement = new element ( array (
						$name,
						$i 
				), $tname, $type, $options, $attr, $rules );
				$this->_elements [$name] [$i] = $melement;
				$this->_mrules [$name] [$i] = $rules;
			}
		$this->{$name} = $this->_elements [$name];
		
		return $this->_elements [$name];
	}
	public function addErrorMsg($element, $validator, $message='') {
		$this->custom_errors [$element] [$validator] = $message;
	}
	public function addmRules($name, $key, $rules, $message = "") {
		$this->_mrules [$name] [$key] = $rules;
	}
	public function addRules($name, $rules, $message = "") {
		$this->_rules [$name] = $rules;
		if ($message)
			self::addErrorMsg ( $name, $rules, $message );
	}
	public function addFile($name, $tname, $rules, $options = array()) {
		$this->{$name} = new element ( $name, $tname, 'file', $options );
		$obj = $this->{$name};
		
		$obj->_rules = $rules;
		$this->_files [$name] = $obj;
		
		return $obj->_name;
	}
	public function addMultiFile($name= null, $tname= null, $rules= null, $options = array(), $count= null) {
		for($i = 0; $i < $count; $i ++) {
			$file = new element ( array (
					$name,
					$i 
			), $tname, 'file', $options );
			$file->_rules = $rules;
			$this->_files [$name] [$i] = $file;
		}
		return $this->_files [$name];
	}
	public function reset() {
		foreach ( $this->_elements as $key => $element ) {
			if (is_array ( $element )) {
				
				foreach ( $element as $mkey => $melement )
					$melement->resetValue ();
			} else
				$element->resetValue ();
		}
	}
	public function vaidate($data = array(), $files = array()) {
		$mvalid_data = array ();
		$valid_data = array ();
		$valid_fdata = array ();
		
		if (count ( $this->_files ) > 0 /*&& count ( $files ) > 0*/)
			
			foreach ( $this->_files as $fname => $felement ) {
				
				if (is_array ( $felement )) {
					
					foreach ( $felement as $ifname => $mfile ) {
						
						$struct = array (
								'name' => $files [$fname] ['name'] [$ifname],
								'size' => $files [$fname] ['size'] [$ifname] 
						);
						
						$validator = array ();
						$gumpf = new GUMP ();
						$file_data = $gumpf->sanitize ( $struct );
						
						if ($mfile->_rules) {
							
							if ($mfile->_rules ['required'] || $files [$fname] ['name'])
								$validator ['name'] = 'required|';
							
							if (@$validator ['name']) {
								
								if ($mfile->_rules ['exten']) {
									$validator ['name'] = $validator ['name'] . 'extension,' . $mfile->_rules ['exten'];
								}
								
								if ($mfile->_rules ['size']) {
									$validator ['size'] = 'max_numeric,' . $mfile->_rules ['size'];
								}
								
								$valid_files = $gumpf->is_valid ( $file_data, $validator );
								
								if ($valid_files !== true) {
									$this->_isValid = false;
									$mfile->_error = $valid_files [0];
								} else {
									$valid_fdata [$fname] [$ifname] = $file_data;
								}
							}
						} else {
							$valid_fdata [$fname] [$ifname] = $file_data;
						}
					}
				} else {
					
					// echo "<br>" . $fname . "<br>";
					
				    $file_data = array (
							'name' => $files [$fname] ['name'],
							'size' => $files [$fname] ['size'],
							'tmp_name' => $files [$fname] ['tmp_name'] 
					);
					
					$validator = array ();
					$gumpf = new GUMP ();
					$file_data =  $files;
					
					
					if ($felement->_rules) {
						
						if ($felement->_rules ['required'] || $files [$fname] ['name'])
							$validator [$felement->_name] = 'required_file';
						
						if (@$validator [$felement->_name]) {
							
							if ($felement->_rules ['exten']) {
								$validator [$felement->_name] = $validator [$felement->_name] . '|extension,' . $felement->_rules ['exten'];
							}
							
							if ($felement->_rules ['size']) {
							    $validator ['file_size'] = 'file_size,' . $felement->_rules ['size'];
							    $file_data['file_size'] = $files [$fname] ['size'];
							}
							
							$valid_files = $gumpf->is_valid ( $file_data, $validator );
							
							//s($file_data,$validator,$valid_files);
							
							if ($valid_files !== true) {
								$this->_isValid = false;
								$felement->_error = $valid_files [0];
							} else {
								$valid_fdata = $file_data;
							}
						}
					} else {
						$valid_fdata [$fname] = $file_data;
					}
				}
			}
		
		foreach ( $data as $dkey => $dvalue ) {
			if (is_array ( $dvalue )) {
				foreach ( $dvalue as $mkey => $mvalue ) {
					$mpost [$mkey] = trim ( $mvalue );
				}
				$post [$dkey] = $mpost;
				$mpost = [];
			} else
				$post [$dkey] = trim ( $dvalue );
		}
		
		$data = $post; // temp copy
		$gump = new GUMP ();
		if (is_array ( $this->_rules )) {
			$data = $gump->sanitize ( $data, array_keys ( $this->_rules ) );
			$gump->validation_rules ( array_filter ( $this->_rules ) );
		}
		
		foreach ( $this->_elements as $key => $element ) {
			
			if (is_array ( $element )) {
				$gump1 = new GUMP ();
				
				$mdata = $gump1->sanitize ( $post [$key] );
				$gump1->validation_rules ( array_filter ( $this->_mrules [$key] ) );
				
				foreach ( $element as $ckey => $celement ) {
					$gump1->set_field_name ( $celement->_index, $celement->_name );
					$celement->setValue ( $mdata [$celement->_index] );
					
					if (! empty ( $this->custom_errors [$key] )) {
						$gump1->add_custom_error ( $ckey, $this->custom_errors [$key] );
					}
				}
				
				$mvalidated_data = $gump1->run ( $mdata );
				if ($mvalidated_data === false) {
					$this->_isValid = false;
					$merrors = $gump1->get_errors_array ();
					
					foreach ( $element as $ckey => $celement ) {
						$celement->_error = @$merrors [$celement->_index];
					}
				} else {
					$mvalid_data [$key] = $mvalidated_data;
				}
			} else {
				$gump->set_field_name ( $element->_name, $element->_tname );
				$element->setValue ( @$data [$element->_name] );
				
				if (! empty ( $this->custom_errors [$key] )) {
					$gump->add_custom_error ( $element->_name, $this->custom_errors [$key] );
				}
			}
		}
		
		$valid_data = $gump->run ( $data );
		
		if ($valid_data === false) {
			$this->_isValid = false;
			$errors = $gump->get_errors_array ();
			
			foreach ( $this->_elements as $element ) {
				if (! is_array ( $element )) {
					$element->_error = @$errors [$element->_name];
				}
			}
		}
		
		if ($this->_isValid) {
			return array (
					array_merge ( $mvalid_data, $valid_data, $valid_fdata ) 
			);
		} else
			return $this->_isValid;
	}
	public function sanitise($data) {
		$gump = new GUMP ();
		return $gump->sanitize ( $data );
	}
}
?>
