$(document).ready(function(){
 $(".medias a").click(function() {
 $(".medias a").each(function(){
 $(this).removeClass("active");
 });
 $(this).addClass("active");
 });
});