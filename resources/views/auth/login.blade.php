<!doctype html>
<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-skin="default" data-bs-theme="light" data-assets-path="{{ asset('assets/assets/') }}" data-template="vertical-menu-template">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <title>Login - {{ config('app.name', 'Tour Admin') }}</title>
    
    <!-- Canonical SEO -->
    <meta name="description" content="{{ config('app.name', 'Tour Admin') }} - Login to your account" />
    <meta name="keywords" content="login, authentication, tour admin" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/assets/img/favicon/favicon.ico') }}" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/fonts/remixicon/remixicon.css') }}" />
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/assets/css/demo.css') }}" />
    
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor/css/pages/page-auth.css') }}" />
    
    <!-- Helpers -->
    <script src="{{ asset('assets/assets/vendor/js/helpers.js') }}"></script>
    
    <!--? Config: Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file. -->
    <script src="{{ asset('assets/assets/js/config.js') }}"></script>
    
    <style>
      /* Login Splash Screen */
      .login-splash {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #3ea572 0%, #2d7a5f 100%);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: #fff;
        transition: opacity 0.3s ease;
      }
      
      .login-splash.show {
        display: flex;
      }
      
      .login-splash.hide {
        opacity: 0;
        pointer-events: none;
      }
      
      .splash-content {
        text-align: center;
        width: 90%;
        max-width: 400px;
      }
      
      .splash-logo {
        width: 80px;
        height: 80px;
        margin: 0 auto 2rem;
        animation: pulse 2s infinite;
      }
      
      .splash-logo svg {
        width: 100%;
        height: 100%;
      }
      
      .splash-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
      }
      
      .splash-subtitle {
        font-size: 0.875rem;
        opacity: 0.9;
        margin-bottom: 2rem;
      }
      
      .progress-container {
        width: 100%;
        height: 8px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1rem;
      }
      
      .progress-bar {
        height: 100%;
        background: #fff;
        border-radius: 10px;
        width: 0%;
        transition: width 0.3s ease;
        position: relative;
        overflow: hidden;
      }
      
      .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shimmer 1.5s infinite;
      }
      
      .progress-text {
        font-size: 0.875rem;
        opacity: 0.8;
        margin-top: 0.5rem;
      }
      
      @keyframes pulse {
        0%, 100% {
          transform: scale(1);
          opacity: 1;
        }
        50% {
          transform: scale(1.05);
          opacity: 0.9;
        }
      }
      
      @keyframes shimmer {
        0% {
          transform: translateX(-100%);
        }
        100% {
          transform: translateX(100%);
        }
      }
      
      .splash-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s linear infinite;
        margin-right: 0.5rem;
      }
      
      @keyframes spin {
        to { transform: rotate(360deg); }
      }
    </style>
