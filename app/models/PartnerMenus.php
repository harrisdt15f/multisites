<?php

namespace App\models;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PartnerMenus extends BaseModel
{
    protected $table = 'partner_admin_menus';

    protected $redisFirstTag = 'ms_menu';

    /**
     * @param  PartnerAdminGroupAccess  $accessGroupEloq
     * @return array
     * TODO : 由于快速开发 后续需要弄缓存与异常处理
     */
    public function menuLists(PartnerAdminGroupAccess $accessGroupEloq)
    {
        $parent_menu = [];
        $role = $accessGroupEloq->role;
        if ($role == '*') {
            $parent_menu = $this->forStar();
        } else {
            $parent_menu = $this->getUserMenuDatas($accessGroupEloq);
        }
        return $parent_menu;
    }

    public function forStar()
    {
        $redisKey = '*';
        if (Cache::tags([$this->redisFirstTag])->has($redisKey)) {
            $parent_menu = Cache::tags([$this->redisFirstTag])->get($redisKey);
        } else {
            $menuLists = self::all();
            $parent_menu = self::createMenuDatas($menuLists);
        }
        return $parent_menu;
    }

    /**
     * @param  PartnerAdminGroupAccess  $accessGroupEloq
     * @return array|mixed
     */
    public function getUserMenuDatas(PartnerAdminGroupAccess $accessGroupEloq)
    {
        $redisKey = $accessGroupEloq->id;
        if (Cache::tags([$this->redisFirstTag])->has($redisKey)) {
            $parent_menu = Cache::tags([$this->redisFirstTag])->get($redisKey);
        } else {
            $role = json_decode($accessGroupEloq->role); //[1,2,3,4,5]
            $menuLists = self::whereIn('id', $role)->get();
            $parent_menu = self::createMenuDatas($menuLists, $accessGroupEloq->id);
        }
        return $parent_menu;
    }

    /**
     * @param $menuLists
     * @param  string  $redisKey
     * @return array
     */
    public function createMenuDatas($menuLists, $redisKey = '*')
    {
        $menuForFE = [];
        foreach ($menuLists as $key => $firstMenu) {
            if ($firstMenu->pid === 0) {
                $menuForFE[$firstMenu->id] = $firstMenu->toArray();
                if ($firstMenu->childs()->exists()) {
                    foreach ($firstMenu->childs as $secondMenu) {
                        $menuForFE[$firstMenu->id]['child'][$secondMenu->id] = $secondMenu->toArray();
                        if ($secondMenu->childs()->exists()) {
                            foreach ($secondMenu->childs as $thirdMenu) {
                                $menuForFE[$firstMenu->id]['child'][$secondMenu->id]['child'][$thirdMenu->id] = $thirdMenu->toArray();
                            }
                        }
                    }
                }
            }
        }
        //            $hourToStore = 24;
//            $expiresAt = Carbon::now()->addHours($hourToStore)->diffInMinutes();
//            Cache::put('ms_menus', $parent_menu, $expiresAt);
        Cache::tags([$this->redisFirstTag])->forever($redisKey, $menuForFE);
//        Cache::forever($redisKey, $menuForFE);
        return $menuForFE;
    }

    /**
     * @return bool
     */
    public function refreshStar(): bool
    {
        Cache::tags([$this->redisFirstTag])->flush();
        return true;
    }

    public static function getFirstLevelList()
    {
        return self::where('pid', 0)->get();
    }

    public function childs()
    {
        $data = $this->hasMany(__CLASS__, 'pid', 'id');
        return $data;
    }
}
