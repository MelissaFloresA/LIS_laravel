@include('partials.navbar')

<link href="{{ asset('css/cupon_style.css') }}" rel="stylesheet">

<div class="container py-4">
    <div class="d-flex flex-column justify-content-between align-items-start mb-4">
        <a href="{{ route('clientes') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a><br>
        <h1>Cupones de {{ $cliente->NombreCompleto }}</h1>
    </div>

    <!-- Pestañas para categorías -->
    <ul class="nav nav-tabs mb-4" id="cuponesTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="disponibles-tab" data-bs-toggle="tab" data-bs-target="#disponibles"
                type="button" role="tab">
                Disponibles ({{ count($cupones['disponibles']) }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="canjeados-tab" data-bs-toggle="tab" data-bs-target="#canjeados" type="button"
                role="tab">
                Canjeados ({{ count($cupones['canjeados']) }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="vencidos-tab" data-bs-toggle="tab" data-bs-target="#vencidos" type="button"
                role="tab">
                Vencidos ({{ count($cupones['vencidos']) }})
            </button>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="cuponesTabsContent">
        <!-- Cupones Disponibles -->
        <div class="tab-pane fade show active" id="disponibles" role="tabpanel">
            @if(count($cupones['disponibles']) > 0)
                <div class="row">
                    @foreach($cupones['disponibles'] as $cupon)
                        <div class="col-md-6 mb-4">
                            <div class="card coupon-card position-relative overflow-hidden border-0  rounded-4">
                                <div class="row g-0 h-100">
                                    <div class="col-md-4">
                                        <img src="{{ $cupon['Imagen'] }}" class="img-fluid h-100 rounded-start object-fit-cover">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body d-flex flex-column justify-content-between h-100 text-white">
                                            <div>
                                                <h4 class="card-title fw-bold">{{ $cupon['Titulo'] }}</h4>
                                                <p class="fw-bold mb-1">
                                                    ${{ number_format($cupon['PrecioO'], 2) }}
                                                    <small class="text-muted text-decoration-line-through">
                                                        ${{ number_format($cupon['PrecioR'], 2) }}
                                                    </small>
                                                </p>
                                                <p class="card-text small">{{ $cupon['Descripcion'] }}</p>
                                            </div>
                                            <div class="mt-auto">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i> Disponible
                                                    </span>
                                                    <small class="text-muted">
                                                        Usos: {{ $cupon['Veces_Canje'] }}/{{ $cupon['Cantidad'] }}
                                                    </small>
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        Comprado: {{ $cupon['Fecha_Compra'] }}
                                                    </small>
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Válido hasta: {{ $cupon['Fecha_Final'] }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="circle-left"></div>
                            <div class="circle-right"></div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">No hay cupones disponibles.</div>
            @endif
        </div>

        <!-- Vista de Cupones Canjeados -->
        <div class="tab-pane fade" id="canjeados" role="tabpanel">
            @if(count($cupones['canjeados']) > 0)
                <div class="row">
                    @foreach($cupones['canjeados'] as $cupon)
                        <div class="col-md-6 mb-4">
                            <div class="card coupon-card position-relative overflow-hidden border-0  rounded-4">
                                <div class="row g-0 h-100">
                                    <div class="col-md-4">
                                        <img src="{{ $cupon['Imagen'] }}" class="img-fluid h-100 rounded-start object-fit-cover">
                                    </div>
                                    <div class="col-8 pb-4">
                                        <div class="card-body d-flex flex-column justify-content-between h-100 text-white">
                                            <div>
                                                <h5 class="card-title fw-bold">{{ $cupon['Titulo'] }}</h5>
                                                <p class="fw-bold mb-1">
                                                    ${{ number_format($cupon['PrecioO'], 2) }}
                                                </p>
                                                <p class="card-text small">{{ $cupon['Descripcion'] }}</p>
                                            </div>
                                            <div class="mt-auto">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-check-circle me-1"></i> Canjeado
                                                    </span>
                                                    <small class="text-muted">
                                                        Usado {{ $cupon['Veces_Canje'] }} veces
                                                    </small>
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        Comprado: {{ $cupon['Fecha_Compra'] }}
                                                    </small>
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Válido hasta: {{ $cupon['Fecha_Final'] }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">No hay cupones canjeados.</div>
            @endif
        </div>

        <!-- Cupones Vencidos -->
        <div class="tab-pane fade" id="vencidos" role="tabpanel">
            @if(count($cupones['vencidos']) > 0)
                <div class="row">
                    @foreach($cupones['vencidos'] as $cupon)
                        <div class="col-md-6 mb-4">
                            <div class="card coupon-card position-relative overflow-hidden border-0  rounded-4">
                                <div class="row g-0 h-100">
                                    <div class="col-md-4">
                                        <img src="{{ $cupon['Imagen'] }}" class="img-fluid h-100 rounded-start object-fit-cover">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body d-flex flex-column justify-content-between h-100 text-white">
                                            <div>
                                                <h5 class="card-title fw-bold">{{ $cupon['Titulo'] }}</h5>
                                                <p class="fw-bold mb-1">
                                                    ${{ number_format($cupon['PrecioO'], 2) }}
                                                </p>
                                                <p class="card-text small">{{ $cupon['Descripcion'] }}</p>
                                            </div>
                                            <div class="mt-auto">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-clock me-1"></i> Vencido
                                                    </span>
                                                    <small class="text-muted">
                                                        No usado (0/{{ $cupon['Cantidad'] }})
                                                    </small>
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        Comprado: {{ $cupon['Fecha_Compra'] }}
                                                    </small>
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Venció el: {{ $cupon['Fecha_Final'] }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">No hay cupones vencidos.</div>
            @endif
        </div>
    </div>
</div>



<style>
    .coupon-card {
        transition: transform 0.3s ease;
    }

    .coupon-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
</style>