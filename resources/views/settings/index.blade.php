@extends('oxygen::layouts.master-dashboard')

@section('pageMainActions')

@stop

@section('content')
    {{ lotus()->pageHeadline($pageTitle) }}

    {{ lotus()->breadcrumbs([
        ['Dashboard', route('dashboard')],
        [$pageTitle, null, true]
    ]) }}

    @include('oxygen::dashboard.partials.searchField')

    @if(count($settings))
    <table class="table ">
        <tbody>
            @foreach($settings as $setting)
            <tr>
                <td>
                    <div class="">
                        {{ $setting->name }}
                    </div>
                    <div class="small text-muted">
                        {{ $setting->description }}
                    </div>
                </td>
                <td class="value">
                    @if($setting->isFileInput())
                        <a href="{{ $setting->value }}" class="btn btn-sm btn-secondary" target="_blank">
                            <i class="fa fa-file"></i> Download
                        </a>
                    @elseif($setting->input_type == 'list')
                        @if(count($setting->value))
                        <ul>
                            @foreach($setting->value as $item)
                            <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                        @endif
                    @else
                        <pre>{{ \Illuminate\Support\Str::limit(strip_tags($setting->value), 50) }}</pre>
                    @endif
                </td>
                <td class="text-right">
                    <a href="{{ route('settings.edit', $setting->id) }}" class="btn btn-warning">
                        <i class="fa fa-edit"></i>
                        <span>Edit</span>
                    </a>

                    <div class="modal" id="modal-{{ $setting->id }}">
                        <div class="modal-dialog text-left">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ $setting->name }}</h5>
                                    <button data-dismiss="modal" class="close">&times;</button>
                                </div>
                                <div class="modal-body" style="min-height: 160px">
                                    <div class="small text-muted mb-4">{{ $setting->description }}</div>
                                    <form class="form-horizontal" role="form" method="POST" action="{{ route('settings.update', $setting->id) }}" enctype="multipart/form-data">
                                        {{ method_field('put') }}
                                        @csrf

                                        <div class="form-group">
                                            @if($setting->input_type == 'text')
                                            <input id="setting-value" type="text" name="value" value="" class="form-control">
                                            @endif

                                            @if($setting->input_type == 'file')
                                            <input id="setting-value" type="file" name="file" value="" class="form-control">
                                            @endif

                                            @if($setting->input_type == 'pdf')
                                            <input id="setting-value" type="file" name="file" value="" class="form-control">
                                            @endif
                                        </div>

                                        <div class="buttons text-right mt-4">
                                            <button type="button" data-dismiss="modal" class="btn btn-default mr-2">Cancel</button>
                                            <button type="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@stop

@push('stylesheets')
<style media="screen">
    td.value ul {
        margin: 0;
        padding: 0 0 0 20px;
    }

    td.value ul li {
        font-family: monospace;
        font-size: 10px;
        list-style-type: square;
    }
</style>
@endpush
