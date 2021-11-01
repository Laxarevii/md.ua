@extends('layouts.default')

@section('main')
    <div class="main-wrapper">

        <!--page!-->
        <div class="page is-article-page">

            <div class="article-page-body">
                <div class="container">
                    <div class="row-table article-row">

                        <!--left!-->
                        <div class="col-cell-8 top article-left">

                            @include('partials.breadcrumbs')

                            <div class="page-header">
                                <div class="container">
                                    <div class="container-wrapper">
                                        <h1>{{$page->t('title')}}</h1>
                                        <time>
                                            {{$page->getDate('%d.%m.%Y')}}
                                        </time>
                                    </div>
                                </div>
                            </div>

                            <div class="page-body">
                                <div class="container">
                                    <div class="container-wrapper">
                                        @include('partials.article')
                                        @include('partials.share')
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--left!-->

                        <!--right!-->
                        <div class="col-cell-4 top article-right">
                            @include('partials.related_news')
                        </div>
                        <!--right!-->

                    </div>
                </div>
            </div>

            <div class="article-page-footer">
                @include('partials.banners')
            </div>

        </div>
        <!--page!-->

    </div>
@stop

    {{--@include('partials.breadcrumbs')--}}

    {{--<h1>{{$page->t('title')}}</h1>--}}

    {{--<!-- Дата новости -->--}}
    {{--{{$page->getDate()}}--}}
    {{--<!-- -->--}}

    {{--@include('partials.article')--}}

    {{--@include('partials.share')--}}

    {{--@include('partials.related_news')--}}