<?php

namespace tests\Clearcode\SimpleBusElkBundle\CommandBus;

use Clearcode\SimpleBusElkBundle\CommandBus\LogEventMiddleware;
use Clearcode\SimpleBusElkBundle\Logstash\CannotWriteToLogstash;
use Clearcode\SimpleBusElkBundle\Logstash\Logstash;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class LogEventMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /** @var ObjectProphecy|LoggerInterface */
    private $logger;
    /** @var Logstash|ObjectProphecy */
    private $logstash;
    /** @var LogEventMiddleware */
    private $middleware;

    /** @test */
    public function it_logs_message_when_it_is_event()
    {
        $object = new \stdClass();

        $this->middleware->handle($object, $this->dummyCallable());

        $this->logstash->write($object)->shouldBeCalled();
        $this->logger->error(Argument::cetera())->shouldNotBeCalled();
    }

    /** @test */
    public function it_catches_exception_from_logstash_and_logs_erros()
    {
        $object = new \stdClass();
        $this->logstash->write($object)->willThrow(CannotWriteToLogstash::class);

        $this->middleware->handle($object, $this->dummyCallable());

        $this->logger->error(Argument::cetera())->shouldBeCalled();
    }

    private function dummyCallable()
    {
        return function () {
        };
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        $this->logger    = $this->prophesize(LoggerInterface::class);
        $this->logstash = $this->prophesize(Logstash::class);

        $this->middleware       = new LogEventMiddleware($this->logstash->reveal(), $this->logger->reveal());
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->logger     = $this->prophesize(LoggerInterface::class);
        $this->logstash   = $this->prophesize(Logstash::class);
        $this->middleware = null;
    }
}
