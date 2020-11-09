{{ Form::open($component->getOptions()->toArray()) }}
    @if (!$component->getForm()->getErrors()->isEmpty())
        <div class="alert alert-danger alert-dismissible fade in" data-timeout-remove="2000" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
            <ul>
            @foreach ($component->getForm()->getErrors()->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <fieldset>
    @foreach ($component->getFormFieldGroupsComponents() as $fieldgroup)
        {{-- @if (!in_array($field->getName(), $exclude)) --}}
            {!! $fieldgroup->render($fieldgroup->getOption('template', $fieldgroup->getDefaultTemplateName())) !!}
        {{-- @endif --}}
    @endforeach

    @foreach ($component->getFormFieldsComponents() as $field)
        {{-- @if (!in_array($field->getName(), $exclude)) --}}
            {!! $field->render($field->getOption('template', $field->getDefaultTemplateName())) !!}
        {{-- @endif --}}
    @endforeach
    </fieldset>

    @foreach ($component->getFormButtonToolbarsComponents() as $buttontoolbar)
        {!! $buttontoolbar->render($buttontoolbar->getOption('template', $buttontoolbar->getDefaultTemplateName())) !!}
    @endforeach

    @foreach ($component->getFormButtonGroupsComponents() as $buttongroup)
        {!! $buttongroup->render($buttongroup->getOption('template', $buttongroup->getDefaultTemplateName())) !!}
    @endforeach

    @foreach ($component->getFormButtonsComponents() as $button)
        {!! $button->render($button->getOption('template', $button->getDefaultTemplateName())) !!}
    @endforeach
{{ Form::close() }}