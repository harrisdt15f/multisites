<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\ApiMainController;
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




}
