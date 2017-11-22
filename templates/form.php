<?php
// Используемые переменные:
// 
// $categories_array        Список категорий
// $errors                  Массив с ошибками при неверном заполнении формы
?>


<div class="modal">
    <button onclick="window.location.href='index.php'" class="modal__close" type="button" name="button">Закрыть</button>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form"  action="index.php" method="post" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input <?php if ($errors['name']):?>form__input--error<? endif ?>" type="text" name="name" id="name" value="<?=$_POST['name']?>" placeholder="Введите название">

            <?php if ($errors['name']):?>
            <p class="form__message"><?=$errors['name']?></p>
            <? endif ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="category">Проект <sup>*</sup></label>

            <select class="form__input form__input--select <?php if ($errors['category']):?>form__input--error<? endif ?>" name="category" id="category">
                <option value=""></option>
                <? foreach ($categories_array as $category_id => $category_name) : ?>
                <? if ($category_id != 0) : ?>                
                <option value="<?=$category_id?>"<? if ($_POST['category'] && $_POST['category'] == $category_id) : ?>selected<? endif ?>><?=$category_name?></option>
                <? endif ?>
                <? endforeach ?>
            </select>

            <?php if ($errors['category']):?>
            <p class="form__message"><?='Выберите проект'?></p>
            <? endif ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="date_deadline">Дата выполнения</label>

            <input class="form__input form__input--date <?php if ($errors['date_deadline']):?>form__input--error<? endif ?>" type="date" name="date_deadline" id="date_deadline" value="<?=$_POST['date_deadline']?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">

            <?php if ($errors['date_deadline']):?>
            <p class="form__message"><?=$errors['date_deadline']?></p>
            <? endif ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="task_file">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="task_file" id="task_file" value="">

                <label class="button button--transparent" for="task_file">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>


    </form>
</div>