<x-mail::message>
# ¡Tu pago ha sido enviado!

Hola **{{ $transaction->participants()->where('pivot.role', 'seller')->first()->name }}**,

Buenas noticias. El pago correspondiente a la transacción **"{{ $transaction->title }}"** ha sido procesado y enviado por nuestro equipo.

A continuación, te mostramos el desglose de tu pago:

<x-mail::panel>
<div style="text-align: left;">
    <strong>Monto original de la transacción:</strong>
    <span style="float: right;">${{ number_format($transaction->amount, 2) }} USD</span>
</div>
<div style="text-align: left; color: #d9534f;">
    <strong>Comisión de servicio (15%):</strong>
    <span style="float: right;">-${{ number_format($transaction->amount * 0.15, 2) }} USD</span>
</div>
<hr>
<div style="text-align: left; font-size: 1.2em;">
    <strong>Total depositado en tu cuenta:</strong>
    <strong style="float: right;">${{ number_format($transaction->amount * 0.85, 2) }} USD</strong>
</div>
</x-mail::panel>

El dinero ha sido enviado a tu método de pago registrado en la plataforma. Por favor, ten en cuenta que los tiempos de procesamiento pueden variar dependiendo del método de pago.

Puedes ver los detalles completos de esta transacción haciendo clic en el siguiente botón:

<x-mail::button :url="route('transacciones.show', $transaction)">
Ver Transacción
</x-mail::button>

Gracias por confiar en DeltaScrow para tus transacciones seguras.

Saludos,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>