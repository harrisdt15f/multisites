<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Backend\Menu;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Backend\BackendAdminRoute;
use App\Models\DeveloperUsage\Menu\PartnerMenus;
use function GuzzleHttp\json_decode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class MenuController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\Menu\PartnerMenus';

    public function getAllMenu()
    {
        return $this->msgOut(true, $this->fullMenuLists);
    }

    public function currentPartnerMenu()
    {
        return $this->msgOut(true, $this->partnerMenulists);
    }

    /**
     * @return JsonResponse
     */
    public function allRequireInfos(): JsonResponse
    {
        $rule = [
            'type' => 'required|integer|in:1,2,3,0',
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $routeCollection = Route::getRoutes()->get();
        if ($this->inputs['type'] == 0) {
            foreach ($routeCollection as $key => $r) {
                if (isset($r->action['as']) && $r->action['prefix'] !== '_debugbar') {
                    $routeShortData[$key]['url'] = $r->uri;
                    $routeShortData[$key]['controller'] = $r->action['controller'];
                    $routeShortData[$key]['route_name'] = $r->action['as'];
                    $routeInfo[] = $routeShortData[$key];
                }
            }
        } else {
            $type = [
                1 => 'backend-api',
                2 => 'web-api',
                3 => 'mobile-api',
            ];
            $routeEndKey = $type[$this->inputs['type']] ?? $type[1];
//        $firstlevelmenus = PartnerMenus::getFirstLevelList();

//        $editMenu = PartnerMenus::all();
            //        $routeMatchingName = $editMenu->where('route', '!=', '#')->keyBy('route')->toArray();
            $routeInfo = [];
            $registeredRoute = BackendAdminRoute::pluck('route_name')->toArray();
            foreach ($routeCollection as $key => $r) {
                if (isset($r->action['as']) && $r->action['prefix'] !== '_debugbar' && preg_match('#^' . $routeEndKey . '#',
                    $r->action['as']) === 1 && !in_array($r->action['as'], $registeredRoute)) {
                    $routeShortData[$key]['url'] = $r->uri;
                    $routeShortData[$key]['controller'] = $r->action['controller'];
                    $routeShortData[$key]['route_name'] = $r->action['as'];
                    $routeInfo[] = $routeShortData[$key];
//                $routeInfo[$r->action['as']] = $r->uri;
                }
            }
        }
//        $data['firstlevelmenus'] = $firstlevelmenus;
        $data['route_info'] = $routeInfo;
//        $data['editMenu'] = $editMenu;
        //        $data['routeMatchingName'] = $routeMatchingName;
        return $this->msgOut(true, $data);
    }

    public function add()
    {
        $parent = false;
        $rule = [
            'label' => 'required|regex:/[\x{4e00}-\x{9fa5}]+/u', //操作日志
            'en_name' => 'required|regex:/^(?!\.)(?!.*\.$)(?!.*?\.\.)[a-z.-]+$/', //operation.log
            'display' => 'required|numeric|in:0,1',
            'route' => 'required|regex:/^(?!.*\/$)(?!.*?\/\/)[a-z\/-]+$/', // /operasyon/operation-log
            'icon' => 'regex:/^(?!\-)(?!.*\-$)(?!.*?\-\-)(?!\ )(?!.*\ $)(?!.*?\ \ )[a-z0-9 -]+$/',
            'sort' => 'required|integer',
            //anticon anticon-appstore  icon-6-icon
        ];
        if (isset($this->inputs['isParent']) && $this->inputs['isParent'] === '1') {
            $parent = true;
        } else {
            $rule['parentId'] = 'required|numeric';
            $rule['level'] = 'required|numeric|in:1,2,3';
        }
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $MenuEloq = $this->eloqM::where('label', $this->inputs['label'])->first();
        if (!is_null($MenuEloq)) {
            return $this->msgOut(false, [], '100800');
        }
        $menuEloq = new PartnerMenus();
        $menuEloq->label = $this->inputs['label'];
        $menuEloq->en_name = $this->inputs['en_name'];
        $menuEloq->route = $this->inputs['route'];
        $menuEloq->display = $this->inputs['display'];
        $menuEloq->icon = $this->inputs['icon'] ?? null;
        $menuEloq->sort = $this->inputs['sort'];
        if ($parent === false) {
            $menuEloq->pid = $this->inputs['parentId'];
            $menuEloq->level = $this->inputs['level'];
        }
        try {
            $menuEloq->save();
            $menuEloq->refreshStar();
            return $this->msgOut(true, $menuEloq->toArray());
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function delete()
    {
        $rule = [
            'toDelete' => 'required|array',
            'toDelete.*' => 'int',
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $menuEloq = new PartnerMenus();
        $toDelete = $this->inputs['toDelete'];
        if (!empty($toDelete)) {
            try {
                $datas = $menuEloq->find($toDelete)->each(function ($product, $key) {
                    $data[] = $product->toArray();
                    $product->delete();
                    return $data;
                });
                $menuEloq->refreshStar();
                return $this->msgOut(true, $datas);
            } catch (\Exception $e) {
                return $this->msgOut(false, [], '0002', $e->getMessage());
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
    public function edit():  ? JsonResponse
    {
        $parent = false;
        $rule = [
            'label' => 'required|regex:/[\x{4e00}-\x{9fa5}]+/u', //操作日志
            'en_name' => 'required|regex:/^(?!\.)(?!.*\.$)(?!.*?\.\.)[a-z.-]+$/', //operation.log
            'display' => 'required|numeric|in:0,1',
            'menuId' => 'required|numeric',
            'route' => 'required|regex:/^(?!.*\/$)(?!.*?\/\/)[a-z\/-]+$/', // /operasyon/operation-log
            'icon' => 'regex:/^(?!\-)(?!.*\-$)(?!.*?\-\-)(?!\ )(?!.*\ $)(?!.*?\ \ )[a-z0-9 -]+$/',
            //anticon anticon-appstore  icon-6-icon
        ];
        if (isset($this->inputs['isParent']) && $this->inputs['isParent'] === '1') {
            $rule['isParent'] = 'required|numeric|in:0,1';
            $parent = true;
        } else {
            $rule['parentId'] = 'required|numeric';
        }
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $menuEloq = PartnerMenus::find($this->inputs['menuId']);
        $menuEloq->label = $this->inputs['label'];
        $menuEloq->en_name = $this->inputs['en_name'];
        $menuEloq->display = $this->inputs['display'];
        $menuEloq->icon = $this->inputs['icon'] ?? null;
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
            return $this->msgOut(true, $data);
        } else {
            return $this->msgOut(false, [], '100801');
        }
    }

    public function changeParent() :  ? JsonResponse
    {
        $parseDatas = json_decode($this->inputs['dragResult'], true);
        $itemProcess = [];
        $atLeastOne = false;
        if (!empty($parseDatas)) {
            foreach ($parseDatas as $key => $value) {
                $menuEloq = PartnerMenus::find($value['currentId']);
                $menuEloq->pid = $value['currentParent'] === '#' ? 0 : (int) $value['currentParent'];
                $menuEloq->sort = $value['currentSort'];
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
            return $this->msgOut(true, $itemProcess);
        }
    }

}
