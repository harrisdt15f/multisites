<?php

namespace App\models;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PartnerMenus extends BaseModel
{
    protected $table = 'partner_admin_menus';
    /**
     * @return array
     * TODO : 由于快速开发 后续需要弄缓存与异常处理
     */
    public function menuLists()
    {
        $hourToStore = 24;
        $parent_menu = [];
        $groupId = Auth::guard('web')->user()->group_id;
        if ($groupId === 1) {
            $parent_menu = $this->forStar();
        } else {
            /*if (Cache::has('ms_menus')) {
                $parent_menu = Cache::get('ms_menus');
            } else {
                $menuLists = self::all();
                foreach ($menuLists as $key => $value) {
                    if ($value->pid === 0) {
                        $parent_menu[$value->id]['label'] = $value->label;
                        $parent_menu[$value->id]['route'] = $value->route;
                        $parent_menu[$value->id]['class'] = $value->class;
                        $parent_menu[$value->id]['pid'] = $value->pid;
                    } else {
                        $parent_menu[$value->pid]['child'][$value->id]['label'] = $value->label;
                        $parent_menu[$value->pid]['child'][$value->id]['route'] = $value->route;
                        $parent_menu[$value->pid]['child'][$value->id]['class'] = $value->class;
                        $parent_menu[$value->pid]['child'][$value->id]['pid'] = $value->pid;
                    }
                }
                $expiresAt = Carbon::now()->addHours($hourToStore)->diffInMinutes();
                Cache::put('ms_menus', $parent_menu, $expiresAt);
            }*/
        }
        return $parent_menu;
    }

    public function forStar()
    {
        $parent_menu = [];
        if (Cache::has('ms_menus.*')) {
            $parent_menu = Cache::get('ms_menus.*');
        } else {
            $menuLists = self::all();
            foreach ($menuLists as $key => $value) {
                if ($value->pid === 0) {
                    $parent_menu[$value->id]['label'] = $value->label;
                    $parent_menu[$value->id]['route'] = $value->route;
                    $parent_menu[$value->id]['class'] = $value->class;
                    $parent_menu[$value->id]['pid'] = $value->pid;
                } else {
                    $parent_menu[$value->pid]['child'][$value->id]['label'] = $value->label;
                    $parent_menu[$value->pid]['child'][$value->id]['route'] = $value->route;
                    $parent_menu[$value->pid]['child'][$value->id]['class'] = $value->class;
                    $parent_menu[$value->pid]['child'][$value->id]['pid'] = $value->pid;
                }
            }
            Cache::forever('ms_menus.*', $parent_menu);
        }
        return $parent_menu;
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
        return self::where('pid',0)->get();
    }
}
