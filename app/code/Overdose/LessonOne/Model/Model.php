<?php
namespace Overdose\LessonOne\Model;

class Logger
{
    public function log($message)
    {
        // Здесь можно писать в файл или БД, но для примера просто выводим
        echo "Log: $message\n";
    }
}