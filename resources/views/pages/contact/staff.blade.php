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
            <i class="fa-solid fa-envelope"></i> {{__('Contact Sportlogin Staff')}}<br>
            <p style="font-size:14px;">@if(!$AppUI->isStudent())
                {{ __('Send a message to the Sportlogins Team') }}
                @else
                {{ __('Send a message to the Sportlogins Team') }}
                @endif
            </p>
        </label>
    </div>
    <form action="{{ route('contact.form.submit') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="subject" class="form-label">{{ __('Subject') }}</label>
            <select class="form-control" id="subject" name="subject" required>
                <option value="">{{ __('choose a subject') }}</option>
                <option value="question">{{ __('I have a question') }}</option>
                <option value="probleme_technique">{{ __('Technical problem') }}</option>
                <option value="bug_report">{{ __('Bug report') }}</option>
                <option value="amelioration_suggestion">{{ __('Suggestion for improvement') }}</option>
                <option value="autre">{{ __('Other') }}</option>
            </select>
        </div>

            <input type="hidden" id="emailTo" name="emailTo" value="staff">

        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
        </div>
        @if(!$AppUI->isStudent())
        <input type="hidden" id="headerMessage" name="headerMessage" value="You have a message from the teacher {{ $AppUI->firstname . ' ' . $AppUI->lastname }} of the school #{{ $AppUI->school_id }}">
        @else
        <input type="hidden" id="headerMessage" name="headerMessage" value="You have a message from the student {{ $AppUI->firstname . ' ' . $AppUI->lastname }} of the school #{{ $AppUI->school_id }}">
        @endif
        <input type="hidden" id="person_id" name="person_id" value="{{ $AppUI->person_id}}">
        <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
    </form>
</div>
@endsection
