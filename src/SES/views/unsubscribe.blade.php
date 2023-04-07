@extends('nztses::base')

@section('main')
    <h1>SNS Unsubscribe</h1>
    <p>
        Unsubscribed from SNS notifications.
    </p>
    <h3>Full message:</h3>
    <code>{{ $data }}</code>
@stop