</head>
<body>
    <!-- Login Splash Screen -->
    <div class="login-splash" id="loginSplash">
      <div class="splash-content">
        <div class="splash-logo">
          <img src="{{ asset('lau-adventuress.png') }}" alt="Lau Paradise Adventures" style="width: 100%; height: 100%; object-fit: contain;" />
            </div>
        <h3 class="splash-title">{{ config('app.name', 'Tour Admin') }}</h3>
        <p class="splash-subtitle">Signing you in...</p>
        <div class="progress-container">
          <div class="progress-bar" id="loginProgressBar"></div>
        </div>
        <div class="progress-text">
          <span class="splash-spinner"></span>
          <span id="loginProgressText">Authenticating...</span>
                                        </div>
                                    </div>
                                </div>
    <!-- Content -->
    <div class="position-relative">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-6 mx-4">
          <!-- Login -->
          <div class="card p-sm-7 p-2">
            <!-- Logo -->
            <div class="app-brand justify-content-center mt-5">
              <a href="{{ route('home') }}" class="app-brand-link gap-3">
                <span class="app-brand-logo demo">
                  <img src="{{ asset('lau-adventuress.png') }}" alt="Lau Paradise Adventures" style="height: 48px; width: auto;" />
                </span>
               </a>
                                    </div>
            <!-- /Logo -->
            <div class="card-body mt-1">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
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

            </div>
          </div>
          <!-- /Login -->
          <img src="{{ asset('assets/assets/img/illustrations/tree-3.png') }}" alt="auth-tree" class="authentication-image-object-left d-none d-lg-block" />
          <img src="{{ asset('assets/assets/img/illustrations/auth-basic-mask-light.png') }}" class="authentication-image d-none d-lg-block scaleX-n1-rtl" height="172" alt="triangle-bg" data-app-light-img="illustrations/auth-basic-mask-light.png" data-app-dark-img="illustrations/auth-basic-mask-dark.png" />
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
    
    <!-- Main JS -->
    <script src="{{ asset('assets/assets/js/main.js') }}"></script>
    
    <!-- Page JS -->
    <script>
      // Password toggle functionality
      document.querySelectorAll('.form-password-toggle .input-group-text').forEach(function(element) {
        element.addEventListener('click', function() {
          const passwordInput = this.closest('.form-password-toggle').querySelector('input[type="password"]');
          const icon = this.querySelector('i');
          
          if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('ri-eye-off-line');
            icon.classList.add('ri-eye-line');
          } else {
            passwordInput.type = 'password';
            icon.classList.remove('ri-eye-line');
            icon.classList.add('ri-eye-off-line');
          }
        });
      });
      
      // Login Splash Screen with Progress - Only on successful login
      (function() {
        const splash = document.getElementById('loginSplash');
        const progressBar = document.getElementById('loginProgressBar');
        const progressText = document.getElementById('loginProgressText');
        const form = document.getElementById('formAuthentication');
        const errorAlert = document.querySelector('.alert-danger');
        
        let progressInterval;
        let currentProgress = 0;
        let isSubmitting = false;
        
        // Progress messages
        const progressMessages = [
          { progress: 10, text: 'Credentials verified...' },
          { progress: 25, text: 'Loading user profile...' },
          { progress: 40, text: 'Preparing dashboard...' },
          { progress: 60, text: 'Loading preferences...' },
          { progress: 80, text: 'Almost there...' },
          { progress: 95, text: 'Finalizing...' },
          { progress: 100, text: 'Success! Redirecting...' }
        ];
        
        function updateProgress(targetProgress) {
          if (targetProgress > currentProgress) {
            const increment = 2; // Smooth increment
            const interval = setInterval(() => {
              currentProgress = Math.min(currentProgress + increment, targetProgress);
              progressBar.style.width = currentProgress + '%';
              
              // Update message
              const messageObj = progressMessages.find(m => m.progress >= currentProgress);
              if (messageObj && messageObj.progress <= currentProgress + 2) {
                progressText.textContent = messageObj.text;
              }
              
              if (currentProgress >= targetProgress) {
                clearInterval(interval);
              }
            }, 40);
          }
        }
        
        function showSplash() {
          splash.classList.add('show');
          splash.classList.remove('hide');
          currentProgress = 0;
          progressBar.style.width = '0%';
          progressText.textContent = 'Login successful!';
          
          // Animate progress from 0 to 100%
          let step = 0;
          progressInterval = setInterval(() => {
            if (step < progressMessages.length) {
              const msg = progressMessages[step];
              updateProgress(msg.progress);
              step++;
            } else {
              clearInterval(progressInterval);
            }
          }, 400);
        }
        
        
        // Handle form submission with AJAX
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          
          if (isSubmitting) {
            return false;
          }
          
          isSubmitting = true;
          
          // Remove previous errors
          if (errorAlert) {
            errorAlert.remove();
          }
          
          // Get form data
          const formData = new FormData(form);
          const submitButton = form.querySelector('button[type="submit"]');
          const originalButtonText = submitButton.innerHTML;
          submitButton.disabled = true;
          submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Checking...';
          
          // Submit via AJAX
          fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || formData.get('_token')
            }
          })
          .then(response => {
            return response.json().then(data => {
              return { ok: response.ok, status: response.status, data: data };
            }).catch(() => {
              // If response is not JSON
              if (response.ok || response.redirected) {
                return { ok: true, status: response.status, data: { success: true } };
              }
              return { ok: false, status: response.status, data: { message: 'Invalid credentials' } };
            });
          })
          .then(({ ok, status, data }) => {
            if (ok && data.success) {
              // Login successful - show splash and redirect
              const redirectUrl = data.redirect || '/admin/dashboard';
              showSplash();
              
              // Wait for progress to complete, then redirect
              const checkProgress = setInterval(() => {
                if (currentProgress >= 100) {
                  clearInterval(checkProgress);
                  window.location.href = redirectUrl;
                }
              }, 100);
            } else {
              // Login failed - show error without splash
              isSubmitting = false;
              submitButton.disabled = false;
              submitButton.innerHTML = originalButtonText;
              
              // Show error message
              const errorMessage = data.message || data.errors?.email?.[0] || data.errors?.password?.[0] || 'Invalid email or password. Please try again.';
              
              // Create error alert
              const alertDiv = document.createElement('div');
              alertDiv.className = 'alert alert-danger';
              alertDiv.innerHTML = `<ul class="mb-0"><li>${errorMessage}</li></ul>`;
              
              // Insert before form
              form.parentNode.insertBefore(alertDiv, form);
              
              // Scroll to error
              alertDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
          })
          .catch(error => {
            // Network or other error
            isSubmitting = false;
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            
            console.error('Login error:', error);
            
            // Show error message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger';
            alertDiv.innerHTML = '<ul class="mb-0"><li>Network error. Please check your connection and try again.</li></ul>';
            
            form.parentNode.insertBefore(alertDiv, form);
            alertDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
          });
          
          return false;
        });
      })();
    </script>
</body>
</html>
