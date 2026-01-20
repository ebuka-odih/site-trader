
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ env('APP_NAME') }} - Admin Dashboard</title>
  <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">
    
  <!-- Tailwind CSS CDN for immediate styling -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Pusher JS for real-time updates -->
  <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script>

    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark')
    }
</script>
  <style>
      .active {
          background-color: #4c4ce4;
          color: white;
      }
  </style>
  @livewireStyles

  <style>
    [x-cloak] { display: none !important; }
    
    /* Theme CSS Variables */
    :root {
        /* Light theme colors */
        --bg-primary: #ffffff;
        --bg-secondary: #f8fafc;
        --bg-tertiary: #f1f5f9;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --border-color: #e2e8f0;
        --border-hover: #cbd5e1;
        --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    }
    
    .dark {
        /* Dark theme colors */
        --bg-primary: #0f172a;
        --bg-secondary: #1e293b;
        --bg-tertiary: #334155;
        --text-primary: #f8fafc;
        --text-secondary: #cbd5e1;
        --text-muted: #94a3b8;
        --border-color: #334155;
        --border-hover: #475569;
        --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.3), 0 1px 2px -1px rgb(0 0 0 / 0.3);
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    
    ::-webkit-scrollbar-track {
        background: var(--bg-secondary);
    }
    
    ::-webkit-scrollbar-thumb {
        background: var(--text-muted);
        border-radius: 3px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: var(--text-secondary);
    }

    /* Active sidebar item styling */
    .active {
        background-color: #3b82f6;
        color: white;
    }

    .active:hover {
        background-color: #2563eb;
    }

    /* Smooth transitions */
    * {
        transition: all 0.2s ease-in-out;
    }
  </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900">




<nav class="fixed z-30 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      <div class="flex items-center justify-start">
        <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar" class="p-2 text-gray-600 rounded cursor-pointer lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
          <svg id="toggleSidebarMobileHamburger" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
          <svg id="toggleSidebarMobileClose" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
        <a href="{{ route('admin.dashboard') }}" class="flex ml-2 md:mr-24">
          <img src="{{ asset('img/logo.svg') }}" class="h-8 mr-3" alt="FlowBite Logo" />
          <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Admin</span>
        </a>
      </div>
      <div class="flex items-center">


          <button id="toggleSidebarMobileSearch" type="button" class="p-2 hidden text-gray-500 rounded-lg lg:hidden hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            <span class="sr-only">Search</span>

            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
          </button>




          <button id="theme-toggle" data-tooltip-target="tooltip-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
          </button>
          <div id="tooltip-toggle" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
              Toggle dark mode
              <div class="tooltip-arrow" data-popper-arrow></div>
          </div>

          <div class="flex items-center ml-3">
            <div>
              <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button-2" aria-expanded="false" data-dropdown-toggle="dropdown-2">
                <span class="sr-only">Open user menu</span>
                <img class="w-8 h-8 rounded-full" src="{{ asset('img/trader.jpg') }}" alt="user photo">
              </button>
            </div>

            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-2">
              <div class="px-4 py-3" role="none">
                <p class="text-sm text-gray-900 dark:text-white" role="none">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                    {{ auth()->user()->email }}
                </p>
              </div>
              <ul class="py-1" role="none">
                <li>
                  <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Settings</a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="icon ion-md-power"></i>
                                 <span>Sign Out</span>
                            </a>
                      </form>
                </li>
              </ul>
            </div>
          </div>
        </div>
    </div>
  </div>
</nav>
<div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">

