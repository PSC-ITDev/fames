<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{ config('app.name', 'FAMES') }}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <meta name="msapplication-TileColor" content="#206bc4"/>
    <meta name="theme-color" content="#206bc4"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="HandheldFriendly" content="True"/>
    <meta name="MobileOptimized" content="320"/>
    <meta name="robots" content="noindex,nofollow,noarchive"/>
    <link rel="icon" href="{{ asset('./favicon.ico') }}" type="image/x-icon"/>
    <link rel="shortcut icon" href="{{ asset('./favicon.ico') }}" type="image/x-icon"/>
    <!-- CSS files -->
    
    <link href="{{ asset('bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/libs/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/demo.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/style.css') }}" rel="stylesheet"/>
    <link href="{{ asset('bootstrap-icons/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <!-- <link href="{{ asset('dist/css/bootstrap-icons.css') }}" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"> -->
    
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css"> -->

    <link href="{{ asset('datatable/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/custom.css') }}" rel="stylesheet">

    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script> -->


    
    <style>
      body {
      	display: none;
      }
    </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
            
    <script src="{{ asset('chartjs/chart.umd.min.js') }}"></script>
        
        
	<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    </head>

	<body class="font-sans antialiased">


   
	<!-- <livewire:layout.navigation /> -->
	@include('layouts.navigation')
    
    <div class="page">
		<header class="navbar navbar-expand-md navbar-light d-none d-lg-flex">
        <div class="container-xl" style="max-width: 100% !important">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown d-none d-md-flex mr-3">
              <!-- <a href="#" class="nav-link px-0" data-toggle="dropdown" tabindex="-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                <span class="badge bg-red"></span>
              </a> -->
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-card">
                <div class="card">
                  <div class="card-body">
                     Submitted Evaluation
                  </div>
                </div>
              </div>
            </div>
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-toggle="dropdown">
                <span class="avatar" style="background-image: url('{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('storage/pictures/avatar.png') }}');"></span>
                <div class="d-none d-xl-block pl-2">
                  <div>{{auth()->user()->name}}</div>
                  <div class="mt-1 small text-muted">{{ auth()->user()->department->name   }} - {{ auth()->user()->department->division->name   }}</div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item d-inline-flex align-items-center gap-2" href="#">                  
				  <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><circle cx="12" cy="7" r="4"></circle><path d="M5.5 21v-2a4 4 0 0 1 4 -4h5a4 4 0 0 1 4 4v2"></path></svg>
                  Profile
                </a>
                <a class="dropdown-item d-inline-flex align-items-center gap-2" href="#">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>
                  Change Password
                </a>
                <div class="dropdown-divider"></div>
				<form method="POST" action="{{ route('logout') }}" class="m-0">
				@csrf
				<button type="submit" class="dropdown-item d-flex align-items-center ">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><path d="M15 12h5a2 2 0 0 1 0 4h-15l-3 -6h3l2 2h3l-2 -7h3z" transform="rotate(-15 12 12) translate(0 -1)"></path><line x1="3" y1="21" x2="21" y2="21"></line></svg>
					<span>{{ __('Log Out') }}</span>
				</button>
			</form>
              </div>
            </div>
			
          </div>
          <div class="collapse navbar-collapse" id="navbar-menu">
            <div>
              {{-- <form action="." method="get">
                <div class="input-icon">
                  <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
                  </span>
                  <input type="text" class="form-control" placeholder="Search…">
                </div>
              </form> --}}
            </div>
          </div>
        </div>
      </header>
      <div class="content">
        <div class="container-fluid">

        
             <!-- Page title -->
          <div class="page-header">
            <div class="row align-items-center">
              <div class="col-auto">
                <h1 class="page-title">                 
                 {{ $pageTitle ?? "Dashboard" }}
                </h1>
              </div>
            </div>
          </div>
          <div class="row row-deck row-cards">
            <!-- Page Content -->
             
            {{ $slot }}

           
          </div>
          
             
       </div>
        <footer class="footer footer-transparent">
          <div class="container">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-lg-auto ml-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                  <!-- <li class="list-inline-item"><a href="./docs/index.html" class="link-secondary">Documentation</a></li>
                  <li class="list-inline-item"><a href="./faq.html" class="link-secondary">FAQ</a></li>
                  <li class="list-inline-item"><a href="https://github.com/tabler/tabler" target="_blank" class="link-secondary">Source code</a></li> -->
                </ul>
              </div>
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
               Copyright © <?= date('Y'); ?>
                <a href="." class="link-secondary">FAMES</a>.
                All rights reserved.
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>

    

    <script src="{{ asset('jquery/jquery-4.0.slim.min.js') }}"></script>


       <!-- Libs JS -->
    <script src="{{ asset('dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('dist/libs/jquery/dist/jquery.slim.min.js')}}"></script>
    <script src="{{ asset('dist/libs/jqvmap/dist/jquery.vmap.min.js')}}"></script>
    <script src="{{ asset('dist/libs/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
    <script src="{{ asset('dist/libs/peity/jquery.peity.min.js')}}"></script>

    <!-- Tabler Core -->

    <script src="{{ asset('bootstrap-icons/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('dist/js/tabler.min.js')}}"></script>
    

    <script>
      document.body.style.display = "block"
    </script>
  </body>
</html>