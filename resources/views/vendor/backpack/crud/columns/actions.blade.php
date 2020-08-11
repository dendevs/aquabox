@javascript('api_token', backpack_user()->api_token)

@foreach ($crud->entry->actions as $action)
    <button class="btn btn-secondary btn-list" type="button" id="action_{{$action->id}}"
            @if( $action->description) data-toggle="tooltip" data-placement="bottom" title="{{$action->description}}"@endif>
        {{$action->label}}
    </button>
@endforeach

<script src="{{ asset('js/admin/actions_device.js') }}"></script>

