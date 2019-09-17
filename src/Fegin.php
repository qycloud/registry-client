<?php
namespace Registry\Client;
use Registry\Client\Server\ServerInterface;

class Fegin
{
    public $server = null;

    const SUCESS_STATUS = 1000000;

    public function __construct(ServerInterface $server)
    {
        $this->server = $server;
    }

    public function request($app, $api, $method = "GET", $data = [])
    {
        $result = $this->httpSend($app, $api, $method, $data);
        if (empty($result)) {
            throw new Exception\ServiceResultException("内部请求错误");
        }

        if ($result['code'] == self::SUCESS_STATUS) {
            return $result['result'];
        } else {
            throw new Exception\ServiceResultException($result['msg']);
        }
    }

    public function httpSend($app, $api, $method = "GET", $data = [])
    {
        $domain = $this->server->getServer($app);
        $fetchResult = \Registry\Client\CurlClient::curlData(
            $domain.$api,
            $method,
            $data,
            [],
            static::isGet() ? false : true
        );
        return isset($fetchResult[0]) ? json_decode($fetchResult[0], true) : [];
    }

    public static function isGet()
    {
        return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
    }
}
