<?php

namespace Technical_penguins\Newslurp\DTOs;

class GmailMessageDTO
{
    public function __construct(
        public string $html,
        public string $title,
        public string $date,
        public string $author,
        public string $description,
        public bool   $isLoaded = false
    )
    {
    }
}
