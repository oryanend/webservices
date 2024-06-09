function checkInputs() {
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const button = document.getElementById('submitButton');

    if (email.length > 0 && senha.length > 0) {
        button.disabled = false;
    } else {
        button.disabled = true;
    }
}

document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('email').addEventListener('input', checkInputs);
    document.getElementById('senha').addEventListener('input', checkInputs);
    checkInputs();  // Initial check in case inputs are pre-filled
});

function checkInputSignUp() {
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const nome = document.getElementById('nome').value;
    const button = document.getElementById('submitButtonSignUp');

    if (email.length > 0 && senha.length > 0 && nome.length > 0) {
        button.disabled = false;
    } else {
        button.disabled = true;
    }
}

document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('email').addEventListener('input', checkInputSignUp);
    document.getElementById('senha').addEventListener('input', checkInputSignUp);
    document.getElementById('nome').addEventListener('input', checkInputSignUp);
    checkInputSignUp();  // Initial check in case inputs are pre-filled
});
