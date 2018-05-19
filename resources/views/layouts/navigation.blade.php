<nav class="navbar">
  <div class="container">
    <div class="navbar-brand">
      <a class="navbar-item" href="/">
        <img src="/images/kipr_logo.jpg" alt="Logo">
      </a>
      <span class="navbar-burger burger" data-target="navbarMenuHeroB">
        <span></span>
        <span></span>
        <span></span>
      </span>
    </div>
    <div class="navbar-menu">
      <div class="navbar-end">
        <a href="/" class="navbar-item is-active">
          Home
        </a>
        <a href="/documentation" class="navbar-item">
          Documentation
        </a>
        <a href="/competitions" class="navbar-item">
          Competitions
        </a>
        @if($latestseason != null)
        <a href="/competitions/{{ $latestseason }}" class="navbar-item">
          {{ $latestseason }} Season
        </a>
        @endif
      </div>
    </div>
  </div>
</nav>
