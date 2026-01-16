<div class="horizontal-menu">
  <nav class="navbar top-navbar">
    <div class="container">
      <div class="navbar-content">
						
        <!--<a href="#" class="navbar-brand d-none d-lg-flex">
          PRO<span>ATIVA</span>
        </a>-->
        
        <!-- Logo-mini for small screen devices (mobile/tablet) -->
        <div class="logo-mini-wrapper">
          <img src="{{ url('build/images/Proativa_logo.png') }}" class="logo-mini logo-mini-light" alt="logo">
                <img src="{{ url('build/images/Proativa_logo.png') }}" class="logo-mini logo-mini-dark" alt="logo">
        </div>

        <!--<form class="search-form">
          <div class="input-group">
            <div class="input-group-text">
              <i data-lucide="search"></i>
            </div>
            <input type="text" class="form-control" id="navbarForm" placeholder="Pesquise aqui...">
          </div>
        </form> -->
        
        <ul class="navbar-nav">
          <li class="theme-switcher-wrapper nav-item">
            <input type="checkbox" value="" id="theme-switcher">
            <label for="theme-switcher">
              <div class="box">
                <div class="ball"></div>
                <div class="icons">
                  <i class="link-icon" data-lucide="sun"></i>
                  <i class="link-icon" data-lucide="moon"></i>
                </div>
              </div>
            </label>
          </li>
          
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i data-lucide="bell"></i>
              <div class="indicator">
                <div class="circle"></div>
              </div>
            </a>
            <div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
              <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                <p>2 Novas Notificações</p>
                <a href="javascript:;" class="text-secondary">Limpar</a>
              </div>
              <div class="p-1">
                <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                  <div class="w-30px h-30px d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                    <i class="icon-sm text-white" data-lucide="user"></i>
                  </div>
                  <div class="flex-grow-1 me-2">
                    <p>Novo exame gerado</p>
                    <p class="fs-12px text-secondary">30 min ago</p>
                  </div>	
                </a>
                <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                  <div class="w-30px h-30px d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                    <i class="icon-sm text-white" data-lucide="alert-circle"></i>
                  </div>
                  <div class="flex-grow-1 me-2">
                    <p>Tem exames com prazos vencidos!</p>
                    <p class="fs-12px text-secondary">1 hrs ago</p>
                  </div>	
                </a>
              </div>
              <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                <a href="javascript:;">Ver Todas</a>
              </div>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img class="w-30px h-30px ms-1 rounded-circle" src="{{ url('https://placehold.co/30x30') }}" alt="profile">
            </a>
            <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
              <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                <div class="mb-3">
                  <img class="w-80px h-80px rounded-circle" src="{{ url('https://placehold.co/80x80') }}" alt="">
                </div>
                <div class="text-center">
                  <p class="fs-16px fw-bolder">Ronilson Gomes</p>
                  <p class="fs-12px text-secondary">ronilsonneto2001@gmail.com</p>
                </div>
              </div>
              <ul class="list-unstyled p-1">
                <li>
                  <a href="{{ url('/general/profile') }}" class="dropdown-item py-2 text-body ms-0">
                    <i class="me-2 icon-md" data-lucide="user"></i>
                    <span>Perfil</span>
                  </a>
                </li>
                <li>
                  <a href="javascript:;" class="dropdown-item py-2 text-body ms-0">
                    <i class="me-2 icon-md" data-lucide="edit"></i>
                    <span>Editar Perfil</span>
                  </a>
                </li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item py-2 text-body ms-0 w-100 text-start" style="background: none; border: none;">
                      <i class="me-2 icon-md" data-lucide="log-out"></i>
                      <span>Sair</span>
                    </button>
                  </form>
                </li>
              </ul>
            </div>
          </li>
        </ul>

        <!-- navbar de navegação para dispositivos pequenos -->
        <div data-toggle="horizontal-menu-toggle" class="navbar-toggler navbar-toggler-right d-lg-none align-self-center">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </div>
        
      </div>
    </div>
  </nav>
  <nav class="bottom-navbar">
    <div class="container">
      <ul class="nav page-navigation">
        <li class="nav-item {{ active_class(['/']) }}">
          <a class="nav-link" href="{{ url('/') }}">
            <i class="link-icon" data-lucide="home"></i>
            <span class="menu-title">Pagina Inicial</span>
          </a>
        </li>

        <li class="nav-item {{ active_class(['forms/cadastrar-funcionario']) }}">
          <a href="#" class="nav-link">
            <i class="link-icon" data-lucide="user"></i>
            <span class="menu-title">Funcionarios</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <ul class="submenu-item">
              <li class="nav-item"><a href="{{ url('/forms/cadastrar-funcionario') }}" class="nav-link {{ active_class(['forms/cadastrar-funcionario']) }}">Cadastrar Funcionarios</a></li>
              <li class="nav-item"><a href="{{ url('/forms/listar-funcionario') }}" class="nav-link {{ active_class(['forms/listar-funcionario']) }}">Listar Funcionarios</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item {{ active_class(['forms/cadastrar-empresa']) }}">
          <a href="#" class="nav-link">
            <i class="link-icon" data-lucide="briefcase"></i>
            <span class="menu-title">Empresas</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <ul class="submenu-item">
              <li class="nav-item"><a href="{{ url('/forms/cadastrar-empresa') }}" class="nav-link {{ active_class(['forms/cadastrar-empresa']) }}">Cadastrar Empresas</a></li>
              <li class="nav-item"><a href="{{ url('/forms/listar-empresa') }}" class="nav-link {{ active_class(['forms/listar-empresa']) }}">Listar Empresas</a></li>
            </ul>
          </div>
        </li>

        <li class="nav-item {{ active_class(['forms/listar-exames']) }}">
          <a href="#" class="nav-link">
            <i class="link-icon" data-lucide="file-text"></i>
            <span class="menu-title">Exames</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <ul class="submenu-item">
              <li class="nav-item"><a href="{{ url('/forms/listar-exames') }}" class="nav-link {{ active_class(['forms/listar-exames']) }}">Listar Exames</a></li>
              <li class="nav-item"><a href="{{ url('/forms/gerar-exame') }}" class="nav-link {{ active_class(['forms/gerar-exame']) }}">Agendar Exame</a></li>
            </ul>
          </div>
        </li>
        
        <li class="nav-item {{ active_class(['icons/*']) }}">
          <a href="#" class="nav-link">
            <i class="link-icon" data-lucide="smile"></i>
            <span class="menu-title">Icons</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <ul class="submenu-item">
              <li class="nav-item"><a href="{{ url('/icons/lucide-icons') }}" class="nav-link {{ active_class(['icons/lucide-icons']) }}">Lucide Icons</a></li>
              <li class="nav-item"><a href="{{ url('/icons/flag-icons') }}" class="nav-link {{ active_class(['icons/flag-icons']) }}">Flag Icons</a></li>
              <li class="nav-item"><a href="{{ url('/icons/mdi-icons') }}" class="nav-link {{ active_class(['icons/mdi-icons']) }}">Mdi Icons</a></li>
            </ul>
          </div>
        </li>
        
      </ul>
    </div>
  </nav>
</div>
