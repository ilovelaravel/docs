<h6 class="font-weight-bold">Request</h6>
<table class="table bg-light">
    <thead>
    <tr>
        <th>Parameter key</th>
        <th>Value</th>
        <th>Type</th>
    </tr>
    </thead>
    <tbody>

    @foreach($item['example']['request'] as $pair)
        <tr class="table-lights">
            <td>{{$pair['key'] or ''}}</td>
            <td>{{$pair['value'] or ''}}</td>
            <td>{{$pair['type'] or ''}}</td>
        </tr>
    @endforeach

    </tbody>
</table>