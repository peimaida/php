// JavaScript Document
<!-- 显示窗口事件 -->

$(function(){
$('aside.slide-wrapper').on('touchstart', 'li', function(e){
$(this).addClass('current').siblings('li').removeClass('current');
});

$('a.contact').on('click', function(e){
var wh = $(window).height();
$('div.slide-contact').css('height', wh).show();
$('aside.slide-wrapper').css('height', wh).addClass('moved');
$('html').css('position','fixed').css('overflow','hidden');
});

$('div.slide-contact').on('click', function(){
$('div.slide-contact').hide();
$('aside.slide-wrapper').removeClass('moved');
$('html').css('position','relative').css('overflow','auto');
});
});

$(window).resize(function() {
  var wh = $(window).height();
$('div.slide-contact').css('height', wh);
$('aside.slide-wrapper').css('height', wh);
});



function setSession(index){
	sessionStorage.top = index;
}

function searchLink(){
	ga('send','event','head','click','search');
	window.location.href="http://cn.misumi-ec.com/vona2/result/?Keyword="+$(".input_s").val(); 
}

// Google Analytics
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-6311415-1', 'auto');
ga('send', 'pageview');
// Google Analytics


// Baidu Analytics
var _hmt = _hmt || [];
(function() {
	var hm = document.createElement("script");
	hm.src = "//hm.baidu.com/hm.js?c86e2c4bb4ad01427261d9484e89bf8b";
	var s = document.getElementsByTagName("script")[0];
	s.parentNode.insertBefore(hm, s);
})();
// Baidu Analytics