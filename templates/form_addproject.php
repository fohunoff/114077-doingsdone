<div class="modal">
  <button class="modal__close" onclick="window.location.href='index.php'" type="button" name="button">Закрыть</button>

  <h2 class="modal__heading">Добавление проекта</h2>

  <form class="form" action="index.php" method="post">
    <input hidden name="project">
    <div class="form__row">
        <label class="form__label" for="project_name">Название <sup>*</sup></label>

        <input class="form__input <?php if ($errors['name']):?>form__input--error<?php endif ?>" type="text" name="name" id="project_name" value="<?=$_POST['name']?>" placeholder="Введите название проекта">

        <?php if ($errors['name']):?>
            <p class="form__message"><?=$errors['name']?></p>
        <?php endif ?>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="" value="Добавить">
    </div>
  </form>
</div>