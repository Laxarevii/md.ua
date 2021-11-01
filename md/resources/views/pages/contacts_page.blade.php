@extends('layouts.default')

@section('main')
    <div class="main-wrapper">

        <!--page!-->
        <div class="page is-contacts-page">

            @include('partials.breadcrumbs')

            <div class="page-body">
                <section class="section map-section">
                    <article>

                        <div class="map-section-article-header">
                            <h1>{{$page->t('title')}}</h1>
                        </div>

                        <!--overflow!-->
                        <div class="map-section-article-body">

                            <!--top!-->
                            <div class="current-filials-list">
                                @foreach($residences as $index => $residence)
                                    <div data-index="{{$index}}" class="filials-list-section">
                                        <div class="filials-list-title">{{$residence->t('title')}}</div>
                                        <div class="filials-list-table">
                                            <div class="filials-list-group">
                                                @if($residence->t('description'))
                                                <div class="filials-list-row">
                                                    <div class="filials-list-column">
                                                        <div class="filials-list-name">
                                                            {{__t('Адрес')}}:
                                                        </div>
                                                    </div>
                                                    <div class="filials-list-column">
                                                        <div class="filials-list-description">
                                                            {!! $residence->t('description') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="filials-list-row">
                                                    <div class="filials-list-column">
                                                        <div class="filials-list-name">
                                                            {{__t('Телефон')}}:
                                                        </div>
                                                    </div>
                                                    <div class="filials-list-column">
                                                        <div class="filials-list-phones">
                                                            <ul>
                                                                @foreach($residence->getPhones() as $phone)
                                                                    <li>
                                                                        <a href="tel:{{str_replace(' ', '', $phone)}}">{{$phone}}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($residence->t('email'))
                                                <div class="filials-list-row">
                                                    <div class="filials-list-column">
                                                        <div class="filials-list-name">
                                                            {{__t('Email')}}:
                                                        </div>
                                                    </div>
                                                    <div class="filials-list-column">
                                                        <div class="filials-list-email">
                                                            <p>
                                                                <a href="mailto:{{$residence->email}}">{{$residence->email}}</a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!--top!-->

                            <!--bottom!-->
                            <div class="all-filials-list">
                                <div class="filials-list-title">{{__t('Региональные представительства')}}</div>
                                <div class="filials-list-table">
                                    <div class="filials-list-group">
                                        @foreach($residences as $index => $residence)
                                            <div data-index="{{$index}}"
                                                 data-action="change-filial-map"
                                                 class="filials-list-row"
                                                 data-lat="{{$residence->getCoordinate('lat')}}"
                                                 data-lng="{{$residence->getCoordinate('lng')}}">
                                                <div class="filials-list-column">
                                                    <div class="filials-list-name">
                                                        {{$residence->t('title')}}:
                                                    </div>
                                                </div>
                                                <div class="filials-list-column">
                                                    <div class="filials-list-phones">
                                                        <ul>
                                                            @foreach($residence->getPhones() as $phone)
                                                                <li>
                                                                    <span>{{$phone}}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!--bottom!-->

                        </div>
                        <!--overflow!-->

                    </article>
                    <div id="js-filial-map" class="map"></div>
                </section>

                @include('partials.article')

            </div>

            <div class="page-footer">
                @include('partials.form_feedback')
                @include('partials.banners')
            </div>

        </div>
        <!--page!-->

    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDWh3foNm2V5JaQ6el5AUS6T4mqLHecBtg"></script>
@stop
