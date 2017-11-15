<?php

function include_template($path, $options_array) {
    if(!isset($path)) {
        return $template_view;
    } else {
        $title = $options_array['title'];
        $user_name = $options_array['user_name'];
        $categories_array = $options_array['categories'];
        $tasks_array = $options_array['tasks_array'];
        $content = $options_array['content'];
    
        require_once($path);
        $template_view = ob_get_clean();
        
        return $template_view;
    }
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