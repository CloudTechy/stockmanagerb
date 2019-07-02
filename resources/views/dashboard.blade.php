@extends('layout.master')
@section('page-header', 'Dashboard')
@section('content')

<div>


</div>

@endsection

@section('original')

    @include('layout.dashboard_info')

    <div class="row">
      <div class="col-lg-8">
        <revenue-line-chart-component :token = 'token'></revenue-line-chart-component>
        <orders-component :token = 'token'></orders-component>
        <transaction-component  :token = 'token'></transaction-component>
      </div>
      <!-- /.col-md-4 -->
      <div class="col-lg-4">
        <daily-orders-component :token = 'token'></daily-orders-component>
        <pending-orders-component :token = 'token'  v-on:pending-orders = "updateOrders"></pending-orders-component>
        <new-product-component :token = 'token'></new-product-component>
        <top-product-component :token = 'token'></top-product-component>
        <invoice-donut-component :token = 'token'></invoice-donut-component>
      </div>
      <!-- /.col-md-4 -->
    </div>
    <div class="row">
        <div class="col-lg-6 col-12">
            <top-debtors-component :token = 'token'></top-debtors-component>
        </div>
        <div class="col-lg-6 col-12">
            <top-debt-component :token = 'token'></top-debt-component>
        </div>
    </div>
@endsection
