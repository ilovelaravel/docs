<h6 class="font-weight-bold">Headers</h6>
<table class="table bg-light">
    <thead>
    <tr>
        <th>Header name</th>
        <th>Header value</th>
        <th>Type</th>
    </tr>
    </thead>
    <tbody>

    @foreach($item['headers'] as $pair)
        <tr class="table-lights">
            <td>{{$pair['key']}}</td>
            <td>{{$pair['value']}}</td>
            <td>{{$pair['type']}}</td>
        </tr>
    @endforeach

    </tbody>
</table>