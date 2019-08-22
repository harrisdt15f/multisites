<?php

namespace App\Models\Game\Lottery\Logics;

use App\Lib\Game\Lottery;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryMethod;
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
    /**
     * 标识获取彩种
     * @param  string $sign
     * @return mixed
     */
    public static function findBySign($sign)
    {
        return self::where('en_name', $sign)->first();
    }

    /**
     * 获取列表
     * @param  array $c
     * @return array
     */
    public static function getList($c): array
    {
        $query = self::orderBy('id', 'desc');
        if (isset($c['en_name']) && $c['en_name']) {
            $query->where('en_name', '=', $c['en_name']);
        }
        $currentPage = isset($c['page_index']) ? (int) $c['page_index'] : 1;
        $pageSize = isset($c['page_size']) ? (int) $c['page_size'] : 15;
        $offset = ($currentPage - 1) * $pageSize;
        $total = $query->count();
        $data = $query->skip($offset)->take($pageSize)->get();
        return [
            'data' => $data,
            'total' => $total,
            'currentPage' => $currentPage,
            'totalPage' => (int) ceil($total / $pageSize),
        ];
    }

    /**
     * 保存
     * @return bool
     */
    public function saveItem(): bool
    {
        $data = Request::all();
        $this->cn_name = $data['cn_name'];
        $this->en_name = $data['en_name'];
        $this->series_id = $data['series_id'];
        $this->max_trace_number = (int) $data['max_trace_number'];
        $this->issue_format = $data['issue_format'];
        $this->is_fast = isset($data['is_fast']) ? 1 : 0;
        $this->auto_open = isset($data['auto_open']) ? 1 : 0;
        $this->save();
        return true;
    }

    /**
     * 获取选项
     * @return array
     */
    public static function getOptions(): array
    {
        $items = self::where('status', 1)->get();
        $data = [];
        foreach ($items as $item) {
            $data[$item->en_name] = $item->cn_name;
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getFormatMode(): array
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

    /** ================================= 游戏相关 ================================== */

    /**
     * 合法的倍数
     * @param $times
     * @return bool
     */
    public function isValidTimes($times): bool
    {
        if (!$times || $times <= 0) {
            return false;
        }
        if ($times > $this->max_times || $times < $this->min_times) {
            return false;
        }
        return true;
    }

    /**
     * 是否是彩种合法的奖金组
     * @param $prizeGroup
     * @return bool
     */
    public function isValidPrizeGroup($prizeGroup): bool
    {
        if (!$prizeGroup) {
            return false;
        }
        if ($prizeGroup > $this->max_prize_group || $prizeGroup < $this->min_prize_group) {
            return false;
        }
        return true;
    }

    // 检测追号数据
    public function checkTraceData($traceData)
    {
        $issueItems = LotteryIssue::whereIn('issue', $traceData)->where([
            ['lottery_id', '=', $this->en_name],
            ['end_time', '>=', time()],
        ])->orderBy('begin_time', 'ASC')->get();
        return $issueItems;
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
        return $lotteries[$sign] ?? [];
    }

    /**
     * 获取所有游戏 包含玩法
     * @return array|mixed
     * @throws \Exception
     */
    public static function getAllLotteryByCache($update = 0)
    {
        $key = 'lottery';
        if (self::hasCache($key) && $update === 0) {
            return self::getCacheData($key);
        } else {
            $lotteries = self::getAllLotteries();
            self::saveCacheData($key, $lotteries);
            return $lotteries;
        }
    }

    //更新 所有游戏&玩法 缓存
    public static function updateAllLotteryByCache()
    {
        self::getAllLotteryByCache(1);
    }

    /**
     * 检查是否存在缓存
     * @param $key
     * @return bool
     * @throws \Exception
     */
    public static function hasCache($key)
    {
        $cacheConfig = self::getCacheConfig($key);
        return Cache::has($cacheConfig['key']);
    }

    /**
     * 获取缓存配置
     * @param $key
     * @return mixed
     */
    public static function getCacheConfig($key)
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
    public static function getCacheData($key)
    {
        $cacheConfig = self::getCacheConfig($key);
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
    public static function getAllLotteries(): array
    {
        $lotteries = self::where('status', 1)->get();
        $lotteryData = [];
        foreach ($lotteries as $lottery) {
            $methods = LotteryMethod::where('lottery_id', $lottery->en_name)->where('status', 1)->get();
            $_methods = [];
            foreach ($methods as $method) {
                $_method = $method->toArray();
                $object = $lottery->getMethodObject($method['method_id']);
                if (!$object) {
                    Log::error($lottery->cn_name . '-' . $method['method_id'] . '-不存在');
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
    public static function saveCacheData($key, $value): void
    {
        $cacheConfig = self::getCacheConfig($key);
        if ($cacheConfig['expire_time'] <= 0) {
            Cache::forever($cacheConfig['key'], $value);
        } else {
            $expireTime = Carbon::now()->addSeconds($cacheConfig['expire_time']);
            Cache::put($cacheConfig['key'], $value, $expireTime);
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
        return $data[$methodId] ?? [];
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
        if (self::hasCache('method_object')) {
            $data = self::getCacheData('method_object');
            if (isset($data[$seriesId])) {
                return $data[$seriesId];
            }
        }
        $methods = LotteryMethod::where('series_id', $seriesId)->get();
        $_data = [];
        foreach ($methods as $item) {
            $methodObject = Lottery::getMethodObject($seriesId, $item->method_group, $item->method_id);
            if (!is_object($methodObject)) {
                Log::error($seriesId . '-' . $item->method_id . '-' . $methodObject);
                continue;
            }
            $_data[$item->method_id] = $methodObject;
        }
        $data[$seriesId] = $_data;
        self::saveCacheData('method_object', $data);
        return $_data;
    }

    /**
     * 获取 玩法配置 - 配置 + 对象
     * @param $methodId
     * @return array
     */
    public function getMethod($methodId)
    {
        $methods = $this->method_config;
        return $methods[$methodId] ?? [];
    }

    /**
     * 只用于前端展示
     * @return array
     * @throws
     */
    public static function getAllLotteryToFrontEnd()
    {
        if (self::hasCache('lottery_for_frontend')) {
            return self::getCacheData('lottery_for_frontend');
        }
        $lotteries = self::where('status', 1)->get();
        $cacheData = [];
        foreach ($lotteries as $lottery) {
            $lottery->valid_modes = $lottery->getFormatMode();
            // 获取所有玩法
            $methods = LotteryMethod::getMethodConfig($lottery->en_name);
            $methodData = [];
            $groupName = config('game.method.group_name');
            $rowName = config('game.method.row_name');
            $rowData = [];
            foreach ($methods as $index => $method) {
                $rowData[$method->method_group][$method->method_row][] = [
                    'method_name' => $method->method_name,
                    'method_id' => $method->method_id,
                ];
            }
            $groupData = [];
            $hasRow = [];
            foreach ($methods as $index => $method) {
                // 行
                if (!isset($groupData[$method->method_group])) {
                    $groupData[$method->method_group] = [];
                }

                if (!isset($hasRow[$method->method_group]) ||
                    !in_array($method->method_row, $hasRow[$method->method_group])) {
                    $groupData[$method->method_group][] = [
                        'name' => $rowName[$method->method_row],
                        'sign' => $method->method_row,
                        'methods' => $rowData[$method->method_group][$method->method_row],
                    ];
                    $hasRow[$method->method_group][] = $method->method_row;
                }
            }

            // 组
            $defaultGroup = '';
            $defaultMethod = '';
            $hasGroup = [];
            foreach ($methods as $index => $method) {
                if ($index == 0) {
                    $defaultGroup = $method->method_group;
                    $defaultMethod = $method->method_id;
                }
                // 组
                if (!in_array($method->method_group, $hasGroup)) {
                    $methodData[] = [
                        'name' => $groupName[$lottery->series_id][$method->method_group],
                        'sign' => $method->method_group,
                        'rows' => $groupData[$method->method_group],
                    ];
                    $hasGroup[] = $method->method_group;
                }
            }
            $cacheData[$lottery->en_name] = [
                'lottery' => $lottery,
                'methodConfig' => $methodData,
                'defaultGroup' => $defaultGroup,
                'defaultMethod' => $defaultMethod,
            ];
        }
        self::saveCacheData('lottery_for_frontend', $cacheData);
        return $cacheData;
    }

    /**
     * 检查录入的号码
     * @param $series
     * @param $code
     * @return bool
     */
    public function checkCodeFormat($codeStr)
    {
        $codeArr = explode(',', $codeStr);
        $series = $this->series_id;
        // 数字彩票
        if (in_array($series, ['ssc', '3d', 'p3p5'])) {
            $_code = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
            foreach ($codeArr as $c) {
                if (!in_array($c, $_code)) {
                    return false;
                }
            }
            return !(count($codeArr) != 5);
        }
        // 乐透彩票
        if ($series == '115') {
            $_code = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11'];
            foreach ($codeArr as $c) {
                if (!in_array($c, $_code)) {
                    return false;
                }
            }
            return !(count($codeArr) != 5);
        }
        // pk10
        if ($series == 'pk10') {
            $_code = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10'];
            foreach ($codeArr as $c) {
                if (!in_array($c, $_code)) {
                    return false;
                }
            }
            return !(count($codeArr) != 10);
        }

        // 快三
        if ($series == 'jskl3') {
            $_code = [1, 2, 3, 4, 5, 6];
            foreach ($codeArr as $c) {
                if (!in_array($c, $_code)) {
                    return false;
                }
            }
            return count($codeArr) == 3;
        }
        // 六合彩
        if ($series == 'lhc') {
            $_code = [1, 2, 3, 4, 5, 6];
            foreach ($codeArr as $c) {
                if (!in_array($c, $_code)) {
                    return false;
                }
            }
            return count($codeArr) == 3;
        }
        return false;
    }

    public function formatOpenCode($openCode)
    {
        $positions = explode(',', $this->positions);
        $codes = explode(',', $openCode);
        return array_combine($positions, $codes);
    }

    /**
     * 更新首页lotteryInfo缓存
     */
    public static function lotteryInfoCache()
    {
        $lotteries = self::where('status', 1)->get();
        $cacheData = [];
        foreach ($lotteries as $lottery) {
            $lottery->valid_modes = $lottery->getFormatMode();
            // 获取所有玩法
            $methods = LotteryMethod::getMethodConfig($lottery->en_name);
            $methodData = [];

            $groupName = config('game.method.group_name');
            $rowName = config('game.method.row_name');

            $rowData = [];
            foreach ($methods as $index => $method) {
                $rowData[$method->method_group][$method->method_row][] = [
                    'method_name' => $method->method_name,
                    'method_id' => $method->method_id,
                    'method_group' => $method->method_group,
                ];
            }
            $groupData = [];
            $hasRow = [];
            foreach ($methods as $index => $method) {
                // 行
                if (!isset($groupData[$method->method_group])) {
                    $groupData[$method->method_group] = [];
                }
                if (!isset($hasRow[$method->method_group]) ||
                    !in_array($method->method_row, $hasRow[$method->method_group])) {
                    $groupData[$method->method_group][] = [
                        'name' => $rowName[$method->method_row],
                        'sign' => $method->method_row,
                        'methods' => $rowData[$method->method_group][$method->method_row],
                    ];
                    $hasRow[$method->method_group][] = $method->method_row;
                }
            }

            // 组
            $defaultGroup = '';
            $defaultMethod = '';
            $hasGroup = [];
            foreach ($methods as $index => $method) {
                if ($index == 0) {
                    $defaultGroup = $method->method_group;
                    $defaultMethod = $method->method_id;
                }
                // 组
                if (!in_array($method->method_group, $hasGroup)) {
                    $methodData[] = [
                        'name' => $groupName[$lottery->series_id][$method->method_group],
                        'sign' => $method->method_group,
                        'rows' => $groupData[$method->method_group],
                    ];
                    $hasGroup[] = $method->method_group;
                }
            }
            $lottery->serie;
            $cacheData[$lottery->en_name] = [
                'lottery' => $lottery,
                'methodConfig' => $methodData,
                'defaultGroup' => $defaultGroup,
                'defaultMethod' => $defaultMethod,
            ];
        }
        $hourToStore = 24;
        $expiresAt = Carbon::now()->addHours($hourToStore);
        $frontendLotteryInfoCache = 'frontend.lottery.lotteryInfo';
        Cache::tags('lottery')->put($frontendLotteryInfoCache, $cacheData, $expiresAt);
        return $cacheData;
    }

    //获取除了六合彩以外的所有开启状态的彩种sign（生成奖期用）
    public static function generateIssueLotterys()
    {
        return self::where('status', 1)->where('en_name', '!=', 'hklhc')->pluck('en_name');
    }
}
