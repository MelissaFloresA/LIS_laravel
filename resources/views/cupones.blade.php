@include('partials.navbar')

<div class="container">

    <h1>{{ $titulo }}</h1><!-- Cupones pendientes o edición -->

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(isset($mostrarFormulario) && $mostrarFormulario)
        <!-- Formulario de edición -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ $cupon->Titulo }}</h5>
                <p class="card-text">{{ $cupon->Descripcion }}</p>
                <p><strong>Precio Regular:</strong> ${{ number_format($cupon->PrecioR, 2) }}</p>
                <p><strong>Precio Oferta:</strong> ${{ number_format($cupon->PrecioO, 2) }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('cupones.actualizarestado', $cupon->ID_Cupon) }}">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="Estado_Aprobacion" class="form-label">Estado:</label>
                <select class="form-select" id="Estado_Aprobacion" name="Estado_Aprobacion" required>
                    <option value="Activa">Aprobar</option>
                    <option value="Rechazado">Rechazar</option>
                </select>
            </div>

            <div class="form-group mb-3" id="justificacion-group" style="display: none;">
                <label for="Justificacion" class="form-label">Justificación:</label>
                <textarea class="form-control" id="Justificacion" name="Justificacion" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('cupones.pendientes') }}" class="btn btn-secondary">Cancelar</a>
        </form>

        <script>
            document.getElementById('Estado_Aprobacion').addEventListener('change', function () {
                document.getElementById('justificacion-group').style.display =
                    this.value === 'Rechazado' ? 'block' : 'none';
            });
        </script>
    @else
        <!-- Lista de cupones pendientes -->
        @if($cupones->isEmpty())
            <div class="alert alert-info">
                No hay cupones pendientes de aprobación.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Empresa</th>
                            <th>Título</th>
                            <th>Precio Oferta</th>
                            <th>Fecha Inicial</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cupones as $cupon)
                            <tr>
                                <td>{{ $cupon->nombre_empresa }}</td>
                                <td>{{ $cupon->Titulo }}</td>
                                <td>${{ number_format($cupon->PrecioO, 2) }}</td>
                                <td>{{ date('d/m/Y', strtotime($cupon->Fecha_Inicial)) }}</td>
                                <td>
                                    <a href="{{ route('cupones.editarestado', $cupon->ID_Cupon) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Revisar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endif

</div> <!-- fin container -->