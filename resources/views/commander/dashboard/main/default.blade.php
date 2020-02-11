@extends('rocXolid::layouts.default')

@section('content')
<div class="x_panel">
    <div class="x_title">
        <h2><i class="fa fa-bars"></i> Artisan runner <small>proof of concept</small></h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="col-xs-3 padding-right-no">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs tabs-left tabs-seamless">
            @foreach ($command_tabs as $i => $command_tab)
                {!! $command_tab->render('list', [
                    'i' => $i,
                    'active' => $active,
                    'error' => isset($error) ? $error : null,
                    'output' => isset($output) ? $output : null,
                ]) !!}
            @endforeach
            </ul>
        </div>

        <div class="col-xs-9 padding-left-no">
            <!-- Tab panes -->
            <div class="tab-content tab-content-seamless padding-20">
                <div class="tab-pane @if (!isset($active)) active @endif">
                    <p class="well well-sm">Select command</p>
                </div>
            @foreach ($command_tabs as $i => $command_tab)
                {!! $command_tab->render('tab', [
                    'i' => $i,
                    'active' => $active,
                    'error' => isset($error) ? $error : null,
                    'output' => isset($output) ? $output : null,
                ]) !!}
            @endforeach
            </div>
        </div>

        <div class="clearfix"></div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function($) {
    $('.tab-pane').css('min-height', $('.tabs-left').height() - 44);
});
</script>
@endsection