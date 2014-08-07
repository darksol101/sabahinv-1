$(document).ready(function() {
	$('#main-nav li a').click(function() {
		var lnk = $('#main-nav li a').attr('href');
		if(lnk){
			window.location=this;
		}
	});
});