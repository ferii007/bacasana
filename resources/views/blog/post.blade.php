@extends('main')

@section('mainContent')
    <main class="container mx-auto py-8 text-white">
        <section class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-8 divide-x">
                <article class="col-span-2">
                    <h1 class="text-3xl font-bold mb-2">{{ $blogPost->title }}</h1>
                    <div class="mb-4">
                        <p>BacaSana</p>
                        <p class="text-gray-400">{{ $blogPost->created_at->translatedFormat('l, d F Y') }} · {{ $blogPost->read_duration }} menit dibaca</p>
                    </div>

                    <img src="https://via.placeholder.com/1000x600" alt="Artikel Singkat" class="w-full object-cover rounded-md mb-4">

                    <section class="mb-2">
                        <p>{{ $blogPost->content }}</p>
                    </section>

                    <section class="my-8">
                        <ul class="flex gap-3">
                            @foreach ($blogPostCategory as $postCategory)
                                <li>
                                    <a href="#" class="py-1 px-3 bg-slate-700 rounded-full hover:bg-transparent hover:text-slate-300 transition-all duration-300">
                                        {{ $postCategory->category_name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>

                    <section class="flex items-center space-x-4">
                        <p class="text-2xl">Bagikan artikel ini:</p>
                        <a href="#" class="w-7 h-7">
                            <i class="w-full h-full fa-brands fa-whatsapp"></i>
                        </a>

                        <a href="#" class="w-7 h-7">
                            <i class="w-full h-full fa-brands fa-facebook"></i>
                        </a>

                        <a href="#" class="w-7 h-7">
                            <i class="w-full h-full fa-brands fa-instagram"></i>
                        </a>

                        <a href="#" class="w-7 h-7">
                            <i class="w-full h-full fa-brands fa-square-x-twitter"></i>
                        </a>
                    </section>
                </article>

                <sidebar class="col-span-1 sticky top-8 h-max">
                    <div class="p-4">
                        <img src="https://via.placeholder.com/400x200" alt="Iklan" class="w-full object-cover rounded-lg mb-4">
                        
                        <p class="text-2xl font-bold mb-2">Artikel Terbaru</p>

                        @foreach ($latestBlogs as $latestBlog)    
                            <div>
                                <a href="{{ route('blogPost', $latestBlog->slug) }}" class="flex">
                                    <img src="https://via.placeholder.com/200x100" alt="Artikel Terbaru 1" class="w-2/5 object-cover rounded-lg mb-4 mr-2">
                                    <p class="text-sm font-bold">{{ $latestBlog->title }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </sidebar>
            </div>

            <hr class="w-full mx-auto" />
        </section>

        <section class="p-8">
            <h1 class="text-3xl font-bold mb-4">Artikel Lainnya</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
                @foreach ($randomBlogs as $randomBlog)
                    <article class="rounded-lg">
                        <a href="{{ route('blogPost', $randomBlog->slug) }}">
                            <img src="https://via.placeholder.com/500x300" alt="Design mockup" class="w-full object-cover rounded-lg mb-4">
                            <div class="text-right">
                                <p class="text-sm text-gray-400">{{ $randomBlog->created_at->translatedFormat('l, d F Y') }}</p>
                            </div>
                            <p class="font-bold text-xl">{{ $randomBlog->title }}</p>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
    </main>
@endsection

@section('additionalScripts')

@endsection