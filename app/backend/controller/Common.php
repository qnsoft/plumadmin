<?php

namespace app\backend\controller;

use app\controller\AuthBackendController;
use app\model\SystemConfigModel;
use plum\helper\Arr;
use plum\helper\Helper;

class Common extends AuthBackendController
{
    /**
     * 获取所有的权限
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 11:56
     */
    public function permissions()
    {
        //过滤的方法,注意小写
        $except = ['__construct', 'rendersuccess', 'renderpage','getuserinfo'];
        $namespace = (new \ReflectionClass(__CLASS__))->getNamespaceName();
        //获取所有类库文件
        $paths = Helper::getAllFile(__DIR__, 'php');
        //获取当前的控制器
        $routes = array_map(function ($item) use ($namespace, $except) {
            //去除根路径和.php后缀
            $item = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', strstr($item, '.php', true));
            $controller = str_replace(DIRECTORY_SEPARATOR, '.', strtolower($item));
            //获取所有方法
            $methods = (new \ReflectionClass($namespace . '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $item)))
                ->getMethods(\ReflectionMethod::IS_PUBLIC);
            //过滤不需要的方法
            $methods = array_filter(array_map(function ($item) use ($controller) {
                return [
                    'label' => strtolower($item->getName()),
                    'value' => $controller . '@' . strtolower($item->getName())
                ];
            }, $methods), function ($item) use ($except) {
                return !in_array($item['label'], $except);
            });
            return [
                'label'    => $controller,
                'value'    => $controller,
                'children' => array_values($methods)
            ];
        }, $paths);
        //过滤没有权限的
        $routes = array_filter($routes, function ($item) {
            return !empty($item['children']);
        });
        return $this->renderSuccess(array_values($routes));
    }
}
