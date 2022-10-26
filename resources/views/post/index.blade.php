@extends('client')
@section('content')
  <div class="flex flex-col items-center px-8 md:px-0">
    <p class="text-[rgba(255,255,255,0.6)] mb-3 mt-14">
      {{ $post->category->name }} - {{ date_format($post->created_at, 'F d, Y') }}
    </p>

    <h1 class="md:w-1/2 text-center text-2xl w-full px-8 md:px-0">
      {{ $post->title }}
    </h1>

    <div class="flex items-center my-7">
      <img src="http://localhost:8000/images/Ellipse 1.png" alt="Profile"
        class="rounded-full h-12 w-12 object-cover object-center" />

      <div class="ml-3">
        <p>{{ $post->user->name }}</p>
        <p class="text-[rgba(255,255,255,0.6)] text-sm">
          UI Designer
        </p>
      </div>
    </div>

    <div
      class="text-xl mb-10 prose prose-p:text-white prose-table:text-white prose-li:text-white prose-strong:text-white prose-a:text-purple-600 lg:prose-xl">
      {!! html_entity_decode($post->body) !!}
    </div>

    <div class="rounded-lg p-4 border border-white flex md:w-2/3 gap-2 items-center w-full">
      <div class="min-w-max h-24">
        <img src="https://picsum.photos/200" alt="" class="w-24 h-24 object-cover object-center flex-1">
      </div>
      <div class="">
        <p class="font-bold">{{ $post->user->name }}</p>
        <p>{{ $post->user->profile->biodata }}</p>
      </div>
    </div>
  </div>
  <div id="graphcomment"></div>
  <div class="py-10">
    <p class="text-center">Copyright - Organtri</p>
  </div>
  @push('script')
    <script>
      document.querySelectorAll('oembed[url]').forEach(element => {
        iframely.load(element, element.attributes.url.value);
      });
    </script>
    <script type="text/javascript">
      /* - - - CONFIGURATION VARIABLES - - - */

      var __semio__params = {
        graphcommentId: "Blog-Organtri", // make sure the id is yours

        behaviour: {
          // HIGHLY RECOMMENDED
          uid: '{{ $post->slug }}'
        },

        // configure your variables here

      }

      /* - - - DON'T EDIT BELOW THIS LINE - - - */

      function __semio__onload() {
        __semio__gc_graphlogin(__semio__params)
      }


      (function() {
        var gc = document.createElement('script');
        gc.type = 'text/javascript';
        gc.async = true;
        gc.onload = __semio__onload;
        gc.defer = true;
        gc.src = 'https://integration.graphcomment.com/gc_graphlogin.js?' + Date.now();
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(gc);
      })();
    </script>
  @endpush
@endsection
