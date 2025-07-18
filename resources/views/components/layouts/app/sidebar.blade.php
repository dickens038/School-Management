<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    @if(auth()->check() && in_array(auth()->user()->role->name ?? '', ['it', 'it_department', 'it-staff', 'it_department_user', 'headmaster']))
                        <flux:navlist.item icon="layout-grid" :href="route('settings') . '#results-review'" :current="request()->is('settings*') && request()->getRequestUri() && str_contains(request()->getRequestUri(), 'results-review')" wire:navigate>
                            {{ __('Results Review') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="chart-line" :href="route('admin.school-performance.index')" :current="request()->routeIs('admin.school-performance.*')" wire:navigate>
                            {{ __('School Performance') }}
                        </flux:navlist.item>
                    @endif
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Teacher Menu')" class="grid">
                    @if(auth()->check() && (auth()->user()->role->name ?? '') === 'teacher')
                        <flux:navlist.item icon="home" :href="route('dashboard.teacher')" :current="request()->routeIs('dashboard.teacher')" wire:navigate>Dashboard</flux:navlist.item>
                        <flux:navlist.item icon="book-open-text" :href="route('teacher.assignments.index')" :current="request()->routeIs('teacher.assignments.*')" wire:navigate>Assignments</flux:navlist.item>
                        <flux:navlist.item icon="folder-git-2" :href="route('teacher.results.create')" :current="request()->routeIs('teacher.results.create')" wire:navigate>Send Results to IT</flux:navlist.item>
                        <flux:navlist.item icon="chevrons-up-down" :href="route('teacher.results.feedback')" :current="request()->routeIs('teacher.results.feedback')" wire:navigate>Results Feedback</flux:navlist.item>
                        <flux:navlist.item icon="layout-grid" :href="route('teacher.students.index')" :current="request()->routeIs('teacher.students.*')" wire:navigate>Manage Students</flux:navlist.item>
                        <flux:navlist.item icon="user-plus" :href="route('teacher.admissions.index')" :current="request()->routeIs('teacher.admissions.*')" wire:navigate>Student Admissions</flux:navlist.item>
                        <flux:navlist.item icon="layout-grid" :href="route('teacher.classes.index')" :current="request()->routeIs('teacher.classes.*')" wire:navigate>Manage Classes</flux:navlist.item>
                        <flux:navlist.item icon="layout-grid" :href="route('teacher.attendance.index')" :current="request()->routeIs('teacher.attendance.index')" wire:navigate>Attendance</flux:navlist.item>
                        <flux:navlist.item icon="layout-grid" :href="route('teacher.reports.index')" :current="request()->routeIs('teacher.reports.index')" wire:navigate>Reports</flux:navlist.item>
                        <flux:navlist.item icon="cog" :href="route('profile.show')" :current="request()->routeIs('profile.*')" wire:navigate>Profile</flux:navlist.item>
                    @endif
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    :src="auth()->user()->profilePhotoUrl()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    @if(auth()->user()->profilePhotoUrl())
                                        <img
                                            src="{{ auth()->user()->profilePhotoUrl() }}"
                                            alt="{{ auth()->user()->name }}"
                                            class="h-full w-full object-cover rounded-lg"
                                        />
                                    @else
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    @endif
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        <flux:menu.item :href="route('profile.show')" icon="user" wire:navigate>{{ __('Profile (Alternative)') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    :src="auth()->user()->profilePhotoUrl()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    @if(auth()->user()->profilePhotoUrl())
                                        <img
                                            src="{{ auth()->user()->profilePhotoUrl() }}"
                                            alt="{{ auth()->user()->name }}"
                                            class="h-full w-full object-cover rounded-lg"
                                        />
                                    @else
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    @endif
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        <flux:menu.item :href="route('profile.show')" icon="user" wire:navigate>{{ __('Profile (Alternative)') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
