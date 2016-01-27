<?php

namespace tests\Clearcode\SimpleBusElkBundle\Logstash;

use Clearcode\SimpleBusElkBundle\Converter\DataToConvertIsNotAnObject;
use Clearcode\SimpleBusElkBundle\Converter\ObjectToArrayConverterInterface;
use Clearcode\SimpleBusElkBundle\Logstash\Logstash;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;

class LogstashTest extends \PHPUnit_Framework_TestCase
{
    /** @var LoggerInterface|ObjectProphecy */
    private $logger;
    /** @var ObjectToArrayConverterInterface|ObjectProphecy  */
    private $converter;
    /** @var Logstash */
    private $logstash;

    /** @test */
    public function it_can_write_log()
    {
        $object = new \stdClass();
        $this->converter->toArray($object)->willReturn(['message' => 'hello world!']);

        $this->logstash->write($object);

        $this->logger->info('Event recorded', ['message' => 'hello world!'])->shouldBeCalled();
    }

    /**
     * @test
     * @expectedException \Clearcode\SimpleBusElkBundle\Logstash\CannotWriteToLogstash
     */
    public function it_fails_when_connection_failed()
    {
        $object = new \stdClass();
        $this->converter->toArray($object)->willReturn(['message' => 'hello world!']);
        $this->logger->info('Event recorded', ['message' => 'hello world!'])->willThrow(\UnexpectedValueException::class);

        $this->logstash->write($object);
    }

    /**
     * @test
     * @expectedException \Clearcode\SimpleBusElkBundle\Logstash\CannotWriteToLogstash
     */
    public function it_fails_when_data_conversion_failed()
    {
        $object = new \stdClass();
        $this->converter->toArray($object)->willThrow(DataToConvertIsNotAnObject::class);

        $this->logstash->write($object);
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->converter = $this->prophesize(ObjectToArrayConverterInterface::class);

        $this->logstash = new Logstash($this->logger->reveal(), $this->converter->reveal());
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->logger = null;
        $this->converter = null;
        $this->logstash = null;
    }
}
