<div class="box-typical-body padding-panel">
	<div class="row">
		<div class="col-sm-8">
			<fieldset class="form-group">
				<label for="judul" class="form-label">
					{{ __('slider::general.form.title.label') }}
				</label>
				<div class="form-control-wrapper">
					{!! Form::text('judul', null, ['class' => 'form-control', 'autofocus', 'placeholder' => __('slider::general.form.title.placeholder')]) !!}
				</div>
			</fieldset>
			<fieldset class="form-group">
				<label for="keterangan" class="form-label">
					{{ __('slider::general.form.desc.label') }}
				</label>
				<div class="form-control-wrapper">
					{!! Form::text('keterangan', null, ['class' => 'form-control', 'placeholder' => __('slider::general.form.desc.placeholder')]) !!}
				</div>
			</fieldset>
		</div>
		<div class="col-sm-4">
			<fieldset class="form-group {{ $errors->first('file', 'form-group-error') }}">
				<label for="file" class="form-label">
					{{ __('slider::general.form.file.label') }} <span class="color-red">*</span>
				</label>
				<div class="fileinput fileinput-new" data-provides="fileinput">
					<div class="fileinput-new thumbnail" style="width: 200px; height: 100px;">
						@if(!isset($edit))
							<img data-src="holder.js/1430x500/auto" alt="...">
						@else
							<img src="{{ viewImg($edit->file, 'm') }}" alt="{{ $edit->judul }}">
						@endif
					</div>
					<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 100px;"></div>
					<div>
						<span class="btn btn-default btn-file">
							<span class="fileinput-new">{{ __('slider::general.form.file.select') }}</span>
							<span class="fileinput-exists">{{ __('slider::general.form.file.change') }}</span>
							{!! Form::file('file', ['class' => 'form-control', 'accept' => 'image/*']) !!}
						</span>
						<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">{{ __('slider::general.form.file.remove') }}</a>
					</div>
					{!! $errors->first('file', '<span class="text-muted"><small>:message</small></span>') !!}
				</div>
			</fieldset>
		</div>
	</div>
</div>