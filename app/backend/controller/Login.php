<?php

namespace app\backend\controller;

use app\controller\AuthBackendController;
use app\model\UserModel;
use plum\lib\Token;

class Login extends AuthBackendController
{
    /**
     * 账号登录
     * @auth false
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月03日 12:34
     */
    public function account()
    {
        $this->validate($this->data, [
            'username' => 'require',
            'password' => 'require'
        ]);
        //校验账号和密码是否正确
        $user = UserModel::where('username', $this->data['username'])->find();
        if (!$user || !password_verify($this->data['password'], $user->password)) {
            error('账号或密码不正确');
        }
        return $this->renderSuccess(Token::build($user['id']));
    }

    /**
     * 刷新token
     * @auth false
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月03日 12:39
     */
    public function refresh()
    {
        $this->validate($this->data, [
            'refresh_token' => 'require'
        ]);
        return $this->renderSuccess(Token::refresh($this->data['refresh_token']));
    }
}
