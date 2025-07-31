<template>
  <div>
    <nav-component></nav-component>
    <sidebar-component></sidebar-component>
    <div class="content-wrapper" style="min-height: 606px;">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1 class="m-0 text-dark">Units</h1>
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <div  class="container-fluid">

          <div class="card">
            <div class="card-header">

              <h5 class="card-title">Manage Units</h5>
              <button type="button" class="btn btn-primary float-right " title="Add new unit"  data-toggle="modal" data-target="#addUnit" ><i class="fa fa-plus"></i> Add Unit</button>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="card-body">
                <show-units-component></show-units-component>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <div style="position: fixed; bottom: 25px; right: 25px">
                <button type="button" class="btn btn-success  rounded-circle" title="Add new unit"  data-toggle="modal" data-target="#addUnit" ><i class="fa fa-plus"></i> </button>
              </div>
              
            </div>
            <!-- /.card-footer-->


            <div class="modal fade" id="addUnit">
                <add-unit-component></add-unit-component>
            </div>
          </div>

          
        </div>
      </div>

    </div>
  </div>
  
</template>
