<?php 

    include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'head.php';
    include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'db.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $email = '';
    $password = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $email = limpiar_cadenas($_POST['email']);
        $password = limpiar_cadenas($_POST['password']);

        $pdo = conectar_database();

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");

        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() == 1)
        {
            $check_user = $stmt->fetch();

            if ($check_user['email'] == $email && $check_user['password'] == $password) 
            {
                include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'sessions.php';
                crearSesion();
                $_SESSION['id'] = $check_user['id'];
                $_SESSION['email'] = $check_user['email'];

                echo var_dump($_SESSION);

                if (headers_sent())
                {
                    echo "<script> window.location.href = 'index.php?view=home'; </script>";
                } 
                else
                {
                    header("Location: index.php?view=home");
                }
                exit;
            } else 
            {
                echo '<script> Swal.fire({
                        title: "Contraseña incorrecta!",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500
                        }); </script>';
            }
        } 
        else 
        {
            echo '<script> Swal.fire({
                title: "Email incorrecto!",
                text: "Correo electrónico no encontrado!",
                icon: "error"
                }); </script>';
        }

        $stmt = null;
        $pdo = null;
    }

?>

<main class="main">
    <div class="form-login">
        <h2
            style="text-align: center;">
            Acceder
        </h2>

        <!-- Formulario para acceder -->
        <form
            action=""
            method="post"
            class="formulario-login">
            <fieldset>
                <input
                    style="width: 400px;"
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Correo"
                    autocomplete="email"
                    aria-label="Email"
                    aria-describedby="email-helper" />
                <small
                    id="email-helper">
                    Nunca compartiremos tu correo.
                </small>
            </fieldset>

            <fieldset role="group" style="width: 400px;">
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Clave"
                    autocomplete="current-password"
                    aria-label="Password"
                    aria-describedby="password-helper" />
                <input
                    id="input-submit"
                    type="submit"
                    value="Acceder"
                    disabled />
            </fieldset>

            <small
                id="password-helper">
                No compartiremos tu clave.
            </small>
        </form>

        <div class="container-register">
            <p style="font-size: 20px;">
                No tienes una cuenta?
                <a href="index.php?view=registro">
                    Entra aquí
                </a>
            </p>
        </div>

        <div class="container-google">
            <p style="font-size: 20px; display: flex; justify-content: center; align-items: center;">
                <hr style="border: none; height: 1px; background-color: #e5e7eb; margin: 4px 0 8px 0;">
                <button style="width: 400px; background-color: #fff; color: #000;">
                    <svg width="32px" height="32px" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M30.0014 16.3109C30.0014 15.1598 29.9061 14.3198 29.6998 13.4487H16.2871V18.6442H24.1601C24.0014 19.9354 23.1442 21.8798 21.2394 23.1864L21.2127 23.3604L25.4536 26.58L25.7474 26.6087C28.4458 24.1665 30.0014 20.5731 30.0014 16.3109Z" fill="#4285F4"></path>
                            <path d="M16.2863 29.9998C20.1434 29.9998 23.3814 28.7553 25.7466 26.6086L21.2386 23.1863C20.0323 24.0108 18.4132 24.5863 16.2863 24.5863C12.5086 24.5863 9.30225 22.1441 8.15929 18.7686L7.99176 18.7825L3.58208 22.127L3.52441 22.2841C5.87359 26.8574 10.699 29.9998 16.2863 29.9998Z" fill="#34A853"></path>
                            <path d="M8.15964 18.769C7.85806 17.8979 7.68352 16.9645 7.68352 16.0001C7.68352 15.0356 7.85806 14.1023 8.14377 13.2312L8.13578 13.0456L3.67083 9.64746L3.52475 9.71556C2.55654 11.6134 2.00098 13.7445 2.00098 16.0001C2.00098 18.2556 2.55654 20.3867 3.52475 22.2845L8.15964 18.769Z" fill="#FBBC05"></path>
                            <path d="M16.2864 7.4133C18.9689 7.4133 20.7784 8.54885 21.8102 9.4978L25.8419 5.64C23.3658 3.38445 20.1435 2 16.2864 2C10.699 2 5.8736 5.1422 3.52441 9.71549L8.14345 13.2311C9.30229 9.85555 12.5086 7.4133 16.2864 7.4133Z" fill="#EB4335"></path>
                        </g>
                    </svg>
                    <a href="<?= $pensandoComo; ?>"
                        style="text-decoration: none; color: #000;">
                        Entrar con Google
                    </a>
                </button>
            </p>
        </div>
    </div>
</main>

<script>
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const inputSubmit = document.getElementById('input-submit');

    const emailHelper = document.getElementById('email-helper');
    const passwordHelper = document.getElementById('password-helper');

    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const passwordPattern = /^\d+$/;

    function inputs_validos() {
        const correoValido = emailPattern.test(emailInput.value);
        const contraseñaValida = passwordPattern.test(passwordInput.value) && passwordInput.value.length >= 4;

        if (correoValido && contraseñaValida) {
            inputSubmit.removeAttribute('disabled');
        } else {
            inputSubmit.setAttribute('disabled', 'disabled');
        }
    }

    emailInput.addEventListener('input', function() {
        emailHelper.textContent = '';
        emailInput.setAttribute('aria-invalid', 'false');

        if (!emailPattern.test(emailInput.value)) {
            emailHelper.textContent = 'Esperamos un correo con @ y dominio.'
            emailInput.setAttribute('aria-invalid', 'true');
            event.preventDefault();
        } else {
            emailHelper.textContent = 'Correo válido.';
            emailInput.setAttribute('aria-invalid', 'false');
            event.preventDefault();
        }

        inputs_validos();
    });

    passwordInput.addEventListener('input', function() {
        passwordHelper.textContent = '';
        passwordInput.setAttribute('aria-invalid', 'false');

        if (!passwordPattern.test(passwordInput.value)) {
            passwordHelper.textContent = 'Solo se permite números.';
            passwordInput.setAttribute('aria-invalid', 'true');
            event.preventDefault();
        } else if ((passwordInput.value).length < 4) {
            const length = passwordInput.value.length;
            if (length > 0) {
                passwordHelper.textContent = `Cuentas con ${length} dígitos de 4.`;
            } else {
                passwordHelper.textContent = '';
            }
            passwordInput.setAttribute('aria-invalid', 'true');
            event.preventDefault();
        } else {
            passwordHelper.textContent = 'Todo correcto.';
            passwordInput.setAttribute('aria-invalid', 'false');
            event.preventDefault();
        }

        inputs_validos();
    });
</script>