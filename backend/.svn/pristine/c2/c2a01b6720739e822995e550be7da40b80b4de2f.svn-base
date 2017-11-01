var hori = false;
$(document).ready(function(){
	difStyle();
})

$(window).resize(function(){
	difStyle();
})

function difStyle(){
	var wid = window.innerWidth;
	var hei = window.innerHeight;
	if(wid/hei < 0.7){
		if(hori == false){
			$(".container").removeClass("all").addClass("part");
			$("html").css({"height":"100%","overflow":"hidden","position":"fixed"});
		}else{
			$(".container").removeClass("part").addClass("all");
			$("html").css({"height":"auto","overflow":"auto","position":"static"});
		}		

	}else{
		$(".container").removeClass("part").addClass("all");
		$("html").css({"height":"auto","overflow":"auto","position":"static"});
		hori = true;
	}	
}

function showMore(){
	$(".container").removeClass("part").addClass("all");
	$("html").css({"height":"auto","overflow":"auto","position":"static"});
	hori = true;
}

var startX, startY, moveEndX, moveEndY, X, Y;
$("body").on("touchstart", function(e) {
	startX = e.originalEvent.changedTouches[0].pageX, startY = e.originalEvent.changedTouches[0].pageY;
});
$("body").on("touchmove", function(e) {
	moveEndX = e.originalEvent.changedTouches[0].pageX, moveEndY = e.originalEvent.changedTouches[0].pageY, X = moveEndX - startX, Y = moveEndY - startY;
	
});

$("body").on("touchend",function(e){
	if(Math.abs(Y) > Math.abs(X) && Y < 0){
		showMore();
	}
})