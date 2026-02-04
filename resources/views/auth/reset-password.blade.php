<!doctype html>
<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-skin="default" data-bs-theme="light" data-assets-path="{{ asset('assets/assets/') }}" data-template="vertical-menu-template">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <title>Reset Password - {{ config('app.name', 'Tour Admin') }}</title>
    
    <!-- Canonical SEO -->
    <meta name="description" content="{{ config('app.name', 'Tour Admin') }} - Reset your password" />
    <meta name="keywords" content="reset password, change password, tour admin" />
    
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
        .password-strength-meter {
            height: 4px;
            background-color: #e0e0e0;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }
        .password-strength-meter-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }
        .password-strength-meter-fill.weak { background-color: #f44336; width: 33%; }
        .password-strength-meter-fill.fair { background-color: #ff9800; width: 66%; }
        .password-strength-meter-fill.good { background-color: #4caf50; width: 100%; }
        .password-strength-meter-fill.strong { background-color: #2196F3; width: 100%; }
        
        .password-requirements {
            margin-top: 12px;
            font-size: 0.875rem;
        }
        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .password-requirements li {
            padding: 4px 0;
            display: flex;
            align-items: center;
        }
        .password-requirements li i {
            margin-right: 8px;
            font-size: 14px;
        }
        .password-requirements li.valid {
            color: #4caf50;
        }
        .password-requirements li.invalid {
            color: #757575;
        }
        
        .password-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 4px 8px;
            z-index: 10;
        }
        .password-toggle-btn:hover {
            color: #495057;
        }
        .form-floating-outline {
            position: relative;
        }
    </style>
</head>
<body>
    <!-- Content -->
    <div class="position-relative">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-6 mx-4">
          <!-- Reset Password -->
          <div class="card p-sm-7 p-2">
            <!-- Logo -->
            <div class="app-brand justify-content-center mt-5">
              <a href="{{ route('home') }}" class="app-brand-link gap-3">
                <span class="app-brand-logo demo">
                  <span class="text-primary">
                    <svg width="30" height="24" viewBox="0 0 250 196" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M12.3002 1.25469L56.655 28.6432C59.0349 30.1128 60.4839 32.711 60.4839 35.5089V160.63C60.4839 163.468 58.9941 166.097 56.5603 167.553L12.2055 194.107C8.3836 196.395 3.43136 195.15 1.14435 191.327C0.395485 190.075 0 188.643 0 187.184V8.12039C0 3.66447 3.61061 0.0522461 8.06452 0.0522461C9.56056 0.0522461 11.0271 0.468577 12.3002 1.25469Z" fill="currentColor" />
                      <path opacity="0.077704" fill-rule="evenodd" clip-rule="evenodd" d="M0 65.2656L60.4839 99.9629V133.979L0 65.2656Z" fill="black" />
                      <path opacity="0.077704" fill-rule="evenodd" clip-rule="evenodd" d="M0 65.2656L60.4839 99.0795V119.859L0 65.2656Z" fill="black" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M237.71 1.22393L193.355 28.5207C190.97 29.9889 189.516 32.5905 189.516 35.3927V160.631C189.516 163.469 191.006 166.098 193.44 167.555L237.794 194.108C241.616 196.396 246.569 195.151 248.856 191.328C249.605 190.076 250 188.644 250 187.185V8.09597C250 3.64006 246.389 0.027832 241.935 0.027832C240.444 0.027832 238.981 0.441882 237.71 1.22393Z" fill="currentColor" />
                      <path opacity="0.077704" fill-rule="evenodd" clip-rule="evenodd" d="M250 65.2656L189.516 99.8897V135.006L250 65.2656Z" fill="black" />
                      <path opacity="0.077704" fill-rule="evenodd" clip-rule="evenodd" d="M250 65.2656L189.516 99.0497V120.886L250 65.2656Z" fill="black" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M12.2787 1.18923L125 70.3075V136.87L0 65.2465V8.06814C0 3.61223 3.61061 0 8.06452 0C9.552 0 11.0105 0.411583 12.2787 1.18923Z" fill="currentColor" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M12.2787 1.18923L125 70.3075V136.87L0 65.2465V8.06814C0 3.61223 3.61061 0 8.06452 0C9.552 0 11.0105 0.411583 12.2787 1.18923Z" fill="white" fill-opacity="0.15" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M237.721 1.18923L125 70.3075V136.87L250 65.2465V8.06814C250 3.61223 246.389 0 241.935 0C240.448 0 238.99 0.411583 237.721 1.18923Z" fill="currentColor" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M237.721 1.18923L125 70.3075V136.87L250 65.2465V8.06814C250 3.61223 246.389 0 241.935 0C240.448 0 238.99 0.411583 237.721 1.18923Z" fill="white" fill-opacity="0.3" />
                    </svg>
                  </span>
                </span>
                <span class="app-brand-text demo text-heading fw-semibold">{{ config('app.name', 'Materio') }}</span>
              </a>
            </div>
            <!-- /Logo -->
            <div class="card-body mt-1">
              <h4 class="mb-2">Reset Password 🔐</h4>
              <p class="mb-4">Enter your new password below</p>
              
              @if (session('status'))
                <div class="alert alert-success mb-4">
                  <i class="ri-checkbox-circle-line me-2"></i>
                  {{ session('status') }}
                </div>
              @endif

              @if($errors->any())
                <div class="alert alert-danger mb-4">
                  <ul class="mb-0">
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              
              <form id="formResetPassword" class="mb-5" action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div class="form-floating form-floating-outline mb-4">
                  <input type="email" class="form-control" id="email_display" value="{{ $email }}" disabled />
                  <label for="email_display">Email Address</label>
                </div>
                
                <div class="form-floating form-floating-outline mb-3" style="position: relative;">
                  <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter new password" required autofocus />
                  <label for="password">New Password</label>
                  <button type="button" class="password-toggle-btn" onclick="togglePassword('password')">
                    <i class="ri-eye-line" id="password-toggle-icon"></i>
                  </button>
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                
                <!-- Password Strength Meter -->
                <div class="password-strength-meter">
                  <div class="password-strength-meter-fill" id="strengthMeter"></div>
                </div>
                <small class="text-muted" id="strengthText">Password strength will appear here</small>
                
                <!-- Password Requirements -->
                <div class="password-requirements">
                  <ul>
                    <li id="req-length" class="invalid">
                      <i class="ri-close-circle-line"></i>
                      At least 8 characters
                    </li>
                    <li id="req-uppercase" class="invalid">
                      <i class="ri-close-circle-line"></i>
                      One uppercase letter
                    </li>
                    <li id="req-lowercase" class="invalid">
                      <i class="ri-close-circle-line"></i>
                      One lowercase letter
                    </li>
                    <li id="req-number" class="invalid">
                      <i class="ri-close-circle-line"></i>
                      One number
                    </li>
                    <li id="req-symbol" class="invalid">
                      <i class="ri-close-circle-line"></i>
                      One special character (!@#$%^&*)
                    </li>
                  </ul>
                </div>
                
                <div class="form-floating form-floating-outline mb-5" style="position: relative;">
                  <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required />
                  <label for="password_confirmation">Confirm New Password</label>
                  <button type="button" class="password-toggle-btn" onclick="togglePassword('password_confirmation')">
                    <i class="ri-eye-line" id="password_confirmation-toggle-icon"></i>
                  </button>
                  @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                
                <div id="password-match" class="mb-3" style="display: none;">
                  <small class="text-danger">
                    <i class="ri-close-circle-line me-1"></i>
                    Passwords do not match
                  </small>
                </div>
                
                <div class="mb-5">
                  <button class="btn btn-primary d-grid w-100" type="submit" id="submitBtn" disabled>
                    <i class="ri-lock-password-line me-2"></i>
                    Reset Password
                  </button>
                </div>
                <div class="text-center">
                  <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                    <i class="ri-arrow-left-line me-1"></i>
                    Back to login
                  </a>
                </div>
              </form>

            </div>
          </div>
          <!-- /Reset Password -->
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
    
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-toggle-icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            } else {
                field.type = 'password';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            }
        }
        
        function checkPasswordStrength(password) {
            let strength = 0;
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                symbol: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };
            
            // Update requirement indicators
            document.getElementById('req-length').className = requirements.length ? 'valid' : 'invalid';
            document.getElementById('req-length').querySelector('i').className = requirements.length ? 'ri-checkbox-circle-line' : 'ri-close-circle-line';
            
            document.getElementById('req-uppercase').className = requirements.uppercase ? 'valid' : 'invalid';
            document.getElementById('req-uppercase').querySelector('i').className = requirements.uppercase ? 'ri-checkbox-circle-line' : 'ri-close-circle-line';
            
            document.getElementById('req-lowercase').className = requirements.lowercase ? 'valid' : 'invalid';
            document.getElementById('req-lowercase').querySelector('i').className = requirements.lowercase ? 'ri-checkbox-circle-line' : 'ri-close-circle-line';
            
            document.getElementById('req-number').className = requirements.number ? 'valid' : 'invalid';
            document.getElementById('req-number').querySelector('i').className = requirements.number ? 'ri-checkbox-circle-line' : 'ri-close-circle-line';
            
            document.getElementById('req-symbol').className = requirements.symbol ? 'valid' : 'invalid';
            document.getElementById('req-symbol').querySelector('i').className = requirements.symbol ? 'ri-checkbox-circle-line' : 'ri-close-circle-line';
            
            // Calculate strength
            Object.values(requirements).forEach(req => {
                if (req) strength++;
            });
            
            // Update strength meter
            const meter = document.getElementById('strengthMeter');
            const strengthText = document.getElementById('strengthText');
            const submitBtn = document.getElementById('submitBtn');
            
            meter.className = 'password-strength-meter-fill';
            
            if (strength === 0) {
                meter.style.width = '0%';
                strengthText.textContent = 'Enter a password';
                strengthText.className = 'text-muted';
                submitBtn.disabled = true;
            } else if (strength <= 2) {
                meter.classList.add('weak');
                strengthText.textContent = 'Weak password';
                strengthText.className = 'text-danger';
                submitBtn.disabled = true;
            } else if (strength <= 3) {
                meter.classList.add('fair');
                strengthText.textContent = 'Fair password';
                strengthText.className = 'text-warning';
                submitBtn.disabled = true;
            } else if (strength <= 4) {
                meter.classList.add('good');
                strengthText.textContent = 'Good password';
                strengthText.className = 'text-info';
                // Check if all requirements met
                if (Object.values(requirements).every(r => r)) {
                    submitBtn.disabled = false;
                } else {
                    submitBtn.disabled = true;
                }
            } else {
                meter.classList.add('strong');
                strengthText.textContent = 'Strong password';
                strengthText.className = 'text-success';
                // Check if all requirements met
                if (Object.values(requirements).every(r => r)) {
                    submitBtn.disabled = false;
                } else {
                    submitBtn.disabled = true;
                }
            }
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const matchDiv = document.getElementById('password-match');
            const submitBtn = document.getElementById('submitBtn');
            
            if (passwordConfirmation.length > 0) {
                if (password !== passwordConfirmation) {
                    matchDiv.style.display = 'block';
                    submitBtn.disabled = true;
                } else {
                    matchDiv.style.display = 'none';
                    // Re-check strength to enable/disable button
                    checkPasswordStrength(password);
                }
            } else {
                matchDiv.style.display = 'none';
            }
        }
        
        // Event listeners
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });
        
        document.getElementById('password_confirmation').addEventListener('input', function() {
            checkPasswordMatch();
        });
        
        // Form submission validation
        document.getElementById('formResetPassword').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            
            if (password !== passwordConfirmation) {
                e.preventDefault();
                alert('Passwords do not match. Please try again.');
                return false;
            }
            
            // Final strength check
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                symbol: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };
            
            if (!Object.values(requirements).every(r => r)) {
                e.preventDefault();
                alert('Password does not meet all requirements. Please check the requirements below.');
                return false;
            }
        });
    </script>
</body>
</html>






