<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'POS Warung Paing')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

  {{-- Tailwind CDN (prototipe). Untuk production sebaiknya pakai build Vite/CLI --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="{{ asset('js/tailwind-config.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
  @stack('head')
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col select-none antialiased">
  @yield('content')

  <script src="https://unpkg.com/lucide@0.469.0"></script>
  <script>
    window.POS = {
      apiBase: '{{ url('/api/v1') }}',
      csrf: '{{ csrf_token() }}',
    };
  </script>
  @stack('scripts')
</body>
</html>
