<?php

namespace swordfly1979;

use swordfly1979\Str;

/**
 * Class name File.php
 * Created by PhpStorm.
 * User: 道法自然
 * Date: 2020/5/7
 */

class File
{
    /**
     * 递归获取服务器文件
     * @param string $path 根目录
     * @param array $allowFiles 文件类型
     * @param array $excludePath 排除的目录
     * @param array $files 文件列表
     * @return array
     */
    public function getFiles($path = '', $allowFiles = [], $excludePath = [], &$files = [], &$mtime = [])
    {
        static $i = 0;
        if ($i == 0) {
            if (!empty($allowFiles)) $allowFiles = substr(str_replace(".", "|", implode("", $allowFiles)), 1);
            $excludePath = array_merge($excludePath, ['.', '..']);
        }
        if (!is_dir($path)) return $files;
        if (substr($path, strlen($path) - 1) != '/') $path .= '/';
        if ($handle = opendir($path)) {
            while (($file = readdir($handle)) !== false) {
                $newPath = $path . $file;
                if (is_dir($newPath)) {
                    if (in_array($file, $excludePath)) continue;
                    $i++;
                    $this->getFiles($newPath, $allowFiles, $excludePath, $files, $mtime);
                } else {
                    if (preg_match("/\.(" . $allowFiles . ")$/i", $file)) {
                        $files[] = [
                            'url' => substr($newPath, strlen($_SERVER['DOCUMENT_ROOT'])),
                            'mtime' => fileatime($newPath)
                        ];
                        $mtime[] = fileatime($newPath);
                    }
                }
            }
        }
        closedir($handle);
        array_multisort($mtime, SORT_DESC, SORT_NUMERIC, $files);
        return $files;
    }
}
