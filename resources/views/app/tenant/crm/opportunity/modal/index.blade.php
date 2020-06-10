<div class="modal fade add-opportunity-line-modal" tabindex="1" role="dialog">
    <div class="modal-dialog " role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header bg-primary"> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
                <h5 class="modal-title"> <span class="h4 text-white"> Add New Opportunity Line </span> </h5> 
            </div>
            <div class="modal-body">
            	
            	<form id="add_opportunity_line_form" action="#" method="post" >

	                <div class="row p-l-10 p-r-10">
	                	
	                	<div class="clearfix" style="border: 1px solid #ccc;">
		                	<div class="col-md-12 bg-silver-lighter" style="border-bottom: 2px solid #68A6E3;">
		                		<div class="p-t-20 p-b-25 f-s-15">
		                			<span class="text-primary "> Add Information about the line of opportunity here</span>
		                		</div>
		                	</div>

		                	<div class="col-md-12 m-t-15">
		                		<input type="hidden" name="opportunity_id" value="{{ $opportunity->id }}">
			             		<div class="form-group">
			             			<label class="f-s-14">Title</label>
			             			<input type="text" name="title" class="form-control" placeholder="Title" required="required">

			             		</div>

			             		<div class="form-group">
			             			<label class="f-s-14">Description</label>
				             		<textarea class="form-control" name="description" rows="5" required="required"></textarea>
			             		</div>

			             		<div class="form-group">
			             			<label class="f-s-14">Currency</label>
			             			<select class="form-control" name="currency" required="required">
			             				<option value=""> Select Currency </option>
			             				@foreach($currencies as $currency)
			             					<option value="{{ $currency->id }}"> {{ $currency->name }} </option>
			             				@endforeach
			             			</select>
			             		</div>

			             		<div class="form-group">
			             			<label class="f-s-14">Worth</label>
			             			<input type="text" name="worth" class="form-control" required="required">

			             		</div>

			             		<div class="form-group">
			             			<label class="f-s-14">Primary Contact Person</label>
			             			<select name="contact_person" class="form-control">
			             				<option value="" > Select Contact Person </option>
			             				@foreach($contacts as $contact)
			             					<option value="{{ $contact->id }}"> {{ $contact->name }} </option>
			             				@endforeach
			             			</select>
			             		</div>


		                	</div>
	                	</div>

	                </div>  

	                <div class="row p-l-10 p-r-10">
	                	
	                	<div class="form-group m-t-15">
	                		
	                		<span class="pull-left">
	                			<button type="reset" class="btn btn-default" data-dismiss='modal' > &times; Cancel </button>
	                		</span>

	                		<span class="pull-right">
	                			<button type="submit" class="btn btn-success" > Submit </button>
	                		</span>

	                	</div>

	                </div>

	            </form>

            </div>
        </div>
    </div>
</div>
