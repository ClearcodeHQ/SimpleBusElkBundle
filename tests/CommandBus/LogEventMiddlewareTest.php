<?php

namespace tests\Clearcode\SimpleBusElkBundle\CommandBus;

use Clearcode\SimpleBusElkBundle\CommandBus\LogEventMiddleware;
use Clearcode\SimpleBusElkBundle\Converter\ObjectToArrayConverter;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class LogEventMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LogEventMiddleware
     */
    private $sut;
    /**
     * @var ObjectProphecy|LoggerInterface
     */
    private $logger;
    /**
     * @var ObjectProphecy|ObjectToArrayConverter
     */
    private $converter;

    /**
     * @test
     */
    public function it_logs_message_when_it_is_event()
    {
        $this->logger->info(Argument::cetera())->shouldBeCalled();

        $this->converter->toArray(Argument::any())->willReturn([]);

        $this->sut->handle(new \stdClass(), $this->dummyCallable());
    }

    /**
     * @test
     */
    public function it_catches_logstash_connection_exceptions()
    {
        $this->logger->error('uups... An exception')->shouldBeCalled();

        $this->logger->info(Argument::cetera())->willThrow(new \Exception('uups... An exception'));
        $this->converter->toArray(Argument::any())->willReturn([]);

        $this->sut->handle(new \stdClass(), $this->dummyCallable());
    }

    private function dummyCallable()
    {
        return function () {
        };
    }

    protected function setUp()
    {
        parent::setUp();

        $this->logger    = $this->prophesize(LoggerInterface::class);
        $this->converter = $this->prophesize(ObjectToArrayConverter::class);
        $this->sut       = new LogEventMiddleware($this->logger->reveal(), $this->converter->reveal());
    }
}
