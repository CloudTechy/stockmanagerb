<template>
	<div class="row ">
    	<div class="col-12">
	      <a  ref = 'print' @click.prevent = 'print' target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
		</div>
  	</div>
</template>
<script>
	export default {
		props : ['invoice'],
		methods:{
			payment(){
				
			},
			pdf(){ 
				
			},
			print(){
				this.$session.start()
                Fire.$emit('printing')
                var a = this.$session.set('session',this.invoice)
                this.data_invoice = this.$session.get('invoice')
                this.$root.invoice = this.invoice;
                this.$router.push('/print_invoice')
                console.log('printing');

			}
		},
		
		data() {
			return {
				show : true,
				data_invoice : ''
			};
		},
		watch : {
			
		}
	}
</script>