<?php

namespace Technical_penguins\Newslurp\Model;

use Technical_penguins\Newslurp\Controller\Database;
use Technical_penguins\Newslurp\Controller\Story as StoryController;
use Technical_penguins\Newslurp\Converters\GmailMessageDTOtoStory;
use Technical_penguins\Newslurp\DTOs\GmailMessageDTO;

class Story
{
    public function __construct(
        public string  $author,
        public string  $content,
        public string  $date,
        public ?string $description,
        public string  $title,
        readonly bool  $loaded = false,
        public ?int    $id = null,
        public ?string $authorCleaned = null,
    )
    {
        if (!$this->loaded && isset($this->date)) {
            $this->date = strtotime($this->date);
        }
        $this->authorCleaned = preg_replace('/\s<.*?>/','', $this->author);
    }

    public static function load(int|GmailMessageDTO $content): self
    {
        return (is_int($content)) ? StoryController::load($content) : GmailMessageDTOtoStory::convert($content);
    }

    public function save(): void
    {
        $values = [$this->title, $this->author, $this->content, $this->date, $this->description];
        $query = Database::query('INSERT INTO ' . Database::STORY_TABLE . ' (`title`,`author`,`content`,`date`,`description`) VALUES (?,?,?,?,?)', $values);
    }
}
