<?php

namespace tests\Clearcode\ElkBridgeBundle\Converter;

use Clearcode\ElkBridgeBundle\Converter\ObjectToArrayByGettersConverter;
use tests\Clearcode\ElkBridgeBundle\Converter\Fixture\FullObject;
use tests\Clearcode\ElkBridgeBundle\Converter\Fixture\ObjectWithMethodReturningObjectWithToStringMagicMethod;
use tests\Clearcode\ElkBridgeBundle\Converter\Fixture\ObjectWithMethodReturningSimpleObject;

class ObjectToArrayByGettersConverterTest extends \PHPUnit_Framework_TestCase
{
    /** @var ObjectToArrayByGettersConverter */
    private $converter;

    /**
     * @test
     * @expectedException \Clearcode\ElkBridgeBundle\Converter\DataToConvertIsNotAnObject
     */
    public function it_throws_exception_when_data_to_convert_is_not_object()
    {
        $this->converter->toArray('not-an-object');
    }

    /**
     * @test
     */
    public function it_serializes_objects()
    {
        $object = new FullObject();

        $data = $this->converter->toArray($object);

        $this->assertEquals(
            [
                'name' => 'FullObject',
                'data' => [
                    'string' => 'string',
                    'int'    => 1,
                    'bool'   => true,
                    'float'  => 1.1,
                    'null'   => null,
                ],
            ],
            $data
        );
    }

    /**
     * @test
     */
    public function it_recursively_serializes_objects()
    {
        $object = new ObjectWithMethodReturningSimpleObject();

        $data = $this->converter->toArray($object);

        $this->assertEquals(
            [
                'name' => 'ObjectWithMethodReturningSimpleObject',
                'data' => [
                    'simpleObject' => [
                        'name' => 'SimpleObject',
                        'data' => [
                            'method' => 'something',
                        ],
                    ],
                ],
            ],
            $data
        );
    }

    /**
     * @test
     */
    public function it_serialises_object_using_to_string_magic_method_if_exists()
    {
        $object = new ObjectWithMethodReturningObjectWithToStringMagicMethod();

        $data = $this->converter->toArray($object);

        $this->assertEquals(
            [
                'name' => 'ObjectWithMethodReturningObjectWithToStringMagicMethod',
                'data' => [
                    'objectWithToStringMagicMethod' => 'Hello World!',
                ],
            ],
            $data
        );
    }

    /**
     * @test
     */
    public function it_is_not_serializing_values_of_class_methods_returning_that_class()
    {
        $object = new FullObject();

        $data = $this->converter->toArray($object);

        $this->assertSerializedDataDoesNotContainsKey($data, 'selfObject');
    }

    /**
     * @test
     */
    public function it_does_not_serialize_values_of_magic_methods()
    {
        $object = new FullObject();

        $data = $this->converter->toArray($object);

        $this->assertSerializedDataDoesNotContainsKey($data, '__magic');
    }

    /**
     * @test
     */
    public function it_does_not_serialize_values_of_static_methods()
    {
        $object = new FullObject();

        $data = $this->converter->toArray($object);

        $this->assertSerializedDataDoesNotContainsKey($data, 'staticMethod');
    }

    /**
     * @test
     */
    public function it_does_not_serialize_values_of_methods_with_parameters()
    {
        $object = new FullObject();

        $data = $this->converter->toArray($object);

        $this->assertSerializedDataDoesNotContainsKey($data, 'notGetter');
    }

    /**
     * @test
     */
    public function it_does_not_serialize_values_of_to_array_method()
    {
        $object = new FullObject();

        $data = $this->converter->toArray($object);

        $this->assertSerializedDataDoesNotContainsKey($data, 'toArray');
    }

    /**
     * @test
     */
    public function it_does_not_serialize_values_of_methods_which_returns_array()
    {
        $object = new FullObject();

        $data = $this->converter->toArray($object);

        $this->assertSerializedDataDoesNotContainsKey($data, 'emptyArray');
    }

    /**
     * @test
     */
    public function setUp()
    {
        $this->converter = new ObjectToArrayByGettersConverter();
    }

    private function assertSerializedDataDoesNotContainsKey(array $data, $key)
    {
        $this->assertSerializedDataHasCorrectStructure($data);
        $this->assertArrayNotHasKey($key, $data['data']);
    }

    private function assertSerializedDataHasCorrectStructure(array $data)
    {
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('data', $data);
    }
}
