<!doctype html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets/assets/') }}" data-template="vertical-menu-template-free" data-style="light">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login - {{ config('app.name', 'TourPilot') }}</title>
    <meta name="description" content="" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/assets/img/favicon/favicon.ico') }}" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/fonts/remixicon/remixicon.css') }}" />
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/assets/css/demo.css') }}" />
    
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/css/pages/page-auth.css') }}" />
    
    <!-- Helpers -->
    <script src="{{ asset('assets/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/assets/js/config.js') }}"></script>
    
    <style>
        :root {
            --primary-green: #3ea572;
            --secondary-green: #2d7a5f;
            --accent-green: #6cbe8f;
            --light-green: #e6f4ed;
        }
        .btn-primary { background-color: var(--primary-green) !important; border-color: var(--primary-green) !important; }
        .btn-primary:hover { background-color: var(--secondary-green) !important; border-color: var(--secondary-green) !important; }
    </style>
  </head>
  <body>
    <!-- Content -->
    <div class="position-relative">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-6 mx-4">
          <!-- Login -->
          <div class="card p-7">
            <!-- Logo -->
            <div class="app-brand justify-content-center mt-5">
              <a href="{{ route('home') }}" class="app-brand-link gap-3">
                <span class="app-brand-logo demo">
                  <img src="{{ asset('lau-adventuress.png') }}" alt="Lau Paradise Adventures" style="height: 48px; width: auto;" />
                </span>
                <span class="app-brand-text demo text-heading fw-semibold">{{ config('app.name', 'TourPilot') }}</span>
              </a>
            </div>
            <!-- /Logo -->
            <div class="card-body mt-1">
              <h4 class="mb-1">Welcome to Admin Panel! 👋🏻</h4>
              <p class="mb-5">Please sign-in to your account and start the adventure</p>
              
              @if($errors->any())
                <div class="alert alert-danger">
                  @foreach($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                  @endforeach
                </div>
              @endif
              
              <form id="formAuthentication" class="mb-5" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-floating form-floating-outline mb-5">
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" autofocus required />
                  <label for="email">Email</label>
                </div>
                <div class="mb-5">
                  <div class="form-password-toggle">
                    <div class="input-group input-group-merge">
                      <div class="form-floating form-floating-outline">
                        <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                        <label for="password">Password</label>
                      </div>
                      <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line ri-20px"></i></span>
                    </div>
                  </div>
                </div>
                <div class="mb-5 pb-2 d-flex justify-content-between pt-2 align-items-center">
                  <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                  <a href="{{ route('password.request') }}" class="float-end mb-1">
                    <span>Forgot Password?</span>
                  </a>
                </div>
                <div class="mb-5">
                  <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                </div>
              </form>
              <p class="text-center mb-5">
                <span>New on our platform?</span>
                <a href="{{ route('register') }}">
                  <span>Create an account</span>
                </a>
              </p>
            </div>
          </div>
          <!-- /Login -->
          <img src="{{ asset('assets/assets/img/illustrations/tree-3.png') }}" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block" />
          <img src="{{ asset('assets/assets/img/illustrations/auth-basic-mask-light.png') }}" class="authentication-image d-none d-lg-block" height="172" alt="triangle-bg" />
          <img src="{{ asset('assets/assets/img/illustrations/tree.png') }}" alt="auth-tree" class="authentication-image-object-right d-none d-lg-block" />
        </div>
      </div>
    </div>
    <!-- / Content -->

    <!-- Core JS -->
    <script src="{{ asset('assets/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/assets/js/main.js') }}"></script>
  </body>
</html>




