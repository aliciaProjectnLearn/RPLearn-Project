@extends('layouts.app')

@section('content')

<div class="content-body pt-5 px-4">

    <div class="page-header mb-4">
        <h2 class="fw-bold text-dark mb-1" style="text-align: center">Modul <span style="color: var(--orange)">Pembelajaran</span> Mu</h2>
    </div>

    {{-- FILTER tetap
    @include('partials._module_list') --}}

    {{-- CARD --}}
        <div class="module-cards" id="moduleCardsContainer">
            @include('partials._module_list', ['modules' => $modules])
        </div>

</div>

@endsection