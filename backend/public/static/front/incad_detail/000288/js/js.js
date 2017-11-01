$(document).ready(function(){
  var $address,add = 1;
  $(".select").bind("click",function(){
    $address = $(this).find(".case_title .click img").attr("src");
    $(".select").find("ul").slideUp("slow");
    $(".select").find(".case_title .click img").attr("src","../../../../public/static/front/incad_detail/images/btn_bottom.png");
    $(this).find("ul").stop(true,false).slideToggle("slow");
    if($address == "../../../../public/static/front/incad_detail/images/btn_bottom.png"){
      $(this).find(".case_title .click img").attr("src","../../../../public/static/front/incad_detail/images/btn_top.png");
      var label = 'more'+($(this).index()+1);
      ga('send','event','inCADdetail','click',label);
    }else{
      $(this).find(".case_title .click img").attr("src","../../../../public/static/front/incad_detail/images/btn_bottom.png");
    }
  });
  // 加载
  // 修改： Cavalier
	var liS = $(".list_information").size();

	function load() {
		for(var i = 0; i < (add * 3); i++) {
			$(".list_information").eq(i).css("display", "block");
		}
	}
	load();
	$(".parts_list ul .list_btn .more_btn").bind("click", function() {
		if((liS - 7) > add) {
			add++;
			load();
      ga('send','event','inCADdetail','click','partslist');
		} else {
			$(this).css({
				'display': 'none'
			});
		}
	});
  // scrollTop
  $(".return_top").bind("click",function(){
    $(window).scrollTop(0);
  });

  $(window).scroll(function(){
    var _window_scroll = $(this).scrollTop();
    if(_window_scroll>=300){
      $(".return_top").slideDown('slow');
    }else {
      $(".return_top").slideUp('fast');
    }
  });
});
