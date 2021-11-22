<?php

/* */
$textStorage = [];

/**
 * this fun
 * @param string $title
 * @param string $text
 */
function add(string $title = '', string $text = '') : void
{
    global $textStorage;
    $textStorage[] = ['title' => $title, 'text' => $text];
}

add('main', 'hello');
add('my name', "i will not tell you my name" );

print_r($textStorage);