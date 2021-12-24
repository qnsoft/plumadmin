<?php

namespace app\controller;

use app\backend\service\UserService;
use app\model\RuleModel;
use app\model\UserModel;
use plum\core\base\AuthController;
use plum\core\exception\PermissionException;
use plum\helper\Arr;

class AuthBackendController extends AuthController
{
    /**
     * 权限校验
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/24
     */
    protected function checkPermission()
    {
        $rulesAll = RuleModel::select()->toArray();
        $rulesUser = UserService::getRules($this->userinfo);
        $methodsUser = Arr::collapse(array_column($rulesUser,'method'));
        $methodsAll = Arr::collapse(array_column($rulesAll,'method'));
        $methodsUser = array_map(function($e){
            return strtolower($e);
        },$methodsUser);
        $methodsAll = array_map(function($e){
            return strtolower($e);
        },$methodsAll);
        $method = strtolower($this->request->controller().'@'.$this->request->action());
        if(in_array($method,$methodsAll) && !in_array($method,$methodsUser)){
            throw new PermissionException();
        }
    }

    public function getUserinfo($id)
    {
        if (!$user = UserModel::find($id))
            error('用户不存在');
        return $user;
    }
}
