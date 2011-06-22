$(document).ready(function() {
	
	// Expand Panel
	$("#up").click(function(){
		$("div#navmiddle").hide("slow");
	
	});	
	
	// Collapse Panel
	$("#down").click(function(){
		$("div#navmiddle").show("slow");	
	});		
		
	// Switch buttons from "Log In | Register" to "Close Panel" on click
	$("#toggle a").click(function () {
		$("#toggle a").toggle();
	});				
		
});