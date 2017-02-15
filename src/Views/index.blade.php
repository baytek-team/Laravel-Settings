@extends('Content::admin')

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

{{--
@menu([
    'class' => 'ui pointing inverted menu',
    'wrapper' => 'div',
    'prepend' => '<div class="ui container inverted">',
    'append' => '</div>',
])
    @anchor('Anchor test', [
        'location' => '#testing',
        'class' => 'item'
    ])
    @anchor('Anchor test', [
        'location' => '#testing',
        'class' => 'item',
    ])
    @button('This is a test', [
        'location' => 'settings.index',
        'type' => 'route',
        'class' => 'ui item red button'
    ])
@endmenu

@menu([
    'wrapper' => 'ul'
])
    @anchor('Anchor test', [
        'location' => '#testing',
        'class' => 'item',
        'prepend' => '<li>',
        'append' => '</li>',
    ])
@endmenu

{!! $menu !!}
--}}
<form action="{{ route('settings.save') }}" method="POST" class="ui form">
	{{ csrf_field() }}

	@foreach($settings as $section => $setting)
		@include('Settings::section')
	@endforeach

	<div class="ui hidden divider"></div>



	<button type="submit" class="ui right floated primary button">
        Save Settings
    </button>
</form>

@endsection