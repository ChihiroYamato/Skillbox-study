<?php

/* Инициализация переменных*/
$textStorage = []; // основной массив задания

/**
 * Функция производит заполнение глобального массива переданными параметрами в виде ассоциативного массива
 * @param string $title Принимаемый параметр заголовка, присвоится ключу 'title'
 * @param string $text Принимаемый параметр текста, присвоится ключу 'text'
 */
function add(string $title = '', string $text = '') : void
{
    global $textStorage;
    $textStorage[] = ['title' => $title, 'text' => $text];
}

/**
 * Функция производит удаление из глобального массива конкретного элемента по переданному индексу
 * @param null $key  передаваемый индекс
 * @return bool Возвращает false если индекс не задан, или в массиве нет значения по индексу, в ином случае - true
 */
function remove($key = null) : bool
{
    global $textStorage;
    if (isset($key, $textStorage[$key]) === false) {
        return false;
    }
    array_splice($textStorage, $key, 1);
    return true;
}

add('main', 'hello');
add('my name', "i will not tell you my name" );

print_r($textStorage);

var_dump(remove(0));
var_dump(remove(5));

print_r($textStorage);