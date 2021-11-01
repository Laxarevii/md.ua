@extends('layouts.default')

@section('main')
    <div class="main-wrapper">

        <!--page!-->
        <div class="page is-brand-page">

            @include('partials.breadcrumbs')

            <div class="brand-page-wrapper">
                <div class="container">
                    <div class="row-inline brand-page-row js-sticky-rail">
                        @if($page->picture)
                        <div class="col-inline-xs-3 top brand-page-left js-sticky-column">
                            <div id="brand-page-image" class="brand-page-image js-sticky">
                                <figure>
                                    <img src="{{$page->getImgPath(180)}}" width="180" height="120" alt="{{$page->t('title')}}" title="{{$page->t('title')}}"/>
                                </figure>
                            </div>
                        </div>
                        @endif
                        <div class="col-inline-xs-9 top brand-page-right">
                            <div class="brand-page-header">
                                <h1>{{$page->t('title')}}</h1>
                                @if($page->t('summary'))
                                    <div class="brand-page-subtitle">{{$page->t('summary')}}</div>
                                @endif
                            </div>
                            <div class="brand-page-body">
                                @include('partials.article')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('partials.related_brand_products')
            @include('partials.banners')

        </div>
        <!--page!-->

    </div>
@endsection

    {{--@include('partials.breadcrumbs')--}}

    {{--<h1>{{$page->t('title')}}</h1>--}}

    {{--@include('partials.article')--}}

    {{--@include('partials.related_brand_products')--}}