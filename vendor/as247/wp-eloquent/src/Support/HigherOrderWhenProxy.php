<?php

namespace As247\WpEloquent\Support;

/**
 * @mixin \As247\WpEloquent\Support\Enumerable
 */
class HigherOrderWhenProxy
{
    /**
     * The collection being operated on.
     *
     * @var \As247\WpEloquent\Support\Enumerable
     */
    protected $collection;

    /**
     * The condition for proxying.
     *
     * @var bool
     */
    protected $condition;

    /**
     * Create a new proxy instance.
     *
     * @param  \As247\WpEloquent\Support\Enumerable  $collection
     * @param  bool  $condition
     * @return void
     */
    public function __construct(Enumerable $collection, $condition)
    {
        $this->condition = $condition;
        $this->collection = $collection;
    }

    /**
     * Proxy accessing an attribute onto the collection.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->condition
            ? $this->collection->{$key}
            : $this->collection;
    }

    /**
     * Proxy a method call onto the collection.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->condition
            ? $this->collection->{$method}(...$parameters)
            : $this->collection;
    }
}
