<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_HTML extends MY_Model {

	
	/**
	 * @param	string	The value of the option
	 * @param	string	The text for the option
	 * @param	string	The returned object property name for the value
	 * @param	string	The returned object property name for the text
	 * @return	object
	 */
	function option( $value, $text='', $value_name='value', $text_name='text', $disable=false )
	{
		$obj = new stdClass;
		$obj->$value_name	= $value;
		$obj->$text_name	= trim( $text ) ? $text : $value;
		return $obj;		
	}
	/**
	 * Generates just the option tags for an HTML select list
	 *
	 * @param	array	An array of objects
	 * @param	string	The name of the object variable for the option value
	 * @param	string	The name of the object variable for the option text
	 * @param	mixed	The key that is selected (accepts an array or a string)
	 * @returns	string	HTML for the select list
	 */
	public function options( $arr, $key = 'value', $text = 'text', $selected = null, $translate = false ){
		$html = '';
		foreach ($arr as $i => $option){
			$element =& $arr[$i]; // since current doesn't return a reference, need to do this
			$isArray = is_array( $element );
			$extra	 = '';
			if ($isArray){
				$k 		= $element[$key];
				$t	 	= $element[$text];
				$id 	= ( isset( $element['id'] ) ? $element['id'] : null );
				if(isset($element['disable']) && $element['disable']) {
					$extra .= ' disabled="disabled"';
				}
			}else{
				$k 		= $element->$key;
				$t	 	= $element->$text;
				$id 	= ( isset( $element->id ) ? $element->id : null );
				if(isset( $element->disable ) && $element->disable) {
					$extra .= ' disabled="disabled"';
				}
			}

			// This is real dirty, open to suggestions,
			// barring doing a propper object to handle it
			if ($k === '<OPTGROUP>') {
				$html .= '<optgroup label="' . $t . '">';
			} else if ($k === '</OPTGROUP>') {
				$html .= '</optgroup>';
			} else {
				//if no string after hypen - take hypen out
				$splitText = explode( ' - ', $t, 2 );
				$t = $splitText[0];
				if(isset($splitText[1])){ $t .= ' - '. $splitText[1]; }

				//$extra = '';
				//$extra .= $id ? ' id="' . $arr[$i]->id . '"' : '';
				if (is_array( $selected )){
					foreach ($selected as $val){
						$k2 = is_object( $val ) ? $val->$key : $val;
						if ($k == $k2){
							$extra .= ' selected="selected"';
							break;
						}
					}
				} else {
					$extra .= ( (string)$k == (string)$selected  ? ' selected="selected"' : '' );
				}

				//if flag translate text
				if ($translate) {
					$t = $this->lang->line($t);
				}

				// ensure ampersands are encoded
				//$k = JFilterOutput::ampReplace($k);
				//$t = JFilterOutput::ampReplace($t);

				$html .= '<option value="'. $k .'" '. $extra .'>' . $t . '</option>';
			}
		}
		return $html;
	}
/**
	 * Generates an HTML select list
	 *
	 * @param	array	An array of objects
	 * @param	string	The value of the HTML name attribute
	 * @param	string	Additional HTML attributes for the <select> tag
	 * @param	string	The name of the object variable for the option value
	 * @param	string	The name of the object variable for the option text
	 * @param	mixed	The key that is selected (accepts an array or a string)
	 * @returns	string	HTML for the select list
	 */
	public function genericlist( $arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false )
	{
		if ( is_array( $arr ) ) {
			reset( $arr );
		}

		if (is_array($attribs)) {
			$attribs = Mdl_HTML::toString($attribs);
		 }

		$id = $name;

		if ( $idtag ) {
			$id = $idtag;
		}

		$id		= str_replace('[','',$id);
		$id		= str_replace(']','',$id);

		$html	= '<select name="'. $name .'" id="'. $id .'" '. $attribs .'>';
		$html	.= Mdl_HTML::Options( $arr, $key, $text, $selected, $translate );
		$html	.= '</select>';

		return $html;
	}

	public function toString( $array = null, $inner_glue = '=', $outer_glue = ' ', $keepOuterKey = false )
	{
		$output = array();

		if (is_array($array))
		{
			foreach ($array as $key => $item)
			{
				if (is_array ($item))
				{
					if ($keepOuterKey) {
						$output[] = $key;
					}
					// This is value is an array, go and do it again!
					$output[] = Mdl_HTML::toString( $item, $inner_glue, $outer_glue, $keepOuterKey);
				}
				else {
					$output[] = $key.$inner_glue.'"'.$item.'"';
				}
			}
		}

		return implode( $outer_glue, $output);
	}
}

?>