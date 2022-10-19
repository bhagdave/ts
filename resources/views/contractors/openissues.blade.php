
<div class="my-3 p-3 bg-white rounded shadow-sm">
    <div class="d-flex align-items-center justify-content-between">
        <h4>Open Issues</h4>
    </div>
    <div>
        <table>
            <tr>
                <th>
                    Description
                </th>
                <th>
                    Contractor Required
                </th>
                <th>
                    Address
                </th>
                <th>
                </th>
            </tr>
            @foreach($openIssues as $issue)
                <tr>
                    <td>{{substr($issue->description, 0, 50)}}</td> 
                    <td>{{$issue->name ?? '' }}</td> 
                    <td>{{$issue->inputAddress}} {{$issue->inputPostcode}}</td>
                    <td><a class="openissue button btn btn-primary" href="/issue/{{$issue->id}}/bid">Apply for job</a></td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
