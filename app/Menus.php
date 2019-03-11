<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use LaravelArdent\Ardent\Ardent;

class Menus extends Ardent
{
    protected $table = 'admin_menus';

    /**
     * @return array
     * TODO : 由于快速开发 后续需要弄缓存与异常处理
     */
    public static function menuLists()
    {
        $hourToStore=24;
        $parent_menu = [];
        if (Cache::has('ms_menus')) {
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
        }
        return $parent_menu;
    }
}
