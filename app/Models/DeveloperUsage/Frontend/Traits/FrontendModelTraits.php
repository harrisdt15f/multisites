<?php

namespace App\Models\DeveloperUsage\Frontend\Traits;

/**
 * @Author: LingPh
 * @Date:   2019-05-29 17:38:37
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-05-30 11:00:00
 */
trait FrontendModelTraits
{

    public function allFrontendModel($type)
    {
        if ($type == 2) {
            $typeArr = [1, 2];
        } elseif ($type == 3) {
            $typeArr = [1, 3];
        }
        $parentFrontendModel = self::ParentModel($typeArr);
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

    public function ParentModel($typeArr)
    {
        return self::where('level', 1)->whereIn('type', $typeArr)->get();
    }
}
