<?php

    function crearSesion()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_name('CRUD-PHP');
            session_start();
        }
    }

?>