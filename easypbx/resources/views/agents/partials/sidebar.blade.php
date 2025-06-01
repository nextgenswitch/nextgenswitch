<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-brand d-flex justify-content-center align-items-center">
        <div class="brand-img">
            <i data-feather="command"></i>
        </div>
        <span class="brand-label">{{ env('APP_NAME') }}</span>
    </div>

    <ul class="app-menu">
        <li>
            <a class="app-menu__item @if (request()->is('admin')) active @endif d-flex align-items-center"
                href="{{ route('agent.dashboard') }}">
                <i data-feather="home"></i>
                <span class="app-menu__label">{{ __('Dashboard') }}</span>
            </a>
        </li>

    </ul>
</aside>
