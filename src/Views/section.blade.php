@foreach($setting as $name => $field)
	@if(is_subclass_of($field, \Baytek\Laravel\Settings\Setting::class) && !$field instanceof \Baytek\Laravel\Settings\Types\ArraySetting)
		<div class="ui top attached segment" data-setting-type="{{$field->type}}" data-setting-name="{{$section}}-{{$name}}">
			<div class="ui top attached label">
				{{ title_case(str_replace('_', ' ', $name)) }}
			</div>

			@if(!is_null($field->possibilities))
				@if($field->type == 'select')
				<div class="fields">
					<div class="sixteen wide field">
						<!-- <label for="{{ 'cms.content.' . $section . '.' . $name }}">{{ title_case(str_replace('_', ' ', $name)) }}</label> -->
						<select class="ui dropdown nullable" id="{{ 'cms.content.' . $section . '.' . $name }}" name="{{ $section.'['.$name.']' }}">
							@foreach($field->possibilities as $possibility)
								<option
								@if($possibility == config('cms.content.' . $section . '.' . $name)) selected @endif value="{{ $possibility }}">{{ title_case($possibility) }}</option>
							@endforeach
						</select>
					</div>
				</div>
				@elseif($field->type == 'radio')
				<div class="grouped fields">
					<!-- <label>{{ title_case(str_replace('_', ' ', $name)) }}</label> -->
					@foreach($field->possibilities as $possibility)
						<div class="field">
							<div class="ui radio checkbox">
								<input id="{{ 'cms.content.' . $section . '.' . $name }}" type="radio" class="hidden" name="{{ $section.'['.$name.']' }}" value="{{ $possibility }}" @if($possibility == config('cms.content.' . $section . '.' . $name)) checked @endif/>
								<label for="{{ 'cms.content.' . $section . '.' . $name }}">{{ title_case($possibility) }}</label>
							</div>
						</div>
					@endforeach
				</div>
				@else
					Field Type unknown
				@endif
			@elseif($field->type == 'text' || $field->type == 'number' || $field->type == 'range')
				<div class="fields">
					<div class="sixteen wide field">
						<!-- <label for="{{ 'cms.content.' . $section . '.' . $name }}">{{ title_case(str_replace('_', ' ', $name)) }}</label> -->
						<input id="{{ 'cms.content.' . $section . '.' . $name }}"
							type="{{ $field->type }}"
							name="{{ $section.'['.$name.']' }}"
							value="{{ config('cms.content.' . $section . '.' . $name) }}"
							placeholder="{{ title_case(str_replace('_', ' ', $name)) }}">
					</div>
				</div>
			@elseif($field->type == 'richtext')
				<div class="fields">
					<div class="sixteen wide field">
						<!-- <label for="{{ 'cms.content.' . $section . '.' . $name }}">{{ title_case(str_replace('_', ' ', $name)) }}</label> -->
						<textarea id="{{ 'cms.content.' . $section . '.' . $name }}" class="editor"
							name="{{ $section.'['.$name.']' }}"
							placeholder="{{ title_case(str_replace('_', ' ', $name)) }}">{{ config('cms.content.' . $section . '.' . $name) }}</textarea>
					</div>
				</div>
			@elseif($field->type == 'radio')
				<div class="inline field">
					<div class="ui toggle checkbox">
						<input name="{{ $section.'['.$name.']' }}" type="checkbox" class="hidden" @if(config('cms.content.' . $section . '.' . $name)) checked @endif>
						<!-- <label>{{ title_case(str_replace('_', ' ', $name)) }}</label> -->
					</div>
				</div>
			@endif
		</div>
	@endif
@endforeach