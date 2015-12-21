<?php

namespace tests\Clearcode\ElkBridgeBundle\Serializer\Handler;

use Carbon\Carbon;
use Clearcode\ElkBridgeBundle\Serializer\Handler\StringifyCarbonHandler;
use JMS\Serializer\JsonSerializationVisitor;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class StringifyCarbonHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var StringifyCarbonHandler */
    private $handler;

    /** @var ObjectProphecy|JsonSerializationVisitor */
    private $visitor;

    /** @test */
    public function it_can_serialize_carbon()
    {
        $dateTime = new \DateTime('2015-12-16 00:00:00');
        Carbon::setTestNow(Carbon::instance($dateTime));

        $carbon = Carbon::now();
        $result = $this->handler->toDateTime($this->visitor->reveal(), $carbon);

        $this->assertEquals('2015-12-16 00:00:00', $result);
    }

    protected function setUp()
    {
        $this->visitor = $this->prophesize(JsonSerializationVisitor::class);
        $this->handler = new StringifyCarbonHandler();
    }
}
