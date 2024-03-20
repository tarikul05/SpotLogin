@extends('layouts.main')

@section('content')
<div class="content">
    <div class="container-fluid body pt-3 pb-3">

        <h3> {{__('Tutorials')}}</h3>

        @foreach ($categories as $category)
        <h5 style="color:#0075bf;">{{ $category->name }}</h5> <!-- Afficher le nom de la catégorie -->
    
        <div class="row" id="tutos_col">
            @foreach ($faqs as $faq)
                @if ($faq->category_id == $category->id) <!-- Vérifier si la faq appartient à la catégorie en cours -->
                    <div class="col-md-3 mb-4">
                        <div class='p-1'>
                            <div class="card" style="border-radius:10px 10px 0 0;">
                                <div class="card-header p-0" style="border-radius:10px 10px 0 0; padding-bottom:0px; margin-bottom:0px;">
                                    <!--<div class="embed-responsive embed-responsive-16by9">
                                        <x-embed url="{{ $faq->youtube_link }}" />
                                    </div>-->
                                    <video width="100%" controls style="border-radius:10px 10px 0 0; padding-bottom:0px; margin-bottom:0px;">
                                        <source src="{{ $faq->youtube_link }}" type="video/mp4">
                                        Your browser does not support the video player.
                                    </video>
                                </div>
                                <div class="card-body" style="height: 80px;">
                                    <b class="card-title" style="font-size:16px;">{{ $faq->title }}</b>
                                    <span class="text" style="color:#0075bf; cursor:pointer;"><!--{{ $faq->description }}--><i class="fa-solid fa-circle-info" onclick="openFaqModal('{{ $faq->title }}', '{{ $faq->description }}')"></i></span>
                          
                                    <!--<a href="{{ route('faqs.tutos.show', $faq) }}" class="btn btn-primary mt-3">View Details</a>-->
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endforeach

    </div>
</div>


<div class="modal fade" id="faqModal" tabindex="-1" role="dialog" aria-labelledby="faqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="text-center pt-1">Video Tutorial</div>
            <div class="modal-header text-center" style="width:98%;">
                <h5 class="modal-title" id="faqModalLabel"></h5>
                <a href="#" class="close" id="modalClose" data-bs-dismiss="modal" style="position: absolute; right: 6px; top: 6px; border-radius:50%!important; padding:3px; font-size:23px;">
                    <i class="fa-solid fa-circle-xmark fa-lg" style="color:#0075bf;"></i>
                </a>
            </div>
            <div class="modal-body">
                <p id="faqModalDescription"></p>
            </div>
        </div>
    </div>
</div>

@endsection


@section('footer_js')
    <script>
        function openFaqModal(title, description) {
            $('#faqModalLabel').text(title);
            $('#faqModalDescription').text(description);
            $('#faqModal').modal('show');
        }
    </script>
@endsection