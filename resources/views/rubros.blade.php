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
            <form id="rubroForm" method="POST" action="{{ route('rubros.store') }}">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="POST">

                <div class="form-group mb-3">
                    <h2>Crear Rubro</h2>
                    <label for="Nombre" class="form-label">Nombre del Rubro *</label>
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
                </div>

                <button type="submit" class="btn btn-primary" id="submitButton">
                    Agregar
                </button>
                <button type="button" class="btn btn-secondary ms-2" id="cancelButton" style="display: none;">
                    Cancelar
                </button>
            </form>
        </div>
    </div>

    @if($rubros->isEmpty())
        <div class="alert alert-info">
            No hay rubros registrados.
        </div>
    @else
        <div class="table-responsive text-white">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th> </tr>
                </thead>
                <tbody>
                    @foreach($rubros as $rubro)
                        <tr>
                            <td>{{ $rubro->ID_Rubro }}</td>
                            <td>{{ $rubro->Nombre }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                    onclick="editarRubro({{ $rubro->ID_Rubro }}, '{{ $rubro->Nombre }}')">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmarEliminacion({{ $rubro->ID_Rubro }})">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este rubro? Esta acción no se puede deshacer.
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
    // Validación del lado del cliente
    function validarFormulario() {
        const nombreInput = document.getElementById('Nombre');
        const nombreError = document.getElementById('nombreError');
        const regex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;

        if (!regex.test(nombreInput.value)) {
            nombreError.textContent = 'Solo se permiten letras y espacios';
            nombreError.style.display = 'block';
            return false;
        }

        nombreError.style.display = 'none';
        return true;
    }
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

    let rubroActual = null;

    function editarRubro(id, nombre) {
        rubroActual = id;

        // Actualizar el formulario
        document.getElementById('Nombre').value = nombre;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('rubroForm').action = `/rubros/actualizar/${id}`;

        // Cambiar el texto del botón
        document.getElementById('submitButton').textContent = 'Actualizar';
        document.getElementById('submitButton').classList.remove('btn-primary');
        document.getElementById('submitButton').classList.add('btn-warning');

        // Mostrar botón cancelar
        document.getElementById('cancelButton').style.display = 'inline-block';
    }

    function cancelarEdicion() {
        // Resetear el formulario
        document.getElementById('rubroForm').reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('rubroForm').action = '/rubros/crear';

        // Cambiar el texto del botón
        document.getElementById('submitButton').textContent = 'Guardar';
        document.getElementById('submitButton').classList.remove('btn-warning');
        document.getElementById('submitButton').classList.add('btn-primary');

        // Ocultar botón cancelar
        document.getElementById('cancelButton').style.display = 'none';

        rubroActual = null;
    }

    function confirmarEliminacion(id) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/rubros/eliminar/${id}`;

        const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        modal.show();
    }

    // Configurar evento para el botón cancelar
    document.getElementById('cancelButton').addEventListener('click', cancelarEdicion);
</script>