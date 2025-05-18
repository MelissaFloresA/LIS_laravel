@include('partials.navbar')

<link href="{{ asset('css/cupones_pendientes.css') }}" rel="stylesheet">

<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="statusToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>

<div class="container">
    <br>
    <h1>{{ $titulo }}</h1>
    <br>

    <div class="card mb-4">
        <div class="card-body">
            <form id="empresaForm" method="POST" action="{{ route('empresa.store') }}" >
                @csrf
<input type="hidden" id="formMethod" name="_method" value="POST">
                <div class="form-group mb-3">
                    <h2>Crear Empresa</h2>
                    

                    {{-- Nombre --}}
                    <label for="Nombre" class="form-label">Nombre de la empresa *</label>
                    <input type="text" class="form-control" id="Nombre" name="Nombre" required
                        value="{{ old('Nombre') }}" maxlength="100" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+"
                        title="Solo se permiten letras y espacios">
                    @error('Nombre')
                        <div class="text-danger">{{ $message }}
                            @if($message == 'validation.regex')
                                El nombre solo puede contener letras y espacios
                            @else
                                {{ $message }}
                            @endif
                        </div>
                    @enderror
                    <div id="nombreError" class="text-white" style="display: none;"></div>

                    {{-- Dirección --}}
                    <label for="Direccion" class="form-label">Dirección *</label>
                    <input type="text" class="form-control" id="Direccion" name="Direccion" required
                        value="{{ old('Direccion') }}" maxlength="100">
                    @error('Direccion')
                        <div class="text-danger">{{ $message }}
                            @if($message == 'validation.regex')
                                La dirección solo puede contener letras y espacios
                            @else
                                {{ $message }}
                            @endif
                        </div>
                    @enderror
                    <div id="direccionError" class="text-white" style="display: none;"></div>
                     {{-- Nombre del contacto --}}
                    <label for="Nombre_Contacto" class="form-label">Nombre del contacto *</label>
                    <input type="text" class="form-control" id="Nombre_Contacto" name="Nombre_Contacto" required
                        value="{{ old('Nombre_Contacto') }}" maxlength="100">
                    @error('Nombre_Contacto')
                        <div class="text-danger">{{ $message }}
                            @if($message == 'validation.regex')
                                El nombre solo puede contener letras y espacios
                            @else
                                {{ $message }}
                            @endif
                        </div>
                    @enderror
                    <div id="nombreContactoError" class="text-white" style="display: none;"></div>

                    {{-- Teléfono --}}
                    <label for="Telefono" class="form-label">Teléfono *</label>
                    <input type="text" class="form-control" id="Telefono" name="Telefono" required
                        value="{{ old('Telefono') }}" maxlength="100">
                    @error('Telefono')
                        <div class="text-danger">{{ $message }}
                            @if($message == 'validation.regex')
                                El teléfono solo puede contener números
                            @else
                                {{ $message }}
                            @endif
                        </div>
                    @enderror
                    <div id="telefonoError" class="text-white" style="display: none;"></div>

                    {{-- Correo Electrónico --}}
                    <label for="Correo" class="form-label">Correo Electrónico *</label>
                    <input type="email" class="form-control" id="Correo" name="Correo" required
                        value="{{ old('Correo') }}" maxlength="100">
                    @error('Correo')
                        <div class="text-danger">{{ $message }}
                            @if($message == 'validation.regex')
                                El correo solo puede contener letras y espacios
                            @else
                                {{ $message }}
                            @endif
                        </div>
                    @enderror
                    <div id="correoError" class="text-white" style="display: none;"></div>

                    {{-- Rubro --}}
                    <label for="ID_Rubro" class="form-label">Rubro *</label>
                    <select name="ID_Rubro" id="ID_Rubro" class="form-control">
    <option value="">Seleccione un rubro</option>
    @foreach ($rubros as $rubro)
        <option value="{{ $rubro->ID_Rubro }}">{{ $rubro->Nombre }}</option>
    @endforeach
    
</select>

                    @error('ID_Rubro')
                        <div class="text-danger">{{ $message }}
                            @if($message == 'validation.regex')
                                Seleccione un rubro válido
                            @else
                                {{ $message }}
                            @endif
                        </div>
                    @enderror
                    <div id="rubroError" class="text-white" style="display: none;"></div>

                    {{-- Porcentaje --}}
                    <label for="Porcentaje" class="form-label">Porcentaje de comisión *</label>
                    <input type="number" class="form-control" id="Porcentaje" name="Porcentaje" required
                        value="{{ old('Porcentaje') }}" min="0" max="100" step="0.01">
                    @error('Porcentaje')
                        <div class="text-danger">{{ $message }}
                            @if($message == 'validation.regex')
                                El porcentaje solo puede contener números
                            @else
                                {{ $message }}
                            @endif
                        </div>
                    @enderror
                    <div id="porcentajeError" class="text-white" style="display: none;"></div>

                    {{-- Botones --}}
                </div>
                 
                <button type="submit" class="btn btn-primary" id="submitButton">Agregar</button>
                <button type="button" class="btn btn-secondary ms-2" id="cancelButton" style="display: none;">Cancelar</button>
            </form>
        </div>
    </div>
