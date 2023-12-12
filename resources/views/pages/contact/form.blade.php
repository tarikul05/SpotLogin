@extends('layouts.main')

@section('content')
<div class="container body">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div>
        <label id="page_header" name="page_header">
            <i class="fa-solid fa-envelope"></i> {{__('Contact Form')}}<br>
            <p style="font-size:14px;">@if(!$AppUI->isStudent())
                {{ __('Send a message that your student directly receive by mail') }} :
                @else
                {{ __('Send a message that your teacher directly receive by mail') }} :
                @endif
            </p>
        </label>
    </div>
    <form action="{{ route('contact.form.submit') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="subject" class="form-label">{{ __('Subject') }}</label>
            <input type="text" class="form-control" id="subject" name="subject" required>
        </div>
        <div class="mb-3">
        <label for="emailTo" class="form-label">{{ __('Send To') }} {{ $AppUI->isStudent() ? '' : '' }}</label>
            @if(!$AppUI->isStudent())
            <select class="form-select" id="emailTo" name="emailTo" required>
                @foreach($students as $student)
                <option value="{{ $student->student->email }}">{{ $student->nickname }} [ {{ $student->student->email }} ]</option>
                @endforeach
            </select>
            @else
            <select class="form-select" id="emailTo" name="emailTo" required>
                @foreach($students as $student)
                <option value="{{ $student->email }}">{{ $student->firstname . ' ' . $student->lastname }} [ {{ $student->email }} ]</option>
                @endforeach
            </select>
            @endif
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
        </div>
        @if(!$AppUI->isStudent())
        <input type="hidden" id="headerMessage" name="headerMessage" value="You have a message from your teacher {{ $AppUI->firstname . ' ' . $AppUI->lastname }}">
        @else
        <input type="hidden" id="headerMessage" name="headerMessage" value="You have a message from your student {{ $AppUI->firstname . ' ' . $AppUI->lastname }}">
        @endif
        <input type="hidden" id="person_id" name="person_id" value="{{ $AppUI->person_id}}">
        <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
    </form>
</div>
@endsection
