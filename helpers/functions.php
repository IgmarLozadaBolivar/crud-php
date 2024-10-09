<?php 

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