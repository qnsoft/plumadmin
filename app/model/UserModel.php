<?php

namespace app\model;

use plum\core\base\Model;
use think\model\concern\SoftDelete;

class UserModel extends Model
{
    use SoftDelete;

    protected $name = 'user';
    protected $type = ['is_super' => 'boolean'];

    /**
     * 多对多关联表 role
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月30日 21:34
     */
    public function role()
    {
        return $this->belongsToMany(RoleModel::class, UserRoleModel::class, 'role_id', 'user_id');
    }


    /**
     * 搜索器
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月15日 13:20
     */
    public function searchKeywordAttr($query, $value)
    {
        $query->whereLike('username|nickname', $value);
    }
}
