<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield ('title', 'CommunityHub')</title>
  @vite (['resources/css/app.css', 'resources/js/app.js'])
  <style>
    [x-cloak] {
      display: none !important;
    }
  </style>
  @stack ('styles')
</head>
<body class="min-h-screen bg-stone-50 text-zinc-900 antialiased">
  <div class="flex min-h-screen flex-col">
    @include ('components.nav.main-nav')

    <div class="mx-auto w-full max-w-6xl flex-1 px-4 py-4 md:py-5">
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
