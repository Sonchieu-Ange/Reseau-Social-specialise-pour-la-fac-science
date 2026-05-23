<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="fa-solid fa-graduation-cap me-2"></i>Phoenix FS
        </a>
        <form class="d-flex mx-auto col-md-6 d-none d-sm-flex" action="{{ route('search') }}" method="GET">
            <input class="form-control me-2 bg-light border-0" type="search" name="query" placeholder="Rechercher un étudiant, un enseignant..." aria-label="Search">
        </form>
        <div class="navbar-nav ms-auto align-items-center">
            <a class="nav-link" href="{{ route('messages.index') }}"><i class="fa-solid fa-paper-plane fa-lg"></i></a>
            <div class="nav-item dropdown ms-3">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
                    <div class="bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-menu-item p-2 d-block text-decoration-none text-dark" href="{{ route('profile.show', auth()->id()) }}"><i class="fa-solid fa-user me-2 text-primary"></i>Mon Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i>Déconnexion</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>