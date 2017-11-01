var topCate = [
{"name":"工厂自动化零件","iconURL":"../../../public/static/front/images/topC_1.png","bigURL":"../../../public/static/front/images/Cate1.png","top":"mech","index":1,"chosenIcon":"../../../public/static/front/images/topCw_1.png"},
{"name":"螺钉/螺栓/垫圈/螺帽","iconURL":"../../../public/static/front/images/topC_2.png","bigURL":"../../../public/static/front/images/Cate2.png","top":"mech_screw","index":2,"chosenIcon":"../../../public/static/front/images/topCw_2.png"},
{"name":"工业用材料","iconURL":"../../../public/static/front/images/topC_3.png","bigURL":"../../../public/static/front/images/Cate3.png","top":"mech_material","index":3,"chosenIcon":"../../../public/static/front/images/topCw_3.png"},
{"name":"接线","iconURL":"../../../public/static/front/images/topC_4.png","bigURL":"../../../public/static/front/images/Cate4.png","top":"el_wire","index":4,"chosenIcon":"../../../public/static/front/images/topCw_4.png"},
{"name":"控制","iconURL":"../../../public/static/front/images/topC_5.png","bigURL":"../../../public/static/front/images/Cate5.png","top":"el_control","index":5,"chosenIcon":"../../../public/static/front/images/topCw_5.png"},
{"name":"切削刀具","iconURL":"../../../public/static/front/images/topC_6.png","bigURL":"../../../public/static/front/images/Cate6.png","top":"fs_machining","index":6,"chosenIcon":"../../../public/static/front/images/topCw_6.png"},
{"name":"生产加工用品","iconURL":"../../../public/static/front/images/topC_7.png","bigURL":"../../../public/static/front/images/Cate7.png","top":"fs_processing","index":7,"chosenIcon":"../../../public/static/front/images/topCw_7.png"},
{"name":"捆包用品/物流保管用品","iconURL":"../../../public/static/front/images/topC_8.png","bigURL":"../../../public/static/front/images/Cate8.png","top":"fs_logistics","index":8,"chosenIcon":"../../../public/static/front/images/topCw_8.png"},
{"name":"安全用品/办公用品","iconURL":"../../../public/static/front/images/topC_9.png","bigURL":"../../../public/static/front/images/Cate9.png","top":"fs_health","index":9,"chosenIcon":"../../../public/static/front/images/topCw_9.png"},
{"name":"研究管理用品","iconURL":"../../../public/static/front/images/topC_10.png","bigURL":"../../../public/static/front/images/Cate10.png","top":"fs_lab","index":10,"chosenIcon":"../../../public/static/front/images/topCw_10.png"},
{"name":"冲压模具用零件","iconURL":"../../../public/static/front/images/topC_11.png","bigURL":"../../../public/static/front/images/Cate11.png","top":"press","index":11,"chosenIcon":"../../../public/static/front/images/topCw_11.png"},
{"name":"塑料模具用零件","iconURL":"../../../public/static/front/images/topC_12.png","bigURL":"../../../public/static/front/images/Cate12.png","top":"mold","index":12,"chosenIcon":"../../../public/static/front/images/topCw_12.png"}
];

$(document).ready(function(){
	var wid = window.innerWidth;
	var cateNo = sessionStorage.top?sessionStorage.top:1;
	geneTop(cateNo);
	style();
	if(cateNo>6){
		$(".top-container .cate-tops").css("left",-wid);
	}
	showBtn();

	var startX, startY, moveEndX, moveEndY, X, Y;
	$(".top-container").on("touchstart", function(e) {	  
		e.stopPropagation();     
		startX = e.originalEvent.changedTouches[0].pageX, startY = e.originalEvent.changedTouches [0].pageY;

	});

	$(".top-container").on("touchmove", function(e) {
		e.stopPropagation();
		e.preventDefault();
		moveEndX = e.originalEvent.changedTouches [0].pageX, moveEndY = e.originalEvent.changedTouches [0].pageY, X = moveEndX - startX, Y = moveEndY - startY;
	});

	$(".top-container").on("touchend", function(e) {	        
		e.stopPropagation();
		if (Math.abs(X) > Math.abs(Y) && X > 0) {
			$(".top-container .cate-tops").animate({left:0},function(){
				showBtn();
				ga('send','event','categorytop','slide','catetoparea');
			});
		} else if (Math.abs(X) > Math.abs(Y) && X < 0) {
			var wid = window.innerWidth;
			$(".top-container .cate-tops").animate({left:-wid},function(){
				showBtn();
				ga('send','event','categorytop','slide','catetoparea');
			});
		}
		
	});

	$(".cate-top-item").click(function(){
		changeCate($(this).attr("name"));
		var label = $(this).attr("name")+'Top';
		ga('send','event','categorytop','click',label);
	})
});

