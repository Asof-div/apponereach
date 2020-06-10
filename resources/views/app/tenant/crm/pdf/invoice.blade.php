
<html>
	<head>
		<meta charset="utf-8">
		<title>Invoice</title><!-- 
		<link rel="stylesheet" href="style.css"> -->
		<link rel="license" href="http://www.opensource.org/licenses/mit-license/"><!-- 
		<script src="script.js"></script> -->
		<style type="text/css" media="screen">
			
			@font-face {
			    font-family: 'DejaVu Sans';
			    font-style: normal;
			    font-weight: normal;
			    src: url("{{ url('fonts/dejavu_ttf/DejaVuSans.ttf') }}") format('truetype');
			}

			*[contenteditable] { border-radius: 0.25em; min-width: 1em; outline: 0; }

			*[contenteditable] { cursor: pointer; }

			*[contenteditable]:hover, *[contenteditable]:focus, td:hover *[contenteditable], td:focus *[contenteditable], img.hover { background: #DEF; box-shadow: 0 0 1em 0.5em #DEF; }

			span[contenteditable] { display: inline-block; }


			h2 { font: bold 70% sans-serif; letter-spacing: 0.1em; text-align: center; text-transform: uppercase; }


			table { font-size: 70%; table-layout: fixed; width: 100%; }
			table { border-collapse: separate; border-spacing: 2px; }
			th, td { border-width: 1px; padding: 0.5em 0.5em; position: relative; text-align: left; }
			th, td { border-radius: 0.25em; border-style: solid; }
			th { background: #EEE; border-color: #BBB; }
			td { border-color: #DDD; }

			/* page */

			html { font: 13px/1 'Open Sans', sans-serif; overflow: auto; }
			html { background: #999; cursor: default; }

			body { box-sizing: border-box; height: 100%!important; margin: auto 0; overflow: hidden; padding: 0.5in 13px; width: 100%; position: relative;}
			body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); margin: 0; }

			/* header */

			header { margin: 0 0; }
			header:after { clear: both; content: ""; display: table; }

			header h2 { background: #EEE; border: 1.5px solid #BBB; border-radius: 0.25em; color: #000; margin: 0 0 1em; padding: 0.5em 0; }
			header address { float: left; font-size: 70%; font-style: normal;  margin: 0 1em 0em 0; width: 60%}
			header address p { margin: 0 0 0.25em; }
			header span { display: block; float: right; position: relative;  width: 40%;}
			header img { display: block; position: relative; }
			header span { margin: 0 0 1em 1em; max-height: 25%; max-width: 30%; position: relative; }
			header img { max-height: 100%; max-width: 100%; }


			article, article address, table.meta, table.inventory { margin: 0 0 1em; }
			article:after { clear: both; content: ""; display: table; }
			article h1 {font: bold 70%; clip: rect(0 0 0 0); position: relative; text-align: left; letter-spacing: 2px; margin: 0 0;}

			article address { float: left; font-size: 80%; font-weight: normal; }
			article span.img{ float: left; margin-left: 10px; width: 50px; height: 50px; display: block; }
			article span.img img { max-height: 100%; max-width: 100%; }

			/* table meta & balance */


			/* table items */

			
			table.inventory th:nth-child(1) { width: 4%; }
			table.inventory th:nth-child(2) { text-align: left; width: 18%; }
			table.inventory th:nth-child(3) { width: 40%; }
			table.inventory th:nth-child(4) { width: 6%; }



			div.col-3{
				width: 33.333%;
			}

			.left-4 { float: left; width: 30%; }
			.center-4 { float: left; width: 30%; }
			.right-4 { float: right; width: 30%; }
			.table{
			    width: 100%;
			    max-width: 100%;
			    margin-bottom: 20px;
		        border-color: #e2e7eb;
			    -webkit-border-radius: 3px;
			    -moz-border-radius: 3px;
			    border-radius: 3px;
		        background-color: transparent;
	            border-spacing: 0;
			    border-collapse: collapse;
			    display: table;
			}
			.table thead {
			    display: table-header-group;
			    vertical-align: middle;
			    border-color: inherit;
			}
			.table tbody{
			    display: table-row-group;
			    vertical-align: middle;
			    border-color: inherit;
			}
			.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
			    padding: 8px;
			    line-height: 1.42857143;
			    text-align: left;
			    vertical-align: top;
			    border-top: 1px solid #ddd;
			}
			.table-no-border>tbody>tr>td, .table-no-border>tbody>tr>th, .table-no-border>tfoot>tr>td, .table-no-border>tfoot>tr>th, .table-no-border>thead>tr>td, .table-no-border>thead>tr>th {
			    padding: 8px;
			    line-height: 1.42857143;
			    text-align: left;
			    vertical-align: top;
			    border-top: 0px solid #fff;
			}
			.contact-conts{
				width: 250px;
				display: inline-block;
				padding: 5px;
			}

			@font-face{

				font-family: 'DejaVuSans';
				font-weight: normal;
				src: url("{{ storage_path('fonts/DejaVuSans.ttf') }}") format('truetype');
			} 

			.currency-symbol{

				font-family:'DejaVuSans' !important;
				font-weight: normal !important;
			}

			/* aside */





			.add, .cut
			{
				border-width: 1px;
				display: block;
				font-size: .8rem;
				padding: 0.25em 0.5em;	
				float: left;
				text-align: center;
				width: 0.6em;
			}

			.add, .cut
			{
				background: #9AF;
				box-shadow: 0 1px 2px rgba(0,0,0,0.2);
				background-image: -moz-linear-gradient(#00ADEE 5%, #0078A5 100%);
				background-image: -webkit-linear-gradient(#00ADEE 5%, #0078A5 100%);
				border-radius: 0.5em;
				border-color: #0076A3;
				color: #FFF;
				cursor: pointer;
				font-weight: bold;
				text-shadow: 0 -1px 2px rgba(0,0,0,0.333);
			}

			.add { margin: -2.5em 0 0; }

			.add:hover { background: #00ADEE; }

			.cut { opacity: 0; position: absolute; top: 0; left: -1.5em; }
			.cut { -webkit-transition: opacity 100ms ease-in; }

			tr:hover .cut { opacity: 1; }

			@media print {
				* { -webkit-print-color-adjust: exact; }
				html { background: none; padding: 0; }
				body { box-shadow: none; margin: 0; }
				span:empty { display: none; }
				.add, .cut { display: none; }
			}

			@page { margin: 0; }

			table.terms { font-size: 70%; table-layout: fixed; width: 100%; }
			table.terms { border-collapse: separate; border-spacing: 2px; }
			table.terms th, table.terms td { border-width: 0px; padding: 0.4em 0.5em; position: relative; text-align: left; }
			table.terms th, table.terms td { border-radius: 0.25em; border-style: solid; }
			table.terms th { background: #EEE; border-color: #BBB; }
			table.terms td { border-color: #DDD; }
			table.terms td:nth-child(1) {width: 5%; vertical-align: top; text-align: left; }
			table.terms td:nth-child(2) {width: 95%;  }

			.text-left{text-align: left !important;}
			.text-right{text-align: right !important;}
			.footer {
			    width: 100%;
			    text-align: left;
			    position: fixed;
			    left: 20px;
			    right: 20px;
			    bottom: 70px;
			}

			.heading {
			    width: 100%;
			    text-align: center;
			    position: fixed;
			    left: 20px;
			    top: 0px;
			    padding: 10px 0;
			}

			.dejavu-sans{
			    font-family: 'DejaVu Sans', sans-serif;
		  	}

		</style>
	</head>
	<body>
		<header>
			<address contenteditable>
				<h1>{{ $tenant->name }}</h1>
				<p style="font-size: 11px;"><b>Corporate Address:</b>
				@if($tenant->info->address){{$tenant->info->address}},<br> @endif
				@if($tenant->info->state) {{$tenant->info->state}}.<br> @endif
				{{$tenant->info->nationality}}
				</p>

				<p style="font-size: 11px;">
				Phone: {{$tenant->info->mobile_no}}<br>
				Email: {{$tenant->info->email}}<br>
				@if($tenant->info->website) web: {{$tenant->website}} @endif
				</p>
			</address>
			<span class="img" ><img alt="" src="{{ $tenant->logo() }}" width="100"></span>
			<div style="clear: both;"></div>

			<h2 style="font-size: 13px;">Invoice - {{$invoice->title}}</h2>
			
		</header>
		<div style="width: 100%; font-size: 11px;">
			<div style="width: 40%; float: left;">
				<div> <b>Invoice Name:</b> {{$invoice->title}} </div><br>
				<div> <b>PO No #:</b> {{$invoice->po_no}} </div>
				<div> <b>Invoice No #:</b> {{$invoice->invoice_no}} </div>
			</div>
			<div style="width: 25%; float: left;">
				<div> <b>Currency:</b>  {{ $invoice->currency ? $invoice->currency->name : '' }} </div> <br>
				<div> <b>Payment Terms:</b> {{ $payment }} </div>
			</div>
			<div style="width: 35%; float: left;">
				<div> <b>Created:</b> {{$invoice->invoice_date}} </div><br>
				<div> <b>Expiration Date:</b> {{ $invoice->expiration_date }} </div><br>
				
			</div>
			<div style="clear: both; height: 0.5px; border: 2px solid #eee; border-radius: 2px; margin-bottom: 13px; font-size: 60%;""></div>

		</div>

		<div style="width: 100%; font-size: 11px;">
			<div style="width: 30%; float: left; display: inline-block;">
				<div> <b>Organization:</b> </div><br>
				<div> <b>{{$invoice->account->name}}</b> </div>
				<div> {{ $invoice->account->address }}, {{ $invoice->account->city }} </div>
				<div> {{ $invoice->account->state }}. </div>
				<div> {{ $invoice->account->country }}. </div>
				<div>
				@if($invoice->account->phone){{$invoice->account->phone}} <br>@endif
				@if($invoice->account->email){{$invoice->account->email}} <br>@endif
				@if($invoice->account->website){{$invoice->account->website}} @endif
				</div>
			</div>
			<div style="width: 65%; float: right; display: inline-block;">
				<div style="line-height: 20px;"> <b>Contacts:</b> </div>
				<div style="">
				@foreach($invoice->contacts as $contact)
					<div class="contact-conts"> 
						Name: {{$contact->name}}<br/>
						Email: {{$contact->email}}<br/>
						Title: {{$contact->title}} 
					</div>
				@endforeach				
				</div>
			</div>
			<div style="clear: both; height: 0.5px; border: 2px solid #eee; border-radius: 2px; margin-bottom: 13px; font-size: 60%;""></div>

		</div>

		<article>
			
	
			<table class="inventory table" style="font-size: 11px;">
				<thead>
					<tr>
						<th><span contenteditable>#</span></th>
						<th><span contenteditable>Item</span></th>
						<th><span contenteditable>Description</span></th>
						<th><span contenteditable>Quantity</span></th>
						<th><span contenteditable>Unit Price</span></th>
						<th><span contenteditable>Total</span></th>
					</tr>
				</thead>
				<tbody>
				
					@if($invoice->items)

						@foreach($invoice->items as $index => $item)

							<tr>

								<td><span contenteditable>{{ $index+1 }}</span></td>
								<td><span contenteditable>{{ $item->name }}</span></td>
								<td><span contenteditable>{!! $item->description !!}</span></td>
								<td class="text-right"><span >{{ number_format($item->quantity, 0)  }}</span></td>
								<td class="text-right"><span data-prefix></span><span class="currency-symbol">{{ $invoice->currency ? $invoice->currency->symbol : ''}}</span> <span>{{ number_format($item->uprice, 2) }}</span></td>
								<td class="text-right">@if($item->optional) Optional @else<span data-prefix></span><span class="currency-symbol">{{ $invoice->currency ? $invoice->currency->symbol : '' }} </span><span> {{ number_format($item->price, 2) }}</span>@endif</td>
								
							</tr>

						@endforeach
					@endif

				</tbody>
			</table>

		</article>
		<article>
			<div class="left-4" style="clear: both; position: relative; word-wrap: break-word; font-size: 11px;">
				{{$invoice->public_note}}
			</div>
			<div class="right-4" style="clear: both; position: relative;">
				<table class="" style="clear: both; font-size: 12px;">
					<tr>
	                    <th> <span contenteditable> Sub Total </span></th>
	                    <td class="text-right"> <span class="currency-symbol">{{ $invoice->currency ? $invoice->currency->symbol  : '' }}</span> <span class="grant-total">{{ number_format($invoice->sub_total, 2) }}</span></td>
	                </tr>
					<tr>
	                    <th> <span contenteditable>Vat &nbsp; {{ $invoice->vat_type == 'percentage' ? $invoice->vat_rate.' %' : '' }} {{ $invoice->vat_type == 'flat' ? ' Amount' : '' }} </span> </th>
	                    <td class="text-right"> <span class="currency-symbol">{{ $invoice->currency ? $invoice->currency->symbol  : '' }}</span> <span class="vat-total">{{ number_format($invoice->vat, 2) }}</span></td>
	                </tr>
	                @if($invoice->discount_rate > 0)
	                <tr>
	                    <th> <span contenteditable> Discount &nbsp; {{ $invoice->discount_type == 'percentage' ? $invoice->discount_rate.' %' : '' }} {{ $invoice->discount_type == 'flat' ? ' Amount' : '' }} </span></th>
	                    <td class="text-right"> <span class="currency-symbol">{{ $invoice->currency ? $invoice->currency->symbol  : '' }}</span> <span class="discount-total">{{ $invoice->discount }}</span></td>
	                </tr>
	                @endif
	                <tr>
	                    <th> <span contenteditable>Total </span></th>
	                    <td class="text-right"> <span class="currency-symbol">{{ $invoice->currency ? $invoice->currency->symbol  : '' }}</span> <span class="grant-total">{{ number_format($invoice->grand_total, 2) }}</span></td>
	                </tr>
				</table>
			</div>
		</article>

		<div style="width: 100%; z-index: 1000; ">
			<h2 style="font-size: 13px; text-align: left;">TERMS AND CONDITIONS </h2>
			<div style="width: 100%; font-size: 11px; z-index: 1000; word-wrap: break-word; margin-top: 30px;">
				{{$invoice->terms}}
			</div>
		</div>
		
		
		<footer class="footer" >
				
			

			<div style="clear: both; height: 0.5px; border: 2px solid #eee; border-radius: 2px; margin-bottom: 13px; font-size: 60%;""></div>
			<p style="font-size: 12px; text-transform: uppercase;">
				{{$invoice->footer}}
			</p>
		</footer>


	</body>
</html>

