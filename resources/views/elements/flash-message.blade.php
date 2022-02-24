@if ($message = Session::get('success'))

<div class="alert alert-dismissible alert-success alert-block">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>    
</div>

@endif

  

@if ($message = Session::get('error'))

<div class="alert alert-dismissible alert-danger alert-block">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>    
</div>

@endif

<!-- 
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Opps!</strong> Something went wrong, please check below errors.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
 -->
   

@if ($message = Session::get('warning'))

<div class="alert alert-dismissible alert-warning alert-block">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>    
</div>

@endif

   

@if ($message = Session::get('info'))

<div class="alert alert-dismissible alert-info alert-block">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>    
</div>

@endif

  

@if ($errors->any())

<div class="alert alert-dismissible alert-danger">
    Please check the form below for errors
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>    
</div>

@endif