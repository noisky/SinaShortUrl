<?php 
/**
 * 新浪短网址 Api
 * 
 * Author: Noisky
 * Site:   https://ffis.me
 * Created on 2017/12/31.
 * Revised on 2020/04/21.
 */

echoJson(shortUrl(getParam('longUrl','https://ffis.me/')));

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
    return ["code" => "0", "flag" => "true", "url_short" => $shortUrl];
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