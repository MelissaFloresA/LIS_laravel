document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('cuponForm');

    // Campos requeridos
    const camposRequeridos = [
        'ID_Empresa', 'Titulo', 'PrecioR', 'PrecioO',
        'Fecha_Inicial', 'Fecha_Final', 'Fecha_Limite',
        'Descripcion', 'Stock'
    ];

    // Función para validar números decimales
    function esDecimalValido(valor) {
        const regex = /^\d+(\.\d{1,2})?$/;
        return regex.test(valor);
    }

    // Función para mostrar mensaje de error creacion de div
    function mostrarError(input, mensaje) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-white mt-1';
        errorDiv.textContent = mensaje;

        const errorAnterior = input.nextElementSibling;
        if (errorAnterior && errorAnterior.className === 'text-white mt-1') {
            errorAnterior.remove();
        }

        input.insertAdjacentElement('afterend', errorDiv);
        input.classList.add('is-invalid');
    }

    // Función para limpiar errores
    function limpiarError(input) {
        input.classList.remove('is-invalid');
        const errorDiv = input.nextElementSibling;
        if (errorDiv && errorDiv.className === 'text-white mt-1') {
            errorDiv.remove();
        }
    }

    // Validación campo por campo al perder foco (blur)
    camposRequeridos.forEach(id => {
        const input = document.getElementById(id);
        input.addEventListener('blur', function () {
            if (!this.value.trim()) {
                mostrarError(this, 'Este campo es obligatorio.');
            } else {
                limpiarError(this);
            }

            // Validación adicional para precios
            if ((id === 'PrecioR' || id === 'PrecioO') && !esDecimalValido(this.value)) {
                mostrarError(this, 'Introduce un valor válido con hasta 2 decimales.');
            }

            // Validación entre precios (Oferta <= Regular)
            const precioR = parseFloat(document.getElementById('PrecioR').value);
            const precioO = parseFloat(document.getElementById('PrecioO').value);
            if (!isNaN(precioR) && !isNaN(precioO) && precioO > precioR) {
                mostrarError(document.getElementById('PrecioO'), 'El precio de oferta no puede ser mayor que el precio regular.');
            }

            // Validación entre fechas
            const fechaInicial = new Date(document.getElementById('Fecha_Inicial').value);
            const fechaFinal = new Date(document.getElementById('Fecha_Final').value);
            const fechaLimite = new Date(document.getElementById('Fecha_Limite').value);

            if (fechaFinal < fechaInicial) {
                mostrarError(document.getElementById('Fecha_Final'), 'La fecha final debe ser igual o posterior a la fecha inicial.');
            }

            if (fechaLimite < fechaFinal) {
                mostrarError(document.getElementById('Fecha_Limite'), 'La fecha límite debe ser igual o posterior a la fecha final.');
            }
        });
    });

    // Validación final al enviar
    form.addEventListener('submit', function (e) {
        let valido = true;

        camposRequeridos.forEach(id => {
            const input = document.getElementById(id);
            if (!input.value.trim()) {
                mostrarError(input, 'Este campo es obligatorio.');
                valido = false;
            }

            if ((id === 'PrecioR' || id === 'PrecioO') && !esDecimalValido(input.value)) {
                mostrarError(input, 'Introduce un valor válido con hasta 2 decimales.');
                valido = false;
            }
        });

        const precioR = parseFloat(document.getElementById('PrecioR').value);
        const precioO = parseFloat(document.getElementById('PrecioO').value);
        if (!isNaN(precioR) && !isNaN(precioO) && precioO > precioR) {
            mostrarError(document.getElementById('PrecioO'), 'El precio de oferta no puede ser mayor que el precio regular.');
            valido = false;
        }

        const fechaInicial = new Date(document.getElementById('Fecha_Inicial').value);
        const fechaFinal = new Date(document.getElementById('Fecha_Final').value);
        const fechaLimite = new Date(document.getElementById('Fecha_Limite').value);

        if (fechaFinal < fechaInicial) {
            mostrarError(document.getElementById('Fecha_Final'), 'La fecha final debe ser igual o posterior a la fecha inicial.');
            valido = false;
        }

        if (fechaLimite < fechaFinal) {
            mostrarError(document.getElementById('Fecha_Limite'), 'La fecha límite debe ser igual o posterior a la fecha final.');
            valido = false;
        }

        if (!valido) e.preventDefault();
    });
});
