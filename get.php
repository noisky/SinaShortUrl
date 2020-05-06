<?php 
/**
 * 新浪短网址 Api
 * 
 * Author: Noisky
 * Site:   https://ffis.me
 * Created on 2017/12/31.
 * Revised on 2020/05/06.
 */
//是否开启白名单
$checkDomain = true;
//白名单域名（默认已包含本机域名）
$domain_list = array(
    'api.ffis.me',
    ''
);

/**
 * 短网址函数
 * @param $longUrl 原始网址
 * @return 缩短后的网址
 */
function shortUrl($longUrl) {
    // $url = "http://api.weibo.com/2/short_url/shorten.json?source=1952055376&url_long=" . urlencode($longUrl);
    $url = file_get_contents('http://service.weibo.com/share/share.php?url='.$longUrl.'&title=1');
    $ch = curl_init($url);  //初始化
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // curl请求有返回的值
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    $shortUrl = explode(' "', explode('short_url = " ', $url)[1])[0]; 
    return ["code" => "0", "flag" => true, "url_short" => $shortUrl];
}

/**
 * 获取GET或POST过来的参数
 * @param $key 键值
 * @param $default 默认值
 * @return 获取到的内容（没有则为默认值）
 */
function getParam($key,$default='') {
    return trim(
        $key && is_string($key) ? (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default)) : $default);
}

/**
 * 输出json格式返回结果
 * @param $data 输出的内容(json格式)
 */
function echoJson($data)
{
    die (json_encode($data));
}

/**
 * 域名白名单校验
 * @param $domain_list
 * @return true/false
 */
function checkReferer() {
    $status = false;
    $refer = $_SERVER['HTTP_REFERER']; //获取refer
    if ($refer) {
        $referhost = parse_url($refer);
        /**来源地址主域名**/
        $host = strtolower($referhost['host']);
        if ($host == $_SERVER['HTTP_HOST'] || in_array($host, $GLOBALS['domain_list'])) {
            $status = true;
        }
    }
    return $status;
}

/**
 * 开启域名白名单校验
 * 如果不开启则禁用此代码块即可
 */
$refer = $_SERVER['HTTP_REFERER']; //获取refer
if ($checkDomain) {
    if ($refer) {
        if (!checkReferer()) {
            //请求不在白名单
            echoJson(["code" => "403", "flag" => false, "msg" => "Access denied"]);
            die;
        }
    } else {
        //refer不存在
        echoJson(["code" => "403", "flag" => false, "msg" => "Access denied"]);
        die;
    }
}

//调用方法获取短链接
echoJson(shortUrl(getParam('longUrl','https://ffis.me/')));