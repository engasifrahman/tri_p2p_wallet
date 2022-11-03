<x-mail::message>
Hi there!

<x-mail::panel>
    Good news! You have received money from {{ $transaction->sender->name}}.
</x-mail::panel>

Please check the details below

<x-mail::table>
    | Sender Name                       | Sender Account                    | Received Amount                                                            |
    | --------------------------------- |:---------------------------------:| --------------------------------------------------------------------------:|
    | {{ $transaction->sender->name}}   | {{ $transaction->sender->phone }} | {{ $transaction->received_amount }} {{ $transaction->receiver->currency}}  |
</x-mail::table>

<x-mail::button :url="url('/dashboard/transactions')">
    More Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
