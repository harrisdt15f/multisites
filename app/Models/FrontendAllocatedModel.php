<?php

namespace App\Models;

class FrontendAllocatedModel extends BaseModel
{
    protected $table = 'frontend_allocated_model';

    protected $fillable = [
        'label', 'en_name', 'pid', 'type', 'level', 'updated_at', 'created_at',
    ];

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
    public function childs()
    {
        $data = $this->hasMany(__CLASS__, 'pid', 'id');
        return $data;
    }
    public function Parent()
    {
        return self::where('level', 1)->get();
    }
}