</div>
 @if($empresas->isEmpty())
        <div class="alert alert-info">
            No hay empresas registradas.
        </div>
    @else
    <div class="container">
        <div class="table-responsive text-white">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Nombre del contacto</th>
                        <th>Teléfono</th>
                        <th>Correo Electrónico</th>
                        <th>Rubro</th>
                        <th>Porcentaje de comisión</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empresas as $empresa)
                        <tr>
                            <td>{{ $empresa->ID_Empresa }}</td>
                            <td>{{ $empresa->Nombre }}</td>
                            <td>{{ $empresa->Direccion }}</td>
                            <td>{{ $empresa->Nombre_Contacto }}</td>
                            <td>{{ $empresa->Telefono }}</td>
                            <td>{{ $empresa->Correo }}</td>
                            <td>{{ $empresa->rubro->Nombre }}</td>
                            <td>{{ $empresa->Porcentaje_Comision }}%</td>
                            <td>
                                <!-- Botones de acción -->
                             <button class="btn btn-sm btn-warning"
                                    onclick="editarEmpresa('{{ $empresa->ID_Empresa }}', '{{ $empresa->Nombre }}', '{{ $empresa->Direccion }}', '{{ $empresa->Nombre_Contacto }}', '{{ $empresa->Telefono }}', '{{ $empresa->Correo }}', '{{ $empresa->ID_Rubro }}', '{{ $empresa->Porcentaje_Comision }}')">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-sm btn-danger m-2 p-1" onclick="confirmarEliminacion('{{ $empresa->ID_Empresa }}')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta empresa? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
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

    // Configurar validación en tiempo real
    document.getElementById('Nombre').addEventListener('input', function () {
        const nombreError = document.getElementById('nombreError');
        const regex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]*$/;

        if (!regex.test(this.value)) {
            nombreError.textContent = 'Solo se permiten letras y espacios';
            nombreError.style.display = 'block';
        } else {
            nombreError.style.display = 'none';
        }
    });
 /*   document.getElementById('ID_Empresa').addEventListener('input', function () {
        const idError = document.getElementById('idError');
        const regex = /^[A-Z]{3}[0-9]{3}$/;

        if (!regex.test(this.value)) {
            idError.textContent = 'El ID de la empresa debe tener el formato XXX000';
            idError.style.display = 'block';
        } else {
            idError.style.display = 'none';
        }
    });*/
    document.getElementById('Direccion').addEventListener('input', function () {
        const direccionError = document.getElementById('direccionError');
        const regex = /^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s]*$/;

        if (!regex.test(this.value)) {
            direccionError.textContent = 'La dirección solo puede contener letras y números';
            direccionError.style.display = 'block';
        } else {
            direccionError.style.display = 'none';
        }
    });
    document.getElementById('Nombre_Contacto').addEventListener('input', function () {
        const nombreContactoError = document.getElementById('nombreContactoError');
        const regex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]*$/;

        if (!regex.test(this.value)) {
            nombreContactoError.textContent = 'El nombre solo puede contener letras y espacios';
            nombreContactoError.style.display = 'block';
        } else {
            nombreContactoError.style.display = 'none';
        }
    });
    document.getElementById('Telefono').addEventListener('input', function () {
        const telefonoError = document.getElementById('telefonoError');
        const regex = /^[0-9]{10}$/;

        if (!regex.test(this.value)) {
            telefonoError.textContent = 'El teléfono solo puede contener 10 números';
            telefonoError.style.display = 'block';
        } else {
            telefonoError.style.display = 'none';
        }
    });
    document.getElementById('Correo').addEventListener('input', function () {
        const correoError = document.getElementById('correoError');
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (!regex.test(this.value)) {
            correoError.textContent = 'El correo no es válido';
            correoError.style.display = 'block';
        } else {
            correoError.style.display = 'none';
        }
    });
   document.addEventListener('DOMContentLoaded', function () {
    const rubro = document.getElementById('ID_Rubro');
    if (rubro) {
        rubro.addEventListener('change', function () {
            const rubroError = document.getElementById('rubroError');
            if (this.value === "") {
                rubroError.textContent = 'Seleccione un rubro';
                rubroError.style.display = 'block';
            } else {
                rubroError.style.display = 'none';
            }
        });
    }
});

    document.getElementById('Porcentaje').addEventListener('input', function () {
        const porcentajeError = document.getElementById('porcentajeError');
        const value = parseFloat(this.value);
if (isNaN(value) || value < 0 || value > 100) {
    porcentajeError.textContent = 'El porcentaje debe estar entre 0 y 100';
    porcentajeError.style.display = 'block';
} else {
    porcentajeError.style.display = 'none';
}

    });

    document.addEventListener('DOMContentLoaded', function () {
        const toastEl = document.getElementById('statusToast');
        const toastMessage = document.getElementById('toastMessage');
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 5000 // se muestra el toast 5 segundos 5000milisegundos
        });

        @if(session('success'))
            toastEl.classList.remove('text-bg-danger');
            toastEl.classList.add('text-bg-success');
            toastMessage.textContent = "{{ session('success') }}";
            toast.show();
        @endif

        @if(session('error'))
            toastEl.classList.remove('text-bg-success');
            toastEl.classList.add('text-bg-danger');
            toastMessage.textContent = "{{ session('error') }}";
            toast.show();
        @endif
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
   function confirmarEliminacion(id) {
   
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/empresa/eliminar/${id}`; 

    // Mostrar modal (usando Bootstrap 5)
    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    modal.show();
}

 document.getElementById('cancelButton').addEventListener('click', cancelarEdicion);
</script>