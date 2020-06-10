<div class="superbox bg-silver clearfix" data-offset="54">

    @foreach($images as $image)

        <div class="superbox-list">
            <img src="{{ URL::to('storage/'.$image->source) }}" data-img="{{ URL::to('storage/'.$image->path) }}" data-gallery_id="{{ $image->id }}" alt="" class="superbox-img">
        </div>
        
    @endforeach
 
</div>
