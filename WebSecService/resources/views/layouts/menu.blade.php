<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="./">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./even">Even Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./prime">Prime Numbers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./multable">Multiplication Table</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./bill">Supermarket Bill</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./transcript">Academic transcript</a>
            </li>
            @can("show_users")
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users_index') }}">Users</a>
            </li>
            @endcan
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Products
                </a>
                <ul class="dropdown-menu" aria-labelledby="productsDropdown">
                    <li><a class="dropdown-item" href="{{ route('products_list') }}">Products</a></li>
                    <li><a class="dropdown-item" href="{{ route('brought_products') }}">Brought Products</a></li>
                </ul>
            </li>
            
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('todos.index') }}">To-Do List</a>
            </li>

        </ul>

        
        <ul class="navbar-nav">

            @auth
                <li class="nav-item"><a class="nav-link" href="{{route('profile')}}">{{auth()->user()->name}}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('do_logout')}}">Logout</a></li>
            @else
                <li class="nav-item"><a class="nav-link" href="{{route('login')}}">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('register')}}">Register</a></li>
            @endauth

        </ul>
        
    </div>
</nav>