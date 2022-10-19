<div class="my-3 p-3 bg-white rounded ">
    @if(isset($channel))
        <div class="d-flex align-items-center justify-content-between">
            <h4>News</h4>
        </div>
        @foreach($channel as $story)
        <div class="card text-center">
            <div class="card-body">
                <div class="card-title"><a target="_blank" href="{{$story->link}}">{{ $story->title }}</a></div>
                <p class="card-text" >{{ $story->description }}</p>
                <p class="small">{{ $story->pubDate }}</p>
            </div>
        </div>
        @endforeach
    @endif
</div>
