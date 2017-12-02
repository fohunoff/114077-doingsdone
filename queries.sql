USE doingsdone;

# Добавление пользователей

INSERT INTO users
        (email, password, name, avatar_path, group_id, is_deleted)
    VALUES
        ('ignat.v@gmail.com', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka', 'Игнат', 'img/user-pic.jpg', NULL, 0),
        ('kitty_93@li.ru', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa', 'Леночка', 'img/user-pic.jpg', NULL, 0),
        ('warrior07@mail.ru', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', 'Руслан', 'img/user-pic.jpg', NULL, 0);


# Добавление названия проектов

INSERT INTO projects (name)
    VALUES
        ('Входящие'),
        ('Учеба'),
        ('Работа'),
        ('Домашние дела'),
        ('Авто');


# Добавление задач

INSERT INTO tasks
        (name, date, user_id, project_id, is_done, file_path)
    VALUES
        ('Собеседование в IT компании', '01.06.2018', 1, 3, 0, NULL),
        ('Выполнить тестовое задание', '25.05.2018', 1, 3, 0, NULL),
        ('Сделать задание первого раздела', '21.04.2018', 1, 2, 1, NULL),
        ('Встреча с другом', '22.04.2018', 1, 1, 0, NULL),
        ('Купить корм для кота', NULL, 1, 4, 0, NULL),
        ('Заказать пиццу', NULL, 1, 4, 0, NULL);


# Получить список из всех проектов для одного пользователя

SELECT p.id, p.name FROM projects p
    JOIN users u
    ON p.user_id = u.id;


# Получить список из всех задач для одного проекта

SELECT t.id, t.name, t.date, t.is_done, t.file_path FROM tasks t
    JOIN users u
    ON t.user_id = u.id
    JOIN projects p
    ON t.project_id = p.id;


# Пометить задачу как выполненную

UPDATE tasks SET is_done = 1 WHERE id = 1;


# Получить все задачи для завтрашнего дня

SELECT * FROM tasks WHERE date = CURDATE() + INTERVAL 1 DAY;


# Обновить название задачи по её идентификатору

UPDATE tasks SET name = 'Новое имя задачи' WHERE id = 1;