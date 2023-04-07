@extends('nztses::base')

@section('main')
    <h1>SNS Confirmation</h1>
    <p>
        Confirm SNS subscription by visiting this link:<br>
        <a href="{{ $link }}">{{ $link }}</a>
    </p>
    <h3>Full message:</h3>
    <code>{{ $data }}</code>
@stop
