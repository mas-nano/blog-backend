<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Laravel</title>
  @vite('resources/css/app.css')
  <style>
    body {
      background: -webkit-gradient(linear,
          left top,
          left bottom,
          from(#374151),
          to(#111827)) fixed;
      height: 100vh;
      width: 100vw;
    }
  </style>
</head>

<body>
  <div class="container text-white">
    <div class="flex mt-[71px] items-center justify-between px-8">
      <a class="flex items-center" href="/">
        <span class="bg-[#4B5563] px-3 py-1 shadow-md mr-3">E</span>
        <span class="text-lg">Epictus</span>
      </a>

      <div class="md:flex gap-6 hidden">
        <span>Design</span>
        <span>Front-End</span>
        <span>Back-End</span>
      </div>

      <form method="GET" action="" class="bg-[rgba(31,41,55,0.6)] flex items-center py-1 px-3 rounded-full">
        <i class="fa-solid fa-magnifying-glass px-3 py-1"></i>
        <input type="text" name="search" id="search" placeholder="Search"
          class="py-1 px-2 bg-transparent focus:outline-none hidden md:block" />
      </form>
    </div>
    @yield('content')
  </div>
  <script src="https://kit.fontawesome.com/e4b2ccaaa5.js" crossorigin="anonymous"></script>
  <script charset="utf-8" src="//cdn.iframe.ly/embed.js?api_key={{ config('app.api_iframely_key') }}"></script>
  @stack('script')
</body>

</html>
