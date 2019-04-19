<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\ApiMainController;

class ConfiguresController extends ApiMainController {
	protected $eloqM = 'PartnerSysConfigures';
	//获取全部配置
	public function getConfiguresList() {
		$all = $this->eloqM::all()->toArray();
		foreach ($all as $k1 => $v1) {
			if ($all[$k1]['parent_id'] == 0) {
				$data[] = $all[$k1];
			} else {
				foreach ($data as $k2 => $v2) {
					if ($all[$k1]['parent_id'] == $data[$k2]['id']) {
						$data[$k2]['sub'][] = $all[$k1];
					}
				}
			}
		}
		return $this->msgout(true, $data);
	}
	//添加配置
	public function add() {
		if ($this->inputs) {
			$add = [
				'parent_id' => $this->inputs['parent_id'] ? $this->inputs['parent_id'] : 0,
				'sign' => $this->inputs['sign'],
				'name' => $this->inputs['name'],
				'description' => $this->inputs['description'],
				'value' => $this->inputs['value'],
				'pid' => $this->currentPlatformEloq->platform_id,
				'add_admin_id' => $this->partnerAdmin->id,
				'last_update_admin_id' => $this->partnerAdmin->id,
				'status' => 1,
			];
			$sqlstatus = $this->eloqM::insert($add);
			if ($sqlstatus) {
				return $this->msgout(true, [], '添加配置成功', '200');
			} else {
				return $this->msgout(false, [], '添加配置失败', '0002');
			}
		} else {
			return $this->msgout(false, [], '请传递参数', '0002');
		}
	}
	//修改配置
	public function edit() {
		if ($this->inputs) {
			$id = $this->inputs['id'];
			$edit = [
				'sign' => $this->inputs['sign'],
				'name' => $this->inputs['name'],
				'description' => $this->inputs['description'],
			];
			//不是顶级可修改值
			if ($this->inputs['parent_id'] != 0) {
				$edit['value'] = $this->inputs['value'];
			}
			$sqlstatus = $this->eloqM::where('id', '=', $id)->update($edit);
			if ($sqlstatus) {
				return $this->msgout(true, [], '修改配置成功', '200');
			} else {
				return $this->msgout(false, [], '修改配置失败', '0002');
			}
		} else {
			return $this->msgout(false, [], '请传递参数', '0002');
		}
	}
	//删除配置
	public function delete() {
		if ($this->inputs['id']) {
			$sqlstatus = $this->eloqM::where('id', '=', $this->inputs['id'])->delete();
			if ($sqlstatus) {
				return $this->msgout(true, [], '删除配置成功', '200');
			} else {
				return $this->msgout(false, [], '删除配置失败', '0002');
			}
		} else {
			return $this->msgout(false, [], '请传递参数', '0002');
		}
	}
}