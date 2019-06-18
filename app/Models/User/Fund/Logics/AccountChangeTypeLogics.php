<?php
namespace App\Models\User\Fund\Logics;

use App\Lib\BaseCache;

/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/31/2019
 * Time: 7:59 PM
 */
trait AccountChangeTypeLogics
{
    use BaseCache;

    public static function getList($c)
    {
        $query = self::orderBy('id', 'desc');

        $currentPage = isset($c['page_index']) ? (int) $c['page_index'] : 1;
        $pageSize = isset($c['page_size']) ? (int) $c['page_size'] : 15;
        $offset = ($currentPage - 1) * $pageSize;

        $total = $query->count();
        $data = $query->skip($offset)->take($pageSize)->get();

        return ['data' => $data, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => (int) ceil($total / $pageSize)];
    }

    // 保存
    public function saveItem($data, $adminId = 0)
    {
        $this->name = $data['name'];
        $this->sign = $data['sign'];
        $this->in_out = $data['in_out'];

        $this->amount = $data['amount'] > 0 ? 1 : 0;
        $this->user_id = $data['user_id'] > 0 ? 1 : 0;
        $this->project_id = $data['project_id'] > 0 ? 1 : 0;
        $this->lottery_id = $data['lottery_id'] > 0 ? 1 : 0;
        $this->method_id = $data['method_id'] > 0 ? 1 : 0;
        $this->issue = $data['issue'] > 0 ? 1 : 0;
        $this->from_id = $data['from_id'] > 0 ? 1 : 0;
        $this->from_admin_id = $data['from_admin_id'] > 0 ? 1 : 0;
        $this->to_id = $data['to_id'] > 0 ? 1 : 0;
        $this->frozen_type = $data['frozen_type'] > 0 ? 1 : 0;
        $this->activity_sign = $data['activity_sign'] > 0 ? 1 : 0;
        $this->admin_id = $adminId;
        $this->save();
        return true;
    }

    /**
     * 获取具体详情
     * @param $sign
     * @return array|mixed
     */
    public static function getTypeBySign($sign)
    {
        $data = self::getDataListFromCache();
        return $data[$sign] ?? [];
    }

    // 获取所有配置 缓存
    public static function getDataListFromCache()
    {
        $key = 'account_change_type';
        if (self::_hasCache($key)) {
            return self::_getCacheData($key);
        } else {
            $allCache = self::getDataFromDb();
            if ($allCache) {
                self::_saveCacheData($key, $allCache);
            }
            return $allCache;
        }
    }

    // 获取所有数据 无缓存
    public static function getDataFromDb()
    {
        $items = self::orderBy('id', 'desc')->get();
        $data = [];
        foreach ($items as $item) {
            $data[$item->sign] = $item->toArray();
        }
        return $data;
    }

}
