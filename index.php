<?php 

    #require_once __DIR__ . 'helpers/functions.php';
    #include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'db.php';
    #require_once __DIR__ . '/database/db.php';

    declare(strict_types = 1);

    function render_views($views, array $data = [])
    {
        extract($data);
        require "./views/$views.php";
    }

    function render_templates($templates, array $data = [])
    {
        extract($data);
        require "./templates/$templates.php";
    }

 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php render_templates('head'); ?>
</head>
<body>
    <?php

        if (!isset($_GET['view']) || $_GET['view'] == '')
        {
            $_GET['view'] = 'login';
        }

        if (is_file('./views/'.$_GET['view'].'.php')
            && $_GET['view'] != 'login'
                && $_GET['view'] != '404')
        {
            include __DIR__ . "/views/".$_GET['view'].".php";
        } else
        {
            if ($_GET['view'] == 'login')
            {
                include __DIR__ . '\views\login.php';
            } else if (isset($_GET['view']) && $_GET['view'] == 'home')
            {
                include __DIR__ . '\views\home.php';
            }
            else
            {
                include __DIR__ . '\views\404.php';
            }
        }

    ?>
</body>
</html>