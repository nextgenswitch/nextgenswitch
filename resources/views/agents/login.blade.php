<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EasyPBX - Agent Login</title>

    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Map your design tokens to Tailwind color utilities -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bg-canvas': 'var(--bg-canvas)',
                        'surface-card': 'var(--surface-card)',
                        'primary-blue': 'var(--primary-blue)',
                        'success-green': 'var(--success-green)',
                        'destructive-red': 'var(--destructive-red)',
                        'text-primary': 'var(--text-primary)',
                        'text-secondary': 'var(--text-secondary)',
                        'stroke-divider': 'var(--stroke-divider)',
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <style type="text/tailwindcss">
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Color Tokens from Dashboard */
        @layer base {
            :root {
                --bg-canvas: #F8FAFC;
                --surface-card: #FFFFFF;
                --primary-blue: #3B82F6;
                --success-green: #10B981;
                --destructive-red: #EF4444;
                --text-primary: #111827;
                --text-secondary: #6B7280;
                --stroke-divider: #E5E7EB;
            }
        }

        @layer utilities {
            .card-shadow {
                box-shadow: 0 4px 24px rgba(0, 0, 0, 0.10);
            }

            .small-shadow {
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            }

            .spinner {
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }
        }

        /* Offscreen decoy field style to absorb autofill */
        .offscreen {
            position: fixed !important;
            left: -10000px !important;
            top: auto !important;
            width: 1px !important;
            height: 1px !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        /* remove outline of the input fields */
        input:focus {
            outline: none;
        }
    </style>
</head>

<body class="bg-bg-canvas flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md">
        <!-- Top Brand Block -->
        <div class="text-center mb-8">
            {{-- <div
                class="inline-flex items-center justify-center bg-primary-blue h-16 w-16 rounded-2xl small-shadow mb-4">
                <!-- phone icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                </svg>
            </div> --}}
            <h1 class="text-2xl font-bold text-text-primary">EasyPBX</h1>
            <p class="text-text-secondary mt-1">Welcome back! Please sign in to continue.</p>
        </div>

        <!-- Login Card -->
        <div class="bg-surface-card rounded-2xl p-8 card-shadow">
            <form id="login-form" class="space-y-6" autocomplete="off" autocapitalize="off" spellcheck="false"
                action="{{ route('agent.login') }}" method="POST">
                @csrf

                <!-- Decoy fields (placed BEFORE real fields to satisfy autofill heuristics) -->
                <input type="text" class="offscreen" name="username" autocomplete="username" tabindex="-1"
                    aria-hidden="true" />
                <input type="password" class="offscreen" name="current-password" autocomplete="current-password"
                    tabindex="-1" aria-hidden="true" />

                <!-- Email/Agent ID Input -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-text-primary mb-2">
                        Agent ID
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <input id="email" name="username" type="text" inputmode="email" autocomplete="off"
                            autocapitalize="none" spellcheck="false" readonly data-lpignore="true" data-1p-ignore
                            class="block w-full rounded-lg border border-stroke-divider py-3 pl-10 pr-4 text-text-primary placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-blue sm:text-sm"
                            placeholder="1000" />
                        @if ($errors->has('username'))
                            <p class="mt-2 text-xs text-destructive-red">{{ $errors->first('username') }}</p>
                        @endif
                    </div>
                    <p id="email-error" class="mt-2 text-xs text-destructive-red hidden">Enter a valid agent ID
                    </p>
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-text-primary mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 12V8a6 6 0 1 0-12 0v4m14 4-2 6H4l-2-6h18Z" />
                                <circle cx="12" cy="17" r="1" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="off" readonly
                            data-lpignore="true" data-1p-ignore
                            class="block w-full rounded-lg border border-stroke-divider py-3 pl-10 pr-10 text-text-primary placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-blue sm:text-sm"
                            placeholder="••••••••" />
                        <button type="button" id="password-toggle"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"
                            aria-label="Show password" aria-pressed="false">
                            <svg id="eye-icon" class="h-5 w-5 text-gray-400 hover:text-text-primary"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg id="eye-off-icon" class="h-5 w-5 text-gray-400 hover:text-text-primary hidden"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68">
                                </path>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                <line x1="2" x2="22" y1="2" y2="22"></line>
                            </svg>
                        </button>
                    </div>
                    <p id="password-error" class="mt-2 text-xs text-destructive-red hidden">Password cannot be empty
                    </p>
                </div>



                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" id="submit-button"
                        class="flex w-full h-[44px] items-center justify-center rounded-lg bg-primary-blue px-4 py-3 text-sm font-semibold text-white small-shadow hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-blue transition-all duration-200 disabled:opacity-60 disabled:bg-blue-500">
                        <span class="button-text">Sign in</span>
                        <div class="spinner h-5 w-5 border-2 border-white border-t-transparent rounded-full hidden">
                        </div>
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-sm text-text-secondary mt-8">
            Don't have an account?
            <a href="#" class="font-semibold text-primary-blue hover:text-blue-500">Contact Administrator</a>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loginForm = document.getElementById('login-form');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            const submitButton = document.getElementById('submit-button');
            const buttonText = submitButton.querySelector('.button-text');
            const spinner = submitButton.querySelector('.spinner');

            const passwordToggle = document.getElementById('password-toggle');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');

            // === Autofill mitigation ===
            // Keep fields readonly until real user intent (prevents silent autofill injection)
            const unlockOnUserIntent = (el) => {
                const unlock = () => el.removeAttribute('readonly');
                el.addEventListener('pointerdown', unlock, {
                    once: true
                });
                el.addEventListener('focus', unlock, {
                    once: true
                });
                el.addEventListener('keydown', unlock, {
                    once: true
                });
            };
            unlockOnUserIntent(emailInput);
            unlockOnUserIntent(passwordInput);

            // Password visibility toggle
            passwordToggle.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';
                // Ensure field is editable when the user starts interacting through the toggle
                if (passwordInput.hasAttribute('readonly')) passwordInput.removeAttribute('readonly');
                passwordInput.type = isPassword ? 'text' : 'password';
                eyeIcon.classList.toggle('hidden', isPassword);
                eyeOffIcon.classList.toggle('hidden', !isPassword);
                passwordToggle.setAttribute('aria-pressed', isPassword);
                passwordInput.focus({
                    preventScroll: true
                });
            });

            // Form submission handler
            // loginForm.addEventListener('submit', (e) => {
            //     e.preventDefault();
            //     let isValid = true;

            //     // Reset states
            //     emailError.classList.add('hidden');
            //     emailInput.classList.remove('border-destructive-red', 'focus:ring-destructive-red');
            //     passwordError.classList.add('hidden');
            //     passwordInput.classList.remove('border-destructive-red', 'focus:ring-destructive-red');

            //     // Basic validation
            //     if (emailInput.value.trim() === '' || !emailInput.value.includes('@')) {
            //         emailError.classList.remove('hidden');
            //         emailInput.classList.add('border-destructive-red', 'focus:ring-destructive-red');
            //         isValid = false;
            //     }
            //     if (passwordInput.value.trim() === '') {
            //         passwordError.classList.remove('hidden');
            //         passwordInput.classList.add('border-destructive-red', 'focus:ring-destructive-red');
            //         isValid = false;
            //     }

            //     if (isValid) {
            //         // Show loading state
            //         buttonText.classList.add('hidden');
            //         spinner.classList.remove('hidden');
            //         submitButton.disabled = true;

            //         // Simulate API call
            //         setTimeout(() => {
            //             // On success, navigate to the dashboard
            //             window.location.href = './index.html';
            //         }, 1500);
            //     }
            // });
        });
    </script>
</body>

</html>
