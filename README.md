# 一些php常用工具类
安装：`composer require swordfly1979/php-library`
***
## DataImport
数据导入类，依赖phpoffice/phpspreadsheet。使用前先安装：
`composer require phpoffice/phpspreadsheet`<br/>
`use swordfly1979\DataImport`

+ ### excel() excel导入
    ```
    $import = new DataImport()
    $list = $import->excel($param)

    $param['url'] 导入的文件咱径(必填)
    $param['isHead'] 是否包含表头 默认：false
    $param['format'] 导入的数据格式替换: 如 ['A'=>'yourKeyName'...]
    ```
---
## DataExport
数据导出类 依赖 phpoffice/phpspreadsheet
`use swordfly1979\DataExport`
+ ### excel() 导出excel
    ```
    $export = new DataExport()
    $list = $export->excel($param)

    $param['list'] array 需要导出的数据 (必填项)
    $param['header'] array 导出的表头及格式 (选填,不填不导出表头)
    $param['fileName] string 导出文件名 选填 （选填，不填默认 Y-m-d导出数据
    $param['title'] string 导出的表title （选填）
    数据示例
    $param['list']
    $list = [
        [
            'nickname' => '张三',
            'mobile' => '15000000000',
            'total' => 14
        ],
        [
            'nickname' => '李四',
            'mobile' => '15800000000',
            'total' => 54
        ],
        [
            'nickname' => '王五',
            'mobile' => '15100000000',
            'total' => 554
        ],    [
            'nickname' => '赵六',
            'mobile' => '13800000000',
            'total' => 74
        ]
    ];
    $param['head']数据示例
    $header = [
        [
            'title' => '手机',  //导出的表列名称（必填）
            'key' => 'mobile', //对应的$param['list']数据键值（必填）
            'type' => 'str',   //数据类型（选填，不填自动判断） str字符串；n数值；b布尔; null空；inlineStr
            ‘value'=>’void‘ //此列固定填充值（选填）
        ],
        [
            'key' => 'nickname',
            'title' => '昵称',
            'type' => 'str',
        ],
        [
            'key' => 'je',
            'title' => '金额',
            'type' => 'str',
        ],
        [
            'title' => '金额1',
            'type' => 'str',
            'value'=>'100'
        ],
        [
            'title' => '金额2',
            'type' => 'str',
            'key' =>'nickname'
        ]
    ]
    ```


