@include('partials.navbar')

<!-- Toast de Mensajes Simplificado -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="statusToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<div class="container">
    <h1>{{ $titulo }}</h1>

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

        <form method="POST" action="{{ route('cupones.actualizarestado', $cupon->ID_Cupon) }}" id="formEditarCupon">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="Estado_Aprobacion" class="form-label">Estado *</label>
                <select class="form-select" id="Estado_Aprobacion" name="Estado_Aprobacion" required>
                    <option value="">Seleccione un estado</option>
                    <option value="Activa" {{ old('Estado_Aprobacion', $cupon->Estado_Aprobacion) == 'Activa' ? 'selected' : '' }}>Aprobar</option>
                    <option value="Rechazado" {{ old('Estado_Aprobacion', $cupon->Estado_Aprobacion) == 'Rechazado' ? 'selected' : '' }}>Rechazar</option>
                </select>
                @if($errors->has('Estado_Aprobacion'))
                    <div class="text-danger">{{ $errors->first('Estado_Aprobacion') }}</div>
                @endif
            </div>

            <div class="form-group mb-3" id="justificacion-group" style="display: {{ old('Estado_Aprobacion') == 'Rechazado' ? 'block' : 'none' }};">
                <label for="Justificacion" class="form-label">Justificación *</label>
                <textarea class="form-control" id="Justificacion" name="Justificacion" rows="3">{{ old('Justificacion', $cupon->Justificacion) }}</textarea>
                @if($errors->has('Justificacion'))
                    <div class="text-danger">{{ $errors->first('Justificacion') }}</div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ route('cupones.pendientes') }}" class="btn btn-secondary">Cancelar</a>
        </form>

        <script>
            document.getElementById('Estado_Aprobacion').addEventListener('change', function() {
                const justificacionGroup = document.getElementById('justificacion-group');
                justificacionGroup.style.display = this.value === 'Rechazado' ? 'block' : 'none';
                document.getElementById('Justificacion').required = this.value === 'Rechazado';
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toastEl = document.getElementById('statusToast');
        const toastMessage = document.getElementById('toastMessage');
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 5000
        });
        
        @if(session('success'))
            toastMessage.textContent = "Estado actualizado correctamente";
            toast.show();
        @endif
    });
</script>