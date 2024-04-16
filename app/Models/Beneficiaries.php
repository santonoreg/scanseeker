<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Beneficiaries extends Model
{
    /**
     * Get the payments for the payment type.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
