@inject('settings', 'Baytek\Laravel\Settings\SettingsService')

@foreach($setting as $name => $field)
	@if(is_subclass_of($field, \Baytek\Laravel\Settings\Types\Setting::class) && !$field instanceof \Baytek\Laravel\Settings\Types\ArraySetting)
		@if(!is_null($field->possibilities))
			@if($field->type == 'select')
			<div class="fields">
				<div class="sixteen wide field">
					<label for="{{ $settings->key([$namespace, $section, $name]) }}">{{ $settings->label($field, $name) }}</label>
					<select class="ui dropdown nullable" id="{{ $settings->key([$namespace, $section, $name]) }}" name="{{ $section.'['.$name.']' }}">
						@foreach($field->possibilities as $possibility)
							<option
							@if($possibility == config($settings->key([$namespace, $section, $name]))) selected @endif value="{{ $possibility }}">{{ title_case($possibility) }}</option>
						@endforeach
					</select>
				</div>
			</div>
			@elseif($field->type == 'radio')
			<div class="grouped fields">
				<label>{{ $settings->label($field, $name) }}</label>
				@foreach($field->possibilities as $possibility)
					<div class="field">
						<div class="ui radio checkbox">
							<input id="{{ $settings->key([$namespace, $section, $name]) }}" type="radio" class="hidden" name="{{ $section.'['.$name.']' }}" value="{{ $possibility }}" @if($possibility == config($settings->key([$namespace, $section, $name]))) checked @endif/>
							<label for="{{ $settings->key([$namespace, $section, $name]) }}">{{ title_case($possibility) }}</label>
						</div>
					</div>
				@endforeach
			</div>
			@else
				Field Type unknown
			@endif
		@elseif($field->type == 'text')
			<div class="fields">
				<div class="sixteen wide field">
					<label for="{{ $settings->key([$namespace, $section, $name]) }}">{{ $settings->label($field, $name) }}</label>
					<input id="{{ $settings->key([$namespace, $section, $name]) }}"
						type="{{ $field->type }}"
						name="{{ $section.'['.$name.']' }}"
						value="{{ config($settings->key([$namespace, $section, $name])) }}"
						placeholder="{{ $settings->label($field, $name) }}"
						@if($field->attributes && is_array($field->attributes))
							@foreach($field->attributes as $key => $attribute)
								{{$key}}="{{$attribute}}"
							@endforeach
						@endif
						>
				</div>
			</div>

		@elseif($field->type == 'richtext')
			<div class="fields">
				<div class="sixteen wide field">
					<!-- <label for="{{ 'cms.content.' . $section . '.' . $name }}">{{ title_case(str_replace('_', ' ', $name)) }}</label> -->
					<textarea id="{{ $settings->key([$namespace, $section, $name]) }}" class="editor"
						name="{{ $section.'['.$name.']' }}"
						placeholder="{{ title_case(str_replace('_', ' ', $name)) }}">{{ config('cms.content.' . $section . '.' . $name) }}</textarea>
				</div>
			</div>
		@elseif($field->type == 'number' || $field->type == 'range')
			<div class="fields">
				<div class="sixteen wide field">
					<label for="{{ $settings->key([$namespace, $section, $name]) }}">{{ $settings->label($field, $name) }}</label>
					<input id="{{ $settings->key([$namespace, $section, $name]) }}"
						type="{{ $field->type }}"
						name="{{ $section.'['.$name.']' }}"
						value="{{ config($settings->key([$namespace, $section, $name])) }}"
						placeholder="{{ $settings->label($field, $name) }}"
						@if($field->attributes && is_array($field->attributes))
							@foreach($field->attributes as $key => $attribute)
								{{$key}}="{{$attribute}}"
							@endforeach
						@endif
						>
				</div>
			</div>
		@elseif($field->type == 'radio')
			<div class="inline field">
				<div class="ui toggle checkbox">
					<input name="{{ $section.'['.$name.']' }}" type="checkbox" class="hidden" @if(config($settings->key([$namespace, $section, $name]))) checked @endif>
					<label>{{ $settings->label($field, $name) }}</label>
				</div>
			</div>
		@endif
	@endif
@endforeach