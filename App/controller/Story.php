<?php

namespace Technical_penguins\Newslurp\Controller;

use Exception;
use PDO;
use Technical_penguins\Newslurp\DTOs\GmailMessageDTO;
use Technical_penguins\Newslurp\Model\Story as StoryObj;
use Technical_penguins\Newslurp\Utilities\StoryArray;

class Story
{
    /**
     * @throws Exception
     */
    public static function get_count(): int
    {
        $query = Database::query('SELECT COUNT(*) as count FROM ' . Database::STORY_TABLE);
        $result = $query->fetchAll(PDO::FETCH_CLASS);
        return $result[0]->count;
    }

    /**
     * @throws Exception
     */
    public static function get_stories($page): StoryArray
    {
        $offset = $page * 10;
        $query = Database::query('SELECT * FROM ' . Database::STORY_TABLE . ' ORDER BY `date` DESC LIMIT 10 OFFSET ' . $offset);
        $dtos = $query->fetchAll(PDO::FETCH_CLASSTYPE);
        return new StoryArray(array_map(fn($dto) => StoryObj::load(new GmailMessageDto(...$dto)), $dtos));
    }

    /**
     * @throws Exception
     */
    public static function load(int $id): StoryObj
    {
        $query = Database::query('SELECT * FROM ' . Database::STORY_TABLE . ' WHERE id=?', [$id]);
        return StoryObj::load($query->fetch(PDO::FETCH_CLASSTYPE, GmailMessageDTO::class));
    }

    /**
     * @throws Exception
     */
    public static function get_rss_stories(): StoryArray
    {
        $one_week_ago = time() - 604800;
        $query = Database::query('SELECT * FROM ' . Database::STORY_TABLE . ' WHERE `date` > ' . $one_week_ago . ' ORDER BY `date` DESC');
        $dtos = $query->fetchAll(PDO::FETCH_CLASSTYPE);
        return new StoryArray(array_map(fn($dto) => StoryObj::load(new GmailMessageDto(...$dto)), $dtos));
    }
}
