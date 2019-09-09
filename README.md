# registryClient
本项目是一个简易的 php 注册中心 client。通过注册中心 service 拿到 instance url，进行服务请求。


## 示例代码：
```php
$server = new Registry\Client\Server\Consul("192.168.0.166", 8900);
$request = new Registry\Client\Fegin($server);
$result = $request->request("AYSaaS-mtao-eagle-user", "/getuser");
```
