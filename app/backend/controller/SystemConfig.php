<?php

namespace app\backend\controller;

use app\controller\AuthBackendController;
use app\model\SystemConfigModel;

class SystemConfig extends AuthBackendController
{
    /**
     * set
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月15日 15:53
     */
    public function set()
    {
        $this->validate($this->data, [
            'key'   => 'require',
            'value' => 'require'
        ]);
        $model = new SystemConfigModel();
        $model->setItem($this->data['key'], $this->data['value']);
        return $this->renderSuccess();
    }

    /**
     * get
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月15日 15:53
     */
    public function get()
    {
        $this->validate($this->data, [
            'key' => 'require'
        ]);
        $model = new SystemConfigModel();
        $data = $model->getItem($this->data['key']);
        return $this->renderSuccess($data);
    }
}
