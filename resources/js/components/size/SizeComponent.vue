<template>
  <div>
    <nav-component></nav-component>
    <sidebar-component></sidebar-component>
    <div class="content-wrapper" style="min-height: 606px;">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">
              <h1 class="m-0 text-dark">Sizes</h1>
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <div  class="container-fluid">

          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Manage Sizes</h5>
              <button type="button" class="btn btn-primary float-right " title="Add new size"  data-toggle="modal" data-target="#addSize" ><i class="fa fa-plus"></i> Add Size</button>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="card-body">
                <show-sizes-component></show-sizes-component>
            </div>
            <div class="card-footer">
              <div style="position: fixed; bottom: 25px; right: 25px">
                <button type="button" class="btn btn-success  rounded-circle" title="Add new size"  data-toggle="modal" data-target="#addSize" ><i class="fa fa-plus"></i> </button>
              </div>
            </div>
            <div class="modal fade" id="addSize">
              <add-size-component></add-size-component>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  
</template>
