<?php

require_once 'data.php';
require_once 'functions.php';

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

if (isset($_GET['id'])) {
    $project = $categories_array[$_GET['id']];
    if ($project == 'Все') {
        $project_task = $tasks_array;
    }
    else if (isset($project)) {
        $project_task = array_filter($tasks_array, function($task) use($project) {
            return ($task['category'] == $project);
        });
    } else {
        header('HTTP/1.1 404 Not Found');
        die();
    };
};

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

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

$page_content = include_template('templates/index.php', [
        'tasks_array' => isset($project_task) ? $project_task : $tasks_array
    ]);
$layout_content = include_template('templates/layout.php',
    [
        'title' => $title,
        'user_name' => $user_name,
        'categories_array' => $categories_array,
        'tasks_array' => $tasks_array,
        'page_content' => $page_content,
    ]);

print($layout_content);

?>