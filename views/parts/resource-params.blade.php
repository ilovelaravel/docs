<h6 class="font-weight-bold">Url parameters</h6>
<table class="table bg-light">
    <thead>
    <tr>
        <th>Parameter key</th>
        <th>Value</th>
        <th>Type</th>
    </tr>
    </thead>
    <tbody>

    @foreach($item['params'] as $pair)
        <tr class="table-lights">
            <td>{{$pair['key']}}</td>
            <td>{{$pair['value']}}</td>
            <td>{{$pair['type']}}</td>
        </tr>
    @endforeach

    </tbody>
</table>