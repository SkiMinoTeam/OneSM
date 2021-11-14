<?php
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(0);
include 'vendor/autoload.php';
include 'conststr.php';
include 'common.php';

echo '<center>
<style>
a {
    color: gray;
}
#VercelToken,#adminpassword,.intext {
    border-top: none;
    border-left: none;
    border-right: none;
    border-bottom: 2px solid rgb(90, 45, 196);
    padding: 6px 14px;
    font-size: 20px;
}
#submitbtn,.btn {
    width: 100px;
    height: 40px;
    border-radius: 5px;
    border: none;
    outline: none;
}
</style>';
//echo '<pre>'. json_encode($_SERVER, JSON_PRETTY_PRINT).'</pre>';
//echo '<pre>'. json_encode($_ENV, JSON_PRETTY_PRINT).'</pre>';
if (isset($_SERVER['HEROKU_APP_DIR'])&&$_SERVER['HEROKU_APP_DIR']==='/app') {
    include 'platform/Heroku.php';
    $path = getpath();
    //echo 'path:'. $path;
    $_GET = getGET();
    //echo '<pre>'. json_encode($_GET, JSON_PRETTY_PRINT).'</pre>';
    $re = main($path);
    $sendHeaders = array();
    foreach ($re['headers'] as $headerName => $headerVal) {
        header($headerName . ': ' . $headerVal, true);
    }
    http_response_code($re['statusCode']);
    if ($re['isBase64Encoded']) echo base64_decode($re['body']);
    else echo $re['body'];
} elseif (isset($_SERVER['DOCUMENT_ROOT'])&&$_SERVER['DOCUMENT_ROOT']==='/var/task/user') {
    if (getenv('ONESM_CONFIG_SAVE')=='env') include 'platform/Vercel_env.php';
    else include 'platform/Vercel.php';
    $path = getpath();
    //echo 'path:'. $path;
    $_GET = getGET();
    //echo '<pre>'. json_encode($_GET, JSON_PRETTY_PRINT).'</pre>';
    $re = main($path);
    $sendHeaders = array();
    foreach ($re['headers'] as $headerName => $headerVal) {
        header($headerName . ': ' . $headerVal, true);
    }
    http_response_code($re['statusCode']);
    if ($re['isBase64Encoded']) echo base64_decode($re['body']);
    else echo $re['body'];
} else {
    include 'platform/Normal.php';
    if (!function_exists('curl_init')) {
        return message('<font color="red">需要Curl</font>, 请安装 PHP-Curl.', 'Error', 500);
    }
    $path = getpath();
    //echo 'path:'. $path;
    $_GET = getGET();
    //echo '<pre>'. json_encode($_GET, JSON_PRETTY_PRINT).'</pre>';

    $re = main($path);
    $sendHeaders = array();
    foreach ($re['headers'] as $headerName => $headerVal) {
        header($headerName . ': ' . $headerVal, true);
    }
    http_response_code($re['statusCode']);
    if ($re['isBase64Encoded']) echo base64_decode($re['body']);
    else echo $re['body'];
}
echo '</center>';