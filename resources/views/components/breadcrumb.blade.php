@props(['items' => []])
<div class="max-w-4xl mx-auto px-4 pt-6 pb-2 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-400">
    <a href="/" class="hover:text-gray-700 transition-colors">Inicio</a>
    @foreach($items as $label => $url)
    <span>›</span>
    @if($url)
    <a href="{{ $url }}" class="hover:text-gray-700 transition-colors">{{ $label }}</a>
    @else
    <span class="text-gray-600 font-medium">{{ $label }}</span>
    @endif
    @endforeach
</div>
