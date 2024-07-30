<?php

namespace Technical_penguins\Newslurp\Utilities;


use Technical_penguins\Newslurp\Model\Story;

class StoryArray extends TypedArray
{
    protected string $type = Story::class;

    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
