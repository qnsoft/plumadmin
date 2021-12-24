<?php

namespace app\backend\controller;

use app\controller\AuthBackendController;
use app\model\FileModel;
use think\facade\Filesystem;

class File extends AuthBackendController
{
    /**
     * 上传
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月29日 18:00
     */
    public function upload()
    {
        $file = new FileModel();
        $info = $file->upload();
        return $this->renderSuccess($info);
    }

    /**
     * 当前用户的文件库
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月13日 00:41
     */
    public function userFilePage()
    {
        $page = FileModel::autoOrder()
            ->autoSearch('name')
            ->where('uploader_id', $this->userinfo->id)
            ->where('module', $this->app->http->getName())
            ->append(['size_text'])
            ->visible(['id', 'name', 'url'])
            ->paginate();
        return $this->renderPage($page);
    }

    /**
     * 删除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月13日 13:19
     */
    public function delete()
    {
        $ids = input('ids/a', []);
        $list = FileModel::whereIn('id', $ids)->select();
        //删除文件
        foreach ($list as $v) {
            $path = $v['path'];
            $driver = $v['driver'];
            Filesystem::disk($driver)->delete($path);
        }
        //删除数据库文件
        FileModel::destroy($ids);
        return $this->renderSuccess();
    }
}
