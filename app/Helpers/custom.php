<?php

if(!function_exists('get_day_name')){
    function get_day_name($day):string
    {
        $dayNames = get_days();
        return $dayNames[$day];
    }
}

if(!function_exists('get_days')){
    function get_days():array
    {
        return ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
    }
}

