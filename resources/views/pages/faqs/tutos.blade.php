@extends('layouts.main')

@section('content')
<div class="content">
    <div class="container-fluid body pt-3 pb-3">

        <h5> {{__('Tutorials')}}</h5>

        <div class="row mb-2">
        <div class="col-lg-12 d-flex justify-content-end align-items-end">
            <a href="#" id="view_list" class="btn btn-primary btn-sm"> <i class="fa-regular fa-rectangle-list"></i> View list</a>
            <a href="#" style="display: none;" id="view_col" class="btn btn-primary btn-sm"> <i class="fa-solid fa-table-columns"></i> View Col</a>
        </div>
        </div>

        <div class="row" id="tutos_col">
            @foreach ($faqs as $faq)
            <div class="col-md-4 mb-4">
                <div class='p-1'>
                    <div class="card" style="border-radius:10px 10px 0 0;">
                        <div class="card-header p-0" style="border-radius:10px 10px 0 0; padding-bottom:0px; margin-bottom:0px;">
                            <!--<div class="embed-responsive embed-responsive-16by9">
                                <x-embed url="{{ $faq->youtube_link }}" />
                            </div>-->
                            <video width="100%" controls style="border-radius:10px 10px 0 0; padding-bottom:0px; margin-bottom:0px;">
                                <source src="videos/{{ $faq->youtube_link }}" type="video/mp4">
                                Your browser does not support the video player.
                            </video>
                        </div>
                        <div class="card-body" style="height: 150px;">
                            <h6 class="card-title overflow-ellipsis">{{ $faq->title }}</h6>
                            <p class="card-text overflow-ellipsis" style="font-size:13px;">{{ $faq->description }}</p>
                            <!--<a href="{{ route('faqs.tutos.show', $faq) }}" class="btn btn-primary mt-3">View Details</a>-->
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>


        <div class="row" id="tutos_line" style="display:none;">
            @foreach ($faqs as $faq)
            <div class="col-md-12 mb-4">
                <div class="row bg-tertiary">
                    <div class="col-md-4">
                        <!--<div class="embed-responsive embed-responsive-16by9">
                            <x-embed url="{{ $faq->youtube_link }}" />
                        </div>-->
                        <video width="320" height="240" controls>
                            <source src="{{ $faq->youtube_link }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div class="col-md-8 p-2">
                        <h6 class="card-title">{{ $faq->title }}</h6>
                        <p style="font-size:13px;">{{ $faq->description }}</p>
                        <!--<a href="{{ route('faqs.tutos.show', $faq) }}" class="btn btn-primary mt-3">View Details</a>-->
                    </div>
                </div>
            </div>
            @endforeach
        </div>


    </div>
</div>
@endsection

@section('footer_js')

<script type="text/javascript">
//hide #tutos_col and display tutos_line when click on view_list
    $("#view_list").click(function(){
        $("#tutos_col").hide();
        $("#tutos_line").show();
        $("#view_list").hide();
        $("#view_col").show();
    });

    $("#view_col").click(function(){
        $("#tutos_col").show();
        $("#tutos_line").hide();
        $("#view_list").show();
        $("#view_col").hide();
    });
</script>
@endsection
