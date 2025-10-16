<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('EasyPBX Agent Dashboard') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style type="text/tailwindcss">
        body {
            font-family: 'Inter', sans-serif;
        }


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
                --disabled-gray: #CBD5E1;
            }
        }


        @tailwind base;
        @tailwind components;
        @tailwind utilities;

        @layer utilities {
            .card-shadow {
                box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
            }

            .small-shadow {
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            }

            .toggle-bg:checked {
                background-color: var(--primary-blue);
            }

            .toggle-bg:checked:after {
                transform: translateX(100%);
            }

            .h1 {
                @apply text-4xl font-bold text-gray-900;
            }

            .section-title {
                @apply text-lg font-bold text-gray-900;
            }

            .body-text {
                @apply text-base font-medium text-gray-800;
            }

            .label-text {
                @apply text-sm font-semibold text-gray-700;
            }

            .caption-text {
                @apply text-xs font-normal text-gray-500;
            }

            .kpi-number {
                @apply text-base font-semibold text-gray-900;
            }


            .skeleton {
                @apply bg-gray-200 rounded-md animate-pulse;
            }

            .dial-pad-btn {
                @apply text-2xl font-semibold text-gray-700 bg-gray-100 rounded-lg h-16 w-full flex items-center justify-center hover:bg-gray-200 active:bg-gray-300 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500;
            }
        }
    </style>
</head>

