<template>
    <div>
      <h1>Home View</h1>
      <HelloComponent />
    </div>
  </template>
  
  <script>
  import HelloComponent from '@/components/HelloComponent.vue'
  
  export default {
    name: 'HomeView',
    components: {
      HelloComponent,
    }
  }
  </script>
  