<div id="tab-{{ md5($component->getCommand()->getName()) }}" class="tab-pane ajax-overlay @if (isset($active) && ($component->getCommand()->getName() == $active)) active @endif">
    <p class="well well-sm">{{ $component->getCommand()->getDescription() }}</p>

    @if ($component->isCommandFormable())
        @if ($component->hasForm())
            {!! $component->getFormComponent()->render() !!}
        @else
            <p class="alert alert-danger"><b>{{ get_class($component->getCommand()) }}</b> - no form assigned or class [{{ $component->getCommand()->getFormClass() }}] doesn't exist</p>
        @endif
    @else
        <div class="ln_solid"></div>

        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group btn-group-md pull-right" role="group">
                <a class="btn btn-primary" href="{{ route('rocxolid.devkit.commander.help', $component->getCommand()->getName()) }}">Help</a>
                <button class="btn btn-primary" data-ajax-url="{{ route('rocxolid.devkit.commander.help', $component->getCommand()->getName()) }}" type="button">Help AJAX</button>
            </div>
            <div class="btn-group btn-group-md pull-right" role="group">
                <a class="btn btn-warning" href="{{ route('rocxolid.devkit.commander.run', $component->getCommand()->getName()) }}">Run</a>
                <button class="btn btn-warning" data-ajax-url="{{ route('rocxolid.devkit.commander.run', $component->getCommand()->getName()) }}" type="button">Run AJAX</button>
            </div>
        </div>
    @endif

    @include('rocXolid:devkit::general.message.command.error', [ 'tab' => $component, 'command' => $component->getCommand()->getName() ])
    @include('rocXolid:devkit::general.message.command.output', [ 'tab' => $component, 'command' => $component->getCommand()->getName() ])
</div>