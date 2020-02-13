<?php namespace Anacreation\StateMachine\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Author: Xavier Au
 * Date: 13/2/2020
 * Time: 1:39 PM
 */
class TransitionFromState extends Model
{
    protected $fillable = ['state_id'];

    public $timestamps = false;

    // Relation
    public function state(): BelongsTo {
        return $this->BelongsTo(State::class);
    }

    public function transition(): BelongsTo {
        return $this->belongsTo(Transition::class);
    }
}
