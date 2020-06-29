<?php


namespace Registry\Client;


class CurlClient
{
    /**
     * 通过curl获取数据
     *
     * @param string $uri     请求地址
     * @param string $method  请求方式 GET POST PUT DELETE
     * @param array  $data    提交的数据
     * @param array $options    CURL扩展参数. 详细说明: http://php.net/manual/zh/function.curl-setopt.php <p><pre>
     *                        传输超时(网速慢,传输最长时间)
     *                        <b>[CURLOPT_TIMEOUT => 30]</b>
     *
     * 以x-www-form-urlencoded方式提交数据
     * <b>[CURLOPT_HTTPHEADER => ['Content-Type:application/x-www-form-urlencoded']]</b>
     *
     * HTTP密码验证
     * <b>[CURLOPT_USERPWD => "$username:$password"]</b>
     *
     * HTTPS相关验证
     * <b>[CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0]</b></pre></p>
     * @param bool  $postInJSON 数据格式
     *
     * @return array [$result, $errno] $result:结果, $errno:错误码(0为成功)
     */
    public static function curlData($uri, $method = 'GET', $data = [], $options = [], $postInJSON = false, $header = [])
    {
        $method = strtoupper($method);
        $defaultOpts = [
            CURLOPT_RETURNTRANSFER => 1,    //curl_exec 如果成功返回结果，失败返回false
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_URL => $uri,
            CURLOPT_HTTPHEADER => ['Accept: application/json', 'Content-Type: application/json'], //以json方式请求提交数据
            CURLOPT_SSL_VERIFYHOST => 0,    //HTTPS相关验证
            CURLOPT_SSL_VERIFYPEER => 0,    //从证书中检查SSL加密算法是否存在
            CURLOPT_CONNECTTIMEOUT => 30,   //响应超时(服务器未响应,等待最大时间)
            CURLOPT_TIMEOUT => 30,          //传输超时(网速慢,传输最长时间)
        ];
        if (!empty($data)) {
            $data = $postInJSON ? json_encode($data) : http_build_query($data);
            $options[CURLOPT_POSTFIELDS] = $data; //设置请求体，提交数据包
        }

        $header && $defaultOpts[CURLOPT_HTTPHEADER] = array_merge($defaultOpts[CURLOPT_HTTPHEADER], $header);

        $ch = curl_init($uri);
        curl_setopt_array($ch, $options + $defaultOpts);    //批量设置CURL参数
        $result = curl_exec($ch);

        $errno = curl_errno($ch);
        if ($errno) {
            $result = curl_error($ch);
        }
        curl_close($ch);    //关闭CURL会话
        return [$result, $errno];
    }
}