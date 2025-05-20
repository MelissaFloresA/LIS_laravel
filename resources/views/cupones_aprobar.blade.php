@include('partials.headers')

<body>
    @include('partials.navbar')

    <div class="container pt-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ $cupon->Titulo }}</h5>
                <p class="card-text">{{ $cupon->Descripcion }}</p>
                <p><strong>Precio Regular:</strong> ${{ number_format($cupon->PrecioR, 2) }}</p>
                <p><strong>Precio Oferta:</strong> ${{ number_format($cupon->PrecioO, 2) }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('cupones.aprobar', $cupon->ID_Cupon) }}" id="formEditarCupon">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="Estado_Aprobacion" class="form-label">Estado *</label>
                <select class="form-select" id="Estado_Aprobacion" name="Estado_Aprobacion" required>
                    <option value="">Seleccione un estado</option>
                    <option value="Activa"
                        {{ old('Estado_Aprobacion', $cupon->Estado_Aprobacion) == 'Activa' ? 'selected' : '' }}>
                        Aprobar</option>
                    <option value="Rechazada"
                        {{ old('Estado_Aprobacion', $cupon->Estado_Aprobacion) == 'Rechazada' ? 'selected' : '' }}>
                        Rechazar</option>
                </select>
                @if ($errors->has('Estado_Aprobacion'))
                    <div class="text-danger">{{ $errors->first('Estado_Aprobacion') }}</div>
                @endif
            </div>

            <div class="form-group mb-3" id="justificacion-group"
                style="display: {{ old('Estado_Aprobacion') == 'Rechazado' ? 'block' : 'none' }};">
                <label for="Justificacion" class="form-label">Justificación *</label>
                <textarea class="form-control" id="Justificacion" name="Justificacion" rows="3">{{ old('Justificacion', $cupon->Justificacion) }}</textarea>
                @if ($errors->has('Justificacion'))
                    <div class="text-danger">{{ $errors->first('Justificacion') }}</div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ route('empresa.cupones.index', $cupon->ID_Empresa) }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    @include('partials.toast')

    <script>
        document.getElementById('Estado_Aprobacion').addEventListener('change', function() {
            const justificacionGroup = document.getElementById('justificacion-group');
            justificacionGroup.style.display = this.value === 'Rechazada' ? 'block' : 'none';
            document.getElementById('Justificacion').required = this.value === 'Rechazada';
        });
    </script>
</body>

</html>
