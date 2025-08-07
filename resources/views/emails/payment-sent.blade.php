<x-mail::message>
# {{ __('messages.email.payment_processed_title') }}

{{ __('messages.email.greeting', ['name' => $transaction->participants()->wherePivot('role', 'seller')->first()->name]) }}

{{ __('messages.email.payment_processed_intro', ['title' => $transaction->title]) }}

{{ __('messages.email.payment_breakdown') }}

<x-mail::panel>
<div style="text-align: left;">
    <strong>{{ __('messages.email.original_amount') }}</strong>
    <span style="float: right;">${{ number_format($transaction->amount, 2) }} USD</span>
</div>
<div style="text-align: left; color: #d9534f;">
    <strong>{{ __('messages.email.service_fee') }}</strong>
    <span style="float: right;">-${{ number_format($transaction->amount * 0.15, 2) }} USD</span>
</div>
<hr>
<div style="text-align: left; font-size: 1.2em;">
    <strong>{{ __('messages.email.total_deposited') }}</strong>
    <strong style="float: right;">${{ number_format($transaction->amount * 0.85, 2) }} USD</strong>
</div>
</x-mail::panel>

{{ __('messages.email.payment_sent_outro') }}

<x-mail::button :url="route('transacciones.show', $transaction)">
{{ __('messages.email.view_transaction_button') }}
</x-mail::button>

{{ __('messages.email.thank_you') }}

{{ __('messages.email.regards') }}<br>
{{ __('messages.email.team', ['app_name' => config('app.name')]) }}
</x-mail::message>