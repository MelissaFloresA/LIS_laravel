@include('partials.navbar')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Lista de promociones</h1>
    </div>

    <form method="GET" action="{{ route('empresa.filtrar') }}">
        <div class="mb-3">
            <label for="empresa_id" class="form-label">Seleccione una Empresa</label>
            <select name="empresa_id" id="empresa_id" class="form-select" required onchange="this.form.submit()">
                <option value="">-- Seleccione una empresa --</option>
                @foreach($empresas as $empresa)
                   <option value="{{ $empresa->ID_Empresa }}" {{ ($empresaId ?? '') == $empresa->ID_Empresa ? 'selected' : '' }}>

                        {{ $empresa->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    @if($empresaId)
    <h4 class="mt-4">Ofertas de la Empresa Seleccionada</h4>

    <ul class="nav nav-tabs" id="cuponesTab" role="tablist">
      @foreach(['activa','espera','aprobadas_futuras','pasadas','rechazadas','descartadas'] as $key)
      <li class="nav-item" role="presentation">
        <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $key }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $key }}" type="button" role="tab" aria-controls="{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
          {{ ucfirst(str_replace('_', ' ', $key)) }}
        </button>
      </li>
      @endforeach
    </ul>

    <div class="tab-content" id="cuponesTabContent">
      @foreach(['activa','espera','aprobadas_futuras','pasadas','rechazadas','descartadas'] as $key)
      <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $key }}" role="tabpanel" aria-labelledby="{{ $key }}-tab">
        @if($cuponesPorCategoria[$key]->isEmpty())
  <p class="mt-3">No hay ofertas en esta categoría.</p>
@else
  <div class="row mt-3">
    @foreach($cuponesPorCategoria[$key] as $cupon)
      @php
          $ingresos = $cupon->Cantidad_Vendidos * $cupon->PrecioO;
          $comision = $ingresos * ($cupon->Porcentaje_Comision / 100);
      @endphp
      <div class="col-md-12 mb-4">
  <table class="table table-bordered">
    <thead class="table-primary">
      <tr>
        <th colspan="2">{{ $cupon->Titulo }}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">Empresa</th>
        <td>{{ $cupon->Nombre ?? '' }}</td>
      </tr>
      <tr>
        <th scope="row">Descripción</th>
        <td>{{ $cupon->Descripcion ?? '' }}</td>
      </tr>
      <tr>
        <th scope="row">Fecha inicial</th>
        <td>{{ $cupon->Fecha_Inicial ?? '' }}</td>
      </tr>
      <tr>
        <th scope="row">Fecha final</th>
        <td>{{ $cupon->Fecha_Final ?? '' }}</td>
      </tr>
      <tr>
        <th scope="row">Precio Real</th>
        <td>${{ number_format($cupon->PrecioR, 2) }}</td>
      </tr>
      <tr>
        <th scope="row">Precio Oferta</th>
        <td>${{ number_format($cupon->PrecioO, 2) }}</td>
      </tr>
      <tr>
        <th scope="row">Cantidad</th>
        <td>{{ $cupon->Stock }}</td>
      </tr>
      <tr>
        <th scope="row">Vendidos</th>
        <td>{{ $cupon->Cantidad_Vendidos }}</td>
      </tr>
      <tr>
        <th scope="row">Ingresos Totales</th>
        <td>${{ number_format($ingresos, 2) }}</td>
      </tr>
      <tr>
        <th scope="row">Cargo por Servicio</th>
        <td>${{ number_format($comision, 2) }}</td>
      </tr>
    </tbody>
  </table>
</div>

    @endforeach
  </div>
@endif

</div>
      @endforeach
    </div>
    @endif
</div>

