<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

  <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
      <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a href="." class="navbar-brand navbar-brand-autodark">
          <img src="./static/logo.png" alt="Fames" 
          class="bg-white">
        </a>
         
        <div class=" active navbar-collapse" id="navbar-menu">
          <ul class="navbar-nav pt-lg-3">
            <li class="nav-item">
              <a class="nav-link" href="#" >
                <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                </span>
                <span class="nav-link-title">
                  Dashboard
                </span>
              </a>
            </li>
            <li class="collapse nav-item  dropdown">
              <a class="nav-link dropdown-toggle" href="#navbar-base" data-toggle="dropdown" role="button" aria-expanded="false" >
                <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
                </span>
                <span class="nav-link-title">
                  Fixed Assets
                </span>
              </a>
              <ul class="dropdown-menu dropdown-menu-columns  dropdown-menu-columns-2">
                <li >
                  <a class="dropdown-item" href="#" >
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                    Register
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./blank.html" >
                    List
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./buttons.html" >
                    Buttons
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./cards.html" >
                    Cards
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./dropdowns.html" >
                    Dropdowns
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./icons.html" >
                    Icons
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./modals.html" >
                    Modals
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./maps.html" >
                    Maps
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./maps-vector.html" >
                    Vector maps
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./navigation.html" >
                    Navigation
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./charts.html" >
                    Charts
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./tables.html" >
                    Tables
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./calendar.html" >
                    Calendar
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./carousel.html" >
                    Carousel
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./lists.html" >
                    Lists
                  </a>
                </li>
                <li class="dropright">
                  <a class="dropdown-item dropdown-toggle" href="#sidebar-authentication" data-toggle="dropdown" role="button" aria-expanded="false" >
                    Authentication
                  </a>
                  <div class="dropdown-menu">
                    <a href="./sign-in.html" class="dropdown-item">Sign in</a>
                    <a href="./sign-up.html" class="dropdown-item">Sign up</a>
                    <a href="./forgot-password.html" class="dropdown-item">Forgot password</a>
                    <a href="./terms-of-service.html" class="dropdown-item">Terms of service</a>
                  </div>
                </li>
                <li class="dropright">
                  <a class="dropdown-item dropdown-toggle" href="#sidebar-error" data-toggle="dropdown" role="button" aria-expanded="false" >
                    Error pages
                  </a>
                  <div class="dropdown-menu">
                    <a href="./400.html" class="dropdown-item">400 page</a>
                    <a href="./401.html" class="dropdown-item">401 page</a>
                    <a href="./403.html" class="dropdown-item">403 page</a>
                    <a href="./404.html" class="dropdown-item">404 page</a>
                    <a href="./500.html" class="dropdown-item">500 page</a>
                    <a href="./503.html" class="dropdown-item">503 page</a>
                    <a href="./maintenance.html" class="dropdown-item">Maintenance page</a>
                  </div>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./form-elements.html" >
                <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><polyline points="9 11 12 14 20 6" /><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" /></svg>
                </span>
                <span class="nav-link-title">
                  Evaluation
                </span>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#navbar-extra" data-toggle="dropdown" role="button" aria-expanded="false" >
                <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><path d="M12 17.75l-6.172 3.245 1.179-6.873-4.993-4.867 6.9-1.002L12 2l3.086 6.253 6.9 1.002-4.993 4.867 1.179 6.873z" /></svg>
                </span>
                <span class="nav-link-title">
                  Downloads
                </span>
              </a>
              <ul class="dropdown-menu">
                <li >
                  <a class="dropdown-item" href="./invoice.html" >
                    Invoice
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./blog.html" >
                    Blog cards
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./snippets.html" >
                    Snippets
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./search-results.html" >
                    Search results
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./pricing.html" >
                    Pricing cards
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./users.html" >
                    Users
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./gallery.html" >
                    Gallery
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./profile.html" >
                    Profile
                  </a>
                </li>
                <li >
                  <a class="dropdown-item" href="./music.html" >
                    Music
                  </a>
                </li>
              </ul>
            </li>
            
          </ul>
        </div>
      </div>
    </aside>