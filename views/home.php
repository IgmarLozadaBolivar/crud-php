<?php 

    if (isset($_POST['accion']) && $_POST['accion'] == 'salir')
    {

        echo '<script>
                Swal.fire({
                    title: "Sesión cerrada",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = "index.php?view=login";
                });
            </script>';
    }

?>

<nav>
    <ul>
        <li>
            <strong>
                CRUD • PHP
            </strong>
        </li>
    </ul>
    <ul>
        <li>
            <a href="#">
                Test 1
            </a>
        </li>
        <li>
            <a href="#">
                Test 2
            </a>
        </li>
        <li>
            <a href="<?php echo 'index.php?view=account'; ?>" type="button">
                Mi Cuenta
            </a>
        </li>
        <li>
            <a href="javascript:void(0);" onclick="salir()" type="button">
                Cerrar sesión
            </a>
        </li>
    </ul>
</nav>

<main>
    <div class="temporizador">
        <div class="temporizador-ul">
            <p class="hora-actual">
                <span style="display:flex; text-align: center;" id="date">
                    <!-- <?php 

                        date_default_timezone_set('America/Bogota');
                    
                        $fecha_hora_actual = date("d-m-Y H:i:s");

                        echo $fecha_hora_actual;

                    ?> -->
                </span>
                <div class="grid">
                    <input id="dateInput" style="display: flex; justify-content: center; text-align: center;" type="text" value="" readonly>
                </div>
            </p>
        </div>
    </div>
</main>

<script>
    const dateSpan = document.getElementById('date');

    function obtenerDateNow()
    {
        let date = new Date();
        let now = date.toLocaleString();
        let newDateOptions = {
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            hour12: true
        };
        let nowInput = date.toLocaleString('default', newDateOptions);

        dateSpan.textContent = now
        document.getElementById('dateInput').value = nowInput;
        return dateSpan;
    };

    setInterval(obtenerDateNow, 1000);

    function eliminarCookie(nombre) {
        document.cookie = nombre + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/";
    }

    function salir()
    {
        if(confirm("¿Estás seguro de cerrar sesión en tu cuenta?")) 
        {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'accion=salir'
            }).then(response => response.text())
            .then(data => {
                eliminarCookie('CRUD-PHP');
                Swal.fire({
                    title: "Sesión cerrada",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = "index.php?view=login";
                });
            });
        }
    }
</script>