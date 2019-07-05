<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 7/3/2019
 * Time: 8:56 PM
 */

namespace App\Models\User\Supports\Logics;

trait FrontendUserHelpCenterLogics
{
    /**
     * 获取用户帮助中心数据
     * @param $status
     * @return array
     */
    public function getHelpCenterData($status = 0): array
    {
        $query = $this->select('id','pid','menu','status')->with('children:id,pid,menu,content,status')->where('pid',0);
        if ($status === 1) {
            return $query->with(['children' => function($query) {
                $query->where('status',1);
            }])->where('status',1)->get()->toArray();
        }
        return $query->get()->toArray();
    }

}