# registryClient
本项目是一个简易的 php 注册中心 client。通过注册中心 service 拿到 instance url，进行服务请求。


## Installation
```
 composer require qpaas/registry-client
```

## 示例代码：
```php
$server = new Registry\Client\Server\Consul("192.168.0.166", 8900);
$request = new Registry\Client\Fegin($server);

# 确定返回值
# $result = $request->request("AYSaaS-mtao-eagle-user", "/getuser");

# 原生返回
$result = $request->httpSend("AYSaaS-mtao-eagle-dataflow", "/getuser");
```
