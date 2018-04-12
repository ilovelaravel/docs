<table class="table bg-light">
    <thead>
    <tr>
        <th style="width: 5%">Type</th>
        <th style="width: 25%">Endpoint</th>
        <th>Actie</th>
        <th style="display: none">Info</th>
        <th style="width:15%">Permission</th>
        <th style="width:5%">Auth</th>
    </tr>
    </thead>
    <tbody>

    @foreach($resources as $item)
        <tr class="table-lights">
            <td>
                <span class="request request-{{strtolower($item['type'])}}">
                    {{$item['type']}}
                </span>
            </td>
            <td>
                <kbd><kbd>{{$item['url']}}</kbd></kbd>
            </td>
            <td>{{$item['title']}}</td>
            <td style="display: none">
                {{$item['info']}}
            </td>
            <td>
                <span class="text-{{ $item['permission'] !== "" ? 'success' : 'danger' }}">
                    {{ $item['permission'] !== "" ? $item['permission'] : 'none' }}
                </span>
            </td>
            <td>
                <span class="text-{{ $item['needs_auth'] ? 'danger' : 'success' }}">
                    <i class="material-icons">{{ $item['needs_auth'] ? 'lock' : 'lock_open' }}</i>
                </span>
            </td>
        </tr>
    @endforeach

    </tbody>
</table>