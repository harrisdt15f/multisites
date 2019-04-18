<?php

namespace App\models;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PartnerMenus extends BaseModel
{
    protected $table = 'partner_admin_menus';

    protected $redisFirstTag = 'ms_menu.';

    /**
     * @param PartnerAdminGroupAccess $accessGroupEloq
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
        $redisKey = $this->redisFirstTag . '*';
        if (Cache::has($redisKey)) {
            $parent_menu = Cache::get($redisKey);
        } else {
            $menuLists = self::all();
            $parent_menu = self::createMenuDatas($menuLists);
        }
        return $parent_menu;
    }

    /**
     * @param PartnerAdminGroupAccess $accessGroupEloq
     * @return array|mixed
     */
    public function getUserMenuDatas(PartnerAdminGroupAccess $accessGroupEloq)
    {
        $redisKey = $this->redisFirstTag . $accessGroupEloq->id;
        if (Cache::has($redisKey)) {
            $parent_menu = Cache::get($redisKey);
        } else {
            $role = json_decode($accessGroupEloq->role); //[1,2,3,4,5]
            $menuLists = self::whereIn('id', $role)->get();
            $parent_menu = self::createMenuDatas($menuLists);
        }
        return $parent_menu;
    }

    /**
     * @param $menuLists
     * @param string $tag
     * @return array
     */
    public function createMenuDatas($menuLists, $tag = '*')
    {
        $redisKey = $this->redisFirstTag . $tag;
        $menuForFE = [];
        foreach ($menuLists as $key => $value) {
            if ($value->pid === 0) {
                $menuForFE[$value->id]['label'] = $value->label;
                $menuForFE[$value->id]['route'] = $value->route;
                $menuForFE[$value->id]['display'] = $value->display;
                $menuForFE[$value->id]['icon'] = $value->icon;
                $menuForFE[$value->id]['en_name'] = $value->en_name;
                $menuForFE[$value->id]['pid'] = $value->pid;
            } else {
                $menuForFE[$value->pid]['child'][$value->id]['label'] = $value->label;
                $menuForFE[$value->pid]['child'][$value->id]['route'] = $value->route;
                $menuForFE[$value->pid]['child'][$value->id]['display'] = $value->display;
                $menuForFE[$value->pid]['child'][$value->id]['icon'] = $value->icon;
                $menuForFE[$value->pid]['child'][$value->id]['en_name'] = $value->en_name;
                $menuForFE[$value->pid]['child'][$value->id]['pid'] = $value->pid;
                $secondEloq = Self::find($value->id);
                $thirdChildArr = $secondEloq->thirdChild->toArray();
                if (!empty($thirdChildArr)) {
                    foreach ($thirdChildArr as $tcvalue) {
                        $thirdChildMenuIndex = [];
                        $thirdChildMenuIndex[$tcvalue['id']]['label'] = $tcvalue['label'];
                        $thirdChildMenuIndex[$tcvalue['id']]['route'] = $tcvalue['route'];
                        $thirdChildMenuIndex[$tcvalue['id']]['display'] = $tcvalue['display'];
                        $thirdChildMenuIndex[$tcvalue['id']]['icon'] = $tcvalue['icon'];
                        $thirdChildMenuIndex[$tcvalue['id']]['en_name'] = $tcvalue['en_name'];
                        $thirdChildMenuIndex[$tcvalue['id']]['pid'] = $tcvalue['pid'];
                        $menuForFE[$value->pid]['child'][$value->id]['child'] = $thirdChildMenuIndex;
                    }
                }
            }
        }
        //            $hourToStore = 24;
//            $expiresAt = Carbon::now()->addHours($hourToStore)->diffInMinutes();
//            Cache::put('ms_menus', $parent_menu, $expiresAt);
        Cache::forever($redisKey, $menuForFE);
        return $menuForFE;
    }

    /**
     * @return bool
     */
    public function refreshStar(): bool
    {
        Cache::forget('ms_menus.*');
        return true;
    }

    public static function getFirstLevelList()
    {
        return self::where('pid', 0)->get();
    }

    public function thirdChild()
    {
        $data = $this->hasMany(__CLASS__, 'pid', 'id');
        return $data;
    }
}
