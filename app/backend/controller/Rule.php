<?php

namespace app\backend\controller;

use app\backend\service\UserService;
use app\controller\AuthBackendController;
use app\model\RuleModel;
use plum\helper\Arr;
use plum\helper\Helper;

class Rule extends AuthBackendController
{
    /**
     * 获取树状的规则列表
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 15:24
     */
    public function tree()
    {
        $rules = RuleModel::order(['sort', 'id'])
            ->whereIn('type', [RuleModel::TYPE_DIRECTORY, RuleModel::TYPE_MENU])
            ->select()
            ->toArray();
        //获取所有的权限
        $permissions = RuleModel::order(['sort', 'id'])->where('type', RuleModel::TYPE_PERMISSION)->select()->toArray();
        $rules = array_column($rules, null, 'id');
        foreach ($permissions as $v) {
            if (!array_key_exists($v['parent_id'], $rules)) {
                continue;
            }
            $rules[$v['parent_id']]['permissions'][] = $v;
        }
        $rules = Arr::tree($rules);
        return $this->renderSuccess($rules);
    }

    /**
     * 所有树权限
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月10日 16:53
     */
    public function allTreeByUser()
    {
        $ruleIds = array_column(UserService::getRules($this->userinfo), 'id');
        $rules = RuleModel::order(['sort', 'id'])
            ->whereIn('id', $ruleIds)
            ->select()
            ->toArray();
        $rules = Arr::tree($rules);
        return $this->renderSuccess($rules);
    }

    /**
     * 创建
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 14:18
     */
    public function create()
    {
        $this->validate($this->data, [
            'title' => 'require',
            'mark' => 'requireIn:type,3',
            'name' => 'requireIn:type,2',
            'parent_id' => 'integer',
            'type' => 'require|in:1,2,3',
            'method' => 'requireIn:type,3',
            'routes' => 'requireIn:type,2',
            'component' => 'requireIn:type,2',
            'menu_hidden' => 'boolean',
            'keep_alive' => 'boolean',
            'sort' => 'integer'
        ]);
        RuleModel::create($this->data);
        return $this->renderSuccess([], '保存成功');
    }

    /**
     * 修改
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 15:13
     */
    public function update()
    {
        $this->validate($this->data, [
            'id' => 'require',
            'title' => 'require',
            'mark' => 'requireIn:type,3',
            'name' => 'requireIn:type,2',
            'parent_id' => 'integer',
            'type' => 'require|in:1,2,3',
            'method' => 'requireIn:type,3',
            'routes' => 'requireIn:type,2',
            'component' => 'requireIn:type,2',
            'menu_hidden' => 'boolean',
            'keep_alive' => 'boolean',
            'sort' => 'integer'
        ]);
        RuleModel::update($this->data);
        return $this->renderSuccess();
    }

    /**
     * 规则详情
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 15:18
     */
    public function detail()
    {
        if (!$detail = RuleModel::find($this->data['id'] ?? 0))
            error('规则不存在');
        return $this->renderSuccess($detail);
    }

    /**
     * 删除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 15:19
     */
    public function delete()
    {
        $ids = Helper::getChildrenIds(new RuleModel(), $this->data['ids'] ?? []);
        $ids = array_merge($ids, $this->data['ids'] ?? []);
        RuleModel::whereIn('id', $ids)->select()->delete();
        return $this->renderSuccess([], '删除成功');
    }
}
