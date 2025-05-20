@include('partials.headers')

<body>
    @include('partials.navbar')

    <div class="container pt-4">
        <div class="row">
            <div class="col-12">
                <h1>{{ isset($empleado) ? 'Editar' : 'Crear' }} Empleado</h1>
            </div>
        </div>

        <div class="card my-4">
            <div class="card-body">
                <form id="empleadoForm" method="POST"
                    action="{{ isset($empleado) ? route('empleados.update', $empleado->ID_Empleado) : route('empleados.create') }}">
                    @csrf
                    @if (isset($empleado))
                        @method('PUT')
                    @endif
                    <div class="mb-3">
                        {{-- Nombre --}}
                        <label for="Nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="Nombre_Contacto" name="Nombre" required
                            value="{{ old('Nombre', $empleado->Nombre ?? '') }}" maxlength="100">
                        @error('Nombre')
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
                        {{-- Correo Electrónico --}}
                        <label for="Correo" class="form-label">Correo Electrónico *</label>
                        <input type="email" class="form-control" id="Correo" name="Correo" required
                            value="{{ old('Correo', $empleado->Correo ?? '') }}" maxlength="100">
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
                        {{-- Rol --}}
                        <label for="ID_Rol" class="form-label">Rol *</label>
                        <select name="ID_Rol" id="ID_Rol" class="form-select" required>
                            <option value="">Seleccione un rol</option>
                            <option value="1" {{ old('ID_Rol', $empleado->ID_Rol ?? '') == 1 ? 'selected' : '' }}>Administrador</option>
                            <option value="2" {{ old('ID_Rol', $empleado->ID_Rol ?? '') == 2 ? 'selected' : '' }}>Empleado</option>
                        </select>
                        @error('ID_Rol')
                            <div class="text-danger">
                                @if ($message == 'validation.regex')
                                    Seleccione un rol válido
                                @else
                                    {{ $message }}
                                @endif
                            </div>
                        @enderror
                        <div id="rolError" class="text-danger" style="display: none;"></div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitButton">{{ isset($empleado) ? 'Actualizar' : 'Agregar' }}</button>
                    <button type="button" class="btn btn-secondary ms-2" id="cancelButton"
                        style="display: none;">Cancelar</button>
                </form>
            </div>
        </div>
    </div>

    @include('partials.toast')
</body>

</html>
