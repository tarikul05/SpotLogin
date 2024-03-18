@extends('layouts.main')

@section('head_links')
<script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
<style>
    .ck-content p {
        font-size: 14px!important;
    }
    .ck-content {
        min-height: 300px;
    }
    </style>
    
@endsection

@section('content')
@php
use Illuminate\Support\Str;
@endphp
<div class="container">


        <form action="{{ route('contact.form.submit') }}" method="POST">
        @csrf
        <div class="row justify-content-center pt-1">
            <div class="col-md-7">

                <h5>{{__('Help')}} <small style="font-size:18px;"> // {{__('Contact Sportlogin Staff')}}</small></h5>

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
                        <textarea name="message" id="editor">
                            Hi Sportlogin Support,<br><br>Your message... 
                        </textarea>
                    </div>
                    @if(!$AppUI->isStudent())
                    <input type="hidden" id="headerMessage" name="headerMessage" value="You have a message from the teacher {{ $AppUI->firstname . ' ' . $AppUI->lastname }} of the school #{{ $AppUI->school_id }}">
                    @else
                    <input type="hidden" id="headerMessage" name="headerMessage" value="You have a message from the student {{ $AppUI->firstname . ' ' . $AppUI->lastname }} of the school #{{ $AppUI->school_id }}">
                    @endif
                    <input type="hidden" id="person_id" name="person_id" value="{{ $AppUI->person_id}}">

                    <br>
                        <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>

            </div>
        </div>
                        
    </form>         

    </div>
    <div class="col-md-1"></div>
        <div class="col-md-4 text-right">
            <div class="text-right ml-2">
                <h5><br></h5>
                <div class="card">
                <div class="card-header">
                    Discussions with support
                </div>
                <div class="card-body">

        <ul class="list-group">
            @foreach($messages as $message)
            <li class="list-group-item d-flex align-items-start">
            @if($message->id_expediteur == $AppUI->id)
                <div class="mr-3">
                    @if(!empty($AppUI->profileImage->path_name))
                        <img src="{{ $AppUI->profileImage->path_name }}" class="admin_logo" id="admin_logo_mobile" style="width:35px!important; height:35px!important;" alt="globe">
                    @else
                        <img src="{{ asset('img/photo_blank.jpg') }}" class="admin_logo" id="admin_logo_mobile" style="width:35px!important; height:35px!important;" alt="globe">
                    @endif
                </div>
            @else
                <div class="mr-3">
                    <img src="{{ asset('img/logo-blue.png') }}" class="admin_logo" id="admin_logo_mobile" alt="globe">
                </div>
            @endif
        <div>
            <a href="{{ route('contact.answer', $message->discussion_id) }}">{{ $message->sujet }}</a><br>
            <small style="font-size:11px;">(last message the {{ $message->created_at }})</small><br>
            <small><?php echo strip_tags(Str::limit($message->message, 50)); ?></small>
            @if($message->id_expediteur == $AppUI->id)
                <div style="font-size:12px; position:absolute; top:4px; right:4px;">
                    @if($message->read == 0)
                        <span class="text text-warning"><i class="fa fa-warning"></i> {{ __('not read yet') }}</span>
                    @else
                        <span class="text text-success"><i class="fa fa-check"></i> {{ __('is read') }}</span>
                    @endif
                </div>
            @endif
        </div>
    </li>
    @endforeach
</ul>
                </div>
            </div>

</div>
@endsection

@section('footer_js')

<script>

    ClassicEditor
    .create( document.querySelector( '#editor' ),
     {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-test' },
                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
            ]
        }
    }  )
    .catch( error => {
        console.error('error ?', error );
    } );

</script>

@endSection