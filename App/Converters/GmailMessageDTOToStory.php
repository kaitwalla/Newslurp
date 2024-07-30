<?php

namespace Technical_penguins\Newslurp\Converters;

use Technical_penguins\Newslurp\DTOs\GmailMessageDTO;
use Technical_penguins\Newslurp\Model\Story;

class GmailMessageDTOToStory
{
    public static function convert(GmailMessageDTO $dto): Story
    {
        return new Story(
            title: $dto->title,
            content: $dto->html,
            author: $dto->author,
            date: $dto->date,
            description: $dto->description,
        );        
    }
} 