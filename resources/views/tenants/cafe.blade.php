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
             var input = $('#media-file-name'),
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

<div class="container streamShow">
        <div class="row flex-column-reverse flex-md-row">
            <div class="col-md-12 bg-white rounded shadow-sm">
                <!-- The banner at the top of the stream !-->
                <div id="streamInfo" class="border-bottom border-gray pt-3 pb-2 d-flex align-items-center" style="height: 8vh !important;">
                    <span class="w-100">
                        <h5 class="font-weight-bold">Stream Cafe - The Tenancy Stream Community</h5>
                        <a href="{{ url('rules') }}">Community Rules</a>
                        <p class="text-muted d-flex justify-content-center "> Ask a question to other stream members</p>
                    </span>
                </div>
                <div id="chatContainer" class="panel panel-default" style="height: 56vh !important;">
                    <div class="panel-body">
                        <chat-messages ref="chat" :cafe=true :stream_id="'{{$stream->id}}'" :messages="messages"></chat-messages>
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
                                                <input type="text" id="media-file-name" class="form-control" name="file" readonly>
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
                <div class="d-flex rounded chatInput" id="chatInput" style="height: 24vh !important;">
                    <div class="p-2 flex-grow-1 align-self-center">
                        <chat-form v-on:messagesent="addMessage" :cafe=true :stream_id="'{{$stream->id}}'" :user="{{ Auth::user() }}"></chat-form>
                    </div>
                </div>

        </div>
</div>
</div>
@endsection
