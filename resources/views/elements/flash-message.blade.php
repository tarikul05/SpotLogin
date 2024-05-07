<div class="alertMessages">

@if ($message = Session::get('success'))

<script>
    //check if window sie is mobile
    if (window.innerWidth < 768) {
        const Toast = Swal.mixin({
        toast: true,
        position: "bottom",
        showConfirmButton: false,
        grow:"row",
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
        });
        Toast.fire({
        icon: "success",
        title: "{{ $message }}"
        })

    } else {

        let timerInterval;
        Swal.fire({
        title: "{{ $message }}",
        //html: "{{ $message }}",
        timer: 2000,
        icon:"success",
        showConfirmButton: false,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
            const timer = Swal.getPopup().querySelector("b");
            timerInterval = setInterval(() => {
            timer.textContent = `${Swal.getTimerLeft()}`;
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
        }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log("I was closed by the timer");
        }
        });
    }

    </script>

<!--<div id="alert-container-success" class="alert alert-dismissible alert-success text-success alert-block" style="position:fixed; top:0; z-index:99999; font-size:18px; text-align:center; width:100%; height:66px; border-radius: 0; bakground-color:#333; color:green;">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>-->

@endif



@if ($message = Session::get('error'))

<script>
  Swal.fire({
  icon: "error",
  backdrop:true,
  title: "Oops...",
  text: "{{ $message }}",
  footer: '<a href="{{ route('faqs.tutos') }}">F.A.Q</a> | <a href="{{ route('contact.staff') }}">Contact</a>'
});
</script>

<!--<div class="alert alert-dismissible alert-danger alert-block" style="position:fixed; top:0; z-index:99999; font-size:18px; text-align:center; width:100%; height:66px; border-radius: 0;">
    <strong>{{ $message }}</strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>-->

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

</div>


@if ($errors->any())
    @foreach ($errors->all() as $error)
    <div style="background-color: #eed285; text-align: center; padding: 10px; border-radius: 0px; padding-top:15px; width: 100%; z-index:9999; border-radius:0 0 15px 15px;">
        <strong>{{ $error }}</strong>
    </div>
    @endforeach
@endif



<script>
    setTimeout(function() {
        var alertContainer = document.getElementById('alert-container-success');
        if (alertContainer) {
            alertContainer.style.display = 'none';
        }
    }, 3000);
</script>
