@props(['id', 'size' => 'max-w-sm'])

<div id="{{ $id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
  <div {{ $attributes->merge(['class' => "bg-white rounded-2xl w-full {$size} shadow-2xl overflow-hidden"]) }}>
    {{ $slot }}
  </div>
</div>
