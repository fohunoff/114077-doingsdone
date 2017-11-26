<?php
session_start();

require_once 'data.php';
require_once 'functions.php';

// Заголовок сайта
$title = "Дела в порядке";

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

$body_class = "";

// Проверка на авторизацию пользователя
if (!$_SESSION['user']) {

    // Проверка параметра login и показ формы входа
    if (isset($_GET['login'])) {
        $body_class = "overlay";
        $form_login = include_template('templates/form_login.php', [

        ]);
    }

    // Проверка данных, которые передал пользователь для аутентификации
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        require_once 'userdata.php'; // Подключаемся к "базе данных" пользователей

        $check_user = $_POST;
    
        $required = ['email', 'password'];
        $errors = [];
    
        foreach ($check_user as $key => $value) {
            
            // Если строки пустые
            if (in_array($key, $required) && $value == '') {
                $errors[$key] = "Заполните это поле";
            } else {
                // Если строки нуждаются в проверке
                if ($user = searchUserByEmail($check_user['email'], $users)) {
                    $pass_check = password_verify($check_user['password'], $user['password']);
                    if ($pass_check) {   
                            $_SESSION['user'] = $user;
                            break;
                        } else {    
                            $errors['password'] = "Неверный пароль";
                            break;
                        }

                } else {
                    $errors['email'] = "Такой пользователь не найден";
                }
            }
        }
        

        // Если ошибок во время проверки не было выявлено, то записываем пользователя в сессию и открываем доступ к сайту
        if (!count($errors)) {
            header('Location: index.php');
            die();
    
        // Если возникли ошибки снова выводим форму входа, но уже с массивом ошибок    
        } else {
            $body_class = "overlay";
            $form_login = include_template('templates/form_login.php', [
                'errors' => $errors,
            ]);
        }
    }

    $guest_page = include_template('templates/guest.php', [
        'title' => $title,
        'body_class' => $body_class,
        'form_login' => $form_login
    ]);

    print($guest_page);
    die();  
}

/* Вывод шаблонов и данных для авторизованного пользователя */

if (isset($_GET['show_completed'])) {
    setcookie('show', (int)$_GET['show_completed']);
    header('Location: index.php');
    die();
} 

// Вывод задач согласно активному пункту категории
if (isset($_GET['id'])) {
    $project = $_GET['id'];
    if ($project == '0') {
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

// Подключение и показ формы
if (isset($_GET['add'])) {
    $body_class = "class='overlay'";
    $form_addtask = include_template('templates/form_addtask.php', [
        'categories_array' => $categories_array,
    ]);
}

// Если форма была отправлена, делаем проверку
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_task = $_POST;

    $required = ['name', 'category'];
    $rules = ['date_deadline'];
    $errors = [];

    foreach ($new_task as $key => $value) {

        if (in_array($key, $required) && $value == '') {
            $errors[$key] = "Заполните это поле";
        } 

        if (in_array($key, $rules)) {
        $date_deadline = check_date($value);

            if ($date_deadline == 1) {
                $errors[$key] = "Неверный формат";
            } else {
                $new_task['date_deadline'] = $date_deadline;
            }
        }
    }

    // Если файл был загружен, то переносим его в корень сайта
    if (isset($_FILES['task_file'])) {
        $file_path = __DIR__ . '/' . $_FILES['task_file']['name'];
        move_uploaded_file($_FILES['task_file']['tmp_name'], $file_path);
    }

    if (isset($file_path)) {
        $new_task['file_path'] = $file_path; // Путь к файлу
        $new_task['file_name'] = $_FILES['task_file']['name']; // Имя файла
    }

    // Если ошибок во время проверки не было выявлено, то добавляем новую задачу в начало массива задач
    if (!count($errors)) {
        $new_task['done'] = false; // Чтобы задача не помечалась как выполненная
        array_unshift($tasks_array, $new_task);
        // header('Location: index.php');

    // Если возникли ошибки снова выводим форму, но уже с массивом ошибок    
    } else {
        $body_class = "class='overlay'";
        $form_addtask = include_template('templates/form_addtask.php', [
            'errors' => $errors,
            'categories_array' => $categories_array,
        ]);
    }
}

/* Подключение шаблонов для авторизованного пользователя*/

// блок вывода задач
$page_content = include_template('templates/index.php', [
        'tasks_array' => isset($project_task) ? $project_task : $tasks_array,
        'show_completed' => $show_completed,
    ]);

// Блок вывода всей страницы
$layout_content = include_template('templates/layout.php',
    [
        'title' => $title,
        'body_class' => $body_class,
        'user_name' => $_SESSION['user']['name'],
        'categories_array' => $categories_array,
        'tasks_array' => $tasks_array,
        'page_content' => $page_content,
        'form_addtask' => $form_addtask
    ]);

print($layout_content);

?>