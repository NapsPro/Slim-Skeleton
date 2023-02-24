<?php

function secondsToDatetime(int $seconds){
    $date = DateTime::createFromFormat('U',$seconds);
    $date->setTimezone(new DateTimeZone($_ENV["TIMEZONE"]));
    return $date;
}