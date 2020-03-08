<?php

namespace Technical_penguins\Newslurp\Controller;

use Technical_penguins\Newslurp\Controller\Database;
use Technical_penguins\Newslurp\Model\Story as StoryObj;

class Story {

    public static function save(StoryObj $story) {
        $values = [$story->title, $story->author, $story->content, $story->date, $story->description];
        $query = Database::query('INSERT INTO ' . Database::STORY_TABLE . ' (`title`,`author`,`content`,`date`,`description`) VALUES (?,?,?,?,?)', $values);
    }

    public static function load($id) {
        $query = Database::query('SELECT * FROM ' . Database::STORY_TABLE . ' WHERE id=?', [$id]);
        $story = StoryObj::load($query->fetchAll(\PDO::FETCH_CLASS)[0]);
        return $story;
    }

    public static function get_count(){ 
        $query = Database::query('SELECT COUNT(*) as count FROM ' . Database::STORY_TABLE);
        $result = $query->fetchAll(\PDO::FETCH_CLASS);
        return $result[0]->count;
    }

    public static function get_stories($page) {
        $query = Database::query('SELECT * FROM ' . Database::STORY_TABLE. ' ORDER BY `date` DESC LIMIT 10 OFFSET ' . $page);
        $data = $query->fetchAll();
        $stories = [];
        foreach ($data as $storyData) {
            $story = StoryObj::load($storyData);
            array_push($stories, $story);
        }
        return $stories;
    }

    public static function get_rss_stories() {
        $one_week_ago = time() - 604800;
        $query = Database::query('SELECT * FROM ' . Database::STORY_TABLE. ' WHERE `date` > ' . $one_week_ago . ' ORDER BY `date` DESC');
        $data = $query->fetchAll();
        $stories = [];
        foreach ($data as $storyData) {
            $story = StoryObj::load($storyData);
            array_push($stories, $story);
        }
        return $stories;
    }
}