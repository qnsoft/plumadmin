<?php

use think\migration\Seeder;

class Admin extends Seeder
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
        $rule = $this->table('user');
        if ($rule->exists()) {
            $this->execute('TRUNCATE TABLE user');
            $rule->insert([
                [
                    'username'=>'admin',
                    'password'=>password_hash('123456',PASSWORD_DEFAULT),
                    'nickname'=>'管理员',
                    'avatar'=>'',
                    'is_super'=>1,
                    'create_time'=>date('Y-m-d H:i:s'),
                    'update_time'=>null,
                    'delete_time'=>null
                ]
            ])->save();
        }
    }
}