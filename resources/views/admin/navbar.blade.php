<nav class="navbar navbar-expand-lg navbar-dark bg-primary card-header">
    <a class="navbar-brand" href="{{ url('/admin') }}"><i class="fas fa-user mr-2"></i>{{ Auth::user()->firstname }}</a>
    <div class="collapse navbar-collapse" id="navbarMenus">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/admin/change-password') }}"><i class="fab fa-500px mr-2"></i>Change
                    Password</a>
            </li>

            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </a>
                </form>
                {{-- <a class="nav-link" href="{{ url('/admin/logout') }}"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a> --}}
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/admin/users') }}"><i class="fas fa-users mr-2"></i>List Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/admin/create-user') }}"><i class="fas fa-user-plus mr-2"></i>Create
                    User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/admin/create-role') }}"><i class="fas fa-key"></i> Create Role</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/admin/roles') }}"><i class="fas fa-list mr-2"></i>List Roles</a>
            </li>
        </ul>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenus"
        aria-controls="navbarMenus" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>
