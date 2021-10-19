<?php


namespace EasySwoole\HttpAnnotation\Tests\TestController;


use EasySwoole\Component\Context\ContextManager;
use EasySwoole\HttpAnnotation\AnnotationController;
use EasySwoole\HttpAnnotation\AnnotationTag\Api;
use EasySwoole\HttpAnnotation\AnnotationTag\ApiAuth;
use EasySwoole\HttpAnnotation\AnnotationTag\ApiDescription;
use EasySwoole\HttpAnnotation\AnnotationTag\ApiFail;
use EasySwoole\HttpAnnotation\AnnotationTag\ApiFailParam;
use EasySwoole\HttpAnnotation\AnnotationTag\ApiGroup as ApiGroupTag;
use EasySwoole\HttpAnnotation\AnnotationTag\ApiGroupAuth;
use EasySwoole\HttpAnnotation\AnnotationTag\ApiGroupDescription;
use EasySwoole\HttpAnnotation\AnnotationTag\ApiRequestExample;
use EasySwoole\HttpAnnotation\AnnotationTag\ApiSuccess;
use EasySwoole\HttpAnnotation\AnnotationTag\ApiSuccessParam;
use EasySwoole\HttpAnnotation\AnnotationTag\CircuitBreaker;
use EasySwoole\HttpAnnotation\AnnotationTag\Context;
use EasySwoole\HttpAnnotation\AnnotationTag\Di;
use EasySwoole\HttpAnnotation\AnnotationTag\Inject;
use EasySwoole\HttpAnnotation\AnnotationTag\InjectParamsContext;
use EasySwoole\HttpAnnotation\AnnotationTag\Method;
use EasySwoole\HttpAnnotation\AnnotationTag\Param;
use EasySwoole\HttpAnnotation\Exception\Annotation\ParamValidateError;

/**
 * Class ControllerA
 * @package EasySwoole\HttpAnnotation\Tests\TestController
 * @ApiGroupTag(groupName="GroupA")
 * @ApiGroupDescription("GroupA desc")
 * @ApiGroupAuth(name="groupParamA",required="")
 * @ApiGroupAuth(name="groupParamB",required="")
 */
class Annotation extends AnnotationController
{

    /**
     * @Di(key="di")
     */
    public $di;
    /**
     * @Context(key="context")
     */
    public $context;

    /**
     * @var Test $inject
     * @Inject(className="\EasySwoole\HttpAnnotation\Tests\TestController\Test", args={1,{1,2}})
     */
    public $inject;

    /**
     * @Api(path="/apiGroup/func",name="func")
     * @ApiAuth(name="apiAuth1")
     * @ApiAuth(name="apiAuth2")
     * @ApiDescription("func desc")
     * @ApiFail("func fail example1")
     * @ApiFail("func fail example2")
     * @ApiFailParam(name="failParam1")
     * @ApiFailParam(name="failParam2")
     * @ApiRequestExample("func request example1")
     * @ApiRequestExample("func request example2")
     * @ApiSuccess("func success example1")
     * @ApiSuccess("func success example2")
     * @ApiSuccessParam(name="successParam1")
     * @ApiSuccessParam(name="successParam2")
     * @CircuitBreaker(timeout=5.0)
     * @InjectParamsContext(key="requestData")
     * @Method(allow={POST,GET})
     * @Param(name="param1")
     * @Param(name="param2")
     */
    function func()
    {

    }

    function index()
    {
        $this->response()->write('index');
    }

    /**
     * @Method(allow={POST})
     */
    function allowPostMethod()
    {
        $this->response()->write('allowPostMethod');
    }

    /**
     * @ApiAuth(name="onRequestAuth1", description="onRequest-auth1")
     * @ApiAuth(name="onRequestAuth2", description="onRequest-auth2")
     * @Param(name="onRequestParam1", description="onRequest-param1")
     * @Param(name="onRequestParam2", description="onRequest-param2")
     * @param string|null $action
     * @return bool|null
     */
    protected function onRequest(?string $action): ?bool
    {
        return parent::onRequest($action); // TODO: Change the autogenerated stub
    }

    function onException(\Throwable $throwable): void
    {
        if($throwable instanceof ParamValidateError){
            $this->response()->write("PE-{$throwable->getValidate()->getError()->getField()}");
        }else{
            throw $throwable;
        }
    }

    /**
     * @Param(name="param1",required="",integer="")
     */
    function param1()
    {
        $this->response()->write($this->request()->getRequestParam('param1'));
    }

    /**
     * @Api(name="param2",path="/param2")
     * @Param(name="param1",required="",integer="")
     * @Param(name="param2",required="",integer="")
     */
    function param2()
    {
        $p1 = $this->request()->getRequestParam('param1');
        $p2 = $this->request()->getRequestParam('param2');
        $this->response()->write($p1 + $p2);
    }

    /**
     * @Param(name="param1",required="",integer="")
     * @Param(name="groupParamA",required="",integer="")
     */
    function param3()
    {
        //测试与api group的合并
        $p1 = $this->request()->getRequestParam('param1');
        $p2 = $this->request()->getRequestParam('groupParamA');
        $this->response()->write($p1 + $p2);
    }

    function paramExport1($groupParamA)
    {
        $this->response()->write($groupParamA);
    }

    /**
     * @Param(name="exp")
     */
    function paramExport2($groupParamA,$exp)
    {
        $this->response()->write($exp);
    }

    /**
     * @Param(name="param1",required="")
     * @InjectParamsContext(key="data",onlyParamTag=false);
     */
    function injectParam1()
    {
        $this->response()->write(implode("|",ContextManager::getInstance()->get('data')));
    }

    /**
     * @Param(name="param1",required="")
     * @InjectParamsContext(key="data");
     */
    function injectParam2()
    {
        $this->response()->write(implode("|",ContextManager::getInstance()->get('data')));
    }

    public function inject()
    {
        $this->response()->write($this->inject->index());
    }

    public function injectGetString()
    {
        $this->response()->write($this->inject->getString());
    }

    public function injectGetArray()
    {
        $this->response()->write(json_encode($this->inject->getArray()));
    }

    protected function gc()
    {
        //不调用父类重置成员属性，方便单元测试
    }
}