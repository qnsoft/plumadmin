<?php

namespace app\backend\service;

use app\model\RuleModel;
use app\model\UserModel;
use app\model\UserRoleModel;
use plum\core\exception\AuthException;
use plum\helper\Arr;
use think\Exception;
use think\facade\Db;

class UserService
{
    /**
     * 创建
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 21:39
     */
    public static function create($data)
    {
        try {
            Db::startTrans();
            //创建用户
            $user = UserModel::create($data);
            //关联角色
            $user->role()->saveAll($data['role_ids']);
            Db::commit();
        } catch (Exception $exception) {
            Db::rollback();
            error('保存失败');
        }
    }

    /**
     * 修改
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 21:39
     */
    public static function update($data)
    {
        try {
            Db::startTrans();
            //创建用户
            $user = UserModel::update($data);
            //删除关联表,重新创建关联角色
            UserRoleModel::where('user_id', $data['id'])->delete();
            $user->role()->saveAll($data['role_ids']);
            Db::commit();
        } catch (Exception $exception) {
            Db::rollback();
            error('保存失败');
        }
    }

    /**
     * 获取当前用户的规则
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/24
     */
    public static function getRules($user)
    {
        if ($user['is_super']) {
            //超级管理员获取所有规则
            $rule = RuleModel::select()->toArray();
        } else {
            //普通用户,根据角色获取规则
            $rule = [];
            foreach ($user->role as $role) {
                $rule = array_merge($rule, $role->rule->toArray());
            }
            $rule = array_values(array_column($rule, null, 'id'));
        }
        return $rule;
    }

    /**
     * 获取当前用户的权限
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月02日 14:42
     */
    public static function getCurrentDetail()
    {
        if (!$user = request()->middleware('userinfo'))
            throw new AuthException();
        //获取当前用的管理员权限
        $rule = self::getRules($user);
        //权限和菜单
        $menus = array_filter($rule, function ($item) {
            return $item['type'] === RuleModel::TYPE_MENU || $item['type'] === RuleModel::TYPE_DIRECTORY;
        });
        $routes = array_filter($rule, function ($item) {
            return $item['type'] === RuleModel::TYPE_MENU;
        });
        $permission = array_filter($rule, function ($item) {
            return $item['type'] === RuleModel::TYPE_PERMISSION;
        });
        //赋值
        $user->menus = Arr::tree($menus);
        $user->routes = Arr::tree($routes);
        $user->permission = array_values(array_unique(array_map(function ($item) {
            return $item['mark'];
        }, $permission)));
        $user->hidden(['password', 'role']);
        return $user;
    }
}
