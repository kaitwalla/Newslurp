<?php

namespace Technical_penguins\Newslurp\Action;

use Technical_penguins\Newslurp\Model\Story;
use Technical_penguins\Newslurp\Utilities\GmailMessageDtoArray;

class Ingest
{
    public static function handle(array $data): void
    {
        $array = new GmailMessageDtoArray($data);
        foreach ($array as $GmailMessageDto) {
            $story = new Story(...$GmailMessageDto);
            $story->save();
        }
    }
}
