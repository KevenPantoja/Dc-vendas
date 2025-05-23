<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
      <img src="{{ asset('lg.png') }}" alt="Logo" height="40" class="me-2" />
      <span class="fw-bold">DC</span>
    </a>

    <!-- Hamburger / Toggle button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu links e dropdown -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" 
             class="nav-link @if(request()->routeIs('dashboard')) active @endif" 
             aria-current="page">Dashboard</a>
        </li>
      </ul>

      <!-- Dropdown do usuário -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
             aria-expanded="false">
            {{ Auth::user()->name }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li>
              <a class="dropdown-item" href="{{ route('profile.edit') }}">
                {{ __('Profile') }}
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="dropdown-item" type="submit">{{ __('Log Out') }}</button>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
