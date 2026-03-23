@extends('motor-admin::layouts.backend')

@section('htmlheader_title')
    {{ trans('motor-admin::backend/global.home') }}
@endsection

@section('contentheader_title')
    {{ trans('partymeister-accounting::backend/accounts.accounts') }}
    @if (has_permission('accounts.write'))
        {!! link_to_route('backend.accounts.create', trans('partymeister-accounting::backend/accounts.new'), [], ['class' => 'pull-right float-right btn btn-sm btn-success']) !!}
    @endif
@endsection

@section('main-content')
    <div class="@boxWrapper">
        <div class="@boxHeader">
            @include('motor-admin::layouts.partials.search')
        </div>
        <!-- /.box-header -->
        @if (isset($grid))
            @include('motor-admin::grid.table')
        @endif
    </div>
@endsection

@section('view_scripts')
    <script type="module">
        $('.delete-record').click(function (e) {
            if (!confirm('{{ trans('motor-admin::backend/global.delete_question') }}')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@append