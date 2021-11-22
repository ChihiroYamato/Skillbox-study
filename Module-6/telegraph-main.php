<?php

/* Инициализация переменных */
$textStorage = []; // основной массив задания

/**
 * Функция производит заполнение глобального массива globalArr[] переданными параметрами
 * в виде ассоциативного массива ['title' => 'title', 'text' => 'text']
 * @param array $globalArr принимаемый по адресу глобальный массив
 * @param string $title Принимаемый параметр заголовка, присвоится ключу 'title'
 * @param string $text Принимаемый параметр текста, присвоится ключу 'text'
 */
function add(array &$globalArr, string $title = '', string $text = '') : void
{
    $addTitle = (trim($title) === '') ? 'default title' : trim($title);
    $addText = (trim($text) === '') ? 'default text' : trim($text);
    $globalArr[] = ['title' => $addTitle, 'text' => $addText];
}

/**
 * Функция производит удаление из глобального массива конкретного элемента по переданному индексу
 * @param array $globalArr принимаемый по адресу глобальный массив
 * @param ?int $key  передаваемый индекс
 * @return bool Возвращает false если индекс не задан, или в массиве нет значения по индексу, в ином случае - true
 */
function remove(array &$globalArr, ?int $key = null) : bool
{
    if (isset($key, $globalArr[$key]) === false) {
        return false;
    }
    array_splice($globalArr, $key, 1);
    return true;
}

/**
 * Функция редактирует title||text ассоциативного массива по заданному индексу в передаваемом глобальном массиве
 * @param array $globalArr принимаемый по адресу глобальный массив
 * @param ?int $key передаваемый индекс
 * @param string $newTitle Новый заголовок
 * @param string $newText Новый текст
 * @return bool Возвращает false если индекс не задан, или в массиве нет значения по индексу, в ином случае - true
 */
function edit(array &$globalArr, ?int $key = null, string $newTitle = '', string $newText = '') : bool
{
    if (isset($key, $globalArr[$key]) === false) {
        return false;
    }
    /* Если newTitle определен и не пустой - перезаписывает 'title' */
    if (trim($newTitle) !== '') {
        $globalArr[$key]['title'] = trim($newTitle);
    }
    /* Если newText определен и не пустой - перезаписывает 'text' */
    if (trim($newText) !== '') {
        $globalArr[$key]['text'] = trim($newText);
    }
    return true;
}

/* Testing code */

// Задание 3
add($textStorage, 'main', 'hello');
add($textStorage, 'my name', "i will not tell you my name");
print_r($textStorage);

// Задание 5
var_dump(remove($textStorage,0));
var_dump(remove($textStorage, 5));

// Задание 6
print_r($textStorage);

// Задание 8
edit($textStorage, 0, '   Name of student  ');

// Задание 9
var_dump($textStorage[0]);

// Задание 10
var_dump(edit($textStorage, 66));
