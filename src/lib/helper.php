<?php
/**
 * Class name helper.php
 * Created by PhpStorm.
 * Date: 2020/2/21
 */

if (!function_exists('dump')) {
    /**
     * 浏览器友好的变量输出
     * @param mixed $vars 要输出的变量
     * @return void
     */
    function dump(...$vars)
    {
        ob_start();
        var_dump(...$vars);

        $output = ob_get_clean();
        $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);

        if (PHP_SAPI == 'cli') {
            $output = PHP_EOL . $output . PHP_EOL;
        } else {
            if (!extension_loaded('xdebug')) {
                $output = htmlspecialchars($output, ENT_SUBSTITUTE);
            }
            $output = '<pre>' . $output . '</pre>';
        }

        echo $output;
    }
}