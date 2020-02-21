<?php
/**
 * Class name Date.php
 * Created by PhpStorm.
 * User: 道法自然
 * Date: 2020/2/20
 */

namespace swordfly1979\lib;

/**
 * 查询指定时间范围内的所有日期，月份，季度，年份
 *
 * @param $startDate   指定开始时间，Y-m-d格式或时间戳
 * @param $endDate     指定结束时间，Y-m-d格式格式或时间戳
 * @param $type        类型，day 天，month 月份，quarter 季度，year 年份
 * @param $returnType  返回日期格式默认'Y-m-d' o为返回时间戳
 * @return array
 */
class Date
{
    static public function getDateArr($startDate = '', $endDate = '', $type = '',$returnType='Y-m-d')
    {
        if (is_int($startDate) && !(new self())->isTimeStamp($startDate)) {
            return '开始日期格式不正确';
        }
        if (!is_int($startDate) && !(new self())->isTimeStamp(strtotime($startDate))) {
            return '开始日期格式不正确';
        }
        if (!is_int($startDate)) {
            $startDate = strtotime($startDate);
        }
        if (is_int($endDate) && !(new self())->isTimeStamp($endDate)) {
            return '结束日期格式不正确';
        }
        if (!is_int($endDate) && !(new self())->isTimeStamp(strtotime($endDate))) {
            return '结束日期格式不正确';
        }
        if (!is_int($endDate)) {
            $endDate = strtotime($endDate);
        }
        $tempDate = $startDate;
        $returnData = [];
        $i = 0;
        dump($i);
        if ($type == 'day') {    // 查询所有日期
            while ($tempDate < $endDate) {
                $tempDate = strtotime('+' . $i . ' day', $startDate);
                if($returnType===0){
                    $returnData[] = $tempDate;
                }else{
                    $returnData[] = date($returnType,$tempDate);
                }
                $i++;
            }
        } elseif ($type == 'month') {    // 查询所有月份以及开始结束时间
            while (strtotime($tempDate) < strtotime($endDate)) {
                $temp = [];
                $month = strtotime('+' . $i . ' month', strtotime($startDate));
                $temp['name'] = date('Y-m', $month);
                $temp['startDate'] = date('Y-m-01', $month);
                $temp['endDate'] = date('Y-m-t', $month);
                $tempDate = $temp['endDate'];
                $returnData[] = $temp;
                $i++;
            }
        } elseif ($type == 'quarter') {    // 查询所有季度以及开始结束时间
            while (strtotime($tempDate) < strtotime($endDate)) {
                $temp = [];
                $quarter = strtotime('+' . $i . ' month', strtotime($startDate));
                $q = ceil(date('n', $quarter) / 3);
                $temp['name'] = date('Y', $quarter) . '第' . $q . '季度';
                $temp['startDate'] = date('Y-m-01', mktime(0, 0, 0, $q * 3 - 3 + 1, 1, date('Y', $quarter)));
                $temp['endDate'] = date('Y-m-t', mktime(23, 59, 59, $q * 3, 1, date('Y', $quarter)));
                $tempDate = $temp['endDate'];
                $returnData[] = $temp;
                $i = $i + 3;
            }
        } elseif ($type == 'year') {    // 查询所有年份以及开始结束时间
            while (strtotime($tempDate) < strtotime($endDate)) {
                $temp = [];
                $year = strtotime('+' . $i . ' year', strtotime($startDate));
                $temp['name'] = date('Y', $year) . '年';
                $temp['startDate'] = date('Y-01-01', $year);
                $temp['endDate'] = date('Y-12-31', $year);
                $tempDate = $temp['endDate'];
                $returnData[] = $temp;
                $i++;
            }
        }
        return $returnData;
    }

    /**
     * 是否linux时间戳
     *
     * @param $time   时间戳
     * @return boolean
     */
    protected function isTimeStamp($time = null)
    {
        if (strtotime(date('Y-m-d H:i:s', $time)) === $time) {
            return true;
        } else {
            return false;
        }
    }
}