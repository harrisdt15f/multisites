<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class ActivityInfosController extends ApiMainController {
	protected $eloqM = 'ActivityInfos';
	//活动列表
	public function detail() {
		$datas = $this->eloqM::all()->toArray();
		if (empty($datas)) {
			return $this->msgout(false, [], '没有获取到数据', '0009');
		}
		return $this->msgout(true, $datas);
	}
	//添加活动
	public function add() {
		$validator = Validator::make($this->inputs, [
			'title' => 'required',
			'type' => 'required|numeric',
			'content' => 'required',
			'pic' => 'required',
			'start_time' => 'required',
			'end_time' => 'required',
			'status' => 'required',
			'redirect_url' => 'required',
			'is_time_interval' => 'required|numeric',
		]);
		if ($validator->fails()) {
			return $this->msgout(false, [], $validator->errors()->first());
		}
		$pastData = $this->eloqM::where('title', $this->inputs['title'])->first();
		if (!is_null($pastData)) {
			return $this->msgout(false, [], '该活动名已存在', '0009');
		}
		//接收文件信息
		$file = $this->inputs['pic'];
		$path = 'uploaded_files/mobile_activity_' . $this->currentPlatformEloq->platform_id . '_' . $this->currentPlatformEloq->platform_name;
		$rule = ['jpg', 'png', 'gif'];
		//进行上传
		$pic = $this->upload_img($file, $path, $rule);
		if (!$pic['path'] || is_null($pic['path'])) {
			return $this->msgout(false, [], '图片上传失败', '0009');
		}
		$addDatas = [
			'title' => $this->inputs['title'],
			'type' => $this->inputs['type'],
			'content' => $this->inputs['content'],
			'pic_url' => '/' . $pic['path'],
			'start_time' => $this->inputs['start_time'],
			'end_time' => $this->inputs['end_time'],
			'status' => $this->inputs['status'],
			'admin_id' => $this->partnerAdmin['id'],
			'admin_name' => $this->partnerAdmin['name'],
			'redirect_url' => $this->inputs['redirect_url'],
			'is_time_interval' => $this->inputs['is_time_interval'],
		];
		try {
			$configure = new $this->eloqM();
			$configure->fill($addDatas);
			$configure->save();
			return $this->msgout(true, [], '添加活动成功');
		} catch (\Exception $e) {
			$errorObj = $e->getPrevious()->getPrevious();
			[$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
			return $this->msgout(false, [], $msg, $sqlState);
		}

		exit;
	}
	//编辑活动
	public function edit() {
		$validator = Validator::make($this->inputs, [
			'id' => 'required|numeric',
			'title' => 'required',
			'type' => 'required|numeric',
			'content' => 'required',
			'start_time' => 'required',
			'end_time' => 'required',
			'status' => 'required',
			'redirect_url' => 'required',
			'is_time_interval' => 'required|numeric',
		]);
		if ($validator->fails()) {
			return $this->msgout(false, [], $validator->errors()->first());
		}
		$pastData = $this->eloqM::where('title', $this->inputs['title'])->where('id', '!=', $this->inputs['id'])->first();
		if (!is_null($pastData)) {
			return $this->msgout(false, [], '该活动名已存在', '0009');
		}
		$editDataEloq = $this->eloqM::find($this->inputs['id']);
		$editDataEloq->title = $this->inputs['title'];
		$editDataEloq->type = $this->inputs['type'];
		$editDataEloq->content = $this->inputs['content'];
		$editDataEloq->start_time = $this->inputs['start_time'];
		$editDataEloq->end_time = $this->inputs['end_time'];
		$editDataEloq->status = $this->inputs['status'];
		$editDataEloq->redirect_url = $this->inputs['redirect_url'];
		$editDataEloq->is_time_interval = $this->inputs['is_time_interval'];
		//如果修改了图片
		if (isset($this->inputs['pic']) && !is_null($this->inputs['pic'])) {
			$pastpic = $editDataEloq->pic_url;
			//接收文件信息
			$file = $this->inputs['pic'];
			$path = 'uploaded_files/mobile_activity_' . $this->currentPlatformEloq->platform_id . '_' . $this->currentPlatformEloq->platform_name;
			$rule = ['jpg', 'png', 'gif'];
			//进行上传
			$pic = $this->upload_img($file, $path, $rule);
			if (!$pic['path'] || is_null($pic['path'])) {
				return $this->msgout(false, [], '图片上传失败', '0009');
			}
			$editDataEloq->pic_url = '/' . $pic['path'];
		}
		try {
			$editDataEloq->save();
			if (isset($this->inputs['pic']) && !is_null($this->inputs['pic'])) {
				$this->delete_file(substr($pastpic, 1));
			}
			return $this->msgout(true, [], '修改活动成功');
		} catch (\Exception $e) {
			$errorObj = $e->getPrevious()->getPrevious();
			[$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
			return $this->msgout(false, [], $msg, $sqlState);
		}
	}
	//删除活动
	public function delete() {
		$validator = Validator::make($this->inputs, [
			'id' => 'required|numeric',
		]);
		if ($validator->fails()) {
			return $this->msgout(false, [], $validator->errors()->first(), 200);
		}
		$pastData = $this->eloqM::find($this->inputs['id']);
		if (!is_null($pastData)) {
			try {
				$this->eloqM::where('id', $this->inputs['id'])->delete();
				$this->delete_file(substr($pastData['pic_url'], 1));
				return $this->msgout(true, [], '删除活动成功');
			} catch (\Exception $e) {
				$errorObj = $e->getPrevious()->getPrevious();
				[$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
				return $this->msgout(false, [], $msg, $sqlState);
			}
		} else {
			return $this->msgout(false, [], '该活动不存在', '0009');
		}
	}
	//图片上传
	public function upload_img($file, $url_path, $rule) {
		// 检验一下上传的文件是否有效.
		if ($file->isValid()) {
			// 缓存在tmp文件夹中的文件名 例如 php8933.tmp 这种类型的.
			$clientName = $file->getClientOriginalName();
			$tmpName = $file->getFileName();
			// 这个表示的是缓存在tmp文件夹下的文件的绝对路径(这里要注意,如果我使用接下来的move方法之后, getRealPath() 就找不到文件的路径了.因为文件已经被移走了.所以这里道出了文件上传的原理,将文件上传的某个临时目录中,然后使用Php的函数将文件移动到指定的文件夹.)
			$realPath = $file->getRealPath();
			// 上传文件的后缀.
			$entension = $file->getClientOriginalExtension();

			if (!in_array($entension, $rule)) {

				return '图片格式为jpg,png,gif';
			}
			// 大家对mimeType应该不陌生了. 我得到的结果是 image/jpeg.(这里要注意一点,以前我们使用 mime_content_type() ,在php5.3 之后,开始使用 fileinfo 来获取文件的mime类型.所以要加入 php_fileinfo的php拓展.windows下是 php_fileinfo.dll,在php.ini文件中将 extension=php_fileinfo.dll前面的分号去掉即可.当然要重启服务器. )
			$mimeTye = $file->getMimeType();
			// (第一种)最后我们使用
			//$path = $file -> move('storage/uploads');
			// 如果你这样写的话,默认是会放置在 我们 public/storage/uploads/php79DB.tmp
			// 貌似不是我们希望的,如果我们希望将其放置在app的storage目录下的uploads目录中,并且需要改名的话..
			//(第二种)
			$newName = md5(date("Y-m-d H:i:s") . $clientName) . "." . $entension;
			$path = $file->move($url_path, $newName);
			// 这里public_path()就是public文件夹所在的路径.$newName 通过算法获得的文件的名称.主要是不能重复产生冲突即可.
			// 利用日期和客户端文件名结合 使用md5 算法加密得到结果.后面加上文件原始的拓展名.
			//文件名
			$namePath = $url_path . '/' . $newName;
			return ['name' => $newName, 'path' => $namePath];
		}
	}
	public function delete_file($path) {
		if (file_exists($path)) {
			return unlink($path);
		}
	}
}