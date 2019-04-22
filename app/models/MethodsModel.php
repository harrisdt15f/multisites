<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class MethodsModel extends BaseModel
{
    protected $table = 'methods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'series_id', 'lottery_name', 'lottery_id', 'method_id', 'method_name', 'method_group', 'method_row, group_sort', 'tab_sort', 'method_sort', 'status'
    ];

    public function lotteriesIds()
    {
        return  $this->hasMany(__CLASS__,'series_id','series_id')->select(['lottery_id'])->groupBy('lottery_id');
    }

    public function methodGroups()
    {
        return  $this->hasMany(__CLASS__,'lottery_id','lottery_id');
    }

    public function methodRows()
    {
        return  $this->hasMany(__CLASS__,'method_group','method_group');
    }

    public function methodDetails()
    {
        return  $this->hasMany(__CLASS__,'method_row','method_row');
    }
}
