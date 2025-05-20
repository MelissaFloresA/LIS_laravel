
// validaciones de los campos

document.addEventListener('DOMContentLoaded', function () {
    // Obtener campos
    const form = document.getElementById('empresaForm');
    const idEmpresa = document.getElementById('ID_Empresa');
    const nombre = document.getElementById('Nombre');
    const direccion = document.getElementById('Direccion');
    const nombreContacto = document.getElementById('Nombre_Contacto');
    const telefono = document.getElementById('Telefono');
    const correo = document.getElementById('Correo');
    const porcentaje = document.getElementById('Porcentaje');
    const rubro = document.getElementById('ID_Rubro');


    // Obtener contenedores de errores
    // const idError = document.getElementById('idError');
    const nombreError = document.getElementById('nombreError');
    const direccionError = document.getElementById('direccionError');
    const nombreContactoError = document.getElementById('nombreContactoError');
    const telefonoError = document.getElementById('telefonoError');
    const correoError = document.getElementById('correoError');
    const porcentajeError = document.getElementById('porcentajeError');


    // Validación de campos
    /*  idEmpresa.addEventListener('input', function () {
            const regex = /^[A-Z]{3}[0-9]{3}$/;
            if (!regex.test(idEmpresa.value)) {
                idError.style.display = 'block';
                idError.textContent = 'Formato XXX000.';
            } else {
                idError.style.display = 'none';
            }
        });
    */
    nombre.addEventListener('input', function () {
        const regex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;
        if (!regex.test(nombre.value)) {
            nombreError.style.display = 'block';
            nombreError.textContent = 'Solo letras y espacios.';
        } else {
            nombreError.style.display = 'none';
        }
    });

    telefono.addEventListener('input', function () {
        const regex = /^[0-9]{4}-[0-9]{4}$/;

        if (!regex.test(telefono.value)) {
            telefonoError.style.display = 'block';
            telefonoError.textContent = 'Formato inválido. Ejemplo: 000-0000';
        } else {
            telefonoError.style.display = 'none';
        }
    });

    correo.addEventListener('input', function () {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regex.test(correo.value)) {
            correoError.style.display = 'block';
            correoError.textContent = 'Correo no válido.';
        } else {
            correoError.style.display = 'none';
        }
    });

    porcentaje.addEventListener('input', function () {
        const value = parseFloat(porcentaje.value);
        if (isNaN(value) || value < 0 || value > 100) {
            porcentajeError.style.display = 'block';
            porcentajeError.textContent = 'Debe estar entre 0 y 100.';
        } else {
            porcentajeError.style.display = 'none';
        }
    });

    direccion.addEventListener('input', function () {
        const regex = /^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s.,#\-º°/()]+$/;

        if (!regex.test(direccion.value)) {
            direccionError.style.display = 'block';
            direccionError.textContent = 'Direccion no válido.';
        } else {
            direccionError.style.display = 'none';
        }
    });

    nombreContacto.addEventListener('input', function () {
        const regex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;
        if (!regex.test(nombreContacto.value)) {
            nombreContactoError.style.display = 'block';
            nombreContactoError.textContent = 'Nombre de contacto no válido.';
        } else {
            nombreContactoError.style.display = 'none';
        }
    });

    rubro.addEventListener('change', function () {
        const rubroError = document.getElementById('rubroError');
        if (this.value === "") {
            rubroError.style.display = 'block';
            rubroError.textContent = 'Seleccione un rubro.';
        } else {
            rubroError.style.display = 'none';
        }
    });

});


let empresaActual = null;

function editarEmpresa(id, nombre, direccion, nombreContacto, telefono, correo, rubro, porcentaje) {
    empresaActual = id;

    // Actualizar el formulario
    document.getElementById('Nombre').value = nombre;
    document.getElementById('Direccion').value = direccion;
    document.getElementById('Nombre_Contacto').value = nombreContacto;
    document.getElementById('Telefono').value = telefono;
    document.getElementById('Correo').value = correo;
    document.getElementById('ID_Rubro').value = rubro;
    document.getElementById('Porcentaje').value = porcentaje;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('empresaForm').action = `/empresa/actualizar/${id}`;

    // Cambiar el texto del botón
    document.getElementById('submitButton').textContent = 'Actualizar';
    document.getElementById('submitButton').classList.remove('btn-primary');
    document.getElementById('submitButton').classList.add('btn-warning');

    // Mostrar botón cancelar
    document.getElementById('cancelButton').style.display = 'inline-block';
}


function cancelarEdicion() {
    // Resetear el formulario
    document.getElementById('empresaForm').reset();
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('empresaForm').action = '/empresa/crear';

    // Cambiar el texto del botón
    document.getElementById('submitButton').textContent = 'Guardar';
    document.getElementById('submitButton').classList.remove('btn-warning');
    document.getElementById('submitButton').classList.add('btn-primary');

    // Ocultar botón cancelar
    document.getElementById('cancelButton').style.display = 'none';

    empresaActual = null;
}

document.getElementById('cancelButton').addEventListener('click', cancelarEdicion);