<?php

require_once 'init.php';

// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl'))
->setUsername('doingsdone@mail.ru')
->setPassword('rds7BgcL')
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

// Поиск задач, удовлетворяющих условию, и отправка сообщения
$sql = "SELECT * FROM users";
$result = mysqli_query($link, $sql);
if ($result) {
    $users = mysqli_fetch_all($result,  MYSQLI_ASSOC);
}

// Создаём рассылку для каждого пользователя
foreach($users as $user) {
    // Выборка задач по дате
    $sql = "SELECT name, date FROM tasks WHERE user_id = {$user['id']} AND is_done = 0 AND date <= DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND date >= NOW()";
    $result = mysqli_query($link, $sql);
    // Если совпадения есть, то подготавливаем и отправляем сообщение на почту
    if ((int)mysqli_num_rows($result) !== 0)  {
        $task_array = mysqli_fetch_all($result,  MYSQLI_ASSOC);
        foreach($task_array as $value) {
            $task_list = $task_list . "<li>Имя задачи: " . htmlspecialchars($value['name']) . ". Дата исполнения: " . $value['date'] . "</li>";
        }

        $message_topic = "Уведомление от сервиса «Дела в порядке»";
        $message_text = "Уважаемый, " . htmlspecialchars($user['name']) . "! " . "Ваш список дней на завтра:<ul>{$task_list}</ul>";

        // Create a message
        $message = (new Swift_Message('Wonderful Subject'))
        ->setFrom(['doingsdone@mail.ru'])
        ->setTo(['4f-foxy@mail.ru'])
        ->setSubject($message_topic)
        ->setBody($message_text, 'text/html')
        ;

        // Send the message
        $result = $mailer->send($message);
    }
}

?>