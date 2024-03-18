@extends('layouts.navbar')
@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="content">
    <br><br><br>
    <div class="container-fluid">
        <h3>Messages Help</h3>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Email Expéditeur</th>
                    <th>Email Destinataire</th>
                    <th>Sujet</th>
                    <th>Message</th>
                    <th>Reçu le</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contactForm as $message)
                <tr>
                    <td>{{ $message->email_expediteur }}</td>
                    <td>{{ $message->email_destinataire }}</td>
                    <td>{{ $message->sujet }}</td>
                    <td>{{ Str::limit($message->message, 100) }}</td>
                    <td>{{ $message->created_at }}</td>
                    <td>
                        @if($message->read == 0)
                        <span class="text text-warning"><i class="fa fa-warning"></i> {{ __('not read yet') }}</span>
                        @else
                        <span class="text text-success"><i class="fa fa-check"></i> {{ __('is read') }}</span>
                        @endif
                    </td>
                    <td><a href="{{ route('contacts.show', $message->discussion_id) }}" class="btn btn-primary">Voir</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
