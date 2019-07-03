<?php
/**
 * @Author: Fish
 * @Date:   2019/7/3 18:46
 */

namespace App\Models\User;


use App\Models\BaseModel;

class FrontendUsersHelpCenter extends BaseModel
{
    protected $guarded = ['id'];

    /**
     * 帮助中心生成菜单树
     * @param $data
     * @return array
     */
    public function toTree($data): array
    {
        $list = array_column($data, null, 'id');
        foreach ($list as $key => $val) {
            if(isset($list[$val['pid']])){
                $list[$val['pid']]['children'][] = &$list[$key];
            }
        }
        foreach($list as $key=>$val){
            if($val['pid']) unset($list[$key]);
        }
        return array_values($list);
    }
}