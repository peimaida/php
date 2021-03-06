<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

//APP下载统计
Route::rule('qr/:type','index/code/download');
//表单统计
Route::rule('form/:type','index/code/form');

return [
     // 全局变量规则定义
    '__pattern__'         => [
        'name'  => '\w+',
        'id'    => '\d+',
        'year'  => '\d{4}',
        'month' => '\d{2}',
    ],

    //路由规则定义

    //以下为前台路由
    //维护页面
    'maintenance/'                               => 'index/maintenance/index',
    'maintenance/online'                               => 'index/maintenance/online',
    //News列表
    'news/'                               => 'index/news/index',
    'index/news/detail/:id'                     => 'index/news/detail',
    //Topic列表
    'topic/'                               => 'index/topic/index',
    'index/topic/detail/:id'                     => 'index/topic/detail',
    //商品分类
    'category/'                               => 'index/category/index',
    //inCAD
    'incad/'                               => 'index/incad/index',
    'incad/detail/000288'                               => 'index/incad/page_000288',
    'incad/download/000288'                               => 'index/incad/download_000288',
    'incad/detail/000223'                               => 'index/incad/page_000223',
    'incad/download/000223'                               => 'index/incad/download_000223',
    'incad/detail/000016'                               => 'index/incad/page_000016',
    'incad/download/000016'                               => 'index/incad/download_000016',
    //关注我们
    'follow/'                               => 'index/follow/index',
    //联系我们
    'contact/'                               => 'index/contact/index',
    //QR生成
    'qr'                               => 'index/code/index',
    //报表统计
    'stats'                               => 'index/stats/index',
    //测试
    'test'                               => 'index/test/index',
    //alipay
    'alipay'=>'index/alipay/index',
    'alipay/pay'=>'index/alipay/pay',
    'alipay/dopay'=>'index/alipay/dopay',
    'alipay/query'=>'index/alipay/query',
    'alipay/doquery'=>'index/alipay/doquery',

    //以下为后台路由
    /*登录模块*/
    'admin/login/'                               => 'admin/index/login',
    'admin/login_action/'                        => 'admin/index/login_action',
    'admin/lost_password/'                       => 'admin/index/lost_password',
    'admin/logout/'                              => 'admin/index/logout',
    /*管理员模块*/
    'admin/administrator/:id'                    => 'admin/administrator/read',
    'admin/administrator/update/:id'             => 'admin/administrator/update',
    'admin/administrator/delete/:id'             => 'admin/administrator/delete',
    'admin/administrator/block/:id'             => 'admin/administrator/block',
    'admin/administrator/delete_image/:id'       => 'admin/administrator/delete_image',
    'admin/administrator/update_expire_time/:id' => 'admin/administrator/update_expire_time',
    /*新闻模块*/
    'admin/posts/:id'                            => 'admin/posts/read',
    'admin/posts/update/:id'                     => 'admin/posts/update',
    'admin/posts/delete/:id'                     => 'admin/posts/delete',
    'admin/posts/delete_image/:id'               => 'admin/posts/delete_image',
    'admin/posts/search/:id'                            => 'admin/posts/search',
    /*活动模块*/
    'admin/events/:id'                            => 'admin/events/read',
    'admin/events/update/:id'                     => 'admin/events/update',
    'admin/events/delete/:id'                     => 'admin/events/delete',
    'admin/events/delete_image/:id'               => 'admin/events/delete_image',
    'admin/events/search/:id'                            => 'admin/events/search',
    /*banner模块*/
    'admin/banners/:id'                            => 'admin/banners/read',
    'admin/banners/delete/:id'                     => 'admin/banners/delete',
    'admin/banners/delete_image/:id'               => 'admin/banners/delete_image',
    'admin/banners/search/:id'                            => 'admin/banners/search',
];