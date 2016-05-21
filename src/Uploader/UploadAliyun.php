<?php
/**
 * Created by PhpStorm.
 * User: pc335
 * Date: 2015/11/6
 * Time: 9:51
 */

namespace Ender\UEditor\Uploader;

use App\Services\OSS;
/**
 *
 *
 * trait UploadAliyun
 *
 * 七牛 上传 类
 *
 * @package Ender\UEditor\Uploader
 */
trait UploadAliyun
{
    /**
     * 获取文件路径
     * @return string
     */
    protected function getFilePath()
    {
        $fullName = $this->fullName;


        $fullName = ltrim($fullName, '/');


        return $fullName;
    }

    public function uploadAliyun($key, $filePath)
    {
        OSS::upload($key, $filePath);
        $this->fullName=  $fullName = OSS::getUrl($key);
        $this->stateInfo = $this->stateMap[0];
        return true;
    }
}