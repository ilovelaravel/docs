<ul class="list-group">
    @foreach($resources as $item)
        <li class="list-group-item">

            <div class="col-12 d-flex">
                <div class="col-4">
                    <br>
                    @include('docs::parts.resource-basic',['item'=>$item, 'api'=>$api])
                </div>
                <div class="col-8 pl-5">

                    @if(count($item['headers']) )
                        <br>
                        @include('docs::parts.resource-headers',['item'=>$item])
                    @endif

                    @if(count($item['params']))
                        <br>
                        @include('docs::parts.resource-params',['item'=>$item])
                    @endif

                    @if(count($item['schema']))
                        <br>
                        @include('docs::parts.resource-schema',['item'=>$item])
                    @endif

                    @if(count($item['example']['request']) && !array_key_exists('empty',$item['example']['request']))
                        <br>
                        @include('docs::parts.resource-request',['item'=>$item])
                    @endif

                    @if(count($item['example']['response']) && !array_key_exists('empty',$item['example']['response']))
                        <br>
                        @include('docs::parts.resource-response',['item'=>$item])
                    @endif
                </div>
            </div>
        </li>
    @endforeach
</ul>