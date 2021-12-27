<?php

namespace app\backend\controller;

use app\backend\service\UserService;
use app\controller\AuthBackendController;
use app\model\UserModel;
use plum\helper\Arr;

class User extends AuthBackendController
{
    /**
     * 分页
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 21:49
     */
    public function page()
    {
        $page = UserModel::autoOrder()
            ->autoSearch()
            ->paginate();
        get_image($page,['avatar']);
        return $this->renderPage($page);
    }

    /**
     * 创建
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 21:38
     */
    public function create()
    {
        $this->validate($this->data, [
            'username' => 'require',
            'password' => 'require',
            'nickname' => 'require',
            'avatar' => 'require',
            'role_ids' => 'require'
        ]);
        set_image($this->data['avatar']);
        UserService::create($this->data);
        return $this->renderSuccess();
    }

    /**
     * 更新
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 21:41
     */
    public function update()
    {
        $this->validate($this->data, [
            'id' => 'require',
            'username' => 'require',
            'nickname' => 'require',
            'avatar' => 'require',
            'role_ids' => 'require'
        ]);
        //如果,password存在不为空,那么重设密码
        if (!empty($this->data['password'])) {
            $this->data['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);
        } else {
            unset($this->data['password']);
        }
        set_image($this->data['avatar']);
        UserService::update($this->data);
        return $this->renderSuccess();
    }

    /**
     * 详情
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 21:43
     */
    public function detail()
    {
        if (!$detail = UserModel::find($this->data['id'] ?? 0))
            error('用户不存在');
        //管理员ids
        $detail['role_ids'] = Arr::pluck($detail->role, 'id');
        $detail->hidden(['role', 'password']);
        get_image($detail,['avatar']);
        return $this->renderSuccess($detail);
    }

    /**
     * 删除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 21:44\
     */
    public function delete()
    {
        UserModel::whereIn('id', $this->data['ids'] ?? [])->select()->delete();
        return $this->renderSuccess();
    }
}
