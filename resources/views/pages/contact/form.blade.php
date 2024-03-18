@extends('layouts.main')

@section('head_links')


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.min.css" integrity="sha512-fZNmykQ6RlCyzGl9he+ScLrlU0LWeaR6MO/Kq9lelfXOw54O63gizFMSD5fVgZvU1YfDIc6mxom5n60qJ1nCrQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js" integrity="sha512-lxQ4VnKKW7foGFV6L9zlSe+6QppP9B2t+tMMaV4s4iqAv4iHIyXED7O+fke1VeLNaRdoVkVt8Hw/jmZ+XocsXQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
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
<div class="container">
    <div class="row">

        <div class="col-md-8">
            <h5>{{__('Contact Form')}}</h5>

            <form action="{{ route('contact.form.submit') }}" method="POST" id="contactForm">
            @csrf


            <div class="card">
                <div class="card-header">
                    @if(!$AppUI->isStudent() && !$AppUI->isParent())
                    {{ __('Send a message that your student directly receive by mail') }} :
                    @else
                    {{ __('Send a message that your teacher directly receive by mail') }} :
                    @endif
                </div>
            <div class="card-body">

            <div class="mb-3">
                <label for="subject" class="form-label">{{ __('Subject') }}</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="mb-3">
            <label for="emailTo" class="form-label">{{ __('Send To') }} {{ $AppUI->isStudent() || $AppUI->isParent() ? '' : '' }}</label>
                @if(!$AppUI->isStudent() && !$AppUI->isParent())
                <div class="student_list text-left">
                    <div class="input-group">
                        <select class="form-select multiselect" id="emailTo" name="emailTo[]" multiple="multiple" required>
                            @foreach($students as $student)
                            <option value="{{ $student->student->email }}">{{ $student->nickname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @else
                <select class="form-select" id="emailTo" name="emailTo[]" required>
                    @foreach($students as $student)
                    <option value="{{ $student->email }}">{{ $student->firstname . ' ' . $student->lastname }} [ {{ $student->email }} ]</option>
                    @endforeach
                </select>
                @endif
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea name="message" id="editor">
                    &lt;p&gt;This is some sample content.&lt;/p&gt;
                </textarea>
            </div>
            @if($AppUI->isParent())
            <input type="hidden" id="headerMessage" name="headerMessage" value="You have a message from Parents/Family {{ $AppUI->firstname . ' ' . $AppUI->lastname }}">
            @else
            @if(!$AppUI->isStudent() && !$AppUI->isParent())
            <input type="hidden" id="headerMessage" name="headerMessage" value="You have a message from your teacher {{ $AppUI->firstname . ' ' . $AppUI->lastname }}">
            @else
            <input type="hidden" id="headerMessage" name="headerMessage" value="You have a message from your student {{ $AppUI->firstname . ' ' . $AppUI->lastname }}">
            @endif
            @endif
            <input type="hidden" id="person_id" name="person_id" value="{{ $AppUI->id }}">

            <br>
            <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
            </form>
            </div>
            </div>
        </div>

        <div class="col-md-4">
            <h5><br></h5>
            <div class="card">
                <div class="card-header">
                    {{ __('All messages') }}
                </div>
                <div class="card-body">

                <ul class="list-group" style="padding:0px!important; margin:0px!important; border:none!important;">
            @foreach($messages as $message)
            <li class="list-group-item d-flex align-items-start" style="padding:0px!important; margin:0px!important; border:none!important; margin-bottom:15px!important;">
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
            <small style="font-size:11px; color:#AAA;">(last message the {{ $message->created_at }})</small><br>
            <small><?php echo strip_tags(Str::limit($message->message, 50)); ?> </small>
        </div>
    </li>
    @endforeach
</ul>

                </div>
            </div>
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

    $('#emailTo').multiselect({
    maxHeight: 400,
    buttonWidth: '100%',
    dropRight: false,
    enableFiltering: true,
    includeSelectAllOption: true,
    includeFilterClearBtn: true,
    search: true,
    noneSelected: "{{__("None selected") }}",
    selectAllText: "{{__("All Students") }}",
    enableCaseInsensitiveFiltering: true,
    enableFullValueFiltering: false,
  });

</script>

@endSection
