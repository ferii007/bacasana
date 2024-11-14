@extends('main')

@section('mainContent')
    <main class="container mx-auto py-8 text-white" id="home-content">
        <section class="hero flex flex-wrap justify-center py-8">
            <div class="w-full md:w-1/2 lg:w-3/5 xl:w-3/5 px-4">
                <img src="https://via.placeholder.com/1000x600" alt="Design mockup" class="w-full h-full object-cover rounded-lg">
            </div>

            <div class="w-full md:w-1/2 lg:w-2/5 xl:w-2/5 px-4">
                <a href="{{ route('blogPost', $blog->slug) }}">
                    <p class="text-sm text-gray-400">{{ $blog->created_at->translatedFormat('l, d F Y') }} | {{ $blog->created_at->diffForHumans() }}</p>

                    <h2 class="font-bold text-3xl mb-2">{{ $blog->title }}</h2>
                    
                    <p class="text-gray-400 mb-2">
                        {{ Str::of($blog->content)->trim('[]')->limit(100) }}
                    </p>
                </a>
                
                {{-- <div class="flex items-center">
                    <img src="https://via.placeholder.com/30x30" alt="Profile Picture" class="rounded-full mr-2">
                    
                    <div>
                        <h3 class="font-medium">Leslie Alexander</h3>
                        <p class="text-sm">UI Designer</p>
                    </div>
                </div> --}}
            </div>
        </section>

        <section class="py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-12">
                @foreach ($remainingBlogs as $blog)
                    <article class="rounded-lg px-4">
                        <a href="{{ route('blogPost', $blog->slug) }}">
                            <img src="https://via.placeholder.com/500x300" alt="Design mockup" class="w-full object-cover rounded-lg mb-4">
                            
                            <p class="text-right text-sm text-gray-400">{{ $blog->created_at->translatedFormat('l, d F Y') }} | {{ $blog->created_at->diffForHumans() }}</p>
                            
                            <h2 class="font-bold text-xl mb-2">{{ $blog->title }}</h2>
    
                            <p class="text-gray-400 mb-2">
                                {{ Str::of($blog->content)->trim('[]')->limit(100) }}
                            </p>
    
                            {{-- <div class="flex items-center">
                                <img src="https://via.placeholder.com/30x30" alt="Profile Picture" class="rounded-full mr-2">
                                
                                <div>
                                    <h3 class="font-medium">BacaSana</h3>
                                    <p class="text-sm">Blogger</p>
                                </div>
                            </div> --}}
                        </a>
                    </article>
                @endforeach
            </div>

            <div class="flex items-center justify-center mt-8">
                <button class="transition-all duration-300 bg-gray-700 px-5 py-3 rounded-xl shadow-lg hover:scale-110">Lihat Semua</button>
            </div>
        </section>

        <section class="flex flex-col md:flex-row items-center justify-between gap-4 px-4 py-8">
            <div class="flex flex-col items-start gap-4 w-full md:w-1/2">
                <h2 class="font-bold text-3xl mb-2">Download Aplikasi BacaSana</h2>
                <p class="text-gray-400 mb-2">Baca blog/artikel dengan mudah dan tanpa iklan. Download aplikasi BacaSana dan jelajahi dunia inspirasi dan berbagai informasi.</p>
                <button class="transition-all bg-gray-700 px-5 py-3 rounded-xl shadow-lg hover:scale-110">Download Sekarang</button>
            </div>
            <div class="w-full md:w-1/2">
                <img src="https://via.placeholder.com/500x300" alt="Epictetus App Illustration" class="w-full object-cover rounded-lg">
            </div>
        </section>
    </main>
@endsection

@section('additionalScripts')
    @vite(['resources/js/index.js'])
@endsection