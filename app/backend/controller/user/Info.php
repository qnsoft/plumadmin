<?php

namespace app\backend\controller\user;

use app\backend\service\UserService;
use app\controller\AuthBackendController;
use app\model\UserModel;
use plum\lib\Token;

class Info extends AuthBackendController
{
    /**
     * 用户详情
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月02日 11:36
     */
    public function detail()
    {
        $data = UserService::getCurrentDetail();
        return $this->renderSuccess($data);
    }

    /**
     * 修改密码
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/23
     */
    public function editPassword()
    {
        $this->validate($this->data, [
            'old_password' => 'require',
            'password' => 'require',
            'confirm_password' => 'require|confirm:password'
        ]);
        if (!password_verify($this->data['old_password'], $this->userinfo['password'])) {
            error('旧密码不正确');
        }
        $this->userinfo->password = password_hash($this->data['password'], PASSWORD_DEFAULT);
        $this->userinfo->save();
        return $this->renderSuccess([], '操作成功');
    }

    /**
     * 修改用户信息
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/24
     */
    public function patch()
    {
        $allowFields = ['nickname', 'avatar'];
        $this->userinfo->allowField($allowFields)->save($this->data);
        $user = UserService::getCurrentDetail();
        return $this->renderSuccess($user, '操作成功');
    }

    /**
     * 退出登录
     * @auth false
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/24
     */
    public function logout()
    {
        Token::invalid();
        return $this->renderSuccess([], '登出成功');
    }
}


