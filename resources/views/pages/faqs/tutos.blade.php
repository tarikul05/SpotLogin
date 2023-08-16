@extends('layouts.main')

@section('content')
<div class="content">
<div class="container-fluid body pt-3">
 
        <header class="panel-heading" style="border: none;">
            <div class="row panel-row pt-2" style="margin:0;">
                <div class="col-lg-4 col-12 header-area">
                        <div class="page_header_class pt-1">
                            <h1 for="calendar" class="titleCalendar" id="cal_title" style="display: block;">
                              {{__('FAQs/Tutorials')}}
                            </h1> 
                        </div>
                </div>
            </div>
        </header>
        
        <div class="row">
            @foreach ($faqs as $faq)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $faq->title }}</h5>
                            <p class="card-text">{{ $faq->description }}</p>
                            
                            <div class="embed-responsive embed-responsive-16by9">
                                <!--<iframe class="embed-responsive-item" src="{{ $faq->youtube_link }}" allowfullscreen></iframe>-->
                                <x-embed url="{{ $faq->youtube_link }}" />
                            </div>
                            
                            <a href="{{ route('faqs.tutos.show', $faq) }}" class="btn btn-primary mt-3">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection