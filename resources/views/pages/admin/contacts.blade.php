@extends('layouts.navbar')

@section('content')
<div class="content">
    <br><br><br>
    <div class="container-fluid">
        <h3>List of Contact Messages</h3>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sujet</th>
                    <th>Email Expéditeur</th>
                    <th>Email Destinataire</th>
                    <th>Message</th>
                    <th>Créé le</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contactForm as $message)
                <tr>
                    <td>{{ $message->id }}</td>
                    <td>{{ $message->sujet }}</td>
                    <td>{{ $message->email_expediteur }}</td>
                    <td>{{ $message->email_destinataire }}</td>
                    <td>{{ $message->message }}</td>
                    <td>{{ $message->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
