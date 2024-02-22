@extends('layouts.main')

@section('content')
<div class="container">

    <div class="row justify-content-center pt-1">
        <div class="col-md-10">

            <h5>{{__('Help')}} <small style="font-size:18px;"> // {{__('Contact Sportlogin Staff')}}</small></h5>

            <form action="{{ route('contact.form.submit') }}" method="POST">
                @csrf

            <div class="card">
                <div class="card-header">
                    @if(!$AppUI->isStudent())
                    {{ __('Send a message to the Sportlogins Team') }}
                    @else
                    {{ __('Send a message to the Sportlogins Team') }}
                    @endif
                </div>
                <div class="card-body">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <div class="form-check">
        <input class="form-check-input" type="radio" name="language" id="english" value="english" required>
            <label class="form-check-label" for="english">
                {{ __('Help in English') }}
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="language" id="french" value="french" required>
            <label class="form-check-label" for="french">
                {{ __('Aide en Français') }}
            </label>
        </div>
    </div>


        <div class="mb-3">
            <label for="subject" class="form-label">{{ __('Subject') }}</label>
            <select class="form-control" id="subject" name="subject" required>
                <option value="">{{ __('choose a subject') }}</option>
                <option value="question">{{ __('I have a question') }}</option>
                <option value="probleme_technique">{{ __('Technical problem') }}</option>
                <option value="bug_report">{{ __('Bug report') }}</option>
                <option value="amelioration_suggestion">{{ __('Suggestion for improvement') }}</option>
                <option value="autre_sujet">{{ __('Other') }}</option>
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


                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
        </form>
</div>
@endsection
