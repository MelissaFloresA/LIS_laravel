@include('partials.headers')

<body>
    @include('partials.navbar')

    <div class="container pt-4">
        <div class="row align-items-center">
            <div class="col-8">
                <h1>Cupones de {{ $nombre_empresa . ' - ' . $empresa }}</h1>
            </div>
            <div class="col-4 d-flex gap-2">
                <select class="form-select" name="estado" id="estado" onchange="actualizarEstado(this.value)">
                    <option value="pendiente" {{ $estado == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                    <option value="futura" {{ $estado == 'futura' ? 'selected' : '' }}>Futuras</option>
                    <option value="activa" {{ $estado == 'activa' ? 'selected' : '' }}>Activas</option>
                    <option value="pasada" {{ $estado == 'pasada' ? 'selected' : '' }}>Pasadas</option>
                    <option value="rechazada" {{ $estado == 'rechazada' ? 'selected' : '' }}>Rechazadas</option>
                    <option value="descartada" {{ $estado == 'descartada' ? 'selected' : '' }}>Descartadas</option>
                </select>

                <script>
                    function actualizarEstado(estado) {
                        const url = new URL(window.location);
                        url.searchParams.set('estado', estado);
                        window.location.href = url;
                    }
                </script>

                @if (session('ID_Empresa') != 'CUPON')
                    <a href="{{ route('cupones.create', session('ID_Empresa')) }}"
                        class="btn btn-primary text-nowrap">Agregar Cupon</a>
                @endif
            </div>
        </div>
        <!-- Lista de cupones pendientes -->
        @if ($cupones->isEmpty())
            <div class="alert alert-info my-4">
                No hay ofertas {{ $estado }}s.
            </div>
        @else
            <div class="table-responsive text-white my-4">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Título</th>
                            <th>Vendidos</th>
                            <th>Disponibles</th>
                            <th>Ingresos</th>
                            <th>Comisión</th>

                            @if ($estado == 'rechazada')
                                <th>Justificacion</th>
                            @endif
                            @if (
                                ($estado == 'pendiente' && session('ID_Empresa') == 'CUPON') ||
                                    ($estado == 'rechazada' && session('ID_Empresa') != 'CUPON'))
                                <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cupones as $cupon)
                            <tr>
                                <td>{{ $cupon->Titulo }}</td>
                                <td>{{ $cupon->Cantidad_Vendidos }}</td>
                                <td>{{ $cupon->Stock }}</td>
                                <td>${{ number_format($cupon->Cantidad_Vendidos * $cupon->PrecioO, 2) }}</td>
                                <td>${{ number_format($cupon->Cantidad_Vendidos * $cupon->PrecioO * $cupon->Porcentaje_Comision / 100, 2) }}</td>
                                
                                @if ($estado == 'rechazada')
                                    <td>{{ $cupon->Justificacion }}</td>
                                @endif

                                @if (session('ID_Empresa') == 'CUPON' && $estado == 'pendiente')
                                    <td>
                                        <a href="{{ route('cupones.aprobar', $cupon->ID_Cupon) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Revisar
                                        </a>
                                    </td>
                                @endif

                                @if ($estado == 'rechazada' && session('ID_Empresa') == $cupon->ID_Empresa)
                                    <td>
                                        <a href="{{ route('cupones.update', [$cupon->ID_Empresa, $cupon->ID_Cupon]) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>

                                        <button class="btn btn-sm btn-danger"
                                            onclick="confirmarEliminacion('{{ $cupon->ID_Cupon }}')">
                                            <i class="fas fa-trash"></i> Descartar
                                        </button>
                                    </td>
                                @endif
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
                    ¿Estás seguro de que deseas descartar esta oferta? Esta acción no se puede deshacer.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Descartar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmarEliminacion(id) {

            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/cupones/descartar/${id}`;

            // Mostrar modal (usando Bootstrap 5)
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
        }
    </script>

    @include('partials.toast')
</body>

</html>