<aside id="sidebar" class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width" aria-label="Sidebar">
  <div class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
      <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
        <ul class="pb-2 space-y-2">

          <!-- Dashboard Section -->
          <li>
            <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
              Main
            </div>
          </li>
          <li>
            <a href="{{ route('admin.dashboard') }}" class="flex {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M2 5a2 2 0 0 1 2-2h6v18H4a2 2 0 0 1-2-2zm12-2h6a2 2 0 0 1 2 2v5h-8zm0 11h8v5a2 2 0 0 1-2 2h-6z"/>
                </svg>
                <span class="ml-3" sidebar-toggle-item>Dashboard</span>
            </a>
          </li>
          <li>
            <a href="{{ route('user.dashboard') }}" target="_blank" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
                <span class="ml-3" sidebar-toggle-item>User Dashboard</span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.user.index') }}" class="{{ request()->routeIs('admin.user.index') ? "active" : '' }} flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 ">
              <x-gmdi-people-alt-tt class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"/>
                <span class="ml-3" sidebar-toggle-item>Users</span>
            </a>
          </li>

          <!-- Financial Section -->
          <li>
            <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
              Financial
            </div>
          </li>
          <li>
            <a href="{{ route('admin.transactions.deposits') }}" class="{{ request()->routeIs('admin.transactions.deposits') ? "active" : '' }} flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                <x-gmdi-money class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"/>
                <span class="ml-3" sidebar-toggle-item>Transactions</span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.payment-method.index') }}" class="{{ request()->routeIs('admin.payment-method.index') ? "active" : '' }} flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 ">
              <x-gmdi-pentagon class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"/>
                <span class="ml-3" sidebar-toggle-item>Payment Method</span>
            </a>
          </li>

          <!-- Trading Section -->
          <li>
            <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
              Management
            </div>
          </li>
          <li>
            <a href="{{ route('admin.trade.history') }}" class="{{ request()->routeIs('admin.trade.history') ? "active" : '' }} flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                <x-gmdi-analytics class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"/>
                <span class="ml-3" sidebar-toggle-item>Trade History</span>
            </a>
          </li>
          
          <!-- Copy Trade Dropdown -->
          <li>
            <button id="copyTradeDropdownBtn" type="button" class="w-full flex items-center justify-between p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
              <span class="flex items-center">
                <x-gmdi-person-pin-o class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"/>
                <span class="ml-3" sidebar-toggle-item>Copy Trade</span>
              </span>
              <svg id="copyTradeDropdownIcon" class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
            <ul id="copyTradeDropdown" class="ml-9 mt-1 space-y-1 hidden">
              <li>
                <a href="{{ route('admin.copy-trader.index') }}" class="{{ request()->routeIs('admin.copy-trader.*') ? "active" : '' }} flex items-center p-2 text-sm text-gray-900 rounded-lg hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                  <span>Copy Trader</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.copied-trades.index') }}" class="{{ request()->routeIs('admin.copied-trades.*') ? "active" : '' }} flex items-center p-2 text-sm text-gray-900 rounded-lg hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                  <span>Copied History</span>
                </a>
              </li>
            </ul>
          </li>

          
          <li>
            <a href="{{ route('admin.bot-trading.index') }}" class="{{ request()->routeIs('admin.bot-trading.*') ? "active" : '' }} flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span class="ml-3" sidebar-toggle-item>Bot Trading</span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.bot-templates.index') }}" class="{{ request()->routeIs('admin.bot-templates.*') ? "active" : '' }} flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/>
                </svg>
                <span class="ml-3" sidebar-toggle-item>Bot Templates</span>
            </a>
          </li>

          <li>
            <a href="{{ route('admin.mining.index') }}" class="{{ request()->routeIs('admin.mining.*') ? "active" : '' }} flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span class="ml-3" sidebar-toggle-item>Mining Management</span>
            </a>
          </li>

          <!-- AI Trader Dropdown -->
          <li>
            <button id="aiTraderDropdownBtn" type="button" class="w-full flex items-center justify-between p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
              <span class="flex items-center">
                <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span class="ml-3" sidebar-toggle-item>AI Trader</span>
              </span>
              <svg id="aiTraderDropdownIcon" class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
            <ul id="aiTraderDropdown" class="ml-9 mt-1 space-y-1 hidden">
              <li>
                <a href="{{ route('admin.ai-trader-plans.index') }}" class="{{ request()->routeIs('admin.ai-trader-plans.*') ? "active" : '' }} flex items-center p-2 text-sm text-gray-900 rounded-lg hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                  <svg class="w-4 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                  <span class="ml-3">Plans</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.ai-traders.index') }}" class="{{ request()->routeIs('admin.ai-traders.index') || request()->routeIs('admin.ai-traders.create') || request()->routeIs('admin.ai-traders.edit') ? "active" : '' }} flex items-center p-2 text-sm text-gray-900 rounded-lg hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                  <svg class="w-4 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                  </svg>
                  <span class="ml-3">Traders</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.ai-traders.management') }}" class="{{ request()->routeIs('admin.ai-traders.management') || request()->routeIs('admin.ai-traders.history') ? "active" : '' }} flex items-center p-2 text-sm text-gray-900 rounded-lg hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                  <svg class="w-4 h-4 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                  </svg>
                  <span class="ml-3">Management</span>
                </a>
              </li>
            </ul>
          </li>


          <script>
            document.addEventListener('DOMContentLoaded', function(){
              // Copy Trade Dropdown
              var btn = document.getElementById('copyTradeDropdownBtn');
              var menu = document.getElementById('copyTradeDropdown');
              var icon = document.getElementById('copyTradeDropdownIcon');
              if(btn && menu && icon){
                btn.addEventListener('click', function(){
                  var isHidden = menu.classList.contains('hidden');
                  if(isHidden){
                    menu.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                  } else {
                    menu.classList.add('hidden');
                    icon.style.transform = 'rotate(0deg)';
                  }
                });
              }

              // AI Trader Dropdown
              var aiTraderBtn = document.getElementById('aiTraderDropdownBtn');
              var aiTraderMenu = document.getElementById('aiTraderDropdown');
              var aiTraderIcon = document.getElementById('aiTraderDropdownIcon');
              if(aiTraderBtn && aiTraderMenu && aiTraderIcon){
                aiTraderBtn.addEventListener('click', function(){
                  var isHidden = aiTraderMenu.classList.contains('hidden');
                  if(isHidden){
                    aiTraderMenu.classList.remove('hidden');
                    aiTraderIcon.style.transform = 'rotate(180deg)';
                  } else {
                    aiTraderMenu.classList.add('hidden');
                    aiTraderIcon.style.transform = 'rotate(0deg)';
                  }
                });
              }
            });
          </script>

          <!-- System Section -->
          <li>
            <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
              System
            </div>
          </li>
          
          <li>
            <a href="{{ route('admin.notifications.index') }}" class="{{ request()->routeIs('admin.notifications.*') ? "active" : '' }} flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h6v-2H4v2zM4 11h6V9H4v2zM4 7h6V5H4v2zM4 3h6V1H4v2z"></path>
                </svg>
                <span class="ml-3" sidebar-toggle-item>Send Notifications</span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }} flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 ">
                <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path clip-rule="evenodd" fill-rule="evenodd" d="M8.34 1.804A1 1 0 019.32 1h1.36a1 1 0 01.98.804l.295 1.473c.497.144.971.342 1.416.587l1.25-.834a1 1 0 011.262.125l.962.962a1 1 0 01.125 1.262l-.834 1.25c.245.445.443.919.587 1.416l1.473.294a1 1 0 01.804.98v1.361a1 1 0 01-.804.98l-1.473.295a6.95 6.95 0 01-.587 1.416l.834 1.25a1 1 0 01-.125 1.262l-.962.962a1 1 0 01-1.262.125l-1.25-.834a6.953 6.953 0 01-1.416.587l-.294 1.473a1 1 0 01-.98.804H9.32a1 1 0 01-.98-.804l-.295-1.473a6.957 6.957 0 01-1.416-.587l-1.25.834a1 1 0 01-1.262-.125l-.962-.962a1 1 0 01-.125-1.262l.834-1.25a6.957 6.957 0 01-.587-1.416l-1.473-.294A1 1 0 011 10.68V9.32a1 1 0 01.804-.98l1.473-.295c.144-.497.342-.971.587-1.416l-.834-1.25a1 1 0 01.125-1.262l.962-.962A1 1 0 015.38 3.03l1.25.834a6.957 6.957 0 011.416-.587l.294-1.473zM13 10a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="ml-3" sidebar-toggle-item>Settings</span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.security') }}" class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 ">
              <x-gmdi-shield-moon-s  class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"/>
                <span class="ml-3" sidebar-toggle-item>Security</span>
            </a>
          </li>

        </ul>

      </div>
    </div>

  </div>
</aside>

<div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>

  <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">
    <main>
        @include('admin.layouts.alerts')
        @yield('content')
    </main>


    <p class="my-10 text-sm text-center text-gray-500">
        &copy; {{ Date('Y') }} <a href="https://flowbite.com/" class="hover:underline" target="_blank">{{ env('APP_NAME') }}</a>. All rights reserved.
    </p>

  </div>

</div>



@livewireScripts
<script src="{{ asset('src/app.bundle.js') }}"></script>
@stack('scripts')
</body>
</html>
