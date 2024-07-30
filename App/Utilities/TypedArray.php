<?php

namespace Technical_penguins\Newslurp\Utilities;

use ArrayObject;
use ReturnTypeWillChange;
use TypeError;

class TypedArray extends ArrayObject
{
    protected string $type;

    protected function __construct(...$args)
    {
        parent::__construct(...$args);
    }

    public static function forType(string $type): static
    {
        $ret = new static();
        $ret->type = $type;
        return $ret;
    }

    #[ReturnTypeWillChange] public function offsetSet($index, $newval)
    {
        if (!$newval instanceof $this->type) {
            throw new TypeError(
                sprintf('Only values of type %s are supported', $this->type)
            );
        }
        parent::offsetSet($index, $newval);
    }
}
