function updateOutput() {
	$("iframe").contents().find("html").html("<html><head><style type='text/css'>" + $("#cssPanel").val() + "</style></head><body>" + $("#htmlPanel").val() + "</body></html>") ; 

	//need to run javascript separately in order for it to run in the iframe 
	document.getElementById("outputPanel").contentWindow.eval($("#javascriptPanel").val());
	//can check that the javascript is running in the iframe and not the page by 
	// <textarea id="javascriptPanel" class="panel hidden">document.getElementById("paragraph").innerHTML = "Hello Rob!";</textarea>
	
}
//changes color to grey on hover
$(".toggleButton").hover(function() {
	$(this).addClass("highlightedButton"); 
}, function() {
	$(this).removeClass("highlightedButton");
});

//changes color/show and hide panel when clicked
$(".toggleButton").click(function() {
	$(this).toggleClass("active");
	//to remove the grey when you click
	$(this).removeClass("highlightedButton");
	//get panelId in order to show/hide 
	var panelId = $(this).attr("id") + "Panel"; 
	$("#" + panelId).toggleClass("hidden");
	//change width of each panel depending on how many are toggled 
	var numActive = 4 - $(".hidden").length; 
	$(".panel").width(($(window).width()/numActive) - 10);
});

//change height to cover height of the screen 
$(".panel").height($(window).height() - $("#header").height() - 15); 

//change initial width of html and output panels to fit screen
$(".panel").width($(window).width()/2 - 10);

$("iframe").contents().find("html").html($("#htmlPanel").val()); 
//change content of iframe right as html content changes 
updateOutput(); 
$("textarea").on('change keyup paste', function() {
	updateOutput(); 
});