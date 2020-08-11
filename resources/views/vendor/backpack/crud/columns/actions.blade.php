
@foreach ($crud->entry->actions as $action)
    <button class="btn btn-secondary btn-list" type="button"
            @if( $action->description) data-toggle="tooltip" data-placement="bottom" title="{{$action->description}}"@endif>
        <strong>{{$action->label}}</strong>
    </button>
@endforeach
