<?php
/**
 * Class name Date.php
 * Created by PhpStorm.
 * User: 道法自然
 * Date: 2020/2/20
 */

namespace swordfly1979\lib;

class Date
{
    /**
     * 返回指定时间范围内的所有日期，月份
     *
     * @param $startDate (string|int)  指定开始时间,字符串或时间戳
     * @param $endDate (string|int)    指定结束时间，字符串或时间戳
     * @param $type        类型，day 天，month 月份，quarter 季度，year 年份
     * @param $returnType  返回日期格式 1(默认)返回字符串'Y-m-d H:i:s' o为返回时间戳
     * @return array
     */

    static public function DateArr($startDate = '', $endDate = '', $type = '', $returnType = '1')
    {
        $str = 'Y-m-d H:i:s';
        dump($str);
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
        if ($type == 'day') {    // 查询所有日期
            while ($tempDate < $endDate) {
                $tempDate = strtotime('+' . $i . ' day', $startDate);
                if ($returnType === 0) {
                    $returnData[] = $tempDate;
                } else {
                    $returnData[] = date($str, $tempDate);
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
                if ($returnType == 0) {
                    $temp['start'] = strtotime($temp['start']);
                    $temp['end'] = strtotime($temp['end']);
                }
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
        if (is_int($date) && !(new self())->isTimeStamp($date)) {
            return '日期格式不正确';
        }
        if (!is_int($date) && !(new self())->isTimeStamp(strtotime($date))) {
            return '日期格式不正确';
        }
        if (!is_int($date)) {
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