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
                    <p><strong>Estado Aprobación:</strong> {{ $cupon->Estado_Aprobacion }}</p>
                    <p><strong>Estado Cupón:</strong> {{ $cupon->Estado_Cupon }}</p>
                    <p><strong>Fecha de Vencimiento:</strong> {{ $cupon->Fecha_Limite }}</p>
                    <p><strong>DUI:</strong> {{ $cupon->Dui }}</p>
                    <p><strong>Cantidad:</strong> {{ $cupon->Cantidad }}</p>
                    <p><strong>Veces Canjeadas:</strong> {{ $cupon->Veces_Canje }}</p>

                    {{-- Botones --}}

                    <form method="POST" action="{{ route('cupones.canjear', $cupon->Codigo_Cupon) }}">
                        @csrf
                        
                        @if ($cupon->Estado_Cupon == 'Disponible' && $cupon->Veces_Canje < $cupon->Cantidad)
                            <input type="hidden" name="Codigo_Cupon" value="{{ $cupon->Codigo_Cupon }}">
                            <button type="submit" class="btn btn-success">Canjear</button>
                        @endif

                        <a href="{{ route('cupones.canjear') }}" class="btn btn-secondary">Buscar otro</a>
                    </form>
                </div>
            </div>
        @endisset

        @include('partials.toast')
    </div>
</body>

</html>
