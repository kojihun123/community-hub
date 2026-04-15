<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield ('title', 'CommunityHub')</title>
  @vite (['resources/css/app.css', 'resources/js/app.js'])
  @auth
    <script>
      window.Laravel = {
        userId: {{ auth()->id() }},
      };
    </script>
  @endauth
  <script>
    window.Presence = {
      heartbeatUrl: '{{ route('home.presence.heartbeat') }}',
    };
  </script>
  <style>
    [x-cloak] {
      display: none !important;
    }
  </style>
  @stack ('styles')
</head>
<body class="min-h-screen bg-stone-50 text-zinc-900 antialiased">
  <div
    x-data="toastManager"
    x-on:toast.window="show($event.detail)"
    class="pointer-events-none fixed bottom-4 right-4 z-50 space-y-2"
  >
    <template x-for="toast in toasts" :key="toast.id">
      <div
        x-show="toast.visible"
        x-transition:enter="transform transition ease-out duration-200"
        x-transition:enter-start="translate-y-2 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transform transition ease-in duration-150"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-2 opacity-0"
        class="pointer-events-auto w-80 overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-lg"
      >
        <div class="space-y-1 p-4">
          <p class="text-sm font-semibold text-zinc-900" x-text="toast.title"></p>
          <p class="text-sm leading-6 text-zinc-600" x-text="toast.message"></p>
        </div>
      </div>
    </template>
  </div>

  <div class="flex min-h-screen flex-col">
    @include ('components.nav.main-nav')

    <div class="mx-auto w-full max-w-6xl flex-1 px-4 py-2 md:py-3">
      @include ('components.ui.flash-message')

      <main class="grid gap-6">
        @yield ('content')
      </main>
    </div>

    @include ('components.layout.footer')
  </div>

  @stack ('scripts')
</body>
</html>
