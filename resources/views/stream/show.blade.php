@extends('layouts.app', ['mainClassOff' => true])
@section('content')
<script>
    $(function() {
         // We can attach the `fileselect` event to all file inputs on the page
         $(document).on('change', ':file', function() {
             var input    = $(this),
                 numFiles = input.get(0).files ? input.get(0).files.length : 1,
                 label    = input.val().replace(/\\/g, '/').replace(/.*\//, '');
             input.trigger('fileselect', [numFiles, label]);
     });

     // We can watch for our custom `fileselect` event like this
     $(document).ready( function() {
         $(':file').on('fileselect', function(event, numFiles, label) {
             var input = $(this).parents('.input-group').find(':text'),
                 log   = numFiles > 1 ? numFiles + ' files selected' : label;

              if( input.length ) {
                  input.val(log);
              } else {
                  if( log ) alert(log);
              }
         });
      });

    });
</script>
@if (null !== session('wizard'))
    @if (session('wizard') == 'reminder')
        <reminder-wizard></reminder-wizard>
    @endif
@endif
@if (null !== session('wizard'))
    @if (session('wizard') == 'stream')
        <stream-wizard></stream-wizard>
    @endif
@endif
<div class="container streamShow">
        <div class="row flex-column-reverse flex-md-row">
            <div class="col-md-12 bg-white rounded shadow-sm">
                <!-- The banner at the top of the stream !-->
                <div id="streamInfo" class="border-bottom border-gray pt-3 mb-0 d-flex align-items-center justify-content-between" style="height: 8vh !important;">
                    <span>
                        @if(isset($property))
                            <h5 class="font-weight-bold">{{$property->propertyName}} @if(($stream->private)) PRIVATE @endif</h5>
                        @endif
                        @if ($user->userType == 'Agent')
                            @if($stream->extra_attributes->broadcastOnly == "true")
                                <p class="text-muted "><span class="oi oi-wifi mr-2"></span> Broadcast Stream</p>
                            @else
                                <p class="text-muted "><span class="oi oi-chat mr-2"></span> Open Stream</p>
                            @endif
                        @endif
                    </span>
                </div>

                @if(isset($property))
                    <p class="list-group-item border-top-0 pl-2"><strong>Address: </strong> {{ $property->inputAddress }}
                    @if($property->inputCity )
                        {{ $property->inputCity }}</p>
                    @endif
                    @if( !$user->subscribed )
                        <p class="pb-3">
                            Your trial has come to and end and you have not subscribed so will not be able to reply to messages.  
                            Please <a href="{{url('payment')}}">click here</a> to subscribe.
                        </p>
                    @endif
                    @include('properties/nav')
                @endif

                <!-- The area that contains chat contents !-->
                <div id="chatContainer" class="panel panel-default" style="height: 56vh !important;">
                    <div class="panel-body">
                        <chat-messages ref="chat" :stream_id="'{{$stream->id}}'" :messages="messages"></chat-messages>
                    </div>
                    <div class="">
                        <!-- Modal -->
                        <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="/uploadMedia/{{$stream->id}}" method="post" enctype="multipart/form-data"  >
                                        {!! csrf_field() !!}
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="uploadModalLabel">Add media to the stream</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="file" readonly>
                                                <label class="input-group-btn">
                                                    <span class="btn btn-outline-primary">
                                                        Browse&hellip; 
                                                        <input type="file" style="display: none;" name="file" accept="image/*|audio/*|video/*" single required>
                                                    </span>
                                                </label>
                                            </div>
                                            <span class="help-block">
                                                Any media uploaded here will be visible to all members of the Stream.
                                            </span>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Upload Media </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($user->isAn('landlord') || $user->isAn('tenant'))
                    @if($stream->extra_attributes->broadcastOnly == "true")
                        <p class="text-muted text-center w-100 p-2">Posting to this stream has been restricted to select members.</p>
                    @else
                        <div class="d-flex rounded chatInput" id="chatInput" style="height: 24vh !important;">
                            <div class="p-2 flex-grow-1 align-self-center">
                                <chat-form v-on:messagesent="addMessage" :stream_id="'{{$stream->id}}'" :user="{{ Auth::user() }}"></chat-form>
                            </div>
                        </div>
                    @endif
                @endif

                @if ($user->isAn('admin') || $user->isAn('agent'))
                    @if ( $user->subscribed )
                        <div class="d-flex rounded chatInput" id="chatInput" style="height: 24vh !important;">
                            <div class="p-2 flex-grow-1 align-self-center">
                                @if (null !== session('wizard'))
                                    @if (session('wizard') == 'stream')
                                        <chat-form v-on:messagesent="addMessage" :wizard=true :stream_id="'{{$stream->id}}'" :user="{{ Auth::user() }}"></chat-form>
                                    @else
                                        <chat-form v-on:messagesent="addMessage" :stream_id="'{{$stream->id}}'" :user="{{ Auth::user() }}"></chat-form>
                                    @endif
                                @else
                                    <chat-form v-on:messagesent="addMessage" :stream_id="'{{$stream->id}}'" :user="{{ Auth::user() }}"></chat-form>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif

        </div>
</div>
</div>
@endsection
