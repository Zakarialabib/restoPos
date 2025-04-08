@extends('layouts.guest')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description)

@section('content')
<article class="container mx-auto px-4 py-8">
    @if($page->featured_image)
    <div class="mb-8">
        <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="w-full h-auto rounded-lg shadow-lg">
    </div>
    @endif

    <h1 class="text-3xl md:text-4xl font-bold mb-6">{{ $page->title }}</h1>
    
    <div class="prose max-w-none lg:prose-lg">
        {!! $page->content !!}
    </div>
</article>
@endsection 