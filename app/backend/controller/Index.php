<?php

namespace app\backend\controller;

use app\model\RuleModel;
use think\facade\Db;

class Index
{
    public function index()
    {
        $data = Db::table('rule')->whereNull('delete_time')->select()->toArray();
        $data = array_map(function($item){
            $item['create_time'] = date('Y-m-d H:i:s');
            $item['update_time'] = null;
            return $item;
        },$data);
        die(var_export($data,true));die();
    }
}