<?php 

    include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'db.php';
    #include __DIR__ . '/../database/db.php';

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
                if (headers_sent())
                {
                    echo "<script> window.location.href = 'index.php?view=login'; </script>";
                } 
                else
                {
                    header("Location: index.php?view=login");
                }
                exit;
            }
        } 
        else 
        {
            $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
            $stmt->execute(['email' => $email, 'password' => $password]);


            if (headers_sent())
            {
                echo "<script> window.location.href = 'index.php?view=login'; </script>";
            } 
            else
            {
                header("Location: index.php?view=login");
            }
            exit;
        }

        $stmt = null;
        $pdo = null;
    }

?>

<main>
    <h2
        style="text-align: center;">
        Crear cuenta
    </h2>

    <!-- Formulario para crear cuentas -->
    <form
        action=""
        method="post">
        <fieldset>
        <!-- Caja de texto - correo -->
        <label
            for="email"
            style="width: 400px;">
            Email
            <input
                type="email"
                name="email"
                id="email"
                placeholder="Correo electrónico"
                autocomplete="email"
                aria-label="Email"
                aria-describedby="email-helper" />
            <small
                id="email-helper">
                Nunca compartiremos tu correo.
            </small>
        </label>

        <!-- Caja de texto - contraseña -->
        <label
            for="password"
            style="width: 400px;">
            Clave
            <input
                type="password"
                name="password"
                id="password"
                placeholder="Clave"
                autocomplete="current-password"
                aria-label="Password"
                aria-describedby="password-helper" />
            <small
                id="password-helper">
                No compartiremos tu clave.
            </small>
        </label>
        </fieldset>

        <!-- Btn para enviar los datos del formulario -->
        <input
            id="input-submit"
            type="submit"
            value="Crear cuenta"
            disabled>

        <div class="container-register">
            <p style="font-size: 20px;">
                Tienes una cuenta? 
                <a href="index.php?view=login">
                    Entra aquí
                </a>.
            </p>
        </div>
    </form>
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
            emailHelper.textContent = 'Esperamos un correo con arroba y dominio.'
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