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
                                            {{$page->getDate()}}
                                        </time>
                                    </div>
                                </div>
                            </div>

                            <div class="page-body">
                                <div class="container">
                                    <div class="container-wrapper">

                                        @if($page->picture)
                                        <div class="article-image">
                                            <figure>
                                                <img src="{{$page->getImgPath(880)}}" width="880" height="400" alt="{{$page->t('title')}}" title="{{$page->t('title')}}"/>
                                            </figure>
                                        </div>
                                        @endif

                                        @if($page->time || $page->person_qty)
                                        <div class="article-infographics">
                                            @if($page->time)
                                            <div class="infographic-item">
                                                <div class="infographic-item-icon">
                                                    <i class="clock-icon"></i>
                                                </div>
                                                <div class="infographic-item-content">
                                                    <div class="infographic-item-title">{{$page->time}}</div>
                                                    <div class="infographic-item-subtitle">{{__t('время приготовления')}}</div>
                                                </div>
                                            </div>
                                            @endif

                                            @if($page->person_qty)
                                            <div class="infographic-item">
                                                <div class="infographic-item-icon">
                                                    <i class="dish-icon"></i>
                                                </div>
                                                <div class="infographic-item-content">
                                                    <div class="infographic-item-title">{{$page->person_qty}}</div>
                                                    <div class="infographic-item-subtitle">{{__t('количество')}}</div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @endif

                                        @if($page->t('summary'))
                                        <div class="article article-short-description">
                                            <p>{{$page->t('summary')}}</p>
                                        </div>
                                        @endif

                                        @if($page->getIngridients())
                                        <div class="article article-ingridients">
                                            <h2>{{__t('Ингредиенты')}}</h2>
                                            <ul>
                                                @foreach($page->getIngridients() as $ingridient)
                                                    <li>{{$ingridient[getWithLocalePostfix('ingr_title')]}}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif

                                        @include('partials.article')

                                        @include('partials.share')

                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--left!-->

                        <!--right!-->
                        <div class="col-cell-4 top article-right">
                            @include('partials.related_receipts')
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

    {{--@include('partials.breadcrumbs')--}}

    {{--<h1>{{$page->t('title')}}</h1>--}}

    {{--@include('partials.article')--}}

    {{--@if($page->picture)--}}
        {{--<!-- Ресайз изображения рецепта 500px -->--}}
        {{--{{$page->getImgPath(500)}}--}}
        {{--<!-- -->--}}
        {{--<!-- Оригинал изображения рецепта -->--}}
        {{--/{{$page->picture}}--}}
        {{--<!-- -->--}}
    {{--@endif--}}

    {{--<!-- Короткое описание рецепта-->--}}
    {{--{{$page->t('summary')}}--}}
    {{--<!-- -->--}}

    {{--<!-- Время приготовления -->--}}
    {{--{{$page->time}}--}}
    {{--<!-- -->--}}

    {{--<!-- Количество порций/персон -->--}}
    {{--{{$page->person_qty}}--}}
    {{--<!-- -->--}}

    {{--<!-- Ингридиенты -->--}}
    {{--@foreach($page->getIngridients() as $ingridient)--}}
        {{--<!-- Название ингридиента -->--}}
            {{--{{$ingridient[getWithLocalePostfix('ingr_title')]}}--}}
        {{--<!-- -->--}}
        {{--<!-- Коментарий к ингридиенту -->--}}
            {{--{{$ingridient[getWithLocalePostfix('ingr_comment')]}}--}}
        {{--<!-- -->--}}
    {{--@endforeach--}}

    {{--@include('partials.related_receipts')--}}

    {{--@include('partials.share')--}}

@stop