@extends('layouts.app')

@section('content')
  @include('pos.partials.navbar')

  <main class="max-w-7xl mx-auto p-3 sm:p-5 w-full flex-1">
    @include('pos.tabs.kasir')
    @include('pos.tabs.master')
    @include('pos.tabs.history')
    @include('pos.tabs.kasbon')
  </main>

  @include('pos.modals.categories')
  @include('pos.modals.nominal')
  @include('pos.modals.hold-confirm')
  @include('pos.modals.hold-queue')
  @include('pos.modals.checkout')
  @include('pos.modals.receipt')
  @include('pos.modals.product')
  @include('pos.modals.restock')
  @include('pos.modals.pay-kasbon')

  @include('pos.partials.toast')
@endsection

@push('scripts')
  {{-- Urutan penting: config/utils/state dulu, main paling akhir --}}
  @foreach (['config','utils','state','categories','products','cart','nominal','hold','checkout','receipt','master','history','kasbon','shortcuts','main'] as $js)
    <script src="{{ asset("js/pos/{$js}.js") }}"></script>
  @endforeach
@endpush
