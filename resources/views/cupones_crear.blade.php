@include('partials.navbar')<!-- NAVBAR DE REPRESENTANTE DE EMPRESA-->

<link href="{{ asset('css/cupones_pendientes.css') }}" rel="stylesheet">
<script src="{{ asset('js/validaciones_cupon.js') }}" defer></script>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="statusToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<div class="container mt-4">
    <h1>Crear Cupón</h1>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <!-- Columna del formulario (6 columnas) -->
                <div class="col-md-6">
                    <form id="cuponForm" method="POST" action="{{ route('cupones.crear') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="ID_Empresa" class="form-label text-white">ID Empresa *</label> <!--SE TIENE QUE LLENAR CON SESSION LUEGUITO -->
                            <input type="text" class="form-control" id="ID_Empresa" name="ID_Empresa"
                                value="{{ old('ID_Empresa') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="Titulo" class="form-label text-white">Título *</label>
                            <input type="text" class="form-control" id="Titulo" name="Titulo"
                                value="{{ old('Titulo') }}" required>

                        </div>

                        <div class="mb-3">
                            <label for="Imagen" class="form-label text-white">Imagen (URL) *</label>
                            <input type="text" class="form-control" id="Imagen" name="Imagen"
                                value="{{ old('Imagen') }}" oninput="actualizarVistaPrevia(this.value)" required>

                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="PrecioR" class="form-label text-white">Precio Regular *</label>
                                <input type="number" class="form-control" id="PrecioR" name="PrecioR"
                                    step="0.01" value="{{ old('PrecioR') }}" required>

                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="PrecioO" class="form-label text-white">Precio Oferta *</label>
                                <input type="number" class="form-control" id="PrecioO" name="PrecioO"
                                    step="0.01" value="{{ old('PrecioO') }}" required>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="Fecha_Inicial" class="form-label text-white">Fecha Inicial *</label>
                                <input type="date" class="form-control" id="Fecha_Inicial"
                                    name="Fecha_Inicial" value="{{ old('Fecha_Inicial') }}" required>

                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="Fecha_Final" class="form-label text-white">Fecha Final *</label>
                                <input type="date" class="form-control" id="Fecha_Final" name="Fecha_Final"
                                    value="{{ old('Fecha_Final') }}" required>

                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="Fecha_Limite" class="form-label text-white">Fecha Límite *</label>
                                <input type="date" class="form-control" id="Fecha_Limite" name="Fecha_Limite"
                                    value="{{ old('Fecha_Limite') }}" required>

                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="Descripcion" class="form-label text-white">Descripción *</label>
                            <textarea class="form-control" id="Descripcion" name="Descripcion" rows="3"
                                required>{{ old('Descripcion') }}</textarea>

                        </div>

                        <div class="mb-3">
                            <label for="Stock" class="form-label text-white">Stock *</label>
                            <input type="number" class="form-control" id="Stock" name="Stock"
                                value="{{ old('Stock') }}" required>

                        </div>

                        <button type="submit" class="btn btn-outline-light w-100">Agregar Cupón</button>
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

    // Mostrar mensaje de éxito
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
            const toastEl = document.getElementById('statusToast');
            const toastMessage = document.getElementById('toastMessage');
            toastEl.classList.remove('text-bg-danger');
            toastEl.classList.add('text-bg-success');
            toastMessage.textContent = "{{ session('success') }}";
            new bootstrap.Toast(toastEl).show();
        @endif
});
</script>