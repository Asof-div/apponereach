<div class="table-responsive">

    <table id="sound_table_list" class="table table-hover condensed">
    
        <thead>
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Voice Code</th>
                <th>File Option</th>
                <th>Size</th>
                <th>Media</th>
                <th>Action</th>
            </tr>    
        </thead>

        <tbody>

            @foreach($sounds as $index => $sound)
                <tr>
                    <td>{{$index+1}}</td>
                    <td>{{$sound->title}}</td>
                    <td>{{$sound->voice_code}}</td>
                    <td>{{$sound->source}}</td>
                    <td>{{$sound->readableSize }}</td>
                    <td><audio src="{{asset("storage/".$sound->path)}}" controls></audio></td>
                    <td><button class="btn btn-danger btn-xs" data-target=".delete-sound-modal" data-toggle="modal" data-sound_id="{{ $sound->id }}" ><i class="fa fa-trash"></i></button></td>
                </tr>
            @endforeach
        
        </tbody>
    
    </table>

</div>