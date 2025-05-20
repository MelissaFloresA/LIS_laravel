@include('partials.headers')

<body>
    @include('partials.navbar')

    <div class="container pt-4">
        <h4>Canjear Cupón</h4>

        @empty($cupon)
            {{-- Formulario de búsqueda --}}
            <div>
                <div class="input-group">
                    <input id="idCupon" type="text" name="id" class="form-control" placeholder="Ingrese ID del cupón"
                        required>
                    <button onclick="buscarCupon()" class="btn btn-primary">Buscar</button>
                </div>
            </div>

            <script>
                function buscarCupon() {
                    const id = document.getElementById('idCupon').value;
                    window.location.href = '/cupones/canjear/' + id;
                }
            </script>
        @endempty

        {{-- Si se encontró el cupón, mostrar los detalles --}}
        @isset($cupon)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $cupon->Titulo }}</h5>
                    <p class="card-text">{{ $cupon->Descripcion }}</p>
                    <p><strong>Precio Regular:</strong> ${{ number_format($cupon->PrecioR, 2) }}</p>
                    <p><strong>Precio Oferta:</strong> ${{ number_format($cupon->PrecioO, 2) }}</p>
                    <p><strong>Estado:</strong> {{ $cupon->Estado_Aprobacion }}</p>
                    <p><strong>Fecha de Vencimiento:</strong> {{ $cupon->Fecha_Limite }}</p>
                    <p><strong>DUI:</strong> {{ $cupon->Dui }}</p>
                    <p><strong>Cantidad:</strong> {{ $cupon->Cantidad }}</p>
                    <p><strong>Veces Canjeadas:</strong> {{ $cupon->Veces_Canje }}</p>

                    {{-- Botones --}}
                    @if ($cupon->Estado_Aprobacion == 'Activa' && $cupon->Veces_Canje < $cupon->Cantidad)
                        <form method="POST" action="{{ route('cupones.canjear', $cupon->Codigo_Cupon) }}">
                            @csrf
                            <input type="hidden" name="Codigo_Cupon" value="{{ $cupon->Codigo_Cupon }}">
                            <button type="submit" class="btn btn-success">Canjear</button>
                            <a href="{{ route('cupones.canjear') }}" class="btn btn-secondary">Buscar otro</a>
                        </form>
                    @endif
                </div>
            </div>
        @endisset

        {{-- Mostrar mensaje si no se encuentra --}}
        @if (session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        @include('partials.toast')
    </div>
</body>

</html>
