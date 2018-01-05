@extends('layouts.app')
@section('styles')
@endsection
@section('content')
<div class="container">
	<div class="row">
		@foreach($paymentMethod as $key=>$value)
		<div class="col-lg-4 col-md-6 col-sm-6">
			<div class="card-remove" >
				@if($value->isDefault())
				Default
				@else
				<a href="javascript:void(0)" class=" btn btn-primary action " data-toggle="modal" data-target="#modalPopup" data-action="{{route('braintree.update',$value->token)}}" data-method="PUT" data-msg="<p>
					Are you sure you want to set default  <span>Ending in {{$value->last4}}</span><br>{{$value->cardType}} card? 
				</p>" > Default<i class="fa fa-check" title="Default Card"></i></a>
				<a href="javascript:void(0)" class="btn btn-danger action " data-toggle="modal" data-target="#modalPopup" data-action="{{route('braintree.destroy',$value->token)}}" data-method="DELETE" data-msg="<p>
					<span>Are you sure you want to delete Card Ending in {{$value->last4}}</span><br>{{$value->cardType}} card ?</p>"> Delete <i class="fa fa-close" title="Delete Card"></i></a>
					@endif
				</div>
				<div class="card-details" id="card-{{$value->token}}">
					<img class="text-default " src="{{$value->imageUrl}}"/>
					<span>Ending in {{$value->last4}}</span>
					<br>
					{{$value->cardType}}
				</div>
			</div>
			@endforeach
		</div>
	</div>

	<div id="modalPopup" class="modal fade" data-backdrop="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Confirmation</h5>
				</div>
				<div class="modal-body text-center p-lg">
					<p>
						Are you sure you want to set default  <span> Ending in {{$value->last4}}"</span><br><small>{{$value->cardType}}</small> card? 
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn dark-white pull-left" data-dismiss="modal">No</button>
					<form action="{{route('braintree.update',$value->token)}}" method="POST" class="deleteCardPatment">
						<input type="hidden" name="_method" value="PUT">
						{{ csrf_field() }}

						<button type="submit" class="btn btn-primary">Yes</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	@endsection
	@section('scripts')
	<script type="text/javascript">
		$(document).on('click','.action',function(){
			var $this= $(this);
			$('.modal-body').html($this.data('msg'));
			$("input[name='_method']").val($this.data('method'));
			$('.modal-footer').find('form').prop('action',$this.data('action'));
			$(":submit").removeClass('btn-primary btn-danger');
			if($this.data('method')=="PUT"){
				$(":submit").addClass('btn-primary');
			}
			else{
				$(":submit").addClass('btn-danger');

			}

		});
	</script>
	@endsection