$(window).resize(function(){
	style();
})

function style(){
	var wid = window.innerWidth;
	$(".top-container .cate-tops li").width(($(".top-container .cate-tops").width()-4)/6);
	$(".top-container").height($(".top-container .cate-tops li").outerHeight()*2+10);
	$(".list-content .cate-item").width((wid-3)/4);
}


function geneTop(cNo){
	var topAb = checkAb(cNo);
	var topName,topIcon;
	for(var j=0;j<topCate.length;j++){
		topName = topCate[j].name;
		topIcon = topCate[j].iconURL;
		choseIcon = topCate[j].chosenIcon;
		cateAB = topCate[j].top;

		if(j<6){
			if(topCate[j].top == topAb){
				$(".cate-top6").append('<li class="cate-top-item on" name="'+cateAB+'" onclick="changeCate(\''+cateAB+'\')"><img src="'+topIcon+'" class="top-icon unchosen" alt="'+topName+'" ><img src="'+choseIcon+'" class="top-icon chosen" alt="'+topName+'" ><p><span class="top-txt">'+topName+'</span></p></li>');

			}else{
				$(".cate-top6").append('<li class="cate-top-item" name="'+cateAB+'" onclick="changeCate(\''+cateAB+'\')"><img src="'+topIcon+'" class="top-icon unchosen" alt="'+topName+'" ><img src="'+choseIcon+'" class="top-icon chosen" alt="'+topName+'" ><p><span class="top-txt">'+topName+'</span></p></li>');
			}
		}else{
			if(topCate[j].top == topAb){
				$(".cate-top12").append('<li class="cate-top-item on" name="'+cateAB+'" onclick="changeCate(\''+cateAB+'\')"><img src="'+topIcon+'" class="top-icon unchosen" alt="'+topName+'" ><img src="'+choseIcon+'" class="top-icon chosen" alt="'+topName+'" ><p><span class="top-txt">'+topName+'</span></p></li>');
			}else{
				$(".cate-top12").append('<li class="cate-top-item" name="'+cateAB+'" onclick="changeCate(\''+cateAB+'\')"><img src="'+topIcon+'" class="top-icon unchosen" alt="'+topName+'" ><img src="'+choseIcon+'" class="top-icon chosen" alt="'+topName+'" ><p><span class="top-txt">'+topName+'</span></p></li>');
			}
		}
	}

	
	updateTitle(topAb);
	updateCate(topAb);

	function checkAb(index){
		for(var n=0;n<topCate.length;n++){
			if(topCate[n].index == index){
				return topCate[n].top;
			}
		}
	}

}

function updateTitle(top){
	var selectName,selectIcon,selectImg;
	for(var j=0;j<topCate.length;j++){
		if(topCate[j].top == top){
			selectName = topCate[j].name;
			selectIcon = topCate[j].iconURL;
			selectImg = topCate[j].bigURL;
		}
	}
	$(".list-title").html('<h2 class="list-title-cont"><img src="'+selectIcon+'" class="list-title-img"><span class="list-title-txt">'+selectName+'</span></h2><img src="'+selectImg+'" class="top-img" alt="'+selectName+'" >');
}

function changeCate(top){
	$(".cate-top-item[name="+top+"]").addClass("on").siblings().removeClass("on").parents().siblings().children().removeClass("on");

	updateTitle(top);
	updateCate(top);
	style();
}

function updateCate(top){
	$(".list-content").empty();
	var item;
	for(var i=0;i<category.length;i++){
		item = category[i];
		if(item.top == top){
			$(".list-content").append('<a href="http://'+item.link+'" class="cate-item" onclick="ga(\'send\',\'event\',\'categorytop\',\'click\',\''+(item.top+"1")+'\');"><img src="'+item.imgURL+'" class="cate-img" alt="'+item.name+'"><p><span>'+item.name+'</span></p></a>');
		}
	}
}


function showBtn(){
	if(parseInt($(".top-container .cate-tops").css("left"))==0){
		$(".top-container").addClass("one").removeClass("two");
	}else{
		$(".top-container").addClass("two").removeClass("one");
	}
}

