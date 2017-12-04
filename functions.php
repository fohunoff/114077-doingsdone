<?php

/**
* Подключает файлы шаблонов
* 
* @param string     $path           -- путь к расположению подключаемого шаблона
* @param array      $options_array  -- массив с переменными, которые используются в шаблоне
* 
* @return string    $template_view  -- строка обработанного кода
*
*/
function include_template($path, $options_array) {
    if (file_exists($path)) {
        extract($options_array);
        ob_start();
        require_once($path);
        $template_view = ob_get_clean();
    }
    return $template_view;
}

/**
 * 
 * Проверяет присутствие email пользователя
 * 
 * @param string     $email     -- почта, которую ввёл пользователь
 * @param array      $users     -- массив электронных почт зарегистрированных пользователей
 * 
 * @return string
 * 
 */
function searchUserByEmail($email, $users)
{
$result = null;
foreach ($users as $user) {
    if ($user['email'] == $email) { // Если email совпадает, то передаётся весь массив пользователя
        $result = $user; 
        break;
    }
}
return $result;
}

/**
* Подключает файлы шаблонов
* 
* @param array      $array          -- массив со всеми задачами в проекте
* @param string     $category_id    -- индекс категории проекта
* 
* @return int       $task_num       -- количество задач в категории
*
*/
function task_num($array, $category_id) {
    $task_num = 0;
    foreach ($array as $task) {
        if ($category_id == "0") {
            $task_num++;
        } elseif ($category_id == $task['category']) {
            $task_num++;
        }
    }

    return $task_num;
}

/**
* Переводит полученную дату в формат dd.mm.yyyy
* 
* @param string     $date               -- Дата, полученная из пользовательской формы
* 
* @return string    $date_deadline      -- Дата формата dd.mm.yyyy
*
*/
function check_date($date) {
    $task_deadline = strtotime($date);
    if(is_int($task_deadline)) {
        $date_deadline =  date('d.m.Y', $task_deadline);

        return $date_deadline;
    } else {
        $date_deadline = 1;
        return $date_deadline;
    }
}

/*
function check_deadline($date) {
    $days = rand(-3, 3);

    $task_deadline_ts = strtotime("+" . $days . $date); // метка времени даты выполнения задачи
    $current_ts = strtotime($date); // текущая метка времени
    
    // в эту переменную запишите кол-во дней до даты задачи
    $days_until_deadline =  floor(($task_deadline_ts - $current_ts)/86400);
    return $days_until_deadline;
} 
*/
?>