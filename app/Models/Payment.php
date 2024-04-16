<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Payment extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        '',
        'envelope_code',
        'file_code',
        'payment_code',
        'description',
        'relative_files',
        'has_relatives',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasVerifiedEmail();
    }

    /**
     * Get the payment type.
     */
    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentTypes::class, 'payment_type_id', 'id');
    }

    /**
     * Get the beneficiary.
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiaries::class, 'beneficiary_id', 'id');
    }

    public function getPaymentTypeTypeAttribute()
    {
        return $this->paymentType()->type ?? null; // Assuming 'type' is the attribute you want from the related model
    }

}
