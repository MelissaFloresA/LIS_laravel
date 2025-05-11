@include('partials.navbar')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Clientes</h1>
    </div>
    
    <!-- Total de clientes cuenta la colección de datos clientes -->
    <div class="mb-3">
        <p class="text-muted">Total de clientes: <span class="fw-bold">{{ $clientes->count() }}</span></p>
    </div>
    
    <!-- Barra de búsqueda por nombre-->
    <div class="mb-4">
        <form method="GET" action="" class="row g-2 align-items-center">
            <div class="col-md-9 col-lg-10">
                <input type="text" class="form-control" name="search" placeholder="Buscar cliente por nombre..." value="{{ $search ?? '' }}">
            </div>
            <div class="col-md-3 col-lg-2">
                <button type="submit" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-search me-1"></i> Buscar </button>
            </div>
        </form>
    </div>
    
    <!-- Mensaje si no hay resultados -->
    @if($search && $clientes->isEmpty())
        <div class="alert alert-warning">
            No se encontraron clientes con el nombre "{{ $search }}"
        </div>
    @endif
    
    <!-- Lista de clientes -->
    <div class="row">
        @forelse($clientes as $cliente)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $cliente->Nombre }} {{ $cliente->Apellido }}</h5>
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
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                No hay clientes registrados
            </div>
        </div>
        @endforelse
    </div>
</div>



<style>
    .card {
        transition: transform 0.2s ease;
    }
    .card:hover {
        transform: translateY(-3px); /*se mueve*/
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
</style>
