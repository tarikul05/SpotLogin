<div class="alertMessages">

@if ($message = Session::get('success'))

<div id="alert-container-success" class="alert alert-dismissible alert-success text-success alert-block" style="position:fixed; top:0; z-index:99999; font-size:18px; text-align:center; width:100%; height:66px; border-radius: 0; bakground-color:#333; color:green;">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>    
</div>

@endif

  

@if ($message = Session::get('error'))

<div class="alert alert-dismissible alert-danger alert-block" style="position:fixed; top:0; z-index:99999; font-size:18px; text-align:center; width:100%; height:66px; border-radius: 0;">
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

<div class="alert alert-dismissible alert-warning alert-block" style="position:fixed; top:0; z-index:99999; font-size:18px; text-align:center; width:100%; height:66px; border-radius: 0;">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>    
</div>

@endif
   

@if ($message = Session::get('info'))

<div class="alert alert-dismissible alert-info alert-block" style="position:fixed; top:0; z-index:99999; font-size:18px; text-align:center; width:100%; height:66px; border-radius: 0;">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>    
</div>

@endif


@if ($errors->any())
    @foreach ($errors->all() as $error)
    <div class="alert alert-dismissible alert-danger alert-block" style="position:fixed; top:0; z-index:99999; font-size:18px; text-align:center; width:100%; height:66px; border-radius: 0;">
        
        
        {{ $error }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

        
    </div>
    @endforeach
@endif

</div>

<script>
    setTimeout(function() {
        var alertContainer = document.getElementById('alert-container-success');
        if (alertContainer) {
            alertContainer.style.display = 'none';
        }
    }, 3000);
</script>