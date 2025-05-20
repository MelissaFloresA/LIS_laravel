@include('partials.headers')

<body>
    @include('partials.navbar')<!-- NAVBAR DE REPRESENTANTE DE EMPRESA-->

    <div class="container mt-4">
        <h1>{{ isset($cupon) ? 'Editar' : 'Crear' }} Cupón</h1>

        <div class="card my-4 bg-cuponera text-white">
            <div class="card-body">
                <div class="row">
                    <!-- Columna del formulario (6 columnas) -->
                    <div class="col-md-6">
                        <form id="cuponForm" method="POST" action="{{ isset($cupon) ? route('cupones.update', [$cupon->ID_Empresa, $cupon->ID_Cupon]) : route('cupones.create', session('ID_Empresa')) }}">
                            @csrf
                            @if (isset($cupon))
                                @method('PUT')
                            @endif
                            <div class="mb-3">
                                <label for="Titulo" class="form-label">Título *</label>
                                <input type="text" class="form-control" id="Titulo" name="Titulo"
                                    value="{{ old('Titulo', $cupon->Titulo ?? '') }}" required>

                            </div>

                            <div class="mb-3">
                                <label for="Imagen" class="form-label">Imagen (URL) *</label>
                                <input type="text" class="form-control" id="Imagen" name="Imagen"
                                    value="{{ old('Imagen', $cupon->Imagen ?? '') }}"
                                    oninput="actualizarVistaPrevia(this.value)" required>

                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="PrecioR" class="form-label">Precio Regular *</label>
                                    <input type="number" class="form-control" id="PrecioR" name="PrecioR"
                                        step="0.01" value="{{ old('PrecioR', $cupon->PrecioR ?? '') }}" required>

                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="PrecioO" class="form-label">Precio Oferta *</label>
                                    <input type="number" class="form-control" id="PrecioO" name="PrecioO"
                                        step="0.01" value="{{ old('PrecioO', $cupon->PrecioO ?? '') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="Fecha_Inicial" class="form-label">Fecha Inicial *</label>
                                    <input type="date" class="form-control" id="Fecha_Inicial" name="Fecha_Inicial"
                                        value="{{ old('Fecha_Inicial', $cupon->Fecha_Inicial ?? '') }}" required>

                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="Fecha_Final" class="form-label">Fecha Final *</label>
                                    <input type="date" class="form-control" id="Fecha_Final" name="Fecha_Final"
                                        value="{{ old('Fecha_Final', $cupon->Fecha_Final ?? '') }}" required>

                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="Fecha_Limite" class="form-label">Fecha Límite *</label>
                                    <input type="date" class="form-control" id="Fecha_Limite" name="Fecha_Limite"
                                        value="{{ old('Fecha_Limite', $cupon->Fecha_Limite ?? '') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="Descripcion" class="form-label">Descripción *</label>
                                <textarea class="form-control" id="Descripcion" name="Descripcion" rows="3" required>{{ old('Descripcion', $cupon->Descripcion ?? '') }}</textarea>

                            </div>

                            <div class="mb-3">
                                <label for="Stock" class="form-label">Stock *</label>
                                <input type="number" class="form-control" id="Stock" name="Stock"
                                    value="{{ old('Stock', $cupon->Stock ?? '') }}" required>

                            </div>

                            <button type="submit" class="btn btn-outline-light w-100">{{ isset($cupon) ? 'Actualizar' : 'Agregar' }} Cupón</button>
                        </form>
                    </div>


                    <!-- Columna de la imagen (6 columnas) -->
                    <div class="col-md-6">
                        <div class="d-flex flex-column align-items-center justify-content-center h-100">
                            <!-- Vista previa de la imagen (más grande) -->
                            <div class="mb-3 text-center">
                                <img id="imagenPrevia"
                                    src="https://via.placeholder.com/600x450?text=Previsualización+del+Cupón"
                                    alt="Vista previa de la imagen del cupón" class="img-fluid rounded shadow"
                                    style="max-height: 450px; width: auto;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.toast')

    <script src="{{ asset('js/validaciones_cupon.js') }}" defer></script>
    <!-- JavaScript para la vista previa de la imagen -->
    <script>
        function actualizarVistaPrevia(url) {
            const imagenPrevia = document.getElementById('imagenPrevia');

            // Verificar si la URL parece ser una imagen
            if (url && (url.match(/\.(jpeg|jpg|gif|png)$/) || url.startsWith('http'))) {
                imagenPrevia.src = url;
            } else {
                imagenPrevia.src = 'https://via.placeholder.com/600x450?text=URL+de+imagen+no+válida';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            actualizarVistaPrevia(document.getElementById('Imagen').value);
        })
    </script>
</body>

</html>
