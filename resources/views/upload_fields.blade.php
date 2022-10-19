@extends('layouts.app')

@section('content')

<form class="form-horizontal" method="POST" action="{{ route('upload_process') }}">
    {{ csrf_field() }}
    <input type="hidden" name="csv_data_file_id" value="{{ $csvDataFile->id }}" / >
    <input type="hidden" name="agency" value="{{ $agency }}" / >
    <table class="table">
        @foreach ($csvData as $row)
            <tr>
            @foreach ($row as $key => $value)
                <td>{{ $value }}</td>
            @endforeach
            </tr>
        @endforeach
        <tr>
            @foreach ($csvData[0] as $key => $value)
                <td>
                    <select name="fields[{{ $key }}]">
                        @foreach ($fields as $db_field)
                            <option value="{{ $loop->index }}">{{ $db_field }}</option>
                        @endforeach
                    </select>
                </td>
            @endforeach
        </tr>
    </table>

    <button type="submit" class="btn btn-primary">
        Import Data
    </button>
</form>

@endsection
