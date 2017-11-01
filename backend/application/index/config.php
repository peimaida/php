<?php
//配置文件
return [
	'object_name' => 'Misumi Mobile Site',
	'template'  =>  [
	    'layout_on'     =>  true,
	    'layout_name'   =>  'layout',
	],
	'tags'  =>  [//设置活动标签
	    '1'	=> '通知',
	    '2' => '公告',
	],
	'qr_scene'  =>  [//设置qr使用场景
	    '1'	=> 'box',//纸箱
	    '2'	=> 'book',//QRbook
	    '3'	=> 'ecweb',//web
		'4'	=> 'bus',//公交车
		'5'	=> 'station',//车站
		'6'	=> 'fileholder',//文件夹
		'7'	=> 'sem',//SEM广告
	],
	'form_scene' => [//设置表单使用场景
		'1'	=> 'box',//纸箱
	],
	'template_type'  =>  [//设置模板种类内容
	    '1'	=> 'series',
	    '2' => 'category',
	    '3' => 'part',
	],
	'total_num'  => 5000,//设置最大上传数据量
	'shell_path'  => '/var/www/html/mobile_site/backend/public/tif.sh',//shell脚本路径
	'scan_shell_path'  => '/var/www/html/mobile_site/backend/runtime/log/download/scanlog.sh',//shell脚本路径
	'code_url' => 'http://cn.m.stg.misumi-ec.com:8080/Misumi/scan.html?',//二维码URL
	'system_toggle'  => false,//文件系统路径开关，true:linux，false:windows
	'top_event_num'=>3,//首页活动列表最多展示数量
	'top_post_num'=>2,//首页新闻列表最多展示数量
	'top_banner_num'=>3,//首页banner列表最多展示数量
	'detail_event_num'=>4,//活动详情页相关活动最多展示数量
	'detail_post_num'=>4,//新闻详情页相关新闻最多展示数量
	//报错页面配置
    'http_exception_template'    =>  [
    // 定义404错误的重定向页面地址
    404 =>  APP_PATH.'404.html',
    ],
    //数据库配置(用于统计报表)
	'db_config_stats' => [
	    // 数据库类型
	    'type'        => 'mysql',
	    // 服务器地址
	    'hostname'    => '127.0.0.1',
	    // 数据库名
	    'database'    => 'app_share',
	    // 数据库用户名
	    'username'    => 'root',
	    // 数据库密码
	    'password'    => 'root',
	    // 数据库编码默认采用utf8
	    'charset'     => 'utf8',
	    // 数据库表前缀
	    'prefix'      => 'app_',
	],
	//数据库配置(用于test)
	'db_config_test' => [
	    // 数据库类型
	    'type'        => 'mysql',
	    // 服务器地址
	    'hostname'    => '127.0.0.1',
	    // 数据库名
	    'database'    => 'test',
	    // 数据库用户名
	    'username'    => 'root',
	    // 数据库密码
	    'password'    => 'root',
	    // 数据库编码默认采用utf8
	    'charset'     => 'utf8',
	    // 数据库表前缀
	    'prefix'      => '',
	],
	//排除出统计的账号
	'except_accounts' => [
        '000000','000001','000002','000003','00001I','00001J','0001HT','0001HU','0001HV','0001HW','000GGW','000GGZ','000GRM','000IND','000INW','000MYS','000MYW','000S2W','000SGP','000SH2','000THA','00MIND','00MINW','00MKOR','00MKOW','00MSGP','00MSGW','00MTHA','00MTHW','00MTIW','00MTWW','00MUSA','00MUSW','00S001','00S002','00S003','00S004','00S005','00S006','00S011','00S012','00S013','00S014','00S015','00S016','00S021','00S098','00S099','00S121','00S901','00S902','00S903','00S906','00S911','00S912','00S913','00S916','00T000','00T001','00T002','00T003','00T004','00T005','00TEST','00VKOR','016248','016249','017728','017730','019442','019443','021556','021558','022599','022601','022603','022605','022899','029761','0ACS40','0EST01','0EST02','0EST03','0JCSM7','0JCSU3','0KCSD7','0LCSN6','0MCS77','0MJJ01','0MJS04','0QCSB7','0SCGH9','0SCSI1','0TCSB7','0YCGF9','0YCGM9','0YCGT4','0YCSL8','0ZCGA4','0ZCSF7','102567','104949','104971','113553','113976','125473','125695','129954','129966','130475','G0AIOQ','G0AIOT','G0AIOX','G0FCNQ','G0FCNT','G0FCNX','G0TYOQ','G0TYOT','G0TYOX','H0AIOQ','H0AIOT','H0AIOX','H0FCNQ','H0FCNT','H0FCNX','H0TYOQ','H0TYOT','H0TYOX','K0AIOQ','K0AIOT','K0AIOX','K0FCNQ','K0FCNT','K0FCNX','K0TYOQ','K0TYOT','K0TYOX','M0AIOQ','M0AIOT','M0AIOX','M0FCNQ','M0FCNT','M0FCNX','M0TYOQ','M0TYOT','M0TYOX','MJJ011','MJJ012','S0AIOQ','S0AIOT','S0AIOX','S0FCNQ','S0FCNT','S0FCNX','S0TYOQ','S0TYOT','S0TYOX','T0AIOQ','T0AIOT','T0AIOX','T0FCNQ','T0FCNT','T0FCNX','T0TYOQ','T0TYOT','T0TYOX','U0AIOQ','U0AIOT','U0AIOX','U0FCNQ','U0FCNT','U0FCNX','U0TYOQ','U0TYOT','U0TYOX','Z00999','Z10105','004547','00T021','022040','0LTO10','TEST99','WOSCUS','00FKOR','016382','W03U3G','W03U7E','W03VQY','W03W51','W03YY1','W040CP','W042F8','W042I0','W03GBK','W03GU7','W03H1I','W03HMJ','W03IYC','W03KAF','W03KI6','W03KMH','W03LJ8','W03N15','W03NGR','W03PAD','W03PSZ','W03SCS','DEPO08','DEPO06','DEPO09','DEPO07','J0FCNX','DEPO05','DEPO10','DEPO04','DEPO03','J0AIOX','J0TYOX','W043KC','W0449B','W02BFR','W02CTW','W02FHW','W02JJJ','W02K9J','W02K9S','W02L17','W02QOY','W02R32','W045P7','W045V0','W045WK','W045XI','W046XX','W04805','W02TW4','W02VJ5','W04ENJ','W04FA0','W04HRJ','W04I7N','W04IDT','W04LIQ','W04LTF','W04LW7','W04LW6','W04MHU','W04NEF','W04NTX','W04OI4','W04OQI','W04PEH','W04QFC','W04R0S','W04R7N','W04RI2','W04RKY','W04RP1','W04S51','W04T7R','W04TB1','W04UKW','W031Y3','W0320N','W032FY','W034BJ','W03ANI','W03MO5','W02E9K','W03XXG','W03YOI','W04467','W04FO2','W04J5D','W04L54','W04LF6','W04PFH','W04TSR','W00000','W0002V','W00001','W00002','W00006','W0000V','W001XZ','W003II','W003U0','W003UF','W005M9','W005YJ','W005YL','W005YN','W005YV','W005ZZ','W0060N','W0060L','W0060K','W00600','W00601','W006QW','W007N5','W0091U','W00B3N','W00C2H','W00C7L','W00IS1','W00M05','W00NF5','W00OII','W00OKS','W00VLH','W00ZD6','W00ZTT','W013XG','W0169L','W01B2K','W01B2L','W01BQI','W01IJA','W01P55','W01SX4','W01ZGH','W021A2','W021AC','W023AE','W02574','DEPO01','0EST04','W00007','136656','W0000W','TEST03','TEST01','147307','DEPO02','W04V82','W04UYN','W04W4M','W04W52','W04WOP','W04WY1','W0000U','W0000H','W009VV','W0000U','W03HMJ','0XJS43','W03H1I'
    ],

];