<!DOCTYPE html>
<html>
<body>
    <p>Olá {{ $order->customer->user->name }},</p>

    <p>A sua encomenda <strong>#{{ $order->id }}</strong> foi recebida com sucesso.</p>

    <p><strong>Total:</strong> €{{ number_format($order->total, 2, ',', '.') }}</p>
    <p><strong>Estado:</strong> {{ $order->status->label() }}</p>

    <p>Obrigado pela sua compra!</p>
</body>
</html>
