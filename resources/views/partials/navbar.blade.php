<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Jobs</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="#" title="Notifications">
                <i class="far fa-bell"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" title="User">
                <i class="far fa-user-circle"></i>
                <span class="ml-1 d-none d-md-inline">Admin User</span>
            </a>
        </li>
    </ul>
</nav>