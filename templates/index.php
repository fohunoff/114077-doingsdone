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
        <a href="/">
            <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
            <?php if ($show_complete_tasks == 1) : ?>
                <input class="checkbox__input visually-hidden" type="checkbox" checked>
            <?php else : ?>
                <input class="checkbox__input visually-hidden" type="checkbox">
            <?php endif; ?>
            <span class="checkbox__text">Показывать выполненные</span>
        </a>
    </label>
</div>

<table class="tasks">
    <?php for($i = 0; $i < count($tasks_array); $i++) : ?>
    <tr class="tasks__item task <?php if($tasks_array[$i]['done'] == 1) : ?>task--completed<? endif; ?>">
        <td class="task__select">
            <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden" type="checkbox">
                <a href="/"><span class="checkbox__text"><?= htmlspecialchars($tasks_array[$i]['name']); ?></span></a>
            </label>
        </td>

        <td class="task__file">
        </td>

        <td class="task__date">
        <?= $tasks_array[$i]['date_deadline']; ?>
        </td>
    </tr>
    <? endfor; ?>
</table>