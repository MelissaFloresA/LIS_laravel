@include('partials.headers')

<body>
    @include('partials.navbar')

    <div class="container pt-4">
        <div class="row align-items-center">
            <div class="col-6">
                <h1>Empleados</h1>
            </div>
            <div class="col-6">
                <a href="{{ route('empleados.create') }}" class="btn btn-primary float-end">Crear Empleado</a>
            </div>
        </div>

        @if ($empleados->isEmpty())
            <div class="alert alert-info my-4">
                No hay empresas registradas.
            </div>
        @else
            <div class="table-responsive text-white my-4">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Empresa</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($empleados as $empleado)
                            <tr class="align-middle">
                                <td>{{ $empleado->ID_Empleado }}</td>
                                <td>{{ $empleado->ID_Empresa }}</td>
                                <td>{{ $empleado->Nombre }}</td>
                                <td>{{ $empleado->Correo }}</td>
                                <td>{{ $empleado->Nombre_Rol }}</td>
                                <td>{{ $empleado->Estado == 1 ? 'Activo' : 'Inactivo' }}</td>
                                <td>
                                    <!-- Botones de acción -->
                                    <a href="{{ route('empleados.update', $empleado->ID_Empleado) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="confirmarEliminacion('{{ $empleado->ID_Empleado }}')">
                                        <i class="fas fa-trash"></i>
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
                    ¿Estás seguro de que deseas eliminar este empleado? Esta acción no se puede deshacer.
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
        function confirmarEliminacion(id) {

            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/empleados/eliminar/${id}`;

            // Mostrar modal (usando Bootstrap 5)
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
        }
    </script>

    @include('partials.toast')
</body>

</html>
