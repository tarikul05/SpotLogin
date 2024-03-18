@extends('layouts.navbar')
@section('head_links')

@section('content')
<br><br><br>
<div class="container">
    <div class="row justify-content-center pt-1 mb-3">
        <div class="col-md-10">

        <h5>{{__('Messages Help')}} <small style="font-size:18px;"> // {{__('Answer')}}</small></h5>

        @foreach($messages as $messageItem)
        <div class="position-relative mb-2 oldermails {{ $messageItem->id_expediteur == $AppUI->id ? 'pb-2' : '' }}" style="{{ $messageItem->id == $message->id ? '' : 'opacity: 0.6;' }}">

        @if($messageItem->id_expediteur !== 0)
            <div class="p-2 d-flex align-items-center" style="border: 1px solid #EEE;">
            @php
                $AppUI = App\Models\User::find($messageItem->id_expediteur);
            @endphp

                <div>
                    @if(!empty($AppUI->profileImage->path_name))
                        <img src="{{ $AppUI->profileImage->path_name }}" class="admin_logo" id="admin_logo_mobile" style="width:35px!important; height:35px!important; border-radius:50px!important;" alt="globe">
                    @else
                        <img src="{{ asset('img/photo_blank.jpg') }}" class="admin_logo" id="admin_logo_mobile" style="width:35px!important; height :35px!important; border-radius:50px!important;" alt="globe">
                    @endif
                </div>
                <div class="ml-2">
                    <span style="font-size:12px; color:#333;"><b><i>{{ $messageItem->sujet }}</i></b></span> 
                    <span style="font-size:11px; color:#AAA;">write by {{ $AppUI->firstname }} {{ $AppUI->lastname }} the {{ $messageItem->created_at }}</span>
                    <br>
                    <span style="font-size:15px;">{{ $messageItem->message }}</span>
                    @if($messageItem->id_expediteur == $AppUI->id)
                        <div style="font-size:12px;">
                            @if($messageItem->read == 0)
                                <span class="text text-warning"><i class="fa fa-warning"></i> {{ __('not read yet') }}</span>
                            @else
                                <span class="text text-success"><i class="fa fa-check"></i> {{ __('is read') }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="p-2 text-right" style="border: 1px solid #EEE;">
                <span style="font-size:12px; color:#333;"><b><i>{{ $messageItem->sujet }}</i></b></span>
                <span style="font-size:11px; color:#AAA;">write by Support the {{ $messageItem->created_at }}</span>  
                <img src="{{ asset('img/logo-blue.png') }}" style="width:35px!important; height :35px!important;" class="admin_logo" id="admin_logo_mobile" alt="globe">
                <br>
                <span style="font-size:15px;">{{ $messageItem->message }}</span>
            @if($messageItem->id_expediteur == $AppUI->id)
                <div style="font-size:12px;">
                    @if($messageItem->read == 0)
                        <span class="text text-warning"><i class="fa fa-warning"></i> {{ __('not read yet') }}</span>
                    @else
                        <span class="text text-success"><i class="fa fa-check"></i> {{ __('is read') }}</span>
                    @endif
                </div>
            @endif
            </div>
        @endif


    </div>
@endforeach


            <form action="{{ route('contact.form.answer') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                  Re: <b>{{ $message->sujet }}</b> 
                </div>
                <div class="card-body">

            @if($message->id_destinataire !== 0)
            <input type="hidden" id="emailTo" name="emailTo" value="{{ $message->email_destinataire }}">
            <input type="hidden" id="email_from" name="email_from" value="{{ $message->email_expediteur }}">
            @else
            <input type="hidden" id="emailTo" name="emailTo" value="{{ $message->email_expediteur }}">   
            <input type="hidden" id="email_from" name="email_from" value="{{ $message->email_destinataire }}">                        
            @endif
    
        <input type="hidden" id="subject" name="subject" value="{{ $message->sujet }}">
        <input type="hidden" id="discussion_id" name="discussion_id" value="{{ $message->discussion_id }}">

        <div class="mb-3">
            <label for="message" class="form-label">Your answer</label>
            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
        </div>
        @if(!$AppUI->isStudent())
        <input type="hidden" id="headerMessage" name="headerMessage" value="You have an answer from the Help Support Sportlogin">
        @else
        <input type="hidden" id="headerMessage" name="headerMessage" value="You have an answer from the student {{ $AppUI->firstname . ' ' . $AppUI->lastname }} of the school #{{ $AppUI->school_id }}">
        @endif

        @if($message->id_destinataire !== 0)
        <input type="hidden" id="person_id" name="person_id" value="{{ $message->id_destinataire }}">
        @else
        <input type="hidden" id="person_id" name="person_id" value="{{ $message->id_expediteur }}">
        @endif
        <input type="hidden" id="id_destinataire" name="id_destinataire" value="0">


                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">{{ __('Send the answer') }}</button>
        </form>


</div>

@endsection

@section('footer_js')
    <script type="text/javascript">
    $(document).ready(function(){
        window.scrollTo(0, document.body.scrollHeight);
    });
    </script>
@endsection