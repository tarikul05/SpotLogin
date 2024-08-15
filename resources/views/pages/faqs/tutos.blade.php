@extends('layouts.main')

@section('head_links')
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
@endsection

@section('content')
<div class="content">
    <div class="container-fluid body pt-3 pb-3">

        <h3>{{ __('Tutorials') }}</h3>

        <div class="row">
            <div class="col-md-9">
                <div class="alert alert-default" style="background-color: #f9f9f9;"><i class="fa-regular fa-file-video"></i> <span id="modelTitle">{{ $faqs[0]->title }}</span></div>
                <div class="video-container m-0 p-0">
                    <video id="mainVideo" width="100%" height="500" controls style="border-radius:10px; border:3px solid #EEE;" poster="{{ asset('img/background-video.png') }}">
                        <source id="mainVideoSource" src="{{ $faqs[0]->youtube_link }}" type="video/mp4">
                        Your browser does not support the video player.
                    </video>
                </div>
                <div id="modelDescription" class="alert alert-default" style="background-color: #f9f9f9;">{{ $faqs[0]->description }}</div>
            </div>

            <div class="col-md-3 p-3 pt-5">
                <br>
                @foreach ($categories as $index => $category)
                <b id="toggleCategory{{ $category->id }}" style="color:#0075bf; cursor: pointer;" data-toggle="collapse" data-target="#category{{ $category->id }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                    {{ $category->name }} <i class="fa-solid fa-chevron-down"></i>
                </b>
                <br>
                <div class="mb-4 collapse {{ $index == 0 ? 'show' : '' }}" id="category{{ $category->id }}">
                    @foreach ($faqs as $faq)
                        @if ($faq->category_id == $category->id)
                            <div class="video-thumbnail mb-2" style="cursor:pointer; max-width:250px;" onclick="changeVideo('{{ $faq->youtube_link }}', '{{ $faq->title }}', '{{ $faq->description }}')">
                                <div class="card" style="border-radius:10px;">
                                    <div class="card-header p-0" style="border-radius:10px 10px 0 0;">
                                        <video width="100%" height="120" style="border-radius:10px 10px 0 0;" poster="{{ asset('img/background-video.png') }}">
                                            <source src="{{ $faq->youtube_link }}" type="video/mp4">
                                        </video>
                                    </div>
                                    <div class="card-body" style="height: 60px;">
                                        <b class="card-title" style="font-size:14px;">{{ $faq->title }}</b>
                                        <span class="text" style="color:#0075bf; cursor:pointer;">
                                            <i class="fa-solid fa-circle-info" onclick="openFaqModal('{{ $faq->title }}', '{{ $faq->description }}')"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="faqModal" tabindex="-1" role="dialog" aria-labelledby="faqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="faqModalLabel"></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
    // Fonction pour fermer tous les autres accordéons
    function closeOtherAccordions(currentAccordion) {
        var accordions = document.querySelectorAll('.collapse');
        accordions.forEach(function(accordion) {
            if (accordion.id !== currentAccordion) {
                accordion.classList.remove('show');
            }
        });
    }

    // Ajout d'un événement de clic à chaque déclencheur d'accordéon
    document.querySelectorAll('[data-toggle="collapse"]').forEach(function(element) {
        element.addEventListener('click', function() {
            var target = this.getAttribute('data-target').substring(1);
            closeOtherAccordions(target);
        });
    });
    function changeVideo(videoUrl, title, description) {
        var video = document.getElementById('mainVideo');
        var videoSource = document.getElementById('mainVideoSource');
        videoSource.src = videoUrl;
        video.load();
        video.play();
        document.getElementById('faqModalLabel').innerText = title;
        document.getElementById('modelTitle').innerText = title;
        document.getElementById('modelDescription').innerText = description;
    }

    function openFaqModal(title, description) {
        $('#faqModalLabel').text(title);
        $('#faqModalDescription').text(description);
        $('#faqModal').modal('show');
    }
</script>
@endsection