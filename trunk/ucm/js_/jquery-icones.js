$(document).ready(function(){

	$(".medias a").hover(function() {
	  $(this).next("em").show();
	}, function() {
	  $(this).next("em").hide();
	});
	
});
