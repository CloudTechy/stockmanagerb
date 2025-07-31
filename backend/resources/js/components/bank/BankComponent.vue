<template>
  <div>
    <nav-component></nav-component>
    <sidebar-component></sidebar-component>
    <div class="content-wrapper" style="min-height: 606px;">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1 class="m-0 text-dark">Bank</h1>
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <div  class="container-fluid">

          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Manage Bank</h5>

              <button type="button" class="btn btn-primary " title="Add new bank"  data-toggle="modal" data-target="#addBank" ><i class="fa fa-plus"></i> Add Bank</button>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="card-body">
                <show-banks-component></show-banks-component>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <div style="position: fixed; bottom: 25px; right: 25px">
                <button type="button" class="btn btn-success  rounded-circle" title="Add new bank"  data-toggle="modal" data-target="#addBank" ><i class="fa fa-plus"></i> </button>
              </div>
              
            </div>
            <!-- /.card-footer-->


            <div class="modal fade" id="addBank">
                <add-bank-component></add-bank-component>
            </div>
          </div>

          
        </div>
      </div>

    </div>
  </div>
  
</template>
