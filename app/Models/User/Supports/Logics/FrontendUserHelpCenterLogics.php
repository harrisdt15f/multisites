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
     * @return array
     */
    public function getHelpCenterData(): array
    {
        return $this->select('id','pid','menu')->with('children:id,pid,menu,content')->where('pid',0)->get()->toArray();
    }

}