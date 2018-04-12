<h4>
    {{$item['title']}}
    <span class="float-right request request-{{strtolower($item['type'])}}">
        {{$item['type']}}
    </span>
</h4>
<p class="lead">
    {{$item['info']}}
</p>
<hr>

<dl class="row">

    <dt class="col-sm-3">Permission:</dt>
    <dd class="col-sm-9">
        <span class="text-{{ $item['permission'] !== "" ? 'success' : 'danger' }} align-bottom">
            {{ $item['permission'] !== "" ? $item['permission'] : 'none' }}
        </span>
    </dd>

    <dt class="col-sm-3">Endpoint:</dt>
    <dd class="col-sm-9">
        <kbd><kbd>{{$item['url']}}</kbd></kbd>
    </dd>

    <dt class="col-sm-3">Method:</dt>
    <dd class="col-sm-9">
        <span class="request request-{{strtolower($item['type'])}}">
            {{$item['type']}}
        </span>
    </dd>

    <dt class="col-sm-3">Auth:</dt>
    <dd class="col-sm-9">
        <span class="text-{{ $item['needs_auth'] ? 'danger' : 'success' }} align-bottom">
            <i class="material-icons">{{ $item['needs_auth'] ? 'lock' : 'lock_open' }}</i>
            {{ $item['needs_auth'] ? 'jwt' : 'open' }}
        </span>
    </dd>

</dl>