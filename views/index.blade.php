@extends('content::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="world icon"></i>
        <div class="content">
            Global Settings Management
            <div class="sub header">Manage the entire CMS's settings</div>
        </div>
    </h1>
@endsection

@section('content')

{{--!! $menu !!--}}
<form action="{{ route('settings.save') }}" method="POST" class="ui form">
	{{ csrf_field() }}

	@foreach($settings as $section => $setting)
        <h2>{{ title_case($section) }}</h2>
		@include('Settings::section')
	@endforeach

	<div class="ui hidden divider"></div>

	<button type="submit" class="ui right floated primary button">
        Save Settings
    </button>
</form>

@endsection