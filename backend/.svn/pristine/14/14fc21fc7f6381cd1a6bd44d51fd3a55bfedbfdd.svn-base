<?php
namespace app\admin\model;

use think\Model;

class Maintenance extends Model
{

    // 设置完整的数据表（包含前缀）
    protected $table = 'backend_maintenance';

    //默认时间格式
    protected $dateFormat = 'Y-m-d';

    protected $type       = [
        // 设置时间戳类型（整型）
        'create_time'     => 'int',
        'update_time'     => 'int',
    ];

    //自动完成
    protected $insert = [
    	'create_time',
    	'update_time',
    ];

    protected $update = ['update_time'];

    // 属性修改器
    protected function setCreateTimeAttr($value, $data)
    {
        return time();
    }

    // 属性修改器
    protected function setUpdateTimeAttr($value, $data)
    {
        return time();
    }

    // status属性读取器
    protected function getStatusAttr($value)
    {
        $status = [0 => '关闭', 1 => '开启'];
        return $status[$value];
    }

    // useless属性读取器
    protected function getUselessAttr($value)
    {
        $useless = [0 => '可用', 1 => '废弃'];
        return $useless[$value];
    }
}