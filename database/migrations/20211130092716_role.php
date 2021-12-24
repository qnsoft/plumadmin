<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Role extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('role',['comment'=>'角色表'])
            ->addColumn('name','string',['comment'=>'角色名','default'=>''])
            ->addColumn('remark','string',['comment'=>'备注','default'=>'','limit'=>2000])
            ->addColumn('sort', 'integer', ['comment' => '排序(升序)','default'=>200])
            ->addTimestamps()
            ->addSoftDelete()
            ->save();
    }
}