<body class="bg-[#F8FAFC] text-[#111827]">
    <div class="min-h-screen flex flex-col p-6 container mx-auto">

        <header class="bg-white w-full rounded-2xl small-shadow z-10">
            <div class="mx-auto px-6 flex items-center justify-between h-[72px]">

                <div class="flex items-center gap-4">
                    <div class="relative">
                        <img class="h-10 w-10 rounded-full object-cover"
                            src="https://placehold.co/64x64/3B82F6/FFFFFF?text={{ substr(optional($sip_user->extension)->name, 0, 2) }}" alt="Atik Hassan's avatar">
                        <span
                            class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-[#10B981] ring-2 ring-white"></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-semibold text-gray-800">{{ optional($sip_user->extension)->name }}</span>
                        <span class="text-sm font-normal text-gray-600">{{ optional($sip_user->extension)->code }}</span>
                    </div>
                    
                    
                </div>


                <div class="flex items-center gap-2">
                    <!-- <button class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 hover:small-shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3B82F6] transition-all duration-200 disabled:opacity-50 disabled:bg-[#CBD5E1]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-4.44a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.88a2 2 0 0 0-.59-1.41l-4.44-4.44a2 2 0 0 0-1.41-.59z"/><path d="M18 2v6h6"/><path d="M12 18a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/><path d="M12 14v-1"/><path d="m14.535 15.465.88.88"/><path d="M15 12h1"/><path d="m14.535 8.535-.88.88"/><path d="M12 8V7"/><path d="m9.465 8.535.88.88"/><path d="M9 12h-1"/><path d="m9.465 15.465-.88.88"/></svg>
                        Settings
                    </button>
                    <button class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 hover:small-shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3B82F6] transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        Notifications
                    </button> -->
                    <form method="POST" action="{{ route('agent.logout') }}">
                        @csrf
                        <button
                            class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 hover:small-shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3B82F6] transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>


        <div class="w-full bg-white rounded-2xl small-shadow mt-6">
            <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-[#E5E7EB]">
                <div class="p-4 flex items-center gap-3">
                    <svg class="text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg>
                    <div>
                        <p class="kpi-number">128</p>
                        <p class="caption-text">Total Calls</p>
                    </div>
                </div>
                <div class="p-4 flex items-center gap-3">
                    <svg class="text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 8v4l3 3" />
                        <circle cx="12" cy="12" r="10" />
                    </svg>
                    <div>
                        <p class="kpi-number">3m 45s</p>
                        <p class="caption-text">Average Handle Time</p>
                    </div>
                </div>
                <div class="p-4 flex items-center gap-3">
                    <svg class="text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="m14.31 8 5.74 9.94" />
                        <path d="M9.69 8h11.48" />
                        <path d="m7.38 12 5.74-9.94" />
                        <path d="M9.69 16H3.95" />
                        <path d="m14.31 16 5.74-9.94" />
                    </svg>
                    <div>
                        <p class="kpi-number">6h 15m</p>
                        <p class="caption-text">Active Time</p>
                    </div>
                </div>
            </div>
        </div>


        <main class="flex-grow mt-6 grid grid-cols-12 gap-6">


            <div class="col-span-12 lg:col-span-3 space-y-6">
                <h2 class="section-title px-1">Queues</h2>

                <div class="bg-white rounded-2xl p-4 small-shadow">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">Sales</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-500" id="toggle1-status">Joined</span>
                            <label for="toggle1" class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" data-status-target="toggle1-status" id="toggle1"
                                    class="sr-only peer" checked>
                                <div
                                    class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-[#3B82F6] peer-checked:bg-[#3B82F6] after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white">
                                </div>
                            </label>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-3 border-t border-[#E5E7EB] pt-3">
                        <span class="font-medium text-gray-600">Handled:</span> 18 calls | <span
                            class="font-medium text-gray-600">Talk Time:</span> 45m | <span
                            class="font-medium text-gray-600">Waiting:</span> 05
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-4 small-shadow">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">Support</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-500" id="toggle2-status">Not Joined</span>
                            <label for="toggle2" class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" data-status-target="toggle2-status" id="toggle2"
                                    class="sr-only peer">
                                <div
                                    class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-[#3B82F6] peer-checked:bg-[#3B82F6] after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border after:border-gray-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white">
                                </div>
                            </label>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-3 border-t border-[#E5E7EB] pt-3">
                        <span class="font-medium text-gray-600">Handled:</span> 03 calls | <span
                            class="font-medium text-gray-600">Talk Time:</span> 12m | <span
                            class="font-medium text-gray-600">Waiting:</span> 02
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-4 small-shadow">
                    <div class="flex items-center justify-between">
                        <div class="skeleton h-5 w-20"></div>
                        <div class="skeleton h-6 w-11 rounded-full"></div>
                    </div>
                    <div class="mt-3 border-t border-[#E5E7EB] pt-3 space-y-2">
                        <div class="skeleton h-4 w-full"></div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 small-shadow text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M10.2 2.2c-1.1.2-2.1.7-3.1 1.5-1 1.1-1.5 2.5-1.5 4.1 0 1.4.5 2.7 1.4 3.8l-1.9 2.1c-.6.6-.7 1.5-.1 2.1s1.5.7 2.1.1l2-2.1c.5.2 1.1.4 1.7.5.3 0 .5.2.5.5v.3c0 .3.2.5.5.5h2c.3 0 .5-.2.5-.5v-.3c0-.3.2-.5.5-.5.6-.1 1.2-.3 1.7-.5l2 2.1c.6.6 1.5.7 2.1.1s.7-1.5.1-2.1l-1.9-2.1c.9-1.1 1.4-2.4 1.4-3.8 0 -1.6-.5-3-1.5-4.1-1-.8-2-1.3-3.1-1.5-1.1-.2-2.2,0-3.2.5-1.1-.5-2.2-.7-3.2-.5z" />
                        <path d="M6.3 15.3a6.8 6.8 0 0 1 11.4 0" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Queues Available</h3>
                    <p class="mt-1 text-sm text-gray-500">Contact your administrator to be assigned to a queue.</p>
                </div>
            </div>


            <div class="col-span-12 lg:col-span-5 flex justify-center items-start">
                <div class="bg-white rounded-2xl px-8 pt-8 pb-6 card-shadow w-full max-w-lg text-center">
                    <h1 id="agent-name" class="h1"> No Call Available</h1>
                    <p id="agent-phone" class="label-text text-gray-500 mt-1"></p>
                    <p id="agent-call-duration" class="text-lg font-medium text-gray-600 mt-4">00:00</p>


                    <div class="mt-6 max-w-xs mx-auto">
                        <div class="relative">
                            <input type="text" id="dial-input"
                                class="w-full text-center text-2xl font-semibold bg-gray-100 rounded-lg border-transparent focus:outline-none focus:ring-0 focus:border-transparent py-3 pr-10"
                                placeholder="Enter number...">
                            <button id="backspace-btn"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                aria-label="Backspace">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path>
                                    <line x1="18" y1="9" x2="12" y2="15"></line>
                                    <line x1="12" y1="9" x2="18" y2="15"></line>
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mt-4">
                            <button class="dial-pad-btn">1</button>
                            <button class="dial-pad-btn">2</button>
                            <button class="dial-pad-btn">3</button>
                            <button class="dial-pad-btn">4</button>
                            <button class="dial-pad-btn">5</button>
                            <button class="dial-pad-btn">6</button>
                            <button class="dial-pad-btn">7</button>
                            <button class="dial-pad-btn">8</button>
                            <button class="dial-pad-btn">9</button>
                            <button class="dial-pad-btn">*</button>
                            <button class="dial-pad-btn">0</button>
                            <button class="dial-pad-btn">#</button>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-center items-center gap-4">
                        <button
                            class="flex items-center justify-center gap-2 py-3 px-6 bg-[#EF4444] text-white rounded-lg text-sm font-semibold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 active:bg-red-700 small-shadow hover:shadow-lg transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                <line x1="2" x2="22" y1="2" y2="22" />
                            </svg>
                            Hang Up
                        </button>
                        <button id="transfer-button"
                            class="flex items-center justify-center gap-2 py-3 px-6 bg-[#3B82F6] text-white rounded-lg text-sm font-semibold hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 active:bg-blue-700 small-shadow hover:shadow-lg transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 14 20 9 15 4" />
                                <path d="M4 20v-7a4 4 0 0 1 4-4h12" />
                            </svg>
                            Transfer
                        </button>
                    </div>
                </div>
            </div>


            <div class="col-span-12 lg:col-span-4 space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-2xl p-5 small-shadow">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-[#EEF2FF] flex items-center justify-center text-[#4338CA] text-xl font-bold"
                            id="customer-avatar">

                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800" id="customer-name"></h3>
                            <p class="text-xs text-gray-500 font-medium" id="customer-tags"></p>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between items-center py-3 border-b border-[#E5E7EB]">
                            <span class="text-gray-500">Phone</span>
                            <span class="font-semibold text-gray-800" id="customer-phone">-</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-[#E5E7EB]">
                            <span class="text-gray-500">Email</span>
                            <span class="font-semibold text-gray-800" id="customer-email">-</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-[#E5E7EB]">
                            <span class="text-gray-500">Company</span>
                            <span class="font-semibold text-gray-800" id="customer-company">-</span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="text-gray-500">Open Tickets</span>
                            <span
                                class="font-semibold text-gray-800 bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full"
                                id="customer-open-tickets">0</span>
                        </div>
                    </div>

                    <div class="mt-4 border-t border-[#E5E7EB] pt-4">
                        <h4 class="font-bold text-base text-gray-800 mb-3">Recent Interactions</h4>
                        <h3 class="mt-2 text-sm font-medium text-gray-900" id="no-recent-interactions">No Recent Interactions</h3>
                        <ul class="space-y-4" id="recent-interactions">

                            <!-- <li class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#E7F8F1] flex-shrink-0 flex items-center justify-center">
                                    <svg class="text-[#10B981]" xmlns="http://www.w3.org/2000/svg" width="18"
                                        height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M15.2 3.8a2 2 0 0 1 2.8 0l2.2 2.2a2 2 0 0 1 0 2.8l-8.3 8.3c-.3.3-.7.5-1.1.5l-3.2.5c-.5.1-1-.4-1-.9l.5-3.2c.1-.4.2-.8.5-1.1Z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Note added to Ticket #4521</p>
                                    <p class="caption-text mt-0.5">2 hours ago</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#EEF2FF] flex-shrink-0 flex items-center justify-center">
                                    <svg class="text-[#6366F1]" xmlns="http://www.w3.org/2000/svg" width="18"
                                        height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                                        <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                                        <line x1="12" y1="19" x2="12" y2="23"></line>
                                        <line x1="8" y1="23" x2="16" y2="23"></line>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Outgoing call - Answered (16s)</p>
                                    <p class="caption-text mt-0.5">19 hours ago</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#FEE2E2] flex-shrink-0 flex items-center justify-center">
                                    <svg class="text-[#EF4444]" xmlns="http://www.w3.org/2000/svg" width="18"
                                        height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                        </path>
                                        <line x1="12" y1="9" x2="12" y2="13"></line>
                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Missed call from this number</p>
                                    <p class="caption-text mt-0.5">1 day ago</p>
                                </div>
                            </li> -->
                        </ul>
                    </div>
                </div>

                <!-- Recent Calls -->
                <div class="bg-white rounded-2xl p-1 small-shadow">
                    <h3 class="font-bold text-base text-gray-800 p-4">Recent Calls</h3>
                    <ul class="divide-y divide-[#E5E7EB]">
                        <li class="p-4 flex items-center gap-3 hover:bg-gray-50 cursor-pointer">
                            <svg class="text-[#10B981] flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 5v14" />
                                <path d="m19 12-7 7-7-7" />
                            </svg>
                            <div class="w-full">
                                <div class="flex justify-between">
                                    <p class="font-semibold text-sm text-gray-800">Alice Johnson</p>
                                    <p class="text-xs text-gray-500">2h ago</p>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-500">+1 888 123 4567</p>
                                    <span class="text-xs font-medium text-green-700">Answered (2m 15s)</span>
                                </div>
                            </div>
                        </li>
                        <li class="p-4 flex items-center gap-3 hover:bg-gray-50 cursor-pointer">
                            <svg class="text-[#6366F1] flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 19V5" />
                                <path d="m5 12 7-7 7 7" />
                            </svg>
                            <div class="w-full">
                                <div class="flex justify-between">
                                    <p class="font-semibold text-sm text-gray-800">Bob Williams</p>
                                    <p class="text-xs text-gray-500">5h ago</p>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-500">+1 888 987 6543</p>
                                    <span class="text-xs font-medium text-indigo-700">Answered (45s)</span>
                                </div>
                            </div>
                        </li>
                        <li class="p-4 flex items-center gap-3 hover:bg-gray-50 cursor-pointer">
                            <svg class="text-[#EF4444] flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 5v14" />
                                <path d="m19 12-7 7-7-7" />
                                <path d="m15.54 15.54-4.08-4.08" />
                            </svg>
                            <div class="w-full">
                                <div class="flex justify-between">
                                    <p class="font-semibold text-sm text-gray-800">Charlie Brown</p>
                                    <p class="text-xs text-gray-500">1d ago</p>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-500">+1 888 555 1212</p>
                                    <span class="text-xs font-medium text-red-700">Missed</span>
                                </div>
                            </div>
                        </li>
                        <!-- Loading Skeleton for Recent Calls -->
                        <li class="p-4 flex items-center gap-3">
                            <div class="skeleton w-5 h-5 rounded-full"></div>
                            <div class="w-full space-y-2">
                                <div class="flex justify-between">
                                    <div class="skeleton h-4 w-24"></div>
                                    <div class="skeleton h-3 w-12"></div>
                                </div>
                                <div class="flex justify-between">
                                    <div class="skeleton h-3 w-32"></div>
                                    <div class="skeleton h-3 w-20"></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <!-- Transfer Confirmation Modal -->
    <div id="transfer-modal"
        class="fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center hidden z-50 p-4 transition-opacity duration-300">
        <div class="bg-white rounded-2xl p-8 card-shadow w-full max-w-md transform transition-all duration-300 scale-95 opacity-0"
            id="modal-content">
            <h2 class="text-xl font-bold text-gray-900">Confirm Transfer</h2>
            <p class="mt-2 text-gray-600">Are you sure you want to transfer the call to <strong
                    id="transfer-number-display" class="text-gray-800"></strong>?</p>
            <div class="mt-8 flex justify-end gap-4">
                <button id="cancel-transfer-btn"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all">Cancel</button>
                <button id="confirm-transfer-btn"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-[#3B82F6] hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">Confirm
                    Transfer</button>
            </div>
        </div>
    </div>


    @include('agents.script')
</body>

</html>
