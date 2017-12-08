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
<a href="/?sort=all" class="tasks-switch__item <?php if($_GET['sort'] == 'all' || !$_GET['sort']):?>tasks-switch__item--active<?php endif;?>">Все задачи</a>
        <a href="/?sort=today" class="tasks-switch__item <?php if($_GET['sort'] == 'today'):?>tasks-switch__item--active<?php endif;?>">Повестка дня</a>
        <a href="/?sort=tomorrow" class="tasks-switch__item <?php if($_GET['sort'] == 'tomorrow'):?>tasks-switch__item--active<?php endif;?>">Завтра</a>
        <a href="/?sort=overdue" class="tasks-switch__item <?php if($_GET['sort'] == 'overdue'):?>tasks-switch__item--active<?php endif;?>">Просроченные</a>
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