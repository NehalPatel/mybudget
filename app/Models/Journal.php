<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Journal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'attachments' => 'array',
    ];

    // Define the relationship with the "from" account
    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    // Define the relationship with the "to" account
    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }
}
