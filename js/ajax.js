const formulario = document.querySelectorAll('.formulario-login');

function enviar_formulario(e) {
    e.preventDefault();

    let data = new FormData(this);
    let method = this.getAttribute('method');
    let action = this.getAttribute('action');
    let headers = new Headers();

    console.log('Enviando el formulario a:', action);
    console.log('Método:', method);

    let config =
    {
        method : method,
        headers : headers,
        mode : 'cors',
        cache : 'no-cache',
        body : data
    }

    fetch(action, config)
    .then(response => response.text())
    .then(response =>
        {
            let container = document.querySelector('form-rest');
            container.innerHTML = response;
        }
    ).catch(error => console.error('Error en la petición:', error));
}

formulario.forEach(formularios => {
    formularios.addEventListener('submit', enviar_formulario);
});