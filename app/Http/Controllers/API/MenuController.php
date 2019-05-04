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
        $menuEloq = new PartnerMenus();
        if (isset($this->inputs['isParent']) && $this->inputs['isParent'] === 'on') {

            $menuEloq->label = $this->inputs['menulabel'];
        } else {
            $menuEloq->label = $this->inputs['menulabel'];
            $menuEloq->route = $this->inputs['route'];
            $menuEloq->pid = $this->inputs['parentid'];
        }
        if ($menuEloq->save()) {
            $menuEloq->refreshStar();
            return response()->json(['success' => true, 'menucreated' => 1]);
        } else {
            return response()->json(['success' => false, 'menucreated' => 0]);
        }
    }

    public function delete()
    {
        $menuEloq = new PartnerMenus();
        $toDelete = json_decode($this->inputs['toDelete'], true);
        if (!empty($toDelete)) {

            try {
                $menuEloq->find($toDelete)->each(function ($product, $key) {
                    $product->delete();
                });
                $menuEloq->refreshStar();
                return response()->json(['success' => true, 'menudeleted' => 1]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'menudeleted' => 0]);
            }
        }
    }

    public function edit()
    {
        $menuEloq = PartnerMenus::find($this->inputs['menuid']);
        if (isset($this->inputs['eisParent']) && $this->inputs['eisParent'] === 'on') {

            $menuEloq->label = $this->inputs['emenulabel'];
            $menuEloq->route = '#';
            $menuEloq->pid = 0;
        } else {
            $menuEloq->label = $this->inputs['emenulabel'];
            $menuEloq->route = $this->inputs['eroute'];
            $menuEloq->pid = $this->inputs['eparentid'];
        }
        if ($menuEloq->save()) {
            $menuEloq->refreshStar();
            return response()->json(['success' => true, 'menuedited' => 1]);
        } else {
            return response()->json(['success' => false, 'menuedited' => 0]);
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
