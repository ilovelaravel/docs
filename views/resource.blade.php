@extends('docs::layout')
@section('content')
    <h3>{{$title}}</h3>
    <p class="lead">
        {{$info}}
    </p>
    @include('docs::parts.table', ['api'=>$api,'resources'=>$resources])
    <br>
    <h4>All actions and examples</h4>
    <p class="lead">Below you will find all requests, responds and examples</p>
    @include('docs::parts.list',  ['api'=>$api,'resources'=>$resources])
@endsection