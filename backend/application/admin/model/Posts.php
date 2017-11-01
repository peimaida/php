<?php
namespace app\admin\model;

use think\Model;

class Posts extends Model
{
    // 设置完整的数据表（包含前缀）
    protected $table = 'backend_posts';

    // 关闭自动写入时间戳
    //protected $autoWriteTimestamp = false;

    //默认时间格式
    protected $dateFormat = 'Y-m-d';

    protected $type       = [
        // 设置时间戳类型（整型）
        'create_time'     => 'timestamp',
        'update_time'     => 'timestamp',
        'start_time'     => 'int',
        'end_time'     => 'int',
    ];

    //自动完成
    protected $insert = [
    	'create_time',
    	'update_time',
    ];

    protected $update = ['update_time'];

    // status属性读取器
    protected function getStatusAttr($value)
    {
        $status = [-1 => '已删除', 0 => '未公开', 1 => '已公开'];
        return $status[$value];
    }

    // show_flag属性读取器
    protected function getShowFlagAttr($value)
    {
        $show_flag = [0 => '未显示', 1 => '已显示'];
        return $show_flag[$value];
    }
}