<?php namespace Anacreation\StateMachine\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Author: Xavier Au
 * Date: 13/2/2020
 * Time: 1:39 PM
 */
class CurrentState extends Model
{
    // Relation

    protected $fillable = ['state_id'];

    public function state(): BelongsTo {
        return $this->BelongsTo(State::class);
    }

    public function subject(): MorphTo {
        return $this->morphTo();
    }
}
