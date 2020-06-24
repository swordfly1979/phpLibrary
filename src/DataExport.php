<?php

/**
 * Class name ExportExcel.php
 * Created by vscode.
 * User: 道法自然
 * Date: 2020/6/16
 */

namespace swordfly1979;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DataExport
{
    /**
     * excel简单导出
     * $param['list'] 需要导出的数据 必填项
     * $param['header'] 导出的表头及格式 选填
     * $param['fileName] 导出文件名 选填
     * $param['title'] 导出的表title 选填
     */
    public function excel($param)
    {
        $spreadsheet = new Spreadsheet();
        if (isset($param['title'])) {
            $spreadsheet->getActiveSheet()->setTitle($param['title']);
        }
        $sheet = $spreadsheet->getActiveSheet();
        $fileName = empty($param['fileName']) ? (date('Y-m-d') . '导出数据') : $param['fileName'];
        $header = $param['header'] ?? null;
        $row = 1;
        $count = 0;
        //设置表头
        if ($header == null) {
            $count = count($param['list'][0]);
            $col = 1;
            foreach ($param['list'][0] as $key => $val) {
                $sheet->getCellByColumnAndRow($col, $row)->setValue($key);
                $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);    //设置列宽
                $col++;
            }
        } else {
            $count = count($header);
            foreach ($header as $key => $val) {
                $custom = $sheet->getCellByColumnAndRow($key + 1, $row);
                if ($val['type']) {
                    $custom->setValueExplicit($val['title'], $val['type']);
                } else {
                    $custom->setValue($val['title']);
                }
                //设置列宽
                if (isset($val['width']) && is_numeric($val['width'])) {
                    $sheet->getColumnDimensionByColumn($key + 1)->setWidth($val['width']);
                } else {
                    $sheet->getColumnDimensionByColumn($key + 1)->setAutoSize(true);
                }
            }
        }
        $row++;
        //设置主体数据
        foreach ($param['list'] as $key => $val) {
            if ($header == null) {
                $i = 1;
                foreach ($val as $k => $v) {
                    $sheet->getCellByColumnAndRow($i, $row)->setValue($v);
                    $i++;
                }
            } else {
                foreach ($header as $k => $v) {
                    $custom = $sheet->getCellByColumnAndRow($k + 1, $row);
                    $_val = empty($v['key']) ? "" : (empty($val[$v['key']]) ? "" : $val[$v['key']]);
                    if(empty($_val)) continue;
                    // Str::dump($_val);
                    if ($v['type']) {
                        $custom->setValueExplicit($_val, $v['type']);
                    } else {
                        $custom->setValue($_val);
                    }
                }
            }
            $row++;
        }
        //设置样式
        $sheet->getStyleByColumnAndRow(1, 1, $count, 1)->getFont()->setBold(true);
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"); //告诉浏览器输出07Excel文件
        //header('Content-Type:application/vnd.ms-excel');//告诉浏览器将要输出Excel03版本文件
        header("Content-Disposition: attachment;filename=$fileName.xlsx"); //告诉浏览器输出浏览器名称attachment新窗口打印inline本窗口打印
        header("Cache-Control: max-age=0"); //禁止缓存
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        //清理内容
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }
}
