<button id="rzp-button1">Pay Now</button>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "rzp_test_EUAe5lCX3zee2d", // Enter the Key ID generated from the Dashboard
    "amount": "50000", // Amount is in currency subunits. Default is INR. 100 paisa = INR 1
    "currency": "INR",
    "name": "Your Company Name",
    "description": "Test Transaction",
    "image": "https://your-logo-url.com",
    "handler": function (response){
        alert("Payment Successful! Payment ID: " + response.razorpay_payment_id);
        // You can make an AJAX call here to save the transaction details in your server
    },
    "prefill": {
        "name": "John Doe",
        "email": "john.doe@example.com",
        "contact": "9999999999"
    },
    "theme": {
        "color": "#3399cc"
    }
};

var rzp1 = new Razorpay(options);
document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>