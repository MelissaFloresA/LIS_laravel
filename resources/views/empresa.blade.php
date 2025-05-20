@include('partials.headers')

<body>
    @include('partials.navbar')

    <div class="container pt-4">
        <div class="row align-items-center">
            <div class="col-6">
                <h1>Empresas</h1>
            </div>
            <div class="col-6">
                <a href="{{ route('empresas.create') }}" class="btn btn-primary float-end">Crear Empresa</a>
            </div>
        </div>

        @if ($empresas->isEmpty())
            <div class="alert alert-info my-4">
                No hay empresas registradas.
            </div>
        @else
            <div class="table-responsive text-white my-4">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Representante</th>
                            <th>Teléfono</th>
                            <th>Correo Electrónico</th>
                            <th>Rubro</th>
                            <th>Comisión</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($empresas as $empresa)
                            <tr class="align-middle">
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
                                    <a href="{{ route('empresas.update', $empresa->ID_Empresa) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('empresa.cupones.index', $empresa->ID_Empresa) }}"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-ticket"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="confirmarEliminacion('{{ $empresa->ID_Empresa }}')">
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
        function confirmarEliminacion(id) {

            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/empresas/eliminar/${id}`;

            // Mostrar modal (usando Bootstrap 5)
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
        }
    </script>

    @include('partials.toast')
</body>

</html>
