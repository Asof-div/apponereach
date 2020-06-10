
<div class="table-responsive">
    <table class="table table-hover table-striped table-bordered">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>IVR Message</th>
            </tr>
        </thead>
        <tbody>
            @forelse($receptionists as $index => $receptionist)
                
                <tr>
                    <td>{{  $index + 1 }}</td>
                    <td> <a href="{{ route('tenant.media-service.virtual-receptionist.show', [$tenant->domain, $receptionist->id]) }}"> <span class="f-s-15"> {{ $receptionist->name }} </span> </a> </td>
                    <td> {!! $receptionist->is_ivr_tts ? nl2br($receptionist->ivr_tts) : "<audio src='". asset("storage/".$receptionist->ivr_media->path) ."' controls></audio>" !!} </td>
                </tr>

            @empty
                <tr>
                    <td colspan="4"> No Virtual Receptionist Configured ... </td>
                </tr>  
            @endforelse
        </tbody>
    </table>
</div>
