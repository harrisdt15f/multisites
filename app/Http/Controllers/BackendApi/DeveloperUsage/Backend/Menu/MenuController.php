<?php

namespace App\Http\Controllers\BackendApi\DeveloperUsage\Backend\Menu;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Menu\MenuAddRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Menu\MenuAllRequireInfosRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Menu\MenuDeleteRequest;
use App\Http\Requests\Backend\DeveloperUsage\Backend\Menu\MenuEditRequest;
use App\Models\DeveloperUsage\Backend\BackendAdminRoute;
use App\Models\DeveloperUsage\Menu\BackendSystemMenu;
use Exception;
use function GuzzleHttp\json_decode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class MenuController extends BackEndApiMainController
{
    protected $eloqM = 'DeveloperUsage\Menu\BackendSystemMenu';

    public function getAllMenu()
    {
        return $this->msgOut(true, $this->fullMenuLists);
    }

    public function currentPartnerMenu()
    {
        return $this->msgOut(true, $this->partnerMenulists);
    }

    /**
     *
     * @param  MenuAllRequireInfosRequest $request
     * @return JsonResponse
     */
    public function allRequireInfos(MenuAllRequireInfosRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $routeCollection = Route::getRoutes()->get();
        if ($inputDatas['type'] == 0) {
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
            $routeEndKey = $type[$inputDatas['type']] ?? $type[1];
//        $firstlevelmenus = BackendSystemMenu::getFirstLevelList();

//        $editMenu = BackendSystemMenu::all();
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

    /**
     * @param   MenuAddRequest $request
     * @return  JsonResponse
     */
    public function add(MenuAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $parent = false;
        if (isset($inputDatas['isParent']) && $inputDatas['isParent'] === '1') {
            $parent = true;
        }
        $MenuEloq = $this->eloqM::where('label', $inputDatas['label'])->first();
        if (!is_null($MenuEloq)) {
            return $this->msgOut(false, [], '100800');
        }
        $menuEloq = new BackendSystemMenu();
        $menuEloq->label = $inputDatas['label'];
        $menuEloq->en_name = $inputDatas['en_name'];
        $menuEloq->route = $inputDatas['route'];
        $menuEloq->display = $inputDatas['display'];
        $menuEloq->icon = $inputDatas['icon'] ?? null;
        $menuEloq->sort = $inputDatas['sort'];
        if ($parent === false) {
            $menuEloq->pid = $inputDatas['parentId'];
            $menuEloq->level = $inputDatas['level'];
        }
        try {
            $menuEloq->save();
            $menuEloq->refreshStar();
            return $this->msgOut(true, $menuEloq->toArray());
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * @param  MenuDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(MenuDeleteRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $menuEloq = new BackendSystemMenu();
        $toDelete = $inputDatas['toDelete'];
        if (!empty($toDelete)) {
            try {
                $datas = $menuEloq->find($toDelete)->each(function ($product, $key) {
                    $data[] = $product->toArray();
                    $product->delete();
                    return $data;
                });
                $menuEloq->refreshStar();
                return $this->msgOut(true, $datas);
            } catch (Exception $e) {
                return $this->msgOut(false, [], '0002', $e->getMessage());
            }
        }
    }

    /**
     *  菜单编辑接口
     * (?!\.) - don't allow . at start
     * (?!.*?\.\.) - don't allow 2 consecutive dots
     * (?!.*\.$) - don't allow . at end
     * @param  MenuEditRequest $request
     * @return JsonResponse
     */
    public function edit(MenuEditRequest $request):  ? JsonResponse
    {
        $inputDatas = $request->validated();
        $parent = false;
        if (isset($inputDatas['isParent']) && $inputDatas['isParent'] === '1') {
            $parent = true;
        }
        $menuEloq = BackendSystemMenu::find($inputDatas['menuId']);
        $menuEloq->label = $inputDatas['label'];
        $menuEloq->en_name = $inputDatas['en_name'];
        $menuEloq->display = $inputDatas['display'];
        $menuEloq->icon = $inputDatas['icon'] ?? null;
        if ($parent === true) {
            $menuEloq->route = '#';
            $menuEloq->pid = 0;
        } else {
            $menuEloq->route = $inputDatas['route'];
            $menuEloq->pid = $inputDatas['parentId'];
        }
        $data = $menuEloq->toArray();
        if ($menuEloq->save()) {
            $menuEloq->refreshStar();
            return $this->msgOut(true, $data);
        } else {
            return $this->msgOut(false, [], '100801');
        }
    }

    /**
     * @return JsonResponse
     */
    public function changeParent() :  ? JsonResponse
    {
        $parseDatas = json_decode($this->inputs['dragResult'], true);
        $itemProcess = [];
        $atLeastOne = false;
        if (!empty($parseDatas)) {
            $menuELoq = new $this->eloqM;
            $itemProcess = $menuELoq->changeParent($parseDatas);
            return $this->msgOut(true, $itemProcess);
        }
    }

}
