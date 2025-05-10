<!DOCTYPE html>
<html>
<head>
    <title>Midtrans Payment</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body>
    <button id="pay-button">Pay Now</button>

    <script>
        document.getElementById('pay-button').onclick = function () {
            fetch('/payment/charge', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.snap_token) {
                    snap.pay(data.snap_token);
                } else {
                    alert('Payment failed: ' + data.error);
                }
            });
        };
    </script>
</body>
</html>
