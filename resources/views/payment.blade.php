<!DOCTYPE html>
<html>
<head>
    <title>Payment Page</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body>
    <h1>Pembayaran</h1>
    <form method="POST" action="/payment">
        @csrf
        <button type="submit">Dapatkan Snap Token</button>
    </form>

    @isset($snapToken)
    <script type="text/javascript">
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){ console.log('success', result); },
            onPending: function(result){ console.log('pending', result); },
            onError: function(result){ console.log('error', result); }
        });
    </script>
    @endisset
</body>
</html>
