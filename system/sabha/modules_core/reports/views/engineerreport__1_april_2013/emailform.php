<span id="loading1"></span>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
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
<div style="width: 450px;">
<form onsubmit="return false">
<table width="100%">
	<col width="15%" />
	<col />
	<tr>
		<td><label>Email To</label></td>
		<td><textarea name="email_to" id="email_to" class="text-input"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: right"><input type="button"
			name="sendmail" id="sendmail" value="Send" class="button" size="50"
			onclick="sendemail();" /></td>
	</tr>
</table>
</form>
</div>
