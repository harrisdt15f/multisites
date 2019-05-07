<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\PartnerMenus;
use function GuzzleHttp\json_decode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class MenuController extends ApiMainController
{
    protected $eloqM = 'PartnerMenus';

    public function getAllMenu()
    {
        $data = [
            'success' => true,
            'data' => $this->fullMenuLists,
        ];
        return response()->json($data);
    }

    public function currentPartnerMenu()
    {
        $data = [
            'success' => true,
            'data' => $this->partnerMenulists,
        ];
        return response()->json($data);
    }


    /**
     * @return JsonResponse
     */
    public function allRequireInfos(): JsonResponse
    {
        $firstlevelmenus = PartnerMenus::getFirstLevelList();
        $routeCollection = Route::getRoutes()->get();
        $editMenu = PartnerMenus::all();
        $routeMatchingName = $editMenu->where('route', '!=', '#')->keyBy('route')->toArray();
        $rname = [];
        foreach ($routeCollection as $key => $r) {
            if (isset($r->action['as'])) {
                if ($r->action['prefix'] !== '_debugbar') {
                    $rname[$r->action['as']] = $r->uri;
                }
            }
        }
        $data['firstlevelmenus'] = $firstlevelmenus;
        $data['rname'] = $rname;
        $data['editMenu'] = $editMenu;
        $data['routeMatchingName'] = $routeMatchingName;
        return $this->msgout(true, $data);
    }

    public function add()
    {
        $parent = false;
        $rule = [
            'label' => 'required|regex:/[\x{4e00}-\x{9fa5}]+/u',//操作日志
            'en_name' => 'required|regex:/^(?!\.)(?!.*\.$)(?!.*?\.\.)[a-z.-]+$/',//operation.log
            'display' => 'required|numeric|in:0,1',
            'route' => 'required|regex:/^(?!.*\/$)(?!.*?\/\/)[a-z\/-]+$/',// /operasyon/operation-log
        ];
        if (isset($this->inputs['isParent']) && $this->inputs['isParent'] === '1') {
            $parent = true;
        } else {
            $rule['parentId'] = 'required|numeric';
            $rule['level'] = 'required|numeric|in:1,2,3';
        }
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgout(false, [], 400, $validator->errors()->first());
        }
        $MenuEloq = $this->eloqM::where('label', $this->inputs['label'])->first();
        if (!is_null($MenuEloq)) {
            return $this->msgout(false, [], '对不起菜单名已存在', '0002');
        }
        $menuEloq = new PartnerMenus();
        $menuEloq->label = $this->inputs['label'];
        $menuEloq->en_name = $this->inputs['en_name'];
        $menuEloq->route = $this->inputs['route'];
        $menuEloq->display = $this->inputs['display'];
        if ($parent === false) {
            $menuEloq->pid = $this->inputs['parentId'];
            $menuEloq->level = $this->inputs['level'];
        }
        try {
            $menuEloq->save();
            $menuEloq->refreshStar();
            return $this->msgout(true, $menuEloq->toArray());
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgout(false, [], $sqlState, $msg);
        }
    }

    public function delete()
    {
        $rule = [
            'toDelete' => 'required|array',
            'toDelete.*' => 'int'
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgout(false, [], 400, $validator->errors()->first());
        }
        $menuEloq = new PartnerMenus();
        $toDelete = $this->inputs['toDelete'];
        if (!empty($toDelete)) {
            try {
                $datas = $menuEloq->find($toDelete)->each(function ($product, $key)  {
                    $data[] = $product->toArray();
                    $product->delete();
                    return $data;
                });
                $menuEloq->refreshStar();
                return $this->msgout(true, $datas);
            } catch (\Exception $e) {
                return $this->msgout(false, [], $e->getMessage(), '0002');
            }
        }
    }

    /**
     *  菜单编辑接口
     * (?!\.) - don't allow . at start
     * (?!.*?\.\.) - don't allow 2 consecutive dots
     * (?!.*\.$) - don't allow . at end
     * @return JsonResponse
     */
    public function edit(): ?JsonResponse
    {
        $parent = false;
        $rule = [
            'label' => 'required|regex:/[\x{4e00}-\x{9fa5}]+/u',//操作日志
            'en_name' => 'required|regex:/^(?!\.)(?!.*\.$)(?!.*?\.\.)[a-z.-]+$/',//operation.log
            'display' => 'required|numeric|in:0,1',
            'menuId' => 'required|numeric',
            'route' => 'required|regex:/^(?!.*\/$)(?!.*?\/\/)[a-z\/-]+$/',// /operasyon/operation-log
        ];
        if (isset($this->inputs['isParent']) && $this->inputs['isParent'] === '1') {
            $rule['isParent'] = 'required|numeric|in:0,1';
            $parent = true;
        } else {
            $rule['parentId'] = 'required|numeric';
        }
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgout(false, [], 400, $validator->errors()->first());
        }
        $menuEloq = PartnerMenus::find($this->inputs['menuId']);
        $menuEloq->label = $this->inputs['label'];
        $menuEloq->en_name = $this->inputs['en_name'];
        $menuEloq->display = $this->inputs['display'];
        if ($parent === true) {
            $menuEloq->route = '#';
            $menuEloq->pid = 0;
        } else {
            $menuEloq->route = $this->inputs['route'];
            $menuEloq->pid = $this->inputs['parentId'];
        }
        $data = $menuEloq->toArray();
        if ($menuEloq->save()) {
            $menuEloq->refreshStar();
            return $this->msgout(true, $data);
        } else {
            return $this->msgout(false, [], '编辑保存有误', '0002');
        }
    }

    public function changeParent()
    {
        $parseDatas = json_decode($this->inputs['dragResult'], true);
        $itemProcess = [];
        $atLeastOne = false;
        if (!empty($parseDatas)) {
            foreach ($parseDatas as $key => $value) {
                $menuEloq = PartnerMenus::find($value['currentId']);
                $menuEloq->pid = $value['currentParent'] === '#' ? 0 : (int)$value['currentParent'];
                if ($menuEloq->save()) {
                    $pass['pass'] = $value['currentText'];
                    $itemProcess[] = $pass;
                    $atLeastOne = true;
                } else {
                    $fail['fail'] = $value['currentText'];
                    $itemProcess[] = $fail;
                }
            }
            if ($atLeastOne === true) {
                $menuEloq->refreshStar();
            }
            return $this->msgout(true, $itemProcess);
        }
    }


}
