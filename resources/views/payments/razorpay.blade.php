<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment - VirtuGym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
    </style>
</head>
<body class="flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <div class="text-5xl mb-4">💳</div>
        <h1 class="text-2xl font-bold mb-2">Complete Payment</h1>
        <p class="text-gray-600 mb-6">Booking session with <strong>{{ $trainer->name }}</strong></p>
        
        <div class="bg-gray-100 rounded-xl p-4 mb-6">
            <p class="text-gray-600">Amount to Pay</p>
            <p class="text-3xl font-bold text-purple-600">₹{{ number_format($amount) }}</p>
        </div>
        
        <button id="payButton" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 rounded-xl font-semibold hover:shadow-lg transition">
            Pay Now
        </button>
        
        <p class="text-gray-500 text-sm mt-4">Secure payment powered by Razorpay</p>
    </div>

    <script>
        document.getElementById('payButton').onclick = function(e){
            var options = {
                key: "{{ $razorpay_key }}",
                amount: "{{ $order->amount }}",
                currency: "INR",
                name: "VirtuGym",
                description: "Trainer Booking Payment",
                image: "/images/logo.png",
                order_id: "{{ $order->id }}",
                handler: function (response) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("payment.success") }}';
                    
                    var csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    
                    var razorpayOrderId = document.createElement('input');
                    razorpayOrderId.type = 'hidden';
                    razorpayOrderId.name = 'razorpay_order_id';
                    razorpayOrderId.value = response.razorpay_order_id;
                    form.appendChild(razorpayOrderId);
                    
                    var razorpayPaymentId = document.createElement('input');
                    razorpayPaymentId.type = 'hidden';
                    razorpayPaymentId.name = 'razorpay_payment_id';
                    razorpayPaymentId.value = response.razorpay_payment_id;
                    form.appendChild(razorpayPaymentId);
                    
                    var razorpaySignature = document.createElement('input');
                    razorpaySignature.type = 'hidden';
                    razorpaySignature.name = 'razorpay_signature';
                    razorpaySignature.value = response.razorpay_signature;
                    form.appendChild(razorpaySignature);
                    
                    document.body.appendChild(form);
                    form.submit();
                },
                theme: {
                    color: "#8b5cf6"
                }
            };
            
            var rzp = new Razorpay(options);
            rzp.open();
            
            rzp.on('payment.failed', function (response){
                window.location.href = '{{ route("payment.failed") }}';
            });
        }
    </script>
</body>
</html>