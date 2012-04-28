$(document).ready(function(){
 $(".tabs a").click(function() {
 $(".tabs a").each(function(){
 $(this).removeClass("active");
 });
 $(this).addClass("active");
 });
});