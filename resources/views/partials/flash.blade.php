@php
  $types = [
    'ok'    => ['label' => '成功', 'bg' => 'bg-emerald-600'],
    'error' => ['label' => 'エラー', 'bg' => 'bg-rose-600'],
    'warn'  => ['label' => '注意', 'bg' => 'bg-amber-600'],
    'info'  => ['label' => '情報', 'bg' => 'bg-sky-600'],
  ];
@endphp

@if ($errors->any())
  <div data-flash class="fixed right-4 bottom-4 z-50 text-white px-4 py-3 rounded shadow {{ $types['error']['bg'] }}" role="alert" aria-live="assertive">
    <div class="font-semibold">{{ $types['error']['label'] }}</div>
    <ul class="mt-1 list-disc list-inside text-sm">
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
    <button type="button" class="absolute top-1.5 right-2 text-white/80" onclick="this.closest('[data-flash]')?.remove()">×</button>
  </div>
@endif

@foreach ($types as $key => $style)
  @if (session()->has($key))
    @php $msgs = (array) session($key); @endphp
    @foreach ($msgs as $msg)
      <div data-flash class="fixed right-4 bottom-4 mb-2 z-50 text-white px-4 py-3 rounded shadow {{ $style['bg'] }}" role="status" aria-live="polite">
        <span class="font-semibold">{{ $style['label'] }}</span>
        <div class="mt-0.5 text-sm">{{ $msg }}</div>
        <button type="button" class="absolute top-1.5 right-2 text-white/80" onclick="this.closest('[data-flash]')?.remove()">×</button>
      </div>
    @endforeach
  @endif
@endforeach

<script>
  (function(){
    const els = document.querySelectorAll('[data-flash]');
    els.forEach((el, i) => setTimeout(() => el.remove(), 2400 + i * 150));
  })();
</script>
