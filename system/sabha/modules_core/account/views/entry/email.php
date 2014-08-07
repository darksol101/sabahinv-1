<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style type="text/css">
#error-box {
	border:solid 1px #C34A2C;
	background:#FFBABA;
	color:#222222;
	padding:0 10px 0 10px;
	margin:0 0 10px 0;
	text-align:left;
}

#message-box {
	border:solid 1px #FFEC8B;
	background:#FFF8C6;
	color:#222222;
	padding:0 10px 0 10px;
	margin:0 0 10px 0;
	text-align:left;
}
</style>
<?php
$array = array();
foreach($email_tags as $row){
	$array[] = '"'.$row->tag_text.'"';
}
$str = (count($array)>0)?implode(",",$array):'';
?>
<script type="text/javascript">
	$(function() {
		var availableTags = [
			<?php echo $str;?>
		];
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}

		$( "#email_to" )
			// don't navigate away from the field on tab when selecting an item
			.bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
						$( this ).data( "autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			})
			.autocomplete({
				minLength: 1,
				source: function( request, response ) {
					// delegate back to autocomplete, but extract the last term
					response( $.ui.autocomplete.filter(
						availableTags, extractLast( request.term ) ) );
				},
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {
					var terms = split( this.value );
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					terms.push( "" );
					this.value = terms.join( ", " );
					return false;
				}
			});
	});	
</script>
<?php

	if (isset($error) && $error)
	{
		echo "<div id=\"error-box\">";
		echo "<ul>";
		echo ($error);
		echo "</ul>";
		echo "</div>";
	}

	if (isset($message) && $message)
	{
		echo "<div id=\"message-box\">";
		echo "<ul>";
		echo ($message);
		echo "</ul>";
		echo "</div>";
	}

	//echo form_open('account/entry/email/' . $current_entry_type['label'] . "/" . $entry_id);
	echo '<form onsubmit="return false">';
	echo "Emailing " .  $current_entry_type['name'] . " Entry No. " . $entry_number . "<br />";

	echo "<p>";
	echo form_label('Email to', 'email_to');
	echo "<br />";
	echo form_textarea($email_to);
	echo "</p>";

	echo "<p>";
	echo form_submit('button', 'Send Email','onclick="sendemail()" class="button"');
	echo "</p>";

	echo form_close();

?>