<?php

namespace App\Support\Redis;

/**
* @template T of \BackedEnum
*/
class RedisBackedCase extends RedisString
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
    public function getCase(): mixed
    {
        return $this->enumClass::from($this->get());
    }

    /**
    * @param T $case
    */
    public function setCase(mixed $case): void
    {
        $this->set($case->value);
    }
}
