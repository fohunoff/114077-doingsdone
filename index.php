<?php
session_start();

require_once 'functions.php';
require_once 'mysql_helper.php';
require_once 'init.php';

// Заголовок сайта
$title = "Дела в порядке";

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

// Инициализация переменных
$body_class = NULL;
$guest_page = NULL;
$form_login = NULL;
$empty_search = NULL;
$tasks_array = NULL;
$project_task = NULL;
$categories_array = NULL;
$form_addtask = NULL;
$form_addproject = NULL;
$page_content = NULL;
$layout_content = NULL;

// Запрос к базе данных, чтобы получить массив пользователей
$sql = "SELECT * FROM users";
$result = mysqli_query($link, $sql);

if ($result) {
    $users = mysqli_fetch_all($result,  MYSQLI_ASSOC); // Массив пользователя
}
else {
    $error_page = include_template('templates/db_error.php', [
        'error_text' => $error_text,   
    ]);
    print($error_page);
    exit;
}

// Если сессии зарегистрированного пользователя нет
if (!$_SESSION['user']) {

    // Проверка параметра reg и показ формы регистрации
    if (isset($_GET['reg'])) {
        $registration = include_template('templates/registration.php', [
            'title' => $title,
        ]);
        print($registration);
        exit;
    }

    // Проверка и отправка данных для нового пользователя
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reg'])) {
        
        $check_user = $_POST;
        
        $email = $_POST['email'];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);  
    
        $required = ['email', 'password', 'name'];
        $rules = ['email'];
        $errors = [];
    
        foreach ($check_user as $key => $value) {
            // Если строки пустые
            if (in_array($key, $required) && $value == '') {
                $errors[$key] = "Заполните это поле";
            } else {
                // Если строки нуждаются в проверке
                if (searchUserByEmail($email, $users)) {
                    $errors['email'] = "Такой пользователь уже зарегистрирован";
                }

                // Проверять на валидность email
                if (in_array($key, $rules)) {
                    if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $email = $value;
                    } else {
                        $errors[$key] = "Неверный формат эл.почты";
                    }
                }
            }
        } 

        // Если ошибок во время проверки не было выявлено, то добавляем пользователя в базу данных
        if (!count($errors)) { 
            $sql_insert = "INSERT INTO users (email, password, name) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($link, $sql_insert);
            mysqli_stmt_bind_param($stmt, 'sss', $email, $password_hash, $name);
            $res = mysqli_stmt_execute($stmt);   
            if ($res) {
                $user_id = mysqli_insert_id($link);
                $sql = "SELECT * FROM users WHERE id = " . $user_id;
                $row = mysqli_query($link, $sql);
                $user = mysqli_fetch_assoc($row);
                //$_SESSION['user'] = $user;
            } else {
                $error_page = include_template('templates/db_error.php', [
                    'error_text' => $error_text,   
                ]);
                print($error_page);
                exit;
            }
            
            // Создаём категорию по умолчанию -- Входящие
            $project_null = 'Входящие';
            $sql_insert = "INSERT INTO projects (name, user_id) VALUES (?, ?)";
            $stmt = mysqli_prepare($link, $sql_insert);
            mysqli_stmt_bind_param($stmt, 'ss', $project_null, $user['id']);
            $res = mysqli_stmt_execute($stmt);

            // Обновление страницы
            header('Location: index.php?login=1');
            exit;
    
        // Если возникли ошибки снова выводим страницу регистрации, но уже с массивом ошибок    
        } else {
            $registration = include_template('templates/registration.php', [
                'errors' => $errors,
            ]);
            print($registration);
            exit;
        }
    }

    // Проверка параметра login и показ формы входа
    if (isset($_GET['login'])) {
        $body_class = "overlay";
        $form_login = include_template('templates/form_login.php', [

        ]);
    }

    // Проверка данных, которые передал пользователь для аутентификации
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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
    exit;  
}

/* Вывод шаблонов и данных для авторизованного пользователя */

// Вывод всех задач
$sql = "SELECT * FROM tasks WHERE user_id =" . $_SESSION['user']['id'];
$result = mysqli_query($link, $sql);
    if ($result) {
        $tasks_array = mysqli_fetch_all($result,  MYSQLI_ASSOC);
    }

// Вывод задач согласно активному пункту категории проектов
if (isset($_GET['id'])) {
    $project = $_GET['id'];
    if ($project == '0') {
        $project_task = $tasks_array;
    }
    else if (isset($project)) {
        $project_task = array_filter($tasks_array, function($task) use($project) {
            return ($task['project_id'] == $project);
        });
    } else {
        header('HTTP/1.1 404 Not Found');
        exit;
    };
};

// Вывод задач по временной метке
if(isset($_GET['sort'])) {
    if($_GET['sort'] == 'today') {
        $sql = "SELECT * FROM tasks WHERE user_id =" . $_SESSION['user']['id'] . " AND date = CURDATE()";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $tasks_array = mysqli_fetch_all($result,  MYSQLI_ASSOC);
        }
    }
    if($_GET['sort'] == 'tomorrow') {
        $sql = "SELECT * FROM tasks WHERE user_id =" . $_SESSION['user']['id'] . " AND date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $tasks_array = mysqli_fetch_all($result,  MYSQLI_ASSOC);
        }
    }
    if($_GET['sort'] == 'overdue') {
        $sql = "SELECT * FROM tasks WHERE user_id =" . $_SESSION['user']['id'] . " AND is_done = 0 AND date < CURDATE()";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $tasks_array = mysqli_fetch_all($result,  MYSQLI_ASSOC);
        }
    }
}

