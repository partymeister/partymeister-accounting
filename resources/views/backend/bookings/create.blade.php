@extends('motor-admin::layouts.backend')

@section('htmlheader_title')
    {{ trans('motor-admin::backend/global.home') }}
@endsection

@section('contentheader_title')
    {{ trans('partymeister-accounting::backend/bookings.new') }}
    {!! link_to_route('backend.bookings.index', trans('motor-admin::backend/global.back'), [], ['class' => 'pull-right float-right btn btn-sm btn-danger']) !!}
@endsection

@section('main-content')
    @include('motor-admin::errors.list')
    @include('partymeister-accounting::backend.bookings.form')
@endsection