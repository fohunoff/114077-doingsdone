<?php

require_once 'functions.php';
require_once 'data.php';

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

$days = rand(-3, 3);
$task_deadline_ts = strtotime("+" . $days . " day midnight"); // метка времени даты выполнения задачи
$current_ts = strtotime('now midnight'); // текущая метка времени

// запишите сюда дату выполнения задачи в формате дд.мм.гггг
$date_deadline =  date('d.m.Y', $task_deadline_ts);

// в эту переменную запишите кол-во дней до даты задачи
$days_until_deadline =  floor(($task_deadline_ts - $current_ts)/86400);

// Подключение шаблонов
$title = "Дела в порядке";
$user_name = "Иван";

ob_start();
$page_content = include_template('templates/index.php', ['tasks_array' => $tasks_array]);
$layout_content = include_template('templates/layout.php',
    [
        'title' => htmlspecialchars($title),
        'user_name' => htmlspecialchars($user_name),
        'categories' => $categories_array,
        'content' => $page_content,
        'tasks_array' => $tasks_array
    ]);
ob_end_flush();
print($template_view);

?>