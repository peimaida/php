<?php
//配置文件
return [
	'object_name' => 'Misumi Mobile Site',
	'auth_password_check' => true, //动态密码校验
	'auth_expired_check'  => true, //动态过期时间校验
	'auth_expired_time'		  => 3600*24*7, //权限过期时间设置，默认1周,请按需要自行设置
	'comment_toggle'  => false, //评论总开关，默认打开，但在文章中设置评论开关可覆盖该设置，新建文章时，默认值沿用总开关值
	'template'  =>  [
	    'layout_on'     =>  true,
	    'layout_name'   =>  'layout',
	],
	'tags'  =>  [//设置活动标签
	    '0'	=> '通知',
	    '1' => '公告',
	],
	'authors'  =>  [//设置作者
	    '0'	=> '米思米中国',
	],
	'IP_limit_toggle'  => true,//限制IP访问开关,上线时需打开
	'IP_limit' => [//仅允许以下IP访问
		'1'=>'112.65.162.206',
		'2'=>'::1',
		'3'=>'180.166.144.29',
		'4'=>'127.0.0.1',
	],
	'system_toggle'  => false,//文件系统路径开关，true:linux，false:windows
	'tables'  =>  [//定时更新的表
	    '0'	=> 'backend_posts',
	    '1' => 'backend_events',
	    '2' => 'backend_banners',
	],
	'per_page'  => 10,//后台分页每页数量
	/*
	//报错页面配置
    'http_exception_template'    =>  [
    // 定义404错误的重定向页面地址
    404 =>  APP_PATH.'404.html',
    ],
    'exception_tmpl'         => APP_PATH.'404.html',*/
];