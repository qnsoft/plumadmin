<?php

use think\migration\Seeder;

class Rule extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $rule = $this->table('rule');
        if ($rule->exists()) {
            $this->execute('TRUNCATE TABLE rule');
            $data = include_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'rule.php';
            $data = array_map(function ($item) {
                $item['create_time'] = date('Y-m-d H:i:s');
                return $item;
            }, $data);
            $rule->insert($data)
                ->save();
        }
    }
}
