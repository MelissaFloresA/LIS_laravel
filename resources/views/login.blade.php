@include('partials.headers')

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Inicio de sesión</h2>
            <p class="text-muted">Accede a tu cuenta</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('auth.login') }}">
            @csrf

            <div class="mb-3">
                <label for="Correo" class="form-label">Correo Electrónico</label>
                <input type="Correo" name="Correo" id="Correo" class="form-control" value="{{ old('Correo') }}"
                    required autofocus>
                @error('Correo')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="Contrasena" class="form-label">Contraseña</label>
                <input type="password" name="Contrasena" id="Contrasena" class="form-control" required>
                @error('Contrasena')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end align-items-center mb-3">
                <a href="{{ route('auth.reset-password') }}" class="text-decoration-none small">¿Olvidaste tu
                    contraseña?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>
    </div>
</body>

</html>
