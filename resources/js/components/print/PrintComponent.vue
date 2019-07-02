<template>
	<div id = 'app' class="row ">
		<invoice-print-component :invoice = 'invoice'></invoice-print-component>
	    <div v-if="show">
	    	<printer :printcomponent="$options.components.InvoicePrintComponent"  @printerStatus="printer_off = $event" :printer_off="printer_off">
	    	</printer>
	    </div>
  	</div>
</template>
<script>
	import printer from "vue-printer";
	export default {
		props : ['invoice'],
		methods:{
			payment(){
				
			},
			pdf(){
				
			},
		},
		name: "app",
		components: {
			printer,
		},
		data() {
			return {
			  printer_off: false,
			};
		},
	}
</script>