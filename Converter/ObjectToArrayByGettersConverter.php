<?php

namespace Clearcode\ElkBridgeBundle\Converter;

class ObjectToArrayByGettersConverter implements ObjectToArrayConverter
{
    public static function instance()
    {
        return new self();
    }

    public function toArray($event)
    {
        $this->guardAgainstNotObject($event);

        return $this->convert($event);
    }

    private function convert($object, $parentObject = null)
    {
        $class           = new \ReflectionClass(get_class($object));
        $parentClassName = $this->getClassNameByObject($parentObject);

        if ($parentClassName === $class->getShortName()) {
            throw new FunctionReturnsFunctionParentClass();
        }

        return $this->getConvertedData($object, $class);
    }

    private function isMagicMethod($method)
    {
        return substr($method->name, 0, 2) === '__';
    }

    private function isMethodGetter($method)
    {
        return
            !$this->isMagicMethod($method) &&
            !$method->isStatic() &&
            $method->getParameters() == [] &&
            $method->name !== 'toArray';
    }

    private function serializeData($data, $object)
    {
        if (is_object($data) && method_exists($data, '__toString')) {
            $data = $data->__toString();
        } elseif (is_object($data)) {
            $data = $this->convert($data, $object);
        }

        return $data;
    }

    private function getClassNameByObject($object = null)
    {
        $class = $object === null ? null : new \ReflectionClass(get_class($object));

        return $class === null ? null : $class->getShortName();
    }

    private function getConvertedData($object, $class)
    {
        $convertedData = [
            'name' => $class->getShortName(),
            'data' => [],
        ];

        /** @var \ReflectionMethod $method */
        foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $methodName = $method->name;

            if (!$this->isMethodGetter($method)) {
                continue;
            }

            $data = $object->$methodName();

            // TODO: it is possible to pass multiple objects in event?
            if (is_array($data)) {
                continue;
            }

            try {
                $convertedData['data'][$methodName] = $this->serializeData($data, $object);
            } catch (FunctionReturnsFunctionParentClass $e) {
                continue;
            }
        }

        return $convertedData;
    }

    private function guardAgainstNotObject($event)
    {
        if (!is_object($event)) {
            throw new DataToConvertIsNotAnObject();
        }
    }
}
