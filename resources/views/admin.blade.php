<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Laravel</title>
  @vite('resources/css/app.css')
</head>

<body>
  <div class="flex items-center px-4 md:hidden">
    <button type="button" id="hamburger">
      <span class="hamburger-line transition duration-300 ease-in-out origin-top-left"></span>
      <span class="hamburger-line transition duration-300 ease-in-out"></span>
      <span class="hamburger-line transition duration-300 ease-in-out origin-bottom-left"></span>
    </button>
  </div>
  <div class="fixed lg:w-64 md:w-1/4 h-screen md:block hidden bg-black">
    <div class="m-6 bg-white flex flex-row">

    </div>
  </div>
  @yield('content')
  <script src="{{ asset('js/jquery-3.6.1.min.js') }}" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
    crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/e4b2ccaaa5.js" crossorigin="anonymous"></script>
  <script>
    $("#hamburger").on("click", function() {
      if (!$("#nav").hasClass("top-full")) {
        $("#hamburger").addClass("hamburger-active");
        $("#nav").addClass("top-full");
        $("#nav").addClass("opacity-100");
        $("#nav").removeClass("opacity-0");
        $("#nav").removeClass("top-[-400px]");
      } else {
        $("#hamburger").removeClass("hamburger-active");
        $("#nav").removeClass("top-full");
        $("#nav").addClass("top-[-400px]");
        $("#nav").addClass("opacity-0");
        $("#nav").removeClass("opacity-100");
      }
    });
  </script>
  @stack('script')
</body>

</html>
