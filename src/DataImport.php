<?php

/**
 * Class name DataImport.php
 * Created by vscode.
 * User: 道法自然
 * Date: 2020/6/24
 */

namespace swordfly1979;

use PhpOffice\PhpSpreadsheet\IOFactory;

class DataImport
{
    /**
     * excel简单导入
     * $param['url'] 导入的文件咱径
     * $param['isHead'] 是否包含表头 默认：false
     * $param['format'] 导入的数据格式 ['A'=>'keyName']
     */
    public function excel($param = [])
    {
        $tempArr = explode('.', $param['url']);
        $type = ucfirst(strtolower(array_pop($tempArr)));
        $param['isHead'] = $param['isHead'] ?? false;
        $param['format'] = $param['format'] ?? null;
        $reader = IOFactory::createReader($type);
        $spreadsheet = $reader->load($param['url']);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $list = [];
        if ($param['format'] == null) {
            if ($param['isHead'] == false) {
                array_shift($sheetData);
            } else {
                $sheetData = array_values($sheetData);
            }
            $list = $sheetData;
        } else {
            foreach ($sheetData as $key => $val) {
                if ($param['isHead'] == false && $key == 1) continue;
                $arr = [];
                foreach ($param['format'] as $k => $v) {
                    $arr[$v] = $val[$k];
                }
                $list[] = $arr;
            }
        }
        return $list;
    }
}
