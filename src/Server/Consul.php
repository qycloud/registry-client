<?php
namespace Registry\Client\Server;

class Consul implements ServerInterface
{
    public $host = "127.0.0.1";
    public $port = 8500;
    public $schema = 'http';

    public function __construct($host, $port, $schema = 'http')
    {
        $this->host = $host;
        $this->port = $port;

    }

    /**
     * 获取注册中心地址
     */
    public function getServer($app)
    {
        $url = $this->host.":".$this->port;
        $data = json_decode(
            \Registry\Client\CurlClient::curlData(
                $this->schema."://".$url."/v1/health/service/{$app}",
                "GET"
            )[0],
            true
        );

        //过滤无效服务
        if (!empty($data)) {
            //剔除失效实例
            foreach ($data as $key => $node) {
                foreach ($node['Checks'] as $checkNode) {
                    if ($checkNode['Status'] !== 'passing') {
                        unset($data[$key]);
                    }
                }
            }
        }

        if (!empty($data)) {
            $randKey = array_rand($data);
            return $data[$randKey]['Service']['Address'].":".$data[$randKey]['Service']['Port']."/";
        } else {
            throw new \Registry\Client\Exception\AppNotFindException("URL错误: 在注册中心找不到相关服务");
        }
    }
}