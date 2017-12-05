<div class="modal">
    <button onclick="window.location.href='index.php'" class="modal__close" type="button" name="button">Закрыть</button>

    <h2 class="modal__heading">Вход на сайт</h2>

    <form class="form" action="index.php" method="post">
        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>

            <input class="form__input <?php if ($errors['email']):?>form__input--error<?php endif ?>" type="text" name="email" id="email" value="<?=htmlspecialchars($_POST['email'])?>" placeholder="Введите e-mail">

            <?php if ($errors['email']):?>
            <p class="form__message"><?=$errors['email']?></p>
            <?php endif ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>

            <input class="form__input <?php if ($errors['password']):?>form__input--error<?php endif ?>" type="password" name="password" id="password" value="" placeholder="Введите пароль">

            <?php if ($errors['password']):?>
            <p class="form__message"><?=$errors['password']?></p>
            <?php endif ?>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Войти">
        </div>
    </form>
</div>