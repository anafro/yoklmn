<?php

namespace App\Support\Redis;

/**
* @template T of \BackedEnum
*/
class RedisEnum extends RedisEntry
{
    /**
    * @param string $key
    * @param class-string<T> $enumClass
    */
    public function __construct(string $key, private string $enum)
    {
        parent::__construct($key);
    }

    /**
    * @return T
    */
    public function get(): mixed
    {
        if ($this->doesntExist()) {
            return null;
        }

        $string = redis('GET', $this->key);
        return $this->enum::from($string);
    }

    /**
    * @param T $case
    */
    public function set(mixed $case): void
    {
        redis('SET', [$this->key, $case->value]);
    }
}
