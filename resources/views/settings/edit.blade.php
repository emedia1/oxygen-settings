@extends('oxygen::layouts.master-dashboard')

@section('content')
    {{ lotus()->pageHeadline($setting->name) }}

    {{ lotus()->breadcrumbs([
        ['Dashboard', route('dashboard')],
        ['Settings', route('settings.index')],
        [$setting->name, null, true]
    ]) }}

    <div class="row">
        <div class="{{ $setting->input_type == 'editor' ? 'col-12' : 'col-md-6' }}">
            <div class="card">
                <div class="card-header">
                    <span class="small text-muted">{{ $setting->description }}</span>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('settings.update', $setting->id) }}" enctype="multipart/form-data">
                        {{ method_field('put') }}
                        @csrf

                        <div class="form-group">
                            @if($setting->input_type == 'text')
                            <input id="setting-value" type="text" name="value" value="{{ old('value') ? old('value') : $setting->setting_value }}" class="form-control">
                            @endif

                            @if($setting->isTextareaInput())
                                @if($setting->input_type == 'list')
                                <div class="mb-2">
                                    Enter list items separated by newlines.
                                </div>
                                @endif
                            <textarea id="setting-value" name="value" class="form-control" rows="5" cols="80">{{ old('value') ? old('value') : $setting->setting_value }}</textarea>
                            @endif

                            @if($setting->isFileInput())
                            <input id="setting-value" type="file" name="value" value="" class="form-control">
                            @endif
                        </div>

                        <div class="buttons text-right mt-4">
                            <a href="{{ route('settings.index') }}" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @if($setting->input_type == 'editor')
    <script src="https://cdn.ckeditor.com/ckeditor5/11.2.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor.create(document.querySelector('#setting-value'), {
            removePlugins: ['Image', 'ImageUpload'],
            height: 300,
            resize: {
                minHeight: 300,
                maxHeight: 800
            }
        })
        .then(editor => {
            console.log( editor );
        })
        .catch(error => {
            console.error( error );
        });
    </script>
    @endif
@endpush

@push('stylesheets')
<style media="screen">
.ck-editor__editable {
    min-height: 200px;
}
</style>
@endpush
