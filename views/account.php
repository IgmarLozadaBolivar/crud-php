<?php

    include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'db.php';
    include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'sessions.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_name('CRUD-PHP');
    session_start();

    echo var_dump($_SESSION);
    /* if (isset($_SESSION['id'])) {
        echo $_SESSION['id'];
    } else {
        echo "ID no está establecido en la sesión.";
    } */

    $email = $_SESSION['email'];

    $pdo = conectar_database();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");

    $stmt->execute(['email' => $email]);

    if ($stmt->rowCount() == 1)
    {
        $check_user = $stmt->fetch();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            $id = $check_user['id'];
            
            if ($newPassword === $confirmPassword) {
                try {

                    $pdo = conectar_database();

                    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
                    $stmt->execute(['password' => $newPassword, 'id' => $id]);

                    if ($stmt->rowCount() > 0) {
                        echo '<script>
                                Swal.fire({
                                    title: "Contraseña actualizada con éxito",
                                    icon: "success",
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = "index.php?view=home";
                                });
                            </script>';
                    } else {
                        echo '<script>
                                Swal.fire({
                                    title: "Error al actualizar la contraseña",
                                    icon: "error",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            </script>';
                    }

                } catch (Exception $e) {
                    echo '<script>
                            Swal.fire({
                                title: "Error en la actualización",
                                text: "' . $e->getMessage() . '",
                                icon: "error",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        </script>';
                }
            } else {
                echo '<script>
                        Swal.fire({
                            title: "Las contraseñas no coinciden",
                            icon: "error",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>';
            }
        }

        if (isset($_POST['accion']) && $_POST['accion'] == 'eliminar') {
            $email = $_SESSION['email'];
            $pdo = conectar_database();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);

            if ($stmt->rowCount() == 1) {
                try {
                    $stmt = $pdo->prepare("DELETE FROM users WHERE email = :email");
                    $stmt->execute(['email' => $email]);

                    if ($stmt->rowCount() > 0) {
                        echo '<script>
                                Swal.fire({
                                    title: "Cuenta eliminada",
                                    icon: "success",
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = "index.php?view=login";
                                });
                            </script>';
                    }
                } catch (Exception $e) {
                    echo '<script>
                            Swal.fire({
                                title: "Error al eliminar la cuenta",
                                text: "' . $e->getMessage() . '",
                                icon: "error",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        </script>';
                }
            } else {
                echo '<script>
                        Swal.fire({
                            title: "Usuario no encontrado",
                            icon: "error",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>';
            }
        }
    }
?>

<main>
    <h2
        style="text-align: center;">
        Nueva contraseña
    </h2>
    <form action="" method="post">
        <fieldset>

            <!-- NEW PASSWORD -->
            <label
                for="new_password"
                style="width: 400px;">
                Nueva clave
                <input
                    type="password"
                    name="new_password"
                    id="new_password"
                    placeholder="Nueva clave"
                    autocomplete="current-password"
                    aria-label="Password"
                    aria-describedby="new-password-helper" />
                <small
                    id="new-password-helper">
                    Ingresa tu nueva clave.
                </small>
            </label>

            <!-- CONFIRM PASSWORD -->
            <label
                for="confirm_password"
                style="width: 400px;">
                Confirmar clave
                <input
                    type="password"
                    name="confirm_password"
                    id="confirm_password"
                    placeholder="Confirma tu clave"
                    autocomplete="current-password"
                    aria-label="Password"
                    aria-describedby="confirm-password-helper" />
                <small
                    id="confirm-password-helper">
                    Escribe nuevamente tu clave.
                </small>
            </label>
        </fieldset>

        <input
            id="input-submit"
            type="submit"
            value="Actualizar contraseña"
            disabled>

        <div class="container-home">
            <p style="font-size: 20px;">
                Quieres volver? 
                <a href="index.php?view=home">
                    Entra aquí
                </a>
            </p>
            <p style="font-size: 20px;">
                Borrar cuenta? 
                <a style="cursor: pointer;" href="javascript:void(0);" onclick="borrarUsuario()">
                    Entra aquí
                </a>
            </p>
        </div>
    </form>
</main>

<script>
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const inputSubmit = document.getElementById('input-submit');

    const newPasswordHelper = document.getElementById('new-password-helper');
    const confirmPasswordHelper = document.getElementById('confirm-password-helper');

    const passwordPattern = /^\d+$/;

    function eliminarCookie(nombre) {
        document.cookie = nombre + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/";
    }

    function borrarUsuario()
    {
        if (confirm("¿Estás seguro de eliminar tu cuenta?")) {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'accion=eliminar'
            }).then(response => response.text())
            .then(data => {
                eliminarCookie('CRUD-PHP');
                Swal.fire({
                    title: "Cuenta eliminada",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = "index.php?view=login";
                });
            });
        }
    }

    function inputs_validos() {
        const validPassword = passwordPattern.test(newPasswordInput.value) && passwordPattern.test(confirmPasswordInput.value);
        const matchPasswords = newPasswordInput.value === confirmPasswordInput.value;

        if (validPassword && matchPasswords) {
            inputSubmit.removeAttribute('disabled');
        } else {
            inputSubmit.setAttribute('disabled', 'disabled');
        }
    }

    newPasswordInput.addEventListener('input', function() {
        newPasswordHelper.textContent = '';
        newPasswordInput.setAttribute('aria-invalid', 'false');

        if (!passwordPattern.test(newPasswordInput.value)) {
            newPasswordHelper.textContent = 'Solo se permite números.';
            newPasswordInput.setAttribute('aria-invalid', 'true');
            event.preventDefault();
        } else if ((newPasswordInput.value).length < 4) {
            const length = newPasswordInput.value.length;
            if (length > 0) {
                newPasswordHelper.textContent = `Cuentas con ${length} dígitos de 4.`;
            } else {
                newPasswordHelper.textContent = '';
            }
            newPasswordInput.setAttribute('aria-invalid', 'true');
            event.preventDefault();
        } else {
            newPasswordHelper.textContent = 'Todo correcto.';
            newPasswordInput.setAttribute('aria-invalid', 'false');
            event.preventDefault();
        }

        inputs_validos();
    });

    confirmPasswordInput.addEventListener('input', function() {
        confirmPasswordHelper.textContent = '';
        confirmPasswordInput.setAttribute('aria-invalid', 'false');

        if (!passwordPattern.test(confirmPasswordInput.value)) {
            confirmPasswordHelper.textContent = 'Solo se permite números.';
            confirmPasswordInput.setAttribute('aria-invalid', 'true');
            event.preventDefault();
        } else if ((confirmPasswordInput.value).length < 4) {
            const length = confirmPasswordInput.value.length;
            if (length > 0) {
                confirmPasswordHelper.textContent = `Cuentas con ${length} dígitos de 4.`;
            } else {
                confirmPasswordHelper.textContent = '';
            }
            confirmPasswordInput.setAttribute('aria-invalid', 'true');
            event.preventDefault();
        } else {
            confirmPasswordHelper.textContent = 'Todo correcto.';
            confirmPasswordInput.setAttribute('aria-invalid', 'false');
            event.preventDefault();
        }

        inputs_validos();
    });
</script>