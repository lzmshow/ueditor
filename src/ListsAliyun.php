<?php
/**
 * Created by PhpStorm.
 * User: pc335
 * Date: 2015/11/11
 * Time: 10:16
 */

namespace Ender\UEditor;

use App\Services\OSS;

class ListsAliyun
{
    public function __construct($allowFiles, $listSize, $path, $request)
    {
        $this->allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);
        $this->listSize = $listSize;
        $this->path = ltrim($path,'/');
        $this->request = $request;
    }

    public function getList()
    {
        $items = OSS::getAllObjectKey(Config('ueditor.core.aliyun.bucket'));
        $start = 0;
        if(empty($items)){
            return [
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => 0
            ];
        }

        $files=[];
        foreach ($items as  $v) {
            $files[] = array(
                'url' => OSS::getUrl($v),
               // 'mtime' => $v['mimeType'],
            );
        }
        /* 返回数据 */
        $result = [
            "state" => "SUCCESS",
            "list" => $files,
            "start" => $start,
            "total" => count($files)
        ];

        return $result;
    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    protected function  getfiles($path, $allowFiles, &$files = array())
    {

        if (!is_dir($path)) return null;
        if (substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->getfiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(" . $allowFiles . ")$/i", $file)) {
                        $files[] = array(
                            'url' => substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                            'mtime' => filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }
}