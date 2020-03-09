<?php
$url = 'http://www.elshipping.com/ship/index/index.jsp?type=13';
$data = str_replace("\r\n", '-',$_POST['data']);
$data = explode('-',$data);

$Cookie = $_POST['cookie'];
$header = array(
    'Content-Type: application/x-www-form-urlencoded',
    'Cookie:'.$Cookie
);
$rs = '结果:<br><table border="1"><tr><td>单号</td><td>结果</td></tr>';

foreach ($data as $k => $v){
    $post_data = [
        's_tracking' => $v,
        'verify' => $_POST['code']
    ];
    $res = curl($url,$post_data,$header);
    preg_match('/<table width=680 cellpadding=0([\s\S]*?)<\/table>/', $res, $matches);
    //正则结果
    $res_1 = $matches[0] ? $matches[0] : '查无结果';


    $rs .= '<tr><td>'.$v.'</td><td>'. $res_1 .'</td></tr>';
}
$rs .= '<table/>';
echo $rs;die;

function curl($url, $post_data, $header){
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data)); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    $result = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno'.curl_error($curl);//捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $result;
}