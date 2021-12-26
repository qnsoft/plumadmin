<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;


Route::miss(function () {
    //当匹配不到模块的时候直接走前端
    $path = app()->getRootPath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'index.html';
    view($path);
});

