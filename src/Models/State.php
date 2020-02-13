<?php namespace Anacreation\StateMachine\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Author: Xavier Au
 * Date: 13/2/2020
 * Time: 1:39 PM
 */
class State extends Model
{
    protected $fillable = [
        'name',
    ];

    // Relation
    public function machine(): BelongsTo {
        return $this->belongsTo(StateMachine::class);
    }
}
