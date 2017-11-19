<?php

/**
* Подключает файлы шаблонов
* 
* @param string     $path           -- путь к расположению подключаемого шаблона
* @param array      $options_array  -- массив с переменными, которые используются в шаблоне
* 
* @return string    $template_view  -- строка обработанного кода
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
* Подключает файлы шаблонов
* 
* @param array      $array          -- массив со всеми задачами в проекте
* @param string     $category_name  -- название категории проекта
* 
* @return int       $task_num       -- количество задач в категории
*/
function task_num($array, $category_name) {
    $task_num = 0;
    foreach ($array as $task) {
        if ($category_name == "Все") {
            $task_num++;
        } elseif ($category_name == $task['category']) {
            $task_num++;
        }
    }

    return $task_num;
}

?>