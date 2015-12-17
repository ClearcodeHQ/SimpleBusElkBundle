<?php

namespace Clearcode\ElkBridgeBundle\Converter;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

class ObjectToArrayConverter implements ObjectToArrayConverterInterface
{
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray($object)
    {
        $this->guardAgainstNotObject($object);

        return $this->convert($object);
    }

    private function convert($object)
    {
        return [
            'event' => [
                'name' => $this->getClassName($object),
                'data' => json_decode($this->getSerializedObject($object), true),
            ],
        ];
    }

    private function getClassName($object)
    {
        $class = new \ReflectionClass(get_class($object));

        return $class->getShortName();
    }

    private function getSerializedObject($object)
    {
        return $this->serializer->serialize($object, 'json', SerializationContext::create()->setSerializeNull(true));
    }

    private function guardAgainstNotObject($event)
    {
        if (!is_object($event)) {
            throw new DataToConvertIsNotAnObject();
        }
    }
}
