<h3>Confirm Payment Method</h3>
<form action="{{ route('checkout.processPayment') }}" method="POST">
    @csrf
    <p>Pay with Afterpay</p>
    <button type="submit" class="btn btn-success">Proceed to Payment</button>
</form>