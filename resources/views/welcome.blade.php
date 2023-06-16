<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JADMA_STORE</title>
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    @yield('css')

    <!-- Aqui van todos los archivos de css -->

</head>

<body>

    <!-- Navbar -->

    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <div class="container px-4 px-lg-5">
            <!-- nombre de la pagina -->
            <!-- <a class="navbar-brand" href="{{route('main')}}">JADMA STORE</a> -->
            <a class="navbar-brand" href="{{route('main')}}"><img src="{{asset('img/logo/logo_jadma.png')}}" width="70" heigth="70"></a>


            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon btn btn-light"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item dropdown">
                        <!-- cerrar session -->
                        <a  class="nav-link dropdown-toggle text-white" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Mi cuenta</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                @if (Route::has('login'))
                                    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">

                                    @auth
                                        @can('panel.home')
                                         <a href="{{ url('/home') }}" class="dropdown-item">Dashboard</a>
                                        @endcan
                                            <a  class="dropdown-item" href="{{route('clientes.show', Auth::id())}}">Perfil</a>
                                            <form action="{{route('logout')}}" method="post">
                                                @csrf
                                                <button  class="dropdown-item">Cerrar Sessión</button>
                                            </form>
                                        @else
                                            {{-- hasta aquí --}}
                                            <a href="{{ route('login') }}" class="dropdown-item ">Iniciar Sesion</a>

                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="dropdown-item ">Registrarse</a>
                                        @endif
                                    @endauth
                                    </div>
                                @endif
                            </li>
                            </a>
                    </li>
                </ul>

                <!-- menu -->
                <li class="nav-item"><a class="nav-link active text-white" aria-current="page" href="{{route('pqrs.index')}}">PQRS</a></li>
                <!-- <li class="nav-item"><a class="nav-link" href="#!">Acerca de nosotros</a></li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Categorias</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @foreach($categorias as $c)
                          <li><a class="dropdown-item" href="{{Route('mostrarProduct', $c->id)}}">{{$c->nombre}}</a></li> 
                        @endforeach
                    </ul>
                </li>
                <!-- <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right show" style="left: inherit; right: 0px;"> -->
                <!-- <li class="user-footer"> -->
                </ul>

                <form action="{{route('carrito.index')}}" method="get">
                    
                    <button type="submit" class="btn btn-outline-dark text-white">
                        <i class="bi-cart-fill me-1 text-white"></i>
                        Carrito
                    </button>
                </form>
               
            </div>
        </div>
    </nav>

    <!-- fin de navbar -->


    @yield('content')

    <!-- Aqui va todo el contenido donde se extiende la plantilla -->

    <!-- Inicio footer -->

    <footer class="py-4 bg-dark">
        <div class="container">
            <div class="redes-sociales mb-2">
                <a href="https://www.facebook.com" ><i class="bi bi-facebook btn-lg text-light"></i></a>
                <a href="https://www.instagram.com/"><i class="bi bi-instagram btn-lg text-light" ></i></a>
            </div>
            <p class="m-0 text-center text-white">Copyright &copy; JADMA STORE 2022</p>
        </div>
    </footer>

    <!-- Fin de footer -->

    @yield('js')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="js/scripts.js"></script> --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('crear') == 'ok')
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Se envio correctamente',
                showConfirmButton: false,
                timer: 2200
            })
        @endif    
    </script>
   
    <!-- Aqui va todos los JavaScrips -->

</body>

</html>