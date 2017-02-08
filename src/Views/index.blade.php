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
<form action="{{ route('settings.save') }}" method="POST">
	{{ csrf_field() }}
	@foreach($settings as $section => $setting)
		<h1>{{ title_case($section) }}</h1>
		<table class="ui selectable table">
			<thead>
				<tr>
					<th class="center aligned collapsing">Key</th>
					<th>Value</th>
				</tr>
			</thead>
			<tbody>
				@foreach($setting as $name => $field)
					<tr colspan="3">
						<td class="center aligned collapsing">
							{{ title_case(str_replace('_', ' ', $name)) }}
						</td>
						<td>
							@if(is_subclass_of($field, \Baytek\Laravel\Settings\Setting::class) && !is_null($field->possibilities()))
								<select name="{{ $section.'['.$name.']' }}">
									@foreach($field->possibilities() as $possibility)
										<option
										@if($possibility == config('cms.content.' . $section . '.' . $name)) selected @endif value="{{ $possibility }}">{{ title_case($possibility) }}</option>
									@endforeach
								</select>
							@else
								<input type="text" name="{{ $section.'['.$name.']' }}" value="{{ config('cms.content.' . $section . '.' . $name) }}" />
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endforeach

	<input type="submit">
</form>

@endsection