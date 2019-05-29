<?php

namespace App\Models\DeveloperUsage\Frontend\Traits;

/**
 * @Author: LingPh
 * @Date:   2019-05-29 17:38:37
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-05-29 17:39:54
 */
trait FrontendModelTraits
{

    public function allFrontendModel()
    {
        $parentFrontendModel = self::Parent();
        $frontendModelList = [];
        foreach ($parentFrontendModel as $id => $frontendModel) {
            $frontendModelList[$id] = $frontendModel;
            $frontendModelList[$id]['childs'] = $frontendModel->childs;
            foreach ($frontendModelList[$id]['childs'] as $grandsonId => $grandsonFrontendModel) {
                $frontendModelList[$id]['childs'][$grandsonId] = $grandsonFrontendModel;
                $frontendModelList[$id]['childs'][$grandsonId]['childs'] = $grandsonFrontendModel->childs;
            }
        }
        return $frontendModelList;
    }

    public function Parent()
    {
        return self::where('level', 1)->get();
    }
}
