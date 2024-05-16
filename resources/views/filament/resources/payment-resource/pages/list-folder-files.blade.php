<!-- resources/views/filament/resources/payment-resource/pages/list-folder-files.blade.php -->

<div>
    <hr>
    <br>
    @csrf
    <div>
        <table class="table-auto w-full">
            <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <!-- Payment Code and Description -->
                        <td class="align-top medium-font"><b>{{ $payment->payment_code }}</b></td>
                        <td class="align-top pr-2 medium-font">{{ $payment->beneficiary_name }}</td>
                    </tr>
                    <tr>
                        <!-- Beneficiary aligned with Description -->
                        <td></td>
                        <td class="align-top pr-2 small-font bottom-border {">{{ $payment->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <hr>
</div>


<style>
    .pr-2 {
        padding-right: 2rem; /* Adjust the value as needed */
    }
    .small-font {
        font-size: 0.675rem; /* Adjust the value as needed */
        color: #4B5563;
    }
    .medium-font {
        font-size: 0.775rem; /* Adjust the value as needed */
        color: black;
    }
        .bottom-border {
        border-bottom: 1px solid #e5e7eb; /* Adjust the color as needed */
    }
</style>
