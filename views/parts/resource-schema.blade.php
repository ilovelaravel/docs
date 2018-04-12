<h6 class="font-weight-bold">Request parameters</h6>
<table class="table bg-light">
    <thead>
    <tr>
        <th>Parameter key</th>
        <th>Example</th>
        <th>Type</th>
    </tr>
    </thead>
    <tbody>

    @foreach($item['schema'] as $pair)

        <tr class="table-lights">
            <td>{{$pair['key'] or ''}}</td>
            <td>{{$pair['example'] or ''}}</td>
            <td>{{$pair['type'] or ''}}</td>
        </tr>
    @endforeach

    </tbody>
</table>