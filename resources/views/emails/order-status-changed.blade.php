<!DOCTYPE html>
<html>
<body>
    <p>Olá {{ $order->customer->user->name }},</p>

    <p>O estado da sua encomenda <strong>#{{ $order->id }}</strong> foi atualizado.</p>

    <p><strong>Estado anterior:</strong> {{ $previousStatus->label() }}</p>
    <p><strong>Estado atual:</strong> {{ $order->status->label() }}</p>

    <p>Obrigado por comprar na nossa loja!</p>
</body>
</html>
