<?php
/** @var NZTim\MailLog\Entry\Entry $entry */
?>
@if($entry->isSent())
    <span class="label label-primary text-white" style="margin-left:5px;">{{ $entry->type() }}</span>
@elseif($entry->isDelivered())
    <span class="label label-success text-white" style="margin-left:5px;">{{ $entry->type() }}</span>
@elseif($entry->isSpam())
    <span class="badge badge-warning text-white" style="margin-left:5px;">{{ $entry->type() }}</span>
@elseif($entry->isBlocked())
    <span class="badge badge-warning text-white" style="margin-left:5px;">{{ $entry->type() }}</span>
@elseif($entry->isBounce())
    <span class="badge badge-danger text-white" style="margin-left:5px;">{{ $entry->type() }}</span>
@endif
