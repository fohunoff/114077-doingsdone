<?php

$link = mysqli_connect("localhost", "root", "", "doinsdone");

if ($link == false) {
    $error_text = mysqli_connect_error();
    $error_page = include_template('templates/error.php', [
        'error_text' => $error_text,   
    ]);
    print($error_page);
    exit;
}

?>