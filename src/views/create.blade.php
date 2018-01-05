<@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Braintree Payment Create</div>

                <div class="panel-body">
                    <form id="payment-forms" action="{{url('checkout')}}" method="post">
                        {{ csrf_field() }}    
                        <input type="number" name="amount" value="10">
                        <div id="payment-form"></div>
                        <input id="nonce" name="payment_method_nonce" value="" type="hidden" />
                        <button type="submit" id="submit-button">Submit Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://js.braintreegateway.com/web/dropin/1.9.2/js/dropin.min.js"></script>

<script type="text/javascript">

        
        var form = document.querySelector('#payment-forms');
        var hiddenNonceInput = document.querySelector('#nonce');
        var submitButton = document.querySelector('#submit-button');

        braintree.dropin.create(
        {
            authorization: '{{ $clientToken}}',
            container: '#payment-form',
            card: {
                cardholderName: true
            }
        },
        function (createErr, instance) {
            if (createErr) {
                console.log('Create Error', createErr);
                return;
            }
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                instance.requestPaymentMethod(function (err, payload) {
                    if (err) {
                        console.log('Request Payment Method Error', err);
                        return;
                    }
                    console.log(payload);
                    hiddenNonceInput.value=payload.nonce;
                    form.submit();
                });
                instance.on('noPaymentMethodRequestable', function () {
                    submitButton.setAttribute('disabled', true);
                });
            });
        });
    </script>
@endsection