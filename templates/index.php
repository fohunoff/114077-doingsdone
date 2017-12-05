<?php
// Используемые переменные:
// 
// $tasks_array  -- список всех задач
// 
//
?>

<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.html" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <?php if((int)$_COOKIE['show'] === 1) : ?>
            <a href="/?show_completed=0>">
            <input class="checkbox__input visually-hidden" type="checkbox" checked>
            <span class="checkbox__text">Показывать выполненные</span>
            </a>
        <?php else: ?>
            <a href="/?show_completed=1>">
            <input class="checkbox__input visually-hidden" type="checkbox">
            <span class="checkbox__text">Показывать выполненные</span>
            </a>
        <?php endif ?>
    </label>
</div>

<table class="tasks">
    <?php foreach($tasks_array as $task) : ?>
    <?php if($_COOKIE['show'] || $task['is_done'] == 0) : ?>
    <tr class="tasks__item task <?php if($task['is_done'] == 1) : ?>task--completed<?php endif; ?>">
        
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden" type="checkbox">
                <a href="/?done=<?= htmlspecialchars($task['id']); ?>"><span class="checkbox__text"><?= htmlspecialchars($task['name']); ?></span></a>
            </label>
        </td>

        <td class="task__file">
            <?php if ($task['file_name']) : ?>
            <a class="download-link" href="<?='/' . htmlspecialchars($task['file_path']);?>"><?=$task['file_name'];?></a>
            <?php endif ?>
        </td>

        <td class="task__date">
            <?= check_date(htmlspecialchars($task['date'])); ?>
        </td>

    </tr>
    <?php endif; ?>
    <?php endforeach; ?>
</table>