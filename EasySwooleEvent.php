<?php

namespace EasySwoole\EasySwoole;


use App\Process\HotReload;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\ORM\Db\Config;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\Pool\Exception\Exception;
use EasySwoole\RedisPool\RedisPoolException;
use EasySwoole\Whoops\Run;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
        // whoops
        // 实现 onRequest 事件
        \EasySwoole\Component\Di::getInstance()->set(\EasySwoole\EasySwoole\SysConst::HTTP_GLOBAL_ON_REQUEST, function (\EasySwoole\Http\Request $request, \EasySwoole\Http\Response $response): bool {
            if (\EasySwoole\EasySwoole\Core::getInstance()->runMode() == 'dev') {
                \EasySwoole\Whoops\Run::attachRequest($request, $response);
                $whoops = new \EasySwoole\Whoops\Run();
                $whoops->pushHandler(new \EasySwoole\Whoops\Handler\PrettyPageHandler());  // 输出一个漂亮的页面
                $whoops->pushHandler(new \EasySwoole\Whoops\Handler\CallbackHandler(function ($exception, $inspector, $run, $handle) {
                    // 可以推进多个Handle 支持回调做更多后续处理
                }));
                $whoops->register();
            }
            ###### 处理请求的跨域问题 ######
            $response->withHeader('Access-Control-Allow-Origin', '*');
            $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
            $response->withHeader('Access-Control-Allow-Credentials', 'true');
            $response->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            if ($request->getMethod() === 'OPTIONS') {
                $response->withStatus(\EasySwoole\Http\Message\Status::CODE_OK);
                return false;
            }
            return true;
        });
    }

    public static function mainServerCreate(EventRegister $register)
    {
        # hotReload
        $swooleServer = ServerManager::getInstance()->getSwooleServer();
        $swooleServer->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());
        # orm
        $config = new Config();
        $config->setUser('root');
        $config->setPassword('wz95ctxb3hvxezu57ko');
        $config->setHost('121.41.170.120');
        # mysql连接池配置
        $config->setGetObjectTimeout(3.0); //设置获取连接池对象超时时间
        $config->setIntervalCheckTime(60 * 1000); //设置检测连接存活执行回收和创建的周期
        $config->setMaxIdleTime(15); //连接池对象最大闲置时间(秒)
        try {
            $config->setMaxObjectNum(20);
            //设置最大连接池存在连接对象数量
            $config->setMinObjectNum(5); //设置最小连接池存在连接对象数量
        } catch (Exception $e) {
        }
        $config->setDatabase('gomall');
        DbManager::getInstance()->addConnection(new Connection($config));
        Run::attachTemplateRender(ServerManager::getInstance()->getSwooleServer());
        # redis
        //配置连接池连接数
        try {
            $redisPoolConfig = \EasySwoole\RedisPool\RedisPool::getInstance()->register(new \EasySwoole\Redis\Config\RedisConfig([
                'host' => '121.41.170.120',
                'port' => '6379',
                'auth' => 'wz95ctxb3hvxezu57ko',
                'serialize' => \EasySwoole\Redis\Config\RedisConfig::SERIALIZE_NONE
            ]));
            $redisPoolConfig->setMinObjectNum(5);
            $redisPoolConfig->setMaxObjectNum(20);
        } catch (RedisPoolException $e) {
        } catch (\EasySwoole\RedisPool\Exception\Exception $e) {
        }
        # whoops
        if (\EasySwoole\EasySwoole\Core::getInstance()->runMode() == 'dev') {
            \EasySwoole\Whoops\Run::attachTemplateRender(ServerManager::getInstance()->getSwooleServer());
        }
    }


    public
    static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}