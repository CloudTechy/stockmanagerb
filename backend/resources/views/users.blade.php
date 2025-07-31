@extends('layout.master')
@section('page-header', 'Account Summary')
@section('content')



<user-summary-component :token = 'token'></user-summary-component>


@endsection
