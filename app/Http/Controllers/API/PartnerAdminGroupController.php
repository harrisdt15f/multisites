<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\PartnerAdminGroupAccess;
use App\models\PartnerMenus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class PartnerAdminGroupController extends ApiMainController
{
    protected $postUnaccess = ['id', 'updated_at', 'created_at'];//不需要接收的字段

    protected $eloqM = 'PartnerAdminGroupAccess';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = $this->eloqM::all()->toArray();
        return $this->msgout(true, $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $column = $this->accessOnlyColumn();
        $data = Input::only($column);
        $data['platform_id'] = $this->currentPlatformEloq->platform_id;
        $role = json_decode($data['role']); //[1,2,3,4,5]
        $objPartnerAdminGroup = new $this->eloqM;
        $objPartnerAdminGroup->fill($data);
        try {
            $objPartnerAdminGroup->save();
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo;
            return $this->msgout(false, [], $msg, $sqlState);
        }
        $partnerAccessGroupEloq = PartnerMenus::whereIn('id', $role)->get();
        $partnerMenuObj = new PartnerMenus();
        $partnerMenuObj->createMenuDatas($partnerAccessGroupEloq, $objPartnerAdminGroup->id);
        $objPartnerAdminGroup->save();
        return $this->msgout(true, $data);
    }

    protected function accessOnlyColumn()
    {
        $partnerAdminAccess = new PartnerAdminGroupAccess();
        $column = $partnerAdminAccess->getTableColumns();
        $column = array_values(array_diff($column, $this->postUnaccess));
        return $column;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {

    }

}