// Отправка эл.почты за час до наступления задачи
/*
$sql = "SELECT * FROM tasks WHERE is_done = 0 AND date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
$result = mysqli_query($link, $sql);
if ($result) {
    $send_array = mysqli_fetch_all($result,  MYSQLI_ASSOC);
}
foreach ($send_array as $value) {

}
*/

// переключатель задач "выполненно/не выполнено"
if (isset($_GET['show_completed'])) {
    setcookie('show', (int)$_GET['show_completed']);
    header('Location: index.php');
    exit;
} 

// Отмечать задачу как "выполненно/не выполнено"
if(isset($_GET['done'])) {
    $sql_select = "SELECT is_done FROM tasks WHERE id = " . $_GET['done'];
    $result_select = mysqli_query($link, $sql_select);
    $row = mysqli_fetch_assoc($result_select);
    $is_done = $row['is_done'];

    if((int)$is_done === 1) {
        $sql = "UPDATE tasks SET is_done = 0 WHERE id = " . $_GET['done'];
        $result = mysqli_query($link, $sql);
        header('Location: index.php');
        exit;
    }
    
    if((int)$is_done === 0) {
        $sql = "UPDATE tasks SET is_done = 1 WHERE id = " . $_GET['done'];
        $result = mysqli_query($link, $sql);
        header('Location: index.php');
        exit;
    }
}

// Список всех категорий (проектов) для текущего пользователя
$sql = "SELECT * FROM projects WHERE user_id = {$_SESSION['user']['id']};";
$result = mysqli_query($link, $sql);
    if ($result) {
        $categories_array = mysqli_fetch_all($result,  MYSQLI_ASSOC);
    }

// Полнотекстовый поиск
if(isset($_GET['search']) && isset($_POST['search_task'])) {
    $search_query = trim($_POST['search_task']);
    $sql = "SELECT * FROM tasks WHERE MATCH(name) AGAINST('{$search_query}')";
    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) != 0) {
        $project_task = mysqli_fetch_all($result,  MYSQLI_ASSOC);
    } else {
        $empty_search = "Ничего не нашли";
    }
}

// Подключение и показ формы для добавления новой задачи или категории
if (isset($_GET['add'])) {

    $body_class = "class='overlay'";

    if($_GET['add'] == 'task') {
        $form_addtask = include_template('templates/form_addtask.php', [
            'categories_array' => $categories_array,
        ]);
    } 
    
    if($_GET['add'] == 'project') {
        $form_addproject = include_template('templates/form_addproject.php', []);
    }
}

// Если форма на добавление задачи была отправлена, делаем проверку
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task'])) {
    $new_task = $_POST;
    $required = ['name', 'project_id'];
    $rules = ['date_deadline'];
    $errors = [];

    foreach ($new_task as $key => $value) {

        if (in_array($key, $required) && $value == '') {
            $errors[$key] = "Заполните это поле";
        } 

        if (in_array($key, $rules)) {
        $date_deadline = check_date($value);

            if ($date_deadline == 0) {
                $errors[$key] = "Неверный формат";
            } else {
                $new_task['date'] = $date_deadline;
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

    // Если ошибок во время проверки не было выявлено, то добавляем новую задачу в базу данных
    if (!count($errors)) {
        $new_task['is_done'] = false; // Чтобы задача не помечалась как выполненная
        $sql_insert = "INSERT INTO tasks (name, date, user_id, project_id, is_done, file_name, file_path) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $sql_insert);
        mysqli_stmt_bind_param($stmt, 'ssiiiss',
            $new_task['name'],
            $new_task['date'],
            $_SESSION['user']['id'],
            $new_task['project_id'],
            $new_task['is_done'],
            $new_task['file_name'],
            $new_task['file_path']            
        );
        $res = mysqli_stmt_execute($stmt);
        header('Location: index.php');
        exit;

    // Если возникли ошибки снова выводим форму, но уже с массивом ошибок    
    } else {
        $body_class = "class='overlay'";
        $form_addtask = include_template('templates/form_addtask.php', [
            'errors' => $errors,
            'categories_array' => $categories_array,
        ]);
    }
}

// Если форма на добавление проектов отправлена, делаем проверку
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['project'])) {
    $new_project = $_POST;

    $required = ['name'];
    $errors = [];

    foreach ($new_project as $key => $value) {
        if (in_array($key, $required) && $value == '') {
            $errors[$key] = "Заполните это поле";
        } 
    }

    if (!count($errors)) {
        $sql_insert = "INSERT INTO projects (name, user_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($link, $sql_insert);
        mysqli_stmt_bind_param($stmt, 'si', $_POST['name'], $_SESSION['user']['id']);
        $res = mysqli_stmt_execute($stmt);
        header('Location: index.php');

    // Если возникли ошибки снова выводим форму, но уже с массивом ошибок    
    } else {
        $body_class = "class='overlay'";
        $form_addproject = include_template('templates/form_addproject.php', [
            'errors' => $errors
        ]);
    }
}

// Проверка метки времени


/* Подключение шаблонов для авторизованного пользователя*/

// блок вывода задач
$page_content = include_template('templates/index.php', [
        'tasks_array' => isset($project_task) ? $project_task : $tasks_array,
        'show_completed' => $show_completed,
        'empty_search' => $empty_search,
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
        'form_addtask' => $form_addtask,
        'form_addproject' => $form_addproject,
    ]);

print($layout_content);

?>