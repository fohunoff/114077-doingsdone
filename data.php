<?php

$categories_array = ['Все', 'Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

$tasks_array = [
    [
        'name' => 'Собеседование в IT компании',
        'date_deadline' => '01.06.2018',
        'category' => $categories_array[3],
        'done' => false
    ],
    [
        'name' => 'Выполнить тестовое задание',
        'date_deadline' => '25.05.2018',
        'category' => $categories_array[3],
        'done' => false
    ],
    [
        'name' => 'Сделать задание первого раздела',
        'date_deadline' => '21.04.2018',
        'category' => $categories_array[2],
        'done' => true
    ],
    [
        'name' => 'Встреча с другом',
        'date_deadline' => '22.04.2018',
        'category' => $categories_array[1],
        'done' => false
    ],
    [
        'name' => 'Купить корм для кота',
        'date_deadline' => NULL,
        'category' => $categories_array[4],
        'done' => false
    ],
    [
        'name' => 'Заказать пиццу',
        'date_deadline' => NULL,
        'category' => $categories_array[4],
        'done' => false
    ],
];

?>