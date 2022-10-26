@extends('client')
@section('content')
  <div class="my-14 grid md:grid-cols-3 grid-cols-1 gap-7 px-8 md:px-0">
    @foreach ($posts as $post)
      <div class="flex flex-col">
        <div class="flex-1">
          <img src="{{ $post->thumbnail_photo }}" alt="Picture"
            class="h-52 rounded-[10px] object-cover object-center col-span-2 w-full" />

          <p class="text-[rgba(255,255,255,0.6)] mt-4">
            {{ $post->category->name }} - {{ date_format($post->created_at, 'F d, Y') }}
          </p>
          <a href="{{ route('post.show', ['post' => $post->slug]) }}" class="my-3 text-2xl">
            {{ $post->title }}
          </a>

          <p class="text-[rgba(255,255,255,0.6)] mb-5 text-base">
            {{ Str::words($post->first_paragraph, 20, '...') }}
          </p>
        </div>
        <div class="flex items-center">
          <img src="http://localhost:8000/images/Ellipse 1.png" alt="Profile"
            class="rounded-full h-12 w-12 object-cover object-center" />

          <div class="ml-4">
            <p>{{ $post->user->name }}</p>
            <p class="text-[rgba(255,255,255,0.6)] text-sm">
              Product Design
            </p>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  <div class="px-8 md:px-0">
    {{ $posts->links() }}
  </div>
  <div class="py-10">
    <p class="text-center">Copyright - Organtri</p>
  </div>
@endsection
