<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    class FieldForm extends Form {

        static public function primary_input_hidden($field = null) {
            if(isset($field['pk_i_id'])) {
                parent::generic_input_hidden("id", $field["pk_i_id"]) ;
            }
        }

        static public function name_input_text($field = null) {
            parent::generic_input_text("s_name", (isset($field) && isset($field["s_name"])) ? $field["s_name"] : "", null, false) ;
            return true ;
        }
		
		static public function order_input_text($field = null) {
            parent::generic_input_text("list_id", (isset($field) && isset($field["list_id"])) ? $field["list_id"] : "", null, false) ;
            return true ;
        }

        static public function options_input_text($field = null) {
            parent::generic_input_text("s_options", (isset($field) && isset($field["s_options"])) ? $field["s_options"] : "", null, false) ;
            return true ;
        }
		//For the search Option in the custom field
		static public function search_options_input_text($field = null) {
            parent::generic_input_text("search_options", (isset($field) && isset($field["search_options"])) ? $field["search_options"] : "", null, false) ;
            return true ;
        }

        static public function required_checkbox($field = null) {
            parent::generic_input_checkbox('field_required', 1, ($field!=null && isset($field['b_required']) && $field['b_required']==1)?true:false);
        }
        
        static public function type_select($field = null) {
            ?>

<select name="field_type" id="field_type">
  NUMERIC
                
  <option value="TEXT" <?php if($field['e_type']=="TEXT") { echo 'selected="selected"';};?>>TEXT</option>
  <option value="TEXTAREA" <?php if($field['e_type']=="TEXTAREA") { echo 'selected="selected"';};?>>TEXTAREA</option>
  <option value="DROPDOWN" <?php if($field['e_type']=="DROPDOWN") { echo 'selected="selected"';};?>>DROPDOWN</option>
  <option value="RADIO" <?php if($field['e_type']=="RADIO") { echo 'selected="selected"';};?>>RADIO</option>
  <option value="CHECKBOX" <?php if($field['e_type']=="CHECKBOX") { echo 'selected="selected"';};?>>CHECKBOX</option>
  <option value="DATE" <?php if($field['e_type']=="DATE") { echo 'selected="selected"';};?>>DATE</option>
  <option value="NUMERIC" <?php if($field['e_type']=="NUMERIC") { echo 'selected="selected"';};?>>NUMERIC</option>
  <option value="URL" <?php if($field['e_type']=="URL") { echo 'selected="selected"';};?>>URL</option>
</select>
<?php
            return true;
        }
		
		//For the search type select value of my added page
		static public function search_type_select($field = null) {
            ?>
<select name="search_field_type" id="search_field_type">
  <option value="TEXT" <?php if($field['search_type']=="TEXT") { echo 'selected="selected"';};?>>TEXT</option>
  <option value="DROPDOWN" <?php if($field['search_type']=="DROPDOWN") { echo 'selected="selected"';};?>>DROPDOWN</option>
  <option value="RANGE_NUMBER" <?php if($field['search_type']=="RANGE_NUMBER") { echo 'selected="selected"';};?>>Range-Number</option>
  <option value="RANGE_DATE" <?php if($field['search_type']=="RANGE_DATE") { echo 'selected="selected"';};?>>Range-Date</option>
</select>
<?php
            return true;
        }
		
		static public function search_enable_select($field = null) {
            ?>
<select name="search_field_enable" id="search_field_enable">
  <option value="1" <?php if($field['search_enable']==1) { echo 'selected="selected"';};?>>Yes</option>
  <option value="0" <?php if($field['search_enable']==0) { echo 'selected="selected"';};?>>No</option>
</select>
<?php
            return true;
        }
        
        static public function meta($field = null) {
			//$search_page= Session::newInstance()->_get('v_search_page');
			//$item_page= Session::newInstance()->_get('v_item_page');
			$x = ($_SERVER['HTTP_REFERER']);
			$search_page = (strpos($x,"search")>=1)?"search":"";
			$item_page = (strpos($x,"item/new")>=1)?"item":"";
			$admin_page = Session::newInstance()->_get('v_admin_page');
						if($search_page == "search"){
				if($admin_page == ""){
					if($field['search_enable']!=0){
            if($field!=null) {
                if(Session::newInstance()->_getForm('meta_'.$field['pk_i_id']) != ""){
                    $field['s_value'] = Session::newInstance()->_getForm('meta_'.$field['pk_i_id']);
                }
			//echo Session::newInstance()->_getForm('search_page', $p_sPage);
			//echo Session::newInstance()->_getForm('address', $p_sAddress);
                if($field['search_type']=="TEXTAREA") {
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    echo '<textarea id="meta_' . $field['s_slug'] . '" name="meta['.$field['pk_i_id'].']" rows="10">' . ((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") . '</textarea>' ;
                } else if($field['search_type']=="DROPDOWN") {
					
							if($field['pk_i_id']==77) { 
					echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    if(isset($field) && isset($field['s_options'])) {
                        $options = explode(",", $field['s_options']);
                        
                        if(count($options)>0) {
                            echo '<select onchange="drop_merk();" class="merk_make" name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '">';
							
							echo '<option></option>';
                            foreach($options as $option) {
                                echo '<option value="'.osc_esc_html($option).'" '.($field['s_value']==$option?'selected="selected"':'').'>'.$option.'</option>';
                            }
                            echo '</select>';
                        }
					
					} }
					
					elseif($field['pk_i_id']==78){
					$val_merk =  Session::newInstance()->_getForm('meta_77', $value);
					$models = CarModel::newInstance()->getMedelsByBrand($val_merk);
					$var_drop_model =  Session::newInstance()->_getForm('meta_'.$field['pk_i_id'], $value);
					
					
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    if(isset($field) && isset($field['s_options'])) {
                        $options = explode(",", $field['s_options']);
                        if(count($options)>0) {
                            echo '<div id="row_model"><select class="merk" name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '">';			
							if($var_drop_model != "" && $val_merk !=""){
								foreach($models as $model){
									echo '<option value="'.osc_esc_html($model['s_model']).'" '.($var_drop_model==$model['s_model']?'selected="selected"':'').'>'.$model['s_model'].'</option>';
								}
						
							}else{
							
								echo '<option></option>';
                            foreach($options as $option) {
                                //echo '<option value="'.osc_esc_html($option).'" '.($field['s_value']==$option?'selected="selected"':'').'>'.$option.'</option>';
								echo '<option value="'.osc_esc_html($option).'" '.($field['s_value']==$option?'selected="selected"':'').'>'.$option.'</option>';
                            }
							}
                            echo '</select></div>';
                        }
					}
                    
						}
					else{
					$var_drop_search1 =  Session::newInstance()->_getForm('meta_'.$field['pk_i_id'], $value);
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    if(isset($field) && isset($field['search_options'])) {
                        $options = explode(",", $field['search_options']);
                        if(count($options)>0) {
                            echo '<select name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '">';
							echo '<option></option>';
                            foreach($options as $option) {
                                echo '<option value="'.osc_esc_html($option).'" '.(($var_drop_search1==$option||$field['s_value']==$option)?'selected="selected"':'').'>'.$option.'</option>';
                            }
                            echo '</select>';
                        }
                    }
                } }
				
				
				
				
				
				else if($field['search_type']=="RANGE_NUMBER")  {
					$from = Session::newInstance()->_getForm('from_'.$field['pk_i_id'], $value);
					$to =  Session::newInstance()->_getForm('to_'.$field['pk_i_id'], $value);
					
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    if(isset($field) && isset($field['search_options'])) {
                        $options = explode(",", $field['search_options']);
                        //if(count($options)>0) {
                            foreach($options as $key => $option) {
                                echo '<div class="range_box">';
								echo '<input onkeypress="return isNumberKeyFrom(event);" onblur ="compare(this);" type="text" name="from['.$field['pk_i_id'].']" id="from'.$field['pk_i_id'].'" value="'.$from.'" style="width:77px;"/>';
								echo '<label>t/m</label>';
								echo '<input onkeypress="return isNumberKeyTo(event);" onblur ="compare(this);" type="text" name="to['.$field['pk_i_id'].']" id="to'.$field['pk_i_id'].'" value="'.$to.'" style="width:77px;"/>';
								echo '</div>';
                            } ?>
							
							<script type="text/javascript">
								
										function isNumberKeyFrom(evt){        
      								var charcode = document.getElementById('from<?php echo $field['pk_i_id']; ?>').value; 
         							var charCode = (evt.which) ? evt.which : event.keyCode;
                 					if (charCode != 46 && charCode > 31 
          								 && (charCode < 48 || charCode > 57)){
										alert('Er kunnen alleen numerieke waarden met een pun (.) voor decimalen ingevoerd worden punt');
            							return false; 
			
											 }
         								return true; alert('true');
      										}
											
											function isNumberKeyTo(evt){        
      								var charcode = document.getElementById('to<?php echo $field['pk_i_id']; ?>').value; 
         							var charCode = (evt.which) ? evt.which : event.keyCode;
                 					if (charCode != 46 && charCode > 31 
          								 && (charCode < 48 || charCode > 57)){
										alert('Er kunnen alleen numerieke waarden met een pun (.) voor decimalen ingevoerd worden punt');
            							return false; 
			
											 }
         								return true; alert('true');
      										}
											</script> <?php
							
							
							
                            echo '</ul>';
                        //}
                    }
                } else if($field['search_type']=="RANGE_DATE") {
					
					$from = Session::newInstance()->_getForm('from_'.$field['pk_i_id'], $value);
					$to =  Session::newInstance()->_getForm('to_'.$field['pk_i_id'], $value);
					
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    if(isset($field) && isset($field['s_options'])) {
                        $options = explode(",", $field['s_options']);
                        //if(count($options)>0) {
                            echo '<ul>';
                           // foreach($options as $key => $option) {
                                echo '<li><input type="text" onchange = "changeDate(this)" name="from['.$field['pk_i_id'].']" id="meta_from'.$field['pk_i_id'].'" value="'.$from.'" placeholder="Van" style="width:55px;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
								echo '<input type="text" onchange = "changeDate(this)" name="to['.$field['pk_i_id'].']" id="meta_to'.$field['pk_i_id'].'" value="'.$to.'" placeholder="Tot" style="width:55px;"/></li>';
                           // }
                            echo '</ul>';  ?>
<script>
   									$(function() {
      									 $( "#meta_from<?php echo $field['pk_i_id']; ?>" ).datepicker({
           changeMonth: true,
           changeYear: true,
		   dateFormat : "dd/mm/yy"
       });
										 $( "#meta_to<?php echo $field['pk_i_id']; ?>" ).datepicker({
           changeMonth: true,
           changeYear: true,
		   dateFormat : "dd/mm/yy"
       });
   									});
   							</script>
<?php
                        //}
                    }
                } else {
					$var = Session::newInstance()->_getForm('meta_'.$field['pk_i_id'], $value);
					//echo $var;
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    echo '<input id="meta_'.$field['s_slug'].'" type="text" name="meta['.$field['pk_i_id'].']" value="' .$var.'" ' ;
                    echo '/>' ;
                } 
				
			} } } }

			elseif($item_page = "item") {
					$var_date =  Session::newInstance()->_getForm('meta_'.$field['pk_i_id'], $value);
					$manda="";
					if($field['b_required']==1 ){ 
                    $manda="<span style='color:#f00'> * </span>";
					}
					if($field['e_type']=="DATE" ){ 
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].$manda.': </label>';
                    echo '<input readonly="readonly" id="place_swap_date'.$field['pk_i_id'].'" type="text" name="meta['.$field['pk_i_id'].']" value="' . osc_esc_html((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") . $var_date.'" ' ;
                    echo '/>' ; ?>
<script>
   									$(function() {
      									 $( "#place_swap_date<?php echo $field['pk_i_id']; ?>").datepicker({
           changeMonth: true,
           changeYear: true,
		   dateFormat : 'dd/mm/yy'
       });
   									});
   									</script>
<?php
                }
				
				elseif($field['e_type']=="NUMERIC") {
					$var =  Session::newInstance()->_getForm('meta_'.$field['pk_i_id'], $value);
					//echo $var;
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].$manda.': </label>';
                    if($field['pk_i_id'] == "24" || $field['pk_i_id'] == "129" || $field['pk_i_id'] == "135" || $field['pk_i_id'] == "74"){
                    	echo '<input onkeypress="return isNumberKey(event);" id="numeric'.$field['pk_i_id'].'" placeholder="&euro;" type="text" name="meta['.$field['pk_i_id'].']" value="' . osc_esc_html((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") .$var.'" ' ;
						echo '/>' ;
                    }else{
						echo '<input onkeypress="return isNumberKey(event);" id="numeric'.$field['pk_i_id'].'" type="text" name="meta['.$field['pk_i_id'].']" value="' . osc_esc_html((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") .$var.'" ' ;
						echo '/>' ;						
					}
					
					?>
<script type="text/javascript">
								
										function isNumberKey(evt){        
      								var charcode = document.getElementById('numeric<?php echo $field['pk_i_id']; ?>').value; 
         							var charCode = (evt.which) ? evt.which : event.keyCode;
                 					if (charCode != 46 && charCode > 31 
          								 && (charCode < 48 || charCode > 57)){
										alert('Er kunnen alleen numerieke waarden met een pun (.) voor decimalen ingevoerd worden punt');
            							return false; 
			
											 }
         								return true; alert('true');
      										}
								


function isInteger(s)
{
     var i;
     s = s.toString();
     for (i = 0; i < s.length; i++)
     {
        var c = s.charAt(i);
      if (isNaN(c))
          {
               alert("Please Input Number Only!");
			  	var input = document.getElementById("numeric<?php echo $field['pk_i_id'];  ?>").value;
			   //alert(input);
    			$('#numeric<?php echo $field['pk_i_id'];  ?>').val('') ;
               return false;
          }
     }
     return true;
}
</script>

<script type="text/javascript">
							$(document).ready(function() {
							 $("select.merk").attr('disabled', true);
													  //alert('here it is');
													  
													  })
						</script>

<?php 
					
					
                } 
				
				
				
				elseif($field['e_type']=="TEXTAREA") {
					$var_textarea =  Session::newInstance()->_getForm('meta_'.$field['pk_i_id'], $value);
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].$manda.': </label>';
                    echo '<textarea id="meta_' . $field['s_slug'] . '" name="meta['.$field['pk_i_id'].']" rows="10">' . ((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") .$var_textarea. '</textarea>' ;
                } 
				else if($field['e_type']=="DROPDOWN") {
					$var_drop =  Session::newInstance()->_getForm('meta_'.$field['pk_i_id'], $value);
					
					if($field['pk_i_id']==77) { 
					echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].$manda.': </label>';
                    if(isset($field) && isset($field['s_options'])) {
                        $options = explode(",", $field['s_options']);
                        
                        if(count($options)>0) {
                            echo '<select onchange="drop_merk();" class="merk_make" name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '">';
							echo '<option></option>';
                            foreach($options as $option) {
                                echo '<option value="'.osc_esc_html($option).'" '.(($var_drop==$option||$field['s_value']==$option)?'selected="selected"':'').'>'.$option.'</option>';
                            }
                            echo '</select>';
                        }
						
						
						?>
						
						
						<?php									//$aItem = CarModel::newInstance()->getById(1);
									//echo $aItem['s_name'];
									//print_r($aItem);
								?>
						
						
					<?php	
						
                    
					
					} }
					
					elseif($field['pk_i_id']==78){
					$val_merk =  Session::newInstance()->_getForm('meta_77', $value);
					$models = CarModel::newInstance()->getMedelsByBrand($val_merk);
					$var_drop_model =  Session::newInstance()->_getForm('meta_'.$field['pk_i_id'], $value);
					
					
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].$manda.': </label>';
                    if(isset($field) && isset($field['s_options'])) {
                        $options = explode(",", $field['s_options']);
                        if(count($options)>0) {
                            echo '<div id="row_model"><select class="merk" name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '">';
							if($var_drop_model != "" && $val_merk != "" && $var_drop_model != NULL ){
								foreach($models as $model){
									echo '<option value="'.osc_esc_html($model['s_model']).'" '.($var_drop_model==$model['s_model']?'selected="selected"':'').'>'.$model['s_model'].'</option>';
								}
						
							}else{
							
								echo '<option></option>';
                            foreach($options as $option) {
                                //echo '<option value="'.osc_esc_html($option).'" '.($field['s_value']==$option?'selected="selected"':'').'>'.$option.'</option>';
								echo '<option value="'.osc_esc_html($option).'" '.($field['s_value']==$option?'selected="selected"':'').'>'.$option.'</option>';
                            }
							}
                            echo '</select></div>';
                        }
					}
                    
						}
					else{
						$var_drop_other =  Session::newInstance()->_getForm('meta_'.$field['pk_i_id'], $value);
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].$manda.': </label>';
                    if(isset($field) && isset($field['s_options'])) {
                        $options = explode(",", $field['s_options']);
                        if(count($options)>0) {
                            echo '<select name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '">';
							echo'<option></option>';
                            foreach($options as $option) {
								
                                echo '<option value="'.osc_esc_html($option).'" '.(($var_drop_other==$option||$field['s_value']==$option)?'selected="selected"':'').'>'.$option.'</option>';
                            }
                            echo '</select>';
                        }
					}
                    }
                } else if($field['e_type']=="RADIO") {
					$var_radio =  Session::newInstance()->_getForm('meta_'.$field['pk_i_id'], $value);
					//echo $var_radio.'here is radio';
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].$manda.': </label>';
                    if(isset($field) && isset($field['s_options'])) {
                        $options = explode(",", $field['s_options']);
                        if(count($options)>0) {
                            echo '<ul class="radio">';
                            foreach($options as $key => $option) {
								//echo $option;
								
								if($var_radio != $option){
								echo '<li><input type="radio" name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '_'.$key.'" value="'.osc_esc_html($option).'" '.($field['s_value']==$option?'checked="checked"':'').' /><label for="meta_' . $field['s_slug'] . '_'.$key.'">'.$option.'</label></li>';
								
									}
								
								else{
                                echo '<li><input type="radio" name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] . '_'.$key.'" value="'.osc_esc_html($option).'" '.($field['s_value']==$option?'checked="checked"':'').' checked="checked" /><label for="meta_' . $field['s_slug'] . '_'.$key.'">'.$option.'</label></li>';
                            } }
                            echo '</ul>';
                        }
                    }
                } else if($field['e_type']=="CHECKBOX") {
					$var_checkbox =  Session::newInstance()->_getForm('meta_'.$field['pk_i_id'], $value);
					if($var_checkbox==1){
						echo '<input type="checkbox" name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] .'" value="1" 
					checked="checked"	/>';
					echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].$manda.': </label>';
					}
					else {
                    echo '<input type="checkbox" name="meta['.$field['pk_i_id'].']" id="meta_' . $field['s_slug'] .'" value="1" ' . ((isset($field) && isset($field["s_value"]) && ($field["s_value"]==1 || $var_checkbox) ==1) ?'checked="checked"':'') . ' />';
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
					}
                } 
				
				
				
				else {
					$var_text =  Session::newInstance()->_getForm('meta_'.$field['pk_i_id'], $value);
					//echo $var;
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].$manda.': </label>';
                    echo '<input id="meta_'.$field['s_slug'].'" type="text" name="meta['.$field['pk_i_id'].']" value="' . osc_esc_html((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") .$var_text.'" ' ;
                    echo '/>' ;
                } 
				
			
			}
			
			/* Admin Page of managing listing
			else{
					echo 'admin';
					if($field['e_type']=="DATE"){
                    echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    echo '<input id="admin_date" type="date" name="meta['.$field['pk_i_id'].']" value="' . osc_esc_html((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") . '" ' ;
                    echo '/>' ; ?> 
									
                                    
                                    <script>
   									$(function() {
      									 $( "#admin_date" ).datepicker();
   									});
   									</script>
					
					<?php
                }
				
				else{
					
					echo '<label for="meta_'.$field['s_slug'].'">'.$field['s_name'].': </label>';
                    echo '<input id="meta_'.$field['s_slug'].'" type="text" name="meta['.$field['pk_i_id'].']" value="' . osc_esc_html((isset($field) && isset($field["s_value"])) ? $field["s_value"] : "") . '" ' ;
                    echo '/>' ;
			}
			
			} */
		}

        static public function meta_fields_input($catId = null, $itemId = null) {
            $fields = Field::newInstance()->findByCategoryItem($catId, $itemId);
            if(count($fields)>0) {
                echo '<div class="meta_list">';
                foreach($fields as $field) {
                    echo '<div class="meta">';
                        FieldForm::meta($field);
                    echo '</div>';
                }
                echo '</div>';
            }
        }
        
    static public function region_select($regions = null, $item = null) {
            // if have input text instead of select
            if( Session::newInstance()->_getForm('region') != ''){
                $regions = null;
            } else {
                if($regions==null) { $regions = array(); };
            }

            if($item==null) { $item = osc_item(); };

            if( count($regions) >= 1 ) {
                if( Session::newInstance()->_getForm('regionId') != "" ) {
                    $item['fk_i_region_id'] = Session::newInstance()->_getForm('regionId');
                }
                if( Session::newInstance()->_getForm('countryId') != "" ) {
                    $regions = Region::newInstance()->findByCountry(Session::newInstance()->_getForm('countryId')) ;
                }
                parent::generic_select('regionId', $regions, 'pk_i_id', 's_name', __('Select a region...'), (isset($item['fk_i_region_id'])) ? $item['fk_i_region_id'] : null) ;
                return true ;
            } else {
                if( Session::newInstance()->_getForm('region') != "" ) {
                    $item['s_region'] = Session::newInstance()->_getForm('region');
                }
                parent::generic_input_text('region', (isset($item['s_region'])) ? $item['s_region'] : null) ;
                return true ;
            }
        }
	
	
	}

?>