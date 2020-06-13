<?php

/**
 * Class name Date.php
 * Created by PhpStorm.
 * User: 道法自然
 * Date: 2020/2/20
 */

namespace swordfly1979;

class Date
{
    /**
     * 返回指定时间范围内的所有日期，月份
     *
     * @param $startDate (string|int)  指定开始时间,字符串或时间戳
     * @param $endDate (string|int)    指定结束时间，字符串或时间戳
     * @param $type        类型，day 天，month 月份，quarter 季度，year 年份
     * @param $format string  返回日期格式(默认'Y-m-d H:i:s'）timestamp为返回时间戳
     * @return array
     */

    static public function DateArr($startDate = '', $endDate = '', $type = 'day', $format = 'Y-m-d H:i:s')
    {
        if (self::checkDate($startDate) == false) {
            return '开始日期格式不正确';
        }
        if (self::checkDate($endDate) == false) {
            return '结束日期格式不正确';
        }
        if (!is_int($startDate)) {
            $startDate = strtotime($startDate);
        }
        if (!is_int($endDate)) {
            $endDate = strtotime($endDate);
        }
        $tempDate = $startDate;
        $returnData = [];
        $i = 0;
        if ($type == 'day') {    // 查询所有日期
            while ($tempDate < $endDate) {
                $tempDate = strtotime('+' . $i . ' day', $startDate);
                if ($format == 'timestamp') {
                    $returnData[] = $tempDate;
                } else {
                    $returnData[] = date($format, $tempDate);
                }
                $i++;
            }
        } elseif ($type == 'month') {    // 查询所有月份以及开始结束时间
            while ($tempDate < $endDate) {
                $temp = [];
                $month = strtotime('+' . $i . ' month', $startDate);
                $temp['title'] = date('Y-m', $month);
                $temp['start'] = date('Y-m-01 00:00:00', $month);
                $temp['end'] = date('Y-m-t 23:59:59', $month);
                $tempDate = strtotime($temp['end']);
                if ($format == 'timestamp') {
                    $temp['start'] = strtotime($temp['start']);
                    $temp['end'] = strtotime($temp['end']);
                }
                $returnData[] = $temp;
                $i++;
            }
        } elseif ($type == 'quarter') {    // 查询所有季度以及开始结束时间
            while ($tempDate < $endDate) {
                $temp = [];
                $quarter = strtotime('+' . $i . ' month', $startDate);
                $q = ceil(date('n', $quarter) / 3);
                $temp['year'] = date('Y', $quarter);
                $temp['quarter'] = $q;
                $temp['startDate'] = date('Y-m-01', mktime(0, 0, 0, $q * 3 - 3 + 1, 1, date('Y', $quarter)));
                $temp['endDate'] = date('Y-m-t', mktime(23, 59, 59, $q * 3, 1, date('Y', $quarter)));
                $tempDate = strtotime($temp['endDate']);
                $returnData[] = $temp;
                $i = $i + 3;
            }
        } elseif ($type == 'year') {    // 查询所有年份以及开始结束时间
            while ($tempDate < $endDate) {
                $temp = [];
                $year = strtotime('+' . $i . ' year', $startDate);
                $temp['name'] = date('Y', $year) . '年';
                $temp['startDate'] = date('Y-01-01', $year);
                $temp['endDate'] = date('Y-12-31', $year);
                $tempDate = strtotime($temp['endDate']);
                $returnData[] = $temp;
                $i++;
            }
        }
        return $returnData;
    }

    /**
     * 返回指定时间(前/后)的指定(日/月)
     *
     * @param $date (string|int)  指定的时间,字符串或时间戳
     * @param $type string        类型，day 天，month 月份，quarter 季度，year 年份
     * @param $number int         指定的(日/月)数正数为向后日期，负数为向前日期
     * @param $format string      返回的日期格式 默认y-m-d
     * @return array              返回的日期数组
     */

    static public function dateIncDec($date = '', $type = 'day', $number = 1, $format = 'y-m-d')
    {
        $ret = self::checkDate($date);
        if ($ret === false) {
            return '日期格式不正确';
        }
        if ($ret == 'str') {
            $date = strtotime($date);
        }
        $returnData = [];
        $str = $number > 0 ? '+' : '-';
        $number = abs($number);
        $i = 1;
        while ($i <= $number) {
            if ($type == 'day') {
                $returnData[] = date($format, strtotime($str . $i . ' day', $date));
            } elseif ($type == 'month') {
                $returnData[] = date($format, strtotime($str . $i . ' month', $date));
            }
            $i++;
        }
        return $returnData;
    }

    /**
     * 返回指定天的起始、结束时间戳
     *
     * @param $day (string|int)  指定的天,字符串或时间戳
     */
    static public function timeStamp($day = '')
    {
        $return['error'] = 0;
        if (empty($day)) $day = time();
        $ret = self::checkDate($day);
        if ($ret == false) {
            $return['error'] = 1;
            $return['info'] = '日期格式不正确:' . $day;
        }
        if ($return['error'] == 1) return $return;
        if ($ret == 'str') {
            $day = strtotime($day);
        }
        $startTime = strtotime(date("Y-m-d", $day));
        $endTime = $startTime + 60 * 60 * 24;
        return [$startTime, $endTime];
    }

    /*
     * 是否正确的时间格式
     * @param $date 要验证的时间（时间戳或时间字符串）
     * return bool false 时间格式错误; string int 原时间为时间戳 string str 原时间为字符串格式
     */
    static public function checkDate($date = null)
    {
        if (!$date) return false;
        if (is_int($date) && !(new self())->isTimeStamp($date)) {
            return false;
        }
        if (!is_int($date) && !(new self())->isTimeStamp(strtotime($date))) {
            return false;
        }
        if (is_int($date)) {
            return 'int';
        } else {
            return 'str';
        }
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
//git tag -a v0.1.0 -m "init code"
//git push -u origin --tags