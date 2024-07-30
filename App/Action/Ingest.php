<?php

namespace Technical_penguins\Newslurp\Action;

use Technical_penguins\Newslurp\Model\Story;
use Technical_penguins\Newslurp\Utilities\GmailMessageDtoArray;
use Technical_penguins\Newslurp\Dtos\GmailMessageDTO;
use Technical_penguins\Newslurp\Converters\GmailMessageDTOToStory;

class Ingest
{
    public static function handle(array $data): void
    {
        file_put_contents('data', print_r($data));
        if ($data) {
            $dtos = [];
            foreach($data as $message) {
               $dto = new GmailMessageDTO(...$message);
               $dto->html = urldecode($dto->html);
               $dtos[] = $dto;
            }
        };
        $dtoArray = new GmailMessageDtoArray($dtos);
        
        foreach ($dtoArray as $message) {
            $story = GmailMessageDTOToStory::convert($message);
            $story->save();
        }
    }

    public static function fake(): void {
        $data = file_get_contents('hi');
        $data = json_decode($data, true);
        
        $dtos = [];
    }
}
