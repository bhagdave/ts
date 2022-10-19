<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <title>Landlord Report for {{$date->format('M')}}</title>
    </head>
    <body>
        <h1>{{$landlord->name}}</h1>
        <table class="mt-5 table table-sm">
            @foreach($landlord->property as $property)
                <tr>
                    <td colspan="4" class="bg-success">{{$property->propertyName}} {{$property->inputAddress}} {{$property->inputCity}}</td>
                </tr>
                <tr>
                    <td class="text-center" colspan="4"><strong>Issues</strong></td>
                </tr>
                <tr>
                    <th>Issue</th>
                    <th>Status</th>
                    <th>Reported By</th>
                    <th>Description</th>
                </tr>
                    @foreach($property->issues as $issue)
                        @if($issue->created_at > $firstOfMonth && $issue->created_at < $endOfMonth)
                            <tr class="table-warning">
                                <td>{{$issue->description}}</td>
                                <td>{{$issue->attributes}}</td>
                                <td>{{$issue->extra_attributes['author'] ?? '' }}</td>
                                <td>{{$issue->extra_attributes['mainDescription'] ?? '' }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tr>
                <hr>
                <tr>
                    <td class="text-center" colspan="4"><strong>Rent Paid</strong></td>
                </tr>
                <tr>
                    <th>Tenant</th>
                    <th>Status</th>
                    <th>Paid Date</th>
                    <th>Amount</th>
                </tr>
                @foreach($property->rent as $payment)
                    @if($payment->created_at > $firstOfMonth && $payment->created_at < $endOfMonth)
                        <tr>
                            <td>{{$payment->tenant->name}}</td>
                            <td>{{$payment->status}}</td>
                            <td>{{$payment->paid_date}}</td>
                            <td>{{$payment->amount}}</td>
                        </tr>
                    @endif
                @endforeach
                </tr>
            @endforeach
        </table>
    </body>
</html>
