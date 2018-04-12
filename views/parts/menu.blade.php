<ul class="list-group list-group-flush">
    @foreach($groups as $group)
        <li class="list-group-item">
            <details class="nav flex-column">
                <summary>{{ucfirst($group['group'])}}</summary>
                @foreach($group['resources'] as $item)
                    <a class="nav-link" href="{{route('docs.resource',['resource'=>$item['collection']])}}">
                        {{ $item['title']}}
                    </a>
                @endforeach
            </details>
        </li>
    @endforeach
</ul>

<ul class="nav flex-column" style="border-right: 1px solid #d7d7d7;display: none">
    @foreach($groups as $group)
        <li class="nav-item font-weight-bold">{{$group['group']}}</li>
        @foreach($group['resources'] as $item)
            <li class="nav-item font-weight-light">
                <a class="nav-link" href="{{route('docs.resource',['resource'=>$item['collection']])}}">
                    {{$item['title']}}
                </a>
            </li>
        @endforeach
    @endforeach
</ul>