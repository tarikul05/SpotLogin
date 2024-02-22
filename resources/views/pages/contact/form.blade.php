@extends('layouts.main')

@section('head_links')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.43/moment-timezone-with-data-10-year-range.js" integrity="sha512-QSV7x6aYfVs/XXIrUoerB2a7Ea9M8CaX4rY5pK/jVV0CGhYiGSHaDCKx/EPRQ70hYHiaq/NaQp8GtK+05uoSOw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.min.css" integrity="sha512-fZNmykQ6RlCyzGl9he+ScLrlU0LWeaR6MO/Kq9lelfXOw54O63gizFMSD5fVgZvU1YfDIc6mxom5n60qJ1nCrQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js" integrity="sha512-lxQ4VnKKW7foGFV6L9zlSe+6QppP9B2t+tMMaV4s4iqAv4iHIyXED7O+fke1VeLNaRdoVkVt8Hw/jmZ+XocsXQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container">

    <div class="row justify-content-center pt-1">
        <div class="col-md-10">

            <h5>{{__('Contact Form')}}</h5>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('contact.form.submit') }}" method="POST">
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
            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
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
        <input type="hidden" id="person_id" name="person_id" value="{{ $AppUI->person_id}}">
            </div>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
    </form>
</div>
@endsection


@section('footer_js')

<script>
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
