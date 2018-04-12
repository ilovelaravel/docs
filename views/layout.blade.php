<html>
<head>
    <title>{{config('docs.basic.title', 'API docs')}}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        a.nothing { text-decoration: none; color: inherit }
        a.nothing:hover { text-decoration: none }
        a.nothing span { border-radius: 18px; padding: 4px 5px; vertical-align: super; font-size: 10px }
        a.nothing span.domain { font-weight: normal; border-radius: 0px; font-size: 13px }
        .request { background: #fff; width: 60px; float: left; text-align: center; font-weight: lighter; font-size: 12px }
        .request-get { border: 3px solid #ff7827; color: #ff7827 }
        .request-put { border: 3px solid #59b6f0; color: #59b6f0 }
        .request-post { border: 3px solid #00a663; color: #00a663 }
        .request-delete { border: 3px solid #a8a8a8; color: #a8a8a8 }
        .request-200 { border: 3px solid #00a663; color: #00a663 }
        pre { padding: 5px; margin: 5px; white-space: pre; }
        .string { color: green; }
        .number { color: darkorange; }
        .boolean { color: blue; }
        .null { color: magenta; }
        .key { color: red; }
        summary { color: #818182; list-style-image: url(/images/bullit.svg); font-size: 16px; margin-left: -16px }
        summary:focus { list-style-image: url(/images/bullit-hover.svg); }
        summary::-webkit-details-marker { background: url(/images/bullit.svg);color: transparent; }
        summary:focus::-webkit-details-marker { background: url(/images/bullit-hover.svg); }
        .list-group { border-right: 1px solid rgb(222, 226, 230); }
        .list-group-item { border: 1px solid rgb(248, 249, 250); }
        details a { font-size: 15px; }
    </style>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
    <script type="text/javascript">
        window.output=function(e,t){document.getElementsByClassName(t)[0].appendChild(document.createElement("code")).innerHTML=e},window.syntaxHighlight=function(e){return"string"!=typeof e&&(e=JSON.stringify(e,void 0,2)),(e=e.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;")).replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,function(e){var t="number";return/^"/.test(e)?t=/:$/.test(e)?"key":"string":/true|false/.test(e)?t="boolean":/null/.test(e)&&(t="null"),'<span class="'+t+'">'+e+"</span>"})};
    </script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>

<div class="container-fluid p-0">
    <div class="col-12 d-flex">
        <div class="col-2">
            <a href="{{route('docs')}}" class="nothing">
                <h4 style="padding: 20px 0px">
                    {{$api['title']}} <span class="badge-info" style="font-size: 12px">{{$api['version']}}</span><br>
                    <span class="badge badge-warning domain">{{$api['domain']}}/{{$api['version']}}</span>
                </h4>
            </a>
        </div>
        <div class="col-10"></div>
    </div>
    <div class="col-12 d-flex">
        <div class="col-2">
            @include('docs::parts.menu',['groups'=> $api['groups']])
        </div>
        <div class="col-10">
            @yield('content')
        </div>
    </div>
</div>

<script src="//code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>