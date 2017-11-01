console = window.console || {
	dir: new Function(),
	log: new Function()
};
var active = 0,
	as = document.getElementById('pagenavi').getElementsByTagName('a');
for(var i = 0; i < as.length; i++) {
	(function() {
		var j = i;
		as[i].onclick = function() {
			t4.slide(j);
			return false;
		}
	})();
}
//调用插件
var t4 = new TouchSlider('slider', {
	speed: 1000,
	direction: 0,
	interval: 3000,
	fullsize: true
});
t4.on('before', function(m, n) {
	as[m].className = '';
	as[n].className = 'active';
})

