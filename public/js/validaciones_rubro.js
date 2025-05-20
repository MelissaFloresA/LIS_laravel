const btn = document.getElementById('submitButton');

// Configurar validación en tiempo real
document.getElementById('Nombre').addEventListener('input', function () {
    const nombreError = document.getElementById('nombreError');
    const regex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]*$/;

    if (!regex.test(this.value)) {
        nombreError.textContent = 'Solo se permiten letras y espacios';
        nombreError.style.display = 'block';
        btn.disabled = true;
    } else {
        nombreError.style.display = 'none';
        btn.disabled = false;
    }
});
