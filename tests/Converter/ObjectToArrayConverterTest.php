<?php

namespace tests\Clearcode\SimpleBusElkBundle\Converter;

use Clearcode\SimpleBusElkBundle\Converter\ObjectToArrayConverter;
use JMS\Serializer\Serializer;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class ObjectToArrayConverterTest extends \PHPUnit_Framework_TestCase
{
    /** @var ObjectToArrayConverter */
    private $converter;
    /** @var ObjectProphecy|Serializer */
    private $serializer;

    /**
     * @test
     * @expectedException \Clearcode\SimpleBusElkBundle\Converter\DataToConvertIsNotAnObject
     */
    public function it_throws_exception_when_data_to_convert_is_not_object()
    {
        $this->converter->toArray('not-an-object');
    }

    /** @test */
    public function it_serializes_objects()
    {
        $object = new \stdClass();
        $this->serializer->toArray($object, Argument::any())->willReturn(['key' => 'value']);

        $data = $this->converter->toArray($object);

        $this->assertEquals(
            [
                'event' => [
                    'name' => 'stdClass',
                    'data' => [
                        'key' => 'value',
                    ],
                ],
            ],
            $data
        );
    }

    /** {@inheritdoc} */
    public function setUp()
    {
        $this->serializer = $this->prophesize(Serializer::class);
        $this->converter  = new ObjectToArrayConverter($this->serializer->reveal());
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->serializer = null;
        $this->converter  = null;
    }
}
