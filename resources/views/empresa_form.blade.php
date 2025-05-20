@include('partials.headers')

<body>
    @include('partials.navbar')

    <div class="container pt-4">
        <div class="row">
            <div class="col-12">
                <h1>{{ isset($empresa) ? 'Editar' : 'Crear' }} Empresa</h1>
            </div>
        </div>

        <div class="card my-4">
            <div class="card-body">
                <form id="empresaForm" method="POST"
                    action="{{ isset($empresa) ? route('empresas.update', $empresa->ID_Empresa) : route('empresas.create') }}">
                    @csrf
                    @if (isset($empresa))
                        @method('PUT')
                    @endif

                    <div class="mb-3">
                        {{-- Nombre --}}
                        <label for="Nombre" class="form-label">Nombre de la empresa *</label>
                        <input type="text" class="form-control" id="Nombre" name="Nombre" required
                            value="{{ old('Nombre', $empresa->Nombre ?? '') }}" maxlength="100"
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

                    <div class="mb-3">
                        {{-- Dirección --}}
                        <label for="Direccion" class="form-label">Dirección *</label>
                        <input type="text" class="form-control" id="Direccion" name="Direccion" required
                            value="{{ old('Direccion', $empresa->Direccion ?? '') }}" maxlength="100">
                        @error('Direccion')
                            <div class="text-danger">
                                @if ($message == 'validation.regex')
                                    La dirección solo puede contener letras y espacios
                                @else
                                    {{ $message }}
                                @endif
                            </div>
                        @enderror
                        <div id="direccionError" class="text-danger" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        {{-- Nombre del contacto --}}
                        <label for="Nombre_Contacto" class="form-label">Nombre del contacto *</label>
                        <input type="text" class="form-control" id="Nombre_Contacto" name="Nombre_Contacto" required
                            value="{{ old('Nombre_Contacto', $empresa->Nombre_Contacto ?? '') }}" maxlength="100">
                        @error('Nombre_Contacto')
                            <div class="text-danger">
                                @if ($message == 'validation.regex')
                                    El nombre solo puede contener letras y espacios
                                @else
                                    {{ $message }}
                                @endif
                            </div>
                        @enderror
                        <div id="nombreContactoError" class="text-danger" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        {{-- Teléfono --}}
                        <label for="Telefono" class="form-label">Teléfono *</label>
                        <input type="text" class="form-control" id="Telefono" name="Telefono" required
                            value="{{ old('Telefono', $empresa->Telefono ?? '') }}" maxlength="100">
                        @error('Telefono')
                            <div class="text-danger">
                                @if ($message == 'validation.regex')
                                    El teléfono solo puede contener números
                                @else
                                    {{ $message }}
                                @endif
                            </div>
                        @enderror
                        <div id="telefonoError" class="text-danger" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        {{-- Correo Electrónico --}}
                        <label for="Correo" class="form-label">Correo Electrónico *</label>
                        <input type="email" class="form-control" id="Correo" name="Correo" required
                            value="{{ old('Correo', $empresa->Correo ?? '') }}" maxlength="100">
                        @error('Correo')
                            <div class="text-danger">
                                @if ($message == 'validation.regex')
                                    El correo solo puede contener letras y espacios
                                @else
                                    {{ $message }}
                                @endif
                            </div>
                        @enderror
                        <div id="correoError" class="text-danger" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        {{-- Rubro --}}
                        <label for="ID_Rubro" class="form-label">Rubro *</label>
                        <select name="ID_Rubro" id="ID_Rubro" class="form-select" required>
                            <option value="">Seleccione un rubro</option>
                            @foreach ($rubros as $rubro)
                                <option value="{{ $rubro->ID_Rubro }}"
                                    {{ old('ID_Rubro', $empresa->ID_Rubro ?? '') == $rubro->ID_Rubro ? 'selected' : '' }}>
                                    {{ $rubro->Nombre }}</option>
                            @endforeach

                        </select>
                        @error('ID_Rubro')
                            <div class="text-danger">
                                @if ($message == 'validation.regex')
                                    Seleccione un rubro válido
                                @else
                                    {{ $message }}
                                @endif
                            </div>
                        @enderror
                        <div id="rubroError" class="text-danger" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        {{-- Porcentaje --}}
                        <label for="Porcentaje" class="form-label">Porcentaje de comisión *</label>
                        <input type="number" class="form-control" id="Porcentaje" name="Porcentaje" required
                            value="{{ old('Porcentaje', $empresa->Porcentaje_Comision ?? '') }}" min="0"
                            max="100" step="0.01">
                        @error('Porcentaje')
                            <div class="text-danger">
                                @if ($message == 'validation.regex')
                                    El porcentaje solo puede contener números
                                @else
                                    {{ $message }}
                                @endif
                            </div>
                        @enderror
                        <div id="porcentajeError" class="text-danger" style="display: none;"></div>
                    </div>

                    <button type="submit" class="btn btn-primary"
                        id="submitButton">{{ isset($empresa) ? 'Actualizar' : 'Agregar' }}</button>
                    <a href="{{ route('empresas.index') }}" class="btn btn-secondary ms-2"
                        id="cancelButton">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    @include('partials.toast')
    <script src="{{ asset('js/validaciones_empresa.js') }}"></script>
</body>

</html>
