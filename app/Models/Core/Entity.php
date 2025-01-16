<?php namespace Models\Core;

use InvalidArgumentException;
use ReflectionClass;
use stdClass;
use ValueError;

abstract class Entity
{
    private stdClass $rawData;

    /**
     * Creates an instance based on the database row which normally should be the result of the associated view.
     *
     * @param ?stdClass $row
     * @return ?static
     */
    public static function build(?stdClass $row): ?static
    {
        if (is_null($row)) {
            return null;
        }
        $instance = new static();
        $instance->rawData = $row;
        $reflection = new ReflectionClass($instance);
        foreach ($row as $name => $value) {
            if (property_exists($instance, $name)) {
                $reflectionType = $reflection->getProperty($name)->getType();
                if ($reflectionType instanceof \ReflectionUnionType) { // Do not consider Obj1|Obj2 types ... user must defined manually.
                    continue;
                }
                if (!$reflectionType->isBuiltin()) {
                    $className = $reflectionType->getName();
                    $innerReflection = new ReflectionClass($className);
                    if ($innerReflection->isEnum()) {
                        try {
                            $instance->$name = $className::from($value);
                        } catch (ValueError $e) {
                            throw new InvalidArgumentException("Invalid value for enum $className: " . $value);
                        }
                    } else if ($innerReflection->isSubclassOf(self::class)) {
                        $instance->$name = $className::build($value);
                    }
                } else {
                    $instance->$name = $value;
                }
            }
        }
        return $instance;
    }

    public static function buildArray(array $rows): array
    {
        $results = [];
        foreach ($rows as $row) {
            $results[] = static::build($row);
        }
        return $results;
    }

    public function getRawData(): ?stdClass
    {
        return $this->rawData;
    }
}
