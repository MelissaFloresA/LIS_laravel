@include('partials.headers')

<body>
    @include('partials.navbar')

    <div class="container pt-4">
        <div class="row">
            <div class="col-12">
                <h1>{{ isset($rubro) ? 'Editar' : 'Crear' }} Rubro</h1>
            </div>
        </div>

        <div class="card my-4">
            <div class="card-body">
                <form id="rubroForm" method="POST" action="{{ isset($rubro) ? route('rubros.update', $rubro->ID_Rubro) : route('rubros.create') }}">
                    @csrf
                    @if (isset($rubro))
                        @method('PUT')
                    @endif

                    <div class="form-group mb-3">
                        <label for="Nombre" class="form-label">Nombre del Rubro *</label>
                        <input type="text" class="form-control" id="Nombre" name="Nombre" required
                            value="{{ old('Nombre', $rubro->Nombre ?? '') }}" maxlength="100"
                            pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios">
                        @error('Nombre')
                            <div class="text-danger">
                                @if ($message == 'validation.regex')
                                    El nombre solo puede contener letras y espacios
                                @else
                                    {{ $message }}
                                @endif
                            </div>
                        @enderror
                        <div id="nombreError" class="text-danger" style="display: none;"></div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitButton">
                        {{ isset($rubro) ? 'Actualizar' : 'Agregar' }}
                    </button>
                    <a href="{{ route('rubros.index') }}" class="btn btn-secondary ms-2">
                        Cancelar
                    </a>
                </form>
            </div>
        </div>
    </div>

    @include('partials.toast')
    <script src="{{ asset('js/validaciones_rubro.js') }}"></script>
</body>

</html>
