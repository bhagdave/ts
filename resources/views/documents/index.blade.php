@extends('layouts.app', ['mainClassOff' => true])
@section('content')

    @if (null !== session('wizard'))
        @if (session('wizard') == 'document')
            <document-wizard :stage="2" @doc-wizard-stage-3="stage3()"></document-wizard>
        @endif
    @endif
    <main role="main" class="py-4" class="container">
      <div class="container">
        <document-folder :properties="{{$properties}}"></document-folder>

      </div>
    </main>

@endsection
