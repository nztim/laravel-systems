<?php
/** @var NZTim\MailLog\Entry\Entry[] $entries */
?>
@extends('master')

@section('title', 'System Messages')

@section('admin-content')
    <h2 class="page-heading">System Messages</h2>
    {!! Form::open(['method' => 'GET']) !!}

    <div class="input-group">
        {!! Form::input('search', 'search', $search, ['placeholder' => 'Recipient email', 'class' => 'form-control']) !!}
        <span class="input-group-btn">
            <button class="btn btn-default" type="button" style="padding-top:6px;padding-bottom:5px;">
                <span class="icon icon-magnifying-glass"></span>
            </button>
        </span>
    </div>
    {!! Form::select('type', $types, $type, ['class' => 'form-control category index-select', 'onchange' => 'this.form.submit()']) !!}
    {!! Form::close() !!}
    <table class="table admin">
        <tr>
            <th class="border-top-0" style="width:240px;">Date</th>
            <th class="border-top-0">Recipient</th>
            <th class="border-top-0">Subject</th>
        </tr>
        @foreach($entries as $entry)
            <tr>
                <td style="width:240px;">
                    {{ $entry->date()->format('j M Y h:ia') }}
                    @include('admin.system-messages.badge', ['entry' => $entry])
                </td>
                <td>
                    <a href="{{ route('admin.system-messages.index', ['search' => $entry->recipient()]) }}">{{ $entry->recipient() }}</a>
                </td>
                <td>
                    @if($entry->hasContent())
                        <a href="{{ route('admin.system-messages.show', $entry->id()) }}" target="_blank">{{ $entry->subject() }}</a>
                    @else
                        {{ $entry->description() }}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    @pagination($entries)
@stop
