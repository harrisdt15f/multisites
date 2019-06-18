<?php

/**
 * @Author: LingPh
 * @Date:   2019-05-30 14:43:03
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-18 17:13:00
 */

namespace App\Models\DeveloperUsage\MethodLevel\Traits;

trait MethodLevelLogics
{

    /**
     * @return array $data
     */
    public function methodLevelDetail(): array
    {
        $methodtype = $this->groupBy('method_id')->orderBy('id', 'asc')->get();
        $data = [];
        foreach ($methodtype as $method) {
            $data[$method->method_id] = $this->select('id', 'method_id', 'level', 'position', 'count', 'prize')->where('method_id', $method->method_id)->get()->toArray();
        }
        return $data;
    }
}
