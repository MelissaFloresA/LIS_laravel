<link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="ms-3  navbar-brand text-white" href="">
            <i class="fas fa-tag"></i> Cuponera
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar"
            aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-white" id="navbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-3">
                @if (session('ID_Empresa') == 'CUPON')
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('empresas.index') }}">
                            <i class="fas fa-ticket-alt"></i> Empresa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('clientes.index') }}">
                            <i class="fa fa-user-circle"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('rubros.index') }}">
                            <i class="fa fa-plus-square"></i> Rubros
                        </a>
                    </li>
                @endif
                @if (session('ID_Empresa') != 'CUPON' && session('ID_Rol') == 1)
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('cupones.index') }}">
                            <i class="fas fa-ticket-alt"></i> Cupones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('empleados.index') }}">
                            <i class="fas fa-users"></i> Empleados
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('auth.logout') }}">
                        <i class="fas fa-sign-in-alt"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
