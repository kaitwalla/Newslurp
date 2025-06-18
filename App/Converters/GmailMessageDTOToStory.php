<?php

namespace Technical_penguins\Newslurp\Converters;

use Technical_penguins\Newslurp\DTOs\GmailMessageDTO;
use Technical_penguins\Newslurp\Model\Story;

class GmailMessageDTOToStory
{
    public static function convert(GmailMessageDTO $dto): Story
    {
        return new Story(
            author: $dto->author,
            content: $dto->html,
            date: $dto->date,
            description: $dto->description,
            title: $dto->title,
        );
    }
} 
