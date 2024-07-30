<?php

namespace Technical_penguins\Newslurp\Utilities;


use Technical_penguins\Newslurp\DTOs\GmailMessageDTO;

class GmailMessageDtoArray extends TypedArray
{
    protected string $type = GmailMessageDTO::class;

    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
