<?php

function include_template($path, $options_array) {
    $template_view = '';
    if (file_exists($path)) {
        extract($options_array);
        ob_start();
        require_once($path);
        $template_view = ob_get_clean();
    }
    return $template_view;
}

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