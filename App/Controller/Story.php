<?php

namespace Technical_penguins\Newslurp\Controller;

use Exception;
use PDO;
use Technical_penguins\Newslurp\Dtos\GmailMessageDTO;
use Technical_penguins\Newslurp\Converters\GmailMessageDTOToStory;
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
        $result = $query->fetchAll();
        return $result[0]->count;
    }

    /**
     * @throws Exception
     */
    public static function get_stories($page): StoryArray
    {
        $offset = $page * 10;
        $query = Database::query('SELECT * FROM ' . Database::STORY_TABLE . ' ORDER BY `date` DESC LIMIT 10 OFFSET ' . $offset);
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        $stories = new StoryArray([]);
        foreach($data as $storyData) {
            $stories[] = new StoryObj(...[...$storyData, 'loaded' => true, 'authorCleaned' => null]);
        }
        return $stories;
    }

    /**
     * @throws Exception
     */
    public static function load(int $id): StoryObj
    {
        $query = Database::query('SELECT * FROM ' . Database::STORY_TABLE . ' WHERE id=?', [$id]);
        return new StoryObj(...[...$query->fetch(PDO::FETCH_ASSOC), 'loaded' => true, 'authorCleaned' => null]);
    }

    /**
     * @throws Exception
     */
    public static function get_rss_stories(): StoryArray
    {
        $one_week_ago = time() - 604800;
        $query = Database::query('SELECT * FROM ' . Database::STORY_TABLE . ' WHERE `date` > ' . $one_week_ago . ' ORDER BY `date` DESC');
        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        $stories = new StoryArray([]);
        foreach($data as $storyData) {
            $stories[] = new StoryObj(...[...$storyData, 'loaded' => true, 'authorCleaned' => null]);
        }
        return $stories;
    }
}
