@extends('layouts.main')

@section('head_links')
<script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
<style>
    span p {
        font-size: 14px!important;
    }
    .ck-content p {
        font-size: 14px!important;
    }
    .ck-content {
        min-height: 150px;
    }
    </style>
    
@endsection

@section('content')

<div class="container mb-2">
    <div class="row justify-content-center pt-1">
        <div class="col-md-8">

            <h5>{{__('Messages')}} <small style="font-size:18px;"> // {{__('Answer')}}</small> <a href="#" id="refreshPage" class="text text-primary btn-sm"><i class="fa fa-refresh"></i> {{ __('Refresh') }}</a></h5>

            @if($messages->count() > 0)
            
                    @foreach($messages as $messageItem)
                        <div class="position-relative oldermails {{ $messageItem->id_expediteur == $AppUI->id ? 'pb-2' : '' }}" style="{{ $messageItem->id == $message->id ? '' : 'opacity: 0.6;' }}">
               
                            @if($messageItem->id_expediteur == $AppUI->id)
                            <div class="p-2" style="border: 1px solid #EEE; text-align:left!important;">
                                <?php if (!empty($AppUI->profileImage->path_name)): ?>
                                    <img src="{{ $AppUI->profileImage->path_name }}" class="admin_logo" id="admin_logo_mobile" style="width:35px!important; height:35px!important;" alt="globe">
                                <?php else: ?>
                                    <img src="{{ asset('img/photo_blank.jpg') }}" class="admin_logo" id="admin_logo_mobile" style="width:35px!important; height :35px!important;" alt="globe">
                                <?php endif; ?>
                                <span style="font-size:12px; color:#color:#EEE;"><b><i>{{ $messageItem->sujet }}</i></b></span> <span style="font-size:11px; color:#AAA;">write by {{ $AppUI->firstname }} {{ $AppUI->lastname }} the {{ $messageItem->created_at }}</span><br>
                                <span style="font-size:15px;">{!! $messageItem->message !!}</span>
                            </div>
                            @else
                            <div class="p-2" style="border: 1px solid #EEE; text-align:right!important;">   
                                <span style="font-size:12px; color:#color:#EEE;"><b><i>{{ $messageItem->sujet }}</i></b></span> <span style="font-size:11px; color:#AAA;">write by Support the {{ $messageItem->created_at }}</span>  <img src="{{ asset('img/logo-blue.png') }}" class="admin_logo" id="admin_logo_mobile" alt="globe"><br>
                                <span style="font-size:15px;">{!! $messageItem->message !!}</span>
                            </div>
                            @endif

                            @if($messageItem->id_expediteur == $AppUI->id && $messageItem->id_destinataire == $AppUI->id)
                                    <div class="alert alert-info">
                                        The recipient is not registered with a Sportlogin account, then the message cannot be answered.
                                </div>
                            @endif

                            @if($messageItem->id_expediteur == $AppUI->id && $messageItem->id_destinataire !== $AppUI->id)
                                <div style="font-size:12px;">
                                    @if($messageItem->read == 0)
                                        <span class="text text-warning"><i class="fa fa-warning"></i> {{ __('not read yet') }}</span>
                                        @else
                                        <span class="text text-success"><i class="fa fa-check"></i> {{ __('is read') }}</span>
                                    @endif
                                </div>
                            @endif

                           
                        </div>
                    @endforeach
            
            @endif

        <form action="{{ route('contact.form.answer') }}" method="POST">
            @csrf

            <div class="card mt-2">

            @if($message->id_destinataire == 0)
            <input type="hidden" id="emailTo" name="emailTo" value="{{ $message->email_destinataire }}">
            <input type="hidden" id="email_from" name="email_from" value="{{ $message->email_expediteur }}">
            @else
            <input type="hidden" id="emailTo" name="emailTo" value="{{ $message->email_expediteur }}">   
            <input type="hidden" id="email_from" name="email_from" value="{{ $message->email_destinataire }}">                        
            @endif

            <input type="hidden" id="subject" name="subject" value="{{ $message->sujet }}">
            
            <input type="hidden" id="discussion_id" name="discussion_id" value="{{ $message->discussion_id }}">

            <div class="card-header">
                <b>RE: {{ $message->sujet }}</b><br>
            </div>
            <div class="card-body"> 
            <label for="message" class="form-label">Your answer</label>
            <textarea name="message" id="editor">
                
            </textarea>
            @if(!$AppUI->isStudent())
            <input type="hidden" id="headerMessage" name="headerMessage" value="You have an answer from the teacher {{ $AppUI->firstname . ' ' . $AppUI->lastname }} of the school #{{ $AppUI->school_id }}">
            @else
            <input type="hidden" id="headerMessage" name="headerMessage" value="You have an answer from the student {{ $AppUI->firstname . ' ' . $AppUI->lastname }} of the school #{{ $AppUI->school_id }}">
            @endif

            @if($message->id_destinataire == 0)
            <input type="hidden" id="person_id" name="person_id" value="{{ $message->id_destinataire }}">
            @else
            <input type="hidden" id="person_id" name="person_id" value="{{ $message->id_expediteur }}">
            @endif
            <input type="hidden" id="id_destinataire" name="id_destinataire" value="{{ $AppUI->id }}">

    
                <br>
                <button type="submit" class="btn btn-primary">{{ __('Send the answer') }}</button>
          

                    </div>
                </div>
                <br>
                <div class="d-flex justify-content-end">
   
                    <a href="#" id="refreshPageBottom" class="text text-primary btn-sm"><i class="fa fa-refresh"></i> {{ __('Refresh') }}</a>
                </div>
        </form>

</div>
@endsection


@section('footer_js')
    <script type="text/javascript">
    $(document).ready(function(){
        window.scrollTo(0, document.body.scrollHeight);
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('refreshPage').addEventListener('click', function (event) {
            event.preventDefault();
            location.reload();
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('refreshPageBottom').addEventListener('click', function (event) {
            event.preventDefault();
            location.reload();
        });
    });
</script>

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