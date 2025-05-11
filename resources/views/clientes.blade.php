@include('partials.navbar')

<div class="container py-4">
    <h1 class="mb-4">Clientes</h1>
    
    <div class="row">
        @foreach($clientes as $cliente)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $cliente->NombreCompleto }}</h5>
                    <p class="card-text mb-1">
                        <i class="fas fa-phone me-2"></i> {{ $cliente->Telefono }}
                    </p>
                    <p class="card-text mb-1">
                        <i class="fas fa-envelope me-2"></i> {{ $cliente->Correo }}
                    </p>
                    <p class="card-text">
                        <i class="fas fa-map-marker-alt me-2"></i> {{ $cliente->Direccion }}
                    </p>
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('clientes_cupones', $cliente->ID_Cliente) }}" 
                       class="btn btn-primary w-100">
                        <i class="fas fa-ticket-alt me-2"></i> Ver Cupones
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
