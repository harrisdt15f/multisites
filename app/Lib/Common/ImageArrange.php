<?php
namespace App\Lib\Common;

class ImageArrange
{

    //图片上传
    public function uploadImg($file, $path)
    {
        // 检验一下上传的文件是否有效.
        if ($file->isValid()) {
            $folder = 'uploaded_files';
            if (file_exists($folder)) {
                if (!is_writable($folder)) {
                    return ['success' => false, 'msg' => '文件夹' . $folder . '没有写入权限'];
                } else {
                    if (!is_writable($path)) {
                        mkdir($path, 0777, true);
                        chmod($path, 0777);
                    }
                }
            } else {
                return ['success' => false, 'msg' => '文件夹' . $folder . '不存在'];
            }
            // 缓存在tmp文件夹中的文件名 例如 php8933.tmp 这种类型的.
            $clientName = $file->getClientOriginalName();
            // 上传文件的后缀.
            $entension = $file->getClientOriginalExtension();
            $newName = md5(date("Y-m-d H:i:s") . $clientName) . "." . $entension;
            $file->move($path, $newName);
            //文件名
            $namePath = $path . '/' . $newName;
            return ['success' => true, 'name' => $newName, 'path' => $namePath];
        }
    }

    /**
     *
     * 制作缩略图
     * @param $srcPath string 原图路径
     * @param $maxWidth int 画布的宽度
     * @param $maxHight int 画布的高度
     * @param $flag bool 是否是等比缩略图  默认为true
     * @param $prefix string 缩略图的前缀  默认为'sm_'
     *
     */
    public function creatThumbnail($srcPath, $maxWidth, $maxHight, $prefix = 'sm_', $flag = true)
    {
        //获取文件的后缀
        $arr = explode('.', $srcPath);
        $picType = end($arr);
        if ($picType === 'jpg') {
            $picType = 'jpeg';
        }
        //拼接打开图片的函数
        $open_fn = 'imagecreatefrom' . $picType;
        //打开源图
        $src = $open_fn($srcPath);
        //源图的宽
        $src_w = imagesx($src);
        //源图的高
        $src_h = imagesy($src);
        //是否等比缩放
        if ($flag) {
            //等比
            //求目标图片的宽高
            if ($maxWidth / $maxHight < $src_w / $src_h) {
                //横屏图片以宽为标准
                $dst_w = $maxWidth;
                $dst_h = $maxWidth * $src_h / $src_w;
            } else {
                //竖屏图片以高为标准
                $dst_h = $maxHight;
                $dst_w = $maxHight * $src_w / $src_h;
            }
            //在目标图上显示的位置
            // $dst_x = (int) (($maxWidth - $dst_w) / 2);
            // $dst_y = (int) (($maxHight - $dst_h) / 2);
        } else {
            //不等比
            // $dst_x = 0;
            // $dst_y = 0;
            $dst_w = $maxWidth;
            $dst_h = $maxHight;
        }
        $dst_x = 0;
        $dst_y = 0;
        //创建目标图
        $dst = imagecreatetruecolor($dst_w, $dst_h);
        //生成缩略图
        $fool = imagecopyresampled($dst, $src, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        //文件名
        $filename = basename($srcPath);
        //文件夹名
        $foldername = substr(dirname($srcPath), 0);
        //缩略图存放路径
        $thumb_path = $foldername . '/' . $prefix . $filename;
        //把缩略图上传到指定的文件夹
        imagepng($dst, $thumb_path);
        //销毁图片资源
        imagedestroy($dst);
        imagedestroy($src);
        //返回新的缩略图的文件名
        return $thumb_path;
    }

    //删除文件
    public function deletePic($path)
    {
        if (file_exists($path)) {
            if (!is_writable(dirname($path))) {
                return false;
            } else {
                unlink($path);
                return true;
            }
        } else {
            return false;
        }
    }

    //生成存放图片的路径
    public function depositPath($name, $platform_id, $platform_name)
    {
        return 'uploaded_files/' . $platform_name . '_' . $platform_id . '/' . $name . '_' . $platform_name . '_' . $platform_id;
    }
}
