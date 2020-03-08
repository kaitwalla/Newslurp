<?php

namespace Technical_penguins\Newslurp\Model;

use DateTime;

class Story {
    var $author;
    var $content;
    var $date;
    var $description;
    var $title;
    var $type;

    public function __construct($storyContent, $loaded=false) {
        foreach ($storyContent as $attr=>$data) {
            $this->{$attr} = $data;
        }
        if (!$loaded) {
            $this->date = strtotime($this->date);
        } else {
            $date = new DateTime();
            $date->setTimestamp($this->date);
            $this->date = $date->format('M. j, Y');
        }
    }

    public static function load($content) {
        return new Story($content, true);
    }
}