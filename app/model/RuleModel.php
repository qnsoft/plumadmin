<?php

namespace app\model;

use plum\core\base\Model;
use think\model\concern\SoftDelete;

class RuleModel extends Model
{
    use SoftDelete;

    protected $name = 'rule';
    protected $type = ['menu_hidden' => 'boolean', 'keep_alive' => 'boolean', 'method' => 'json'];

    const TYPE_DIRECTORY = 1;
    const TYPE_MENU = 2;
    const TYPE_PERMISSION = 3;
}
