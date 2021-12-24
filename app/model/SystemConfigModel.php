<?php

namespace app\model;

use app\backend\service\CacheTags;
use app\CacheTagEnum;
use plum\core\base\Model;
use plum\helper\Arr;
use think\facade\Cache;

class SystemConfigModel extends Model
{
    protected $table = 'system_config';
    protected $type = ['value' => 'json'];
    const CACHE_PREFIX = 'setting';
    protected $defaultConfig = [
        "filesystem" => [
            'default' => 'local',
            'disks'   => [
                'aliyun' => [
                    'type'         => 'aliyun',
                    'accessId'     => '',
                    'accessSecret' => '',
                    'bucket'       => '',
                    'endpoint'     => '',
                    'url'          => '',
                ],
                'qiniu'  => [
                    'type'      => 'qiniu',
                    'accessKey' => '',
                    'secretKey' => '',
                    'bucket'    => '',
                    'url'       => '',//不要斜杠结尾，此处为URL地址域名。
                ],
                'qcloud' => [
                    'type'      => 'qcloud',
                    'region'    => '', //bucket 所属区域 英文
                    'appId'     => '', // 域名中数字部分
                    'secretId'  => '',
                    'secretKey' => '',
                    'bucket'    => '',
                    'cdn'       => '',
                ]
            ],
            'valid'   => []
        ]
    ];

    /**
     * 保存配置
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月15日 15:20
     */
    public function setItem($key, $data)
    {
        if (!isset($this->defaultConfig[$key])) {
            error('此配置不存在');
        }
        //清空缓存
        Cache::set(self::CACHE_PREFIX . $key, null);
        $info = self::where('key', $key)->find();
        if ($info) {
            $info->value = $data;
            $info->save();
        } else {
            self::create([
                'key'   => $key,
                'value' => $data
            ]);
        }

    }

    /**
     * 获取缓存的数据
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月15日 15:42
     */
    private function getCacheItem($key)
    {
        if (!$cache = Cache::get(self::CACHE_PREFIX . $key)) {
            //获取数据库的数据
            $database = self::where('key', $key)->find();
            $cache = $database ? $database['value'] : [];
            Cache::tag([CacheTagEnum::ALL, CacheTagEnum::SYSTEM_CONFIG])
                ->set(self::CACHE_PREFIX . $key, $cache, 86400);
        }
        return $cache;
    }

    /**
     * 获取配置
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年12月15日 15:44
     */
    public function getItem($key)
    {
        if (!isset($this->defaultConfig[$key])) {
            error('此配置不存在');
        }
        $data = $this->getCacheItem($key);
        //和默认配置进行合并
        return Arr::mergeMultiple($this->defaultConfig[$key], $data);
    }
}
