<div class="table-responsive">

    <table id="sound_table_list" class="table table-hover condensed">
    
        <thead>
            <tr>
                <th> S/N </th>
                <th> Name </th>
                <th> Voice Code </th>
                <th> Type </th>
                <th> Content </th>
                <th> Action </th>
            </tr>    
        </thead>

        <tbody class="tts-list-container">

            @foreach($txttosp as $index => $tts)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $tts->title }} </td>
                    <td>{{ $tts->voice_code }}</td>
                    <td>{{ $tts->mime_type }}</td>
                    <td>{{ $tts->content }}</td>
                    <td>
                        <button class="btn btn-primary btn-xs" data-tts_type="{{ $tts->mime_type }}" data-tts_content="{{ $tts->content }}" data-tts_title="{{ $tts->title }}" data-tts_id="{{ $tts->id }}" data-toggle="modal" data-target=".edit-tts-modal" ><i class="fa fa-edit"></i></button>
                        <button class="btn btn-danger btn-xs" data-tts_id="{{ $tts->id }}" data-toggle="modal" data-target=".delete-tts-modal" ><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        
        </tbody>
    
    </table>

</div>