@include('partials.headers')

<body>
    @include('partials.navbar')

    <div class="container pt-4">
        <div class="row align-items-center">
            <div class="col-6">
                <h1>Rubros</h1>
            </div>
            <div class="col-6">
                <a href="{{ route('rubros.create') }}" class="btn btn-primary float-end">Crear Rubro</a>
            </div>
        </div>

        @if ($rubros->isEmpty())
            <div class="alert alert-info">
                No hay rubros registrados.
            </div>
        @else
            <div class="table-responsive text-white my-4">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rubros as $rubro)
                            <tr>
                                <td>{{ $rubro->ID_Rubro }}</td>
                                <td>{{ $rubro->Nombre }}</td>
                                <td>
                                    <a href="{{ route('rubros.update', $rubro->ID_Rubro) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="confirmarEliminacion('{{ $rubro->ID_Rubro }}')">
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
        function confirmarEliminacion(id) {

            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/rubros/eliminar/${id}`;

            // Mostrar modal (usando Bootstrap 5)
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
        }
    </script>

    @include('partials.toast')
</body>

</html>
