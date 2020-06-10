@if(Session::has('fails'))

<script type="text/javascript">

	//swal("Good job!", "", "danger");

	alertify.set({ delay: 5000 });
	alertify.error("{{Session::get('fails')}}");
	
</script>

@endif