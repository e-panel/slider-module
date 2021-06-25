@extends('core::page.features')
@section('inner-title', __('slider::general.create.title', ['attribute' => $title]) . ' - ')
@section('mSlider', 'opened')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
@endsection

@section('js')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/holder/2.9.4/holder.min.js"></script>
@endsection

@section('content')
	<section class="box-typical">

		{!! Form::open(['route' => "$prefix.store", 'autocomplete' => 'off', 'files' => true]) !!}

	    	@include('core::layouts.components.top', [
                'judul' => __('slider::general.create.title', ['attribute' => $title]),
                'subjudul' =>  __('slider::general.create.desc'),
                'kembali' => route("$prefix.index")
            ])
	    
	        <div class="card">
                @include("$view.form")
                @include('core::layouts.components.submit')
            </div>

	    {!! Form::close() !!}

	</section>
@endsection