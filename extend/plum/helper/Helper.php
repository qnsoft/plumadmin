<?php

namespace plum\helper;

use app\model\FileModel;

class Helper
{
    /**
     * 获取子ID
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月04日 17:26
     */
    public static function getChildrenIds($model, $ids)
    {
        $childrenIds = $model->whereIn('parent_id', $ids)->column('id');
        if (!empty($childrenIds)) {
            $childrenIds = array_merge($childrenIds, self::getChildrenIds($model, $childrenIds));
        }
        return $childrenIds;
    }

    /**
     * 获取当前文件夹下某个扩展的文件
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月06日 19:02
     */
    public static function getAllFile($path, $extension)
    {
        $result = [];
        $pattern = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*';
        $files = glob($pattern);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $result = array_merge($result, self::getAllFile($file, $extension));
            } elseif (strcasecmp(pathinfo($file, PATHINFO_EXTENSION), $extension) === 0) {
                array_push($result, $file);
            }
        }
        return $result;
    }

    /**
     * 获取文档注释
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月24日 15:55
     */
    public static function getNotes(...$arg): array
    {
        try {
            $notes = (new \ReflectionMethod(...$arg))->getDocComment();
            $pattern = "/@([a-zA-Z]+)\s+(\S+)\s+/";
            preg_match_all($pattern, $notes, $matches);
            $data = [];
            foreach ($matches[1] as $k => $v) {
                $data[$v] = $matches[2][$k];
            }
            return $data;
        } catch (\ReflectionException $e) {
            return [];
        }
    }

    /**
     * 判断是不是linux系统
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年11月26日 22:40
     */
    public static function isLinux(): bool
    {
        return DIRECTORY_SEPARATOR !== '\\';
    }

    /**
     * 保存图片,获取图片id
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/27
     */
    public static function setImage(&$data)
    {
        if (empty($data)) {
            if (isset($data)) {
                $data = is_array($data) ? [] : 0;
            }
        } else {
            if (Arr::isAssoc($data)) {
                //关联数组
                $data = $data['id'];
            } else {
                //索引数组,多图形式
                $data = array_map(function ($item) {
                    return $item['id'];
                }, $data);
            }
        }
    }

    /**
     * 将id置换成图片形式
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/27
     */
    public static function getImage(&$data, $fields)
    {
        $array = Arr::toArray($data);
        //一位数组转化成二维数组
        if (Arr::isAssoc($array)) {
            $array = [$array];
        }
        //获取所有的ids
        $ids = [];
        foreach ($array as $item) {
            foreach ($item as $field => $v) {
                if (in_array($field, $fields)) {
                    if (is_array($v)) {
                        $ids = array_merge($ids, $v);
                    } else {
                        $ids[] = $v;
                    }

                }
            }
        }
        //获取文件列表
        $files = FileModel::whereIn('id', $ids)
            ->append(['url'])
            ->visible(['id', 'name'])
            ->select()
            ->toArray();
        //将id提出
        $files = array_column($files, null, 'id');
        if (Arr::isAssoc(Arr::toArray($data))) {
            //一维数组的情况下
            foreach ($array[0] as $field => $itemIds) {
                if (in_array($field, $fields)) {
                    $data[$field] = self::setKeyImage($itemIds, $files);
                }
            }
        } else {
            foreach ($array as $key => $item) {
                foreach ($item as $field => $itemIds) {
                    if (in_array($field, $fields)) {
                        $data[$key][$field] = self::setKeyImage($itemIds, $files);
                    }
                }
            }
        }
    }

    /**
     * 根据id获取图片item
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/27
     */
    private static function setKeyImage($key, $array)
    {
        if (is_array($key)) {
            return array_filter(array_map(function ($item) use ($array) {
                return $array[$item] ?? null;
            }, $key), function ($item) {
                return !!$item;
            });
        }
        return $array[$key] ?? null;
    }
}
