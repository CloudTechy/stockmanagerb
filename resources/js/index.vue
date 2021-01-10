<template>
  <div class="animate-bottom wrapper" id="dashboard" >
      <router-view></router-view>
              <vue-progress-bar></vue-progress-bar>
      <footer class="main-footer no-print text-left">
        <div class="float-right d-none d-sm-inline">
         Version 0.1.1
        </div>
        <strong>Copyright &copy;<!--  {{ date('y') }} --> <span>SpaceHub Technologies</span>.</strong> All rights reserved.
      </footer>
  </div> 
</template>
<script type="text/javascript">
  
</script>