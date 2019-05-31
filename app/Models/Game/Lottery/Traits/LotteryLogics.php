<?php

namespace App\Models\Game\Lottery\Traits;

use App\Lib\Game\Lottery;
use App\Models\Game\Lottery\MethodsModel;
//use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/23/2019
 * Time: 10:18 PM
 */
trait LotteryLogics
{
    // 标识获取彩种
    public static function findBySign($sign)
    {
        return self::where('en_name', $sign)->first();
    }

    // 获取列表
    public static function getList($c) {
        $query = self::orderBy('id', 'desc');
        if (isset($c['en_name']) && $c['en_name']) {
            $query->where('en_name', '=', $c['en_name']);
        }

        $currentPage    = isset($c['page_index']) ? intval($c['page_index']) : 1;
        $pageSize       = isset($c['page_size']) ? intval($c['page_size']) : 15;
        $offset         = ($currentPage - 1) * $pageSize;

        $total  = $query->count();
        $data   = $query->skip($offset)->take($pageSize)->get();

        return ['data' => $data, 'total' => $total, 'currentPage' => $currentPage, 'totalPage' => intval(ceil($total / $pageSize))];
    }

    // 保存
    public function saveItem() {
        $data       = Request::all();
        $this->cn_name          = $data['cn_name'];
        $this->en_name          = $data['en_name'];
        $this->series_id        = $data['series_id'];
        $this->max_trace_number = intval($data['max_trace_number']);
        $this->issue_format     = $data['issue_format'];
        $this->is_fast          = isset($data['is_fast']) ? 1 : 0;
        $this->auto_open        = isset($data['auto_open']) ? 1 : 0;
        $this->save();
        return true;
    }

    // 获取选项
    public static function getOptions() {
        $items = self::where('status', 1)->get();
        $data = [];
        foreach ($items as $item) {
            $data[$item->en_name] = $item->cn_name;
        }
        return $data;
    }

    public function getFormatMode()
    {
        $modeConfig = config('game.main.modes');
        $currentModes = explode(',', $this->valid_modes);

        $data = [];
        foreach ($currentModes as $index) {
            $_mode = $modeConfig[$index];
            $data[$index] = $_mode;
        }
        return $data;
    }

    /**
     * 获取 单个彩种
     * @param $sign
     * @return array|mixed
     * @throws \Exception
     */
    public static function getLottery($sign)
    {
        $lotteries = self::getAllLotteryByCache();
        if (isset($lotteries[$sign])) {
            return $lotteries[$sign];
        }
        return [];
    }

    /**
     * 获取所有游戏 包含玩法
     * @return array|mixed
     * @throws \Exception
     */
    public static function getAllLotteryByCache()
    {
        $key = 'lottery';
        if (self::_hasCache($key)) {
            return self::_getCacheData($key);
        } else {
        return self::getAllLotteries();
            self::_saveCacheData($key, $lotteries);
            return $lotteries;
        }
    }

    /**
     * 检查是否存在缓存
     * @param $key
     * @return bool
     * @throws \Exception
     */
    public static function _hasCache($key)
    {
        $cacheConfig = self::_getCacheConfig($key);
        return  Cache::has($cacheConfig['key']);
    }

    /**
     * 获取缓存配置
     * @param $key
     * @return mixed
     */
    public static function _getCacheConfig($key)
    {
        $cacheConfig = Config::get('web.main.cache');
        return $cacheConfig[$key] ?? $cacheConfig['common'];
    }

    /**
     * 获取缓存
     * @param $key
     * @return Repository
     * @throws \Exception
     */
    public static function _getCacheData($key)
    {
        $cacheConfig = self::_getCacheConfig($key);
        return Cache::get($cacheConfig['key'], []);
    }

    /**
     * 获取玩法的所有配置
     * methods_config = [
     *      'total'  => []
     *      'level'  => []
     *      'object' => new StdClass()
     * ]
     * @return array
     * @throws \Exception
     */
    public static function getAllLotteries()
    {
        $lotteries = self::where('status', 1)->get();
        $lotteryData = [];
        foreach ($lotteries as $lottery) {
            $methods = MethodsModel::where('lottery_id', $lottery->en_name)->where('status', 1)->get();
            $_methods = [];
            foreach ($methods as $method) {
                $_method = $method->toArray();
                $object = $lottery->getMethodObject($method['method_id']);
                if (!$object) {
                    Log::error($lottery->cn_name.'-'.$method['method_id'].'-不存在');
                    continue;
                }
                $_method['object'] = $object;
                $_methods[$method->method_id] = $_method;
            }
            $lottery->method_config = $_methods;
            $lotteryData[$lottery->en_name] = $lottery;
        }
        return $lotteryData;
    }

    /**
     * 保存
     * @param $key
     * @param $value
     * @throws \Exception
     */
    public static function _saveCacheData($key, $value)
    {
        $cacheConfig = self::_getCacheConfig($key);
        if ($cacheConfig['expire_time'] <= 0) {
            return Cache::forever($cacheConfig['key'], $value);
        } else {
            $expireTime = Carbon::now()->addSeconds($cacheConfig['expire_time']);
            return Cache::put($cacheConfig['key'], $value, $expireTime);
        }
    }

    /**
     * 获取 单个玩法 对象
     * @param $methodId
     * @return array|mixed
     * @throws \Exception
     */
    public function getMethodObject($methodId)
    {

        $data = self::getAllMethodObject($this->series_id);
        if (isset($data[$methodId])) {
            return $data[$methodId];
        }
        return [];
    }

    /**
     * 获取 系列 玩法对象 缓存
     * @param $seriesId
     * @return mixed
     * @throws \Exception
     */
    public static function getAllMethodObject($seriesId)
    {
        $data = [];
        if (self::_hasCache('method_object')) {
            $data = self::_getCacheData('method_object');
            if (isset($data[$seriesId])) {
                return $data[$seriesId];
            }
        }
        $methods = MethodsModel::where('series_id', $seriesId)->get();
        $_data = [];
        foreach ($methods as $item) {
            $methodObject = Lottery::getMethodObject($seriesId, $item->method_group, $item->method_id);
            if (!is_object($methodObject)) {
                Log::error($seriesId.'-'.$item->method_id.'-'.$methodObject);
                continue;
            }
            $_data[$item->method_id] = $methodObject;
        }
        $data[$seriesId] = $_data;
        self::_saveCacheData('method_object', $data);
        return $_data;
    }

    /**
     * 获取 玩法配置 - 配置 + 对象
     * @param $methodId
     * @return array
     */
    public function getMethod($methodId) {
        $methods = $this->method_config;
        return $methods[$methodId] ?? [];
    }

    /**
     * 是否是彩种合法的奖金组
     * @param $prizeGroup
     * @return bool
     */
    public function isValidPrizeGroup($prizeGroup) {

        if (!$prizeGroup) {
            return false;
        }

        if ($prizeGroup > $this->max_prize_group || $prizeGroup < $this->min_prize_group) {
            return false;
        }

        return true;
    }
}