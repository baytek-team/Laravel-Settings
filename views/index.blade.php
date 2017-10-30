@extends('contents::admin')

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
        <h2 class="ui dividing header">{{ title_case($section) }}</h2>
		@include('settings::section')
	@endforeach

	<div class="ui hidden divider"></div>

    <div class="field actions">
        <a class="ui button" href="http://public.nebula.dock0/admin/settings">Cancel</a>

        <button type="submit" class="ui right floated primary button">
            Save Settings
        </button>
    </div>
</form>

@endsection