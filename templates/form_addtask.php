<div class="modal">
    <button onclick="window.location.href='index.php'" class="modal__close" type="button" name="button">Закрыть</button>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form"  action="index.php" method="post" enctype="multipart/form-data">
        <input hidden name="task">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input <?php if ($errors['name']):?>form__input--error<?php endif ?>" type="text" name="name" id="name" value="<?=$_POST['name']?>" placeholder="Введите название">

            <?php if ($errors['name']):?>
                <p class="form__message"><?=$errors['name']?></p>
            <?php endif ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="project_id">Проект <sup>*</sup></label>

            <select class="form__input form__input--select <?php if ($errors['project_id']):?>form__input--error<?php endif ?>" name="project_id" id="project_id">
                <option value=""></option>

                <?php foreach ($categories_array as $category) : ?>
                <?php if ($category['id'] != 0) : ?>                
                <option value="<?=$category['id']?>"<?php if ($_POST['project_id'] && $_POST['project_id'] == $category['id']) : ?>selected<?php endif ?>><?=$category['name']?></option>
                <?php endif ?>
                <?php endforeach ?>

            </select>

            <?php if ($errors['project_id']):?>
                <p class="form__message"><?='Выберите проект'?></p>
            <?php endif ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>

            <input class="form__input form__input--date <?php if ($errors['date']):?>form__input--error<?php endif ?>" type="date" name="date" id="date" value="<?=$_POST['date']?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">

            <?php if ($errors['date']):?>
                <p class="form__message"><?=$errors['date']?></p>
            <?php endif ?>
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