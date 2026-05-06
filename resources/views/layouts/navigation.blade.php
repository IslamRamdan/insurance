<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center border-l border-gray-100 pl-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-8 w-auto fill-current text-gray-900" />
                        <span class="text-lg font-bold tracking-tight text-gray-900">VisaFlow<span
                                class="text-green-600">Pro</span></span>
                    </a>
                </div>

                <!-- روابط التنقل الأساسية (كل الروابط تظهر هنا) -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-6 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="px-3 py-2 text-sm font-medium">
                        <i class="fas fa-home ml-2 opacity-70"></i> {{ __('Dashboard') }}
                    </x-nav-link>



                    <x-nav-link :href="route('visa.transactions')" :active="request()->routeIs('visa.transactions')" class="px-3 py-2 text-sm font-medium">
                        <i class="fas fa-history ml-2 opacity-70"></i> المعاملات المالية
                    </x-nav-link>
                </div>
            </div>

            <!-- الجانب الأيمن: الرصيد والإجراءات السريعة -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                <!-- ويدجت الرصيد -->
                <div
                    class="flex items-center bg-gray-50 border border-gray-200 rounded-lg px-4 py-1.5 transition-all hover:bg-gray-100">
                    <div class="flex flex-col items-end leading-none">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">الرصيد</span>
                        <div class="flex items-center gap-1">
                            <span class="text-sm font-bold text-gray-900">{{ auth()->user()->visa_balance ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="w-px h-6 bg-gray-200 mx-3"></div>
                    <a href="{{ route('visa.recharge.view') }}"
                        class="text-green-600 hover:text-green-700 transition-transform hover:scale-110"
                        title="شحن الرصيد">
                        <i class="fas fa-plus-circle text-lg"></i>
                    </a>
                </div>

                <!-- زر تسجيل سريع -->
                <a href="{{ route('visa_requests.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-300 transition ease-in-out duration-150 shadow-sm">
                    <i class="fas fa-user-plus ml-2"></i> عميل جديد
                </a>

                <!-- Dropdown الإعدادات -->
                <div class="ms-3 relative border-r border-gray-100 pr-3">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-gray-500 bg-white hover:text-gray-900 transition ease-in-out duration-150">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="ms-2 h-4 w-4 fill-current opacity-50" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')"> <i class="fas fa-user-cog ml-2 opacity-70"></i>
                                {{ __('Profile') }} </x-dropdown-link>
                            <hr class="border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <span class="text-red-600"><i class="fas fa-sign-out-alt ml-2 opacity-70"></i>
                                        {{ __('Log Out') }}</span>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-50 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-gray-50 border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"> لوحة التحكم </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('visa.transactions')"> سجل المعاملات </x-responsive-nav-link>
        </div>
    </div>
</nav>
