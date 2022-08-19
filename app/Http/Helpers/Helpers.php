<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Auth;

class Solutions
{
    public function object_to_array($object)
    {
        //torna o objeto em array;
        $result = array_map(function ($value) {
            return (array)$value;
        }, $object);
        return $result;
    }

    public function white_text_label(string $description, string $icon, string $bg_color)
    {
        return "<span 
            style='color:white; font-size:90%; background-color: " . $bg_color . "' 
            class='label'>
                <i class='fa {$icon}'></i> {$description}
        </span>";
    }
}