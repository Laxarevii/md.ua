@extends('layouts.default')
<!--  added by LZRV 15.06.21 t.me/Lazarev_iLiya START -->
@section('main')
    <div class="main-wrapper">

        <!--page!-->
        <div class="page">

            @include('partials.breadcrumbs')

            <div class="page-header">
                <div class="container">
                    <h1>{{$page->t('title')}}</h1>
                    <div class="page-sub-header">
                        @include('partials.article')
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="static-catalog">
                    <div class="container">
                        <div class="static-catalog-row">
                            @foreach($partners as $partner)
                                <div class="static-catalog-column">
                                    @include('partials.partner_micro')
                                </div>
                            @endforeach
                        </div>
                        @include('partials.pagination', ['items' => $partners])
                    </div>
                </div>
            </div>

            <div class="page-footer">
                @include('partials.banners')
            </div>

        </div>
        <!--page!-->

    </div>
@stop