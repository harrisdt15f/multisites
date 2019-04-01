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
     * @return array
     * TODO : 由于快速开发 后续需要弄缓存与异常处理
     */
    public function menuLists()
    {
        $parent_menu = [];
        $groupId = Auth::guard('web')->user()->group_id;
        if ($groupId === 1) {
            $parent_menu = $this->forStar();
        } else {

        }
        return $parent_menu;
    }

    public function forStar()
    {
        $redisKey = $this->redisFirstTag. '*';
        if (Cache::has($redisKey)) {
            $parent_menu = Cache::get($redisKey);
        } else {
            $menuLists = self::all();
            $parent_menu = self::createMenuDatas($menuLists);
        }
        return $parent_menu;
    }

    public function createMenuDatas($menuLists, $tag = '*')
    {
        $redisKey = $this->redisFirstTag . $tag;
        $menuForFE = [];
        foreach ($menuLists as $key => $value) {
            if ($value->pid === 0) {
                $menuForFE[$value->id]['label'] = $value->label;
                $menuForFE[$value->id]['route'] = $value->route;
                $menuForFE[$value->id]['class'] = $value->class;
                $menuForFE[$value->id]['pid'] = $value->pid;
            } else {
                $menuForFE[$value->pid]['child'][$value->id]['label'] = $value->label;
                $menuForFE[$value->pid]['child'][$value->id]['route'] = $value->route;
                $menuForFE[$value->pid]['child'][$value->id]['class'] = $value->class;
                $menuForFE[$value->pid]['child'][$value->id]['pid'] = $value->pid;
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
}
