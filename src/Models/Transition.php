<?php namespace Anacreation\StateMachine\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * Author: Xavier Au
 * Date: 13/2/2020
 * Time: 1:39 PM
 */
class Transition extends Model
{
    protected $fillable = [
        'name',
        'to_state_id',
    ];

    // Relation
    public function fromStates(): BelongsToMany {
        return $this->belongsToMany(State::class,
                                    'transition_from_states');
    }

    public function transitionFromStates(): HasMany {
        return $this->hasMany(TransitionFromState::class);
    }

    public function toState(): BelongsTo {
        return $this->belongsTo(State::class);
    }

    public function machine(): BelongsTo {
        return $this->belongsTo(StateMachine::class,
                                'state_machine_id');
    }

    public function modify(string $transitionName, array $fromStates, string $toState): Transition {
        $_toState = $this->machine->states()->where('name',
                                                    $toState)->firstOrFail();

        $_fromStatesId = [];

        foreach($fromStates as $fromState) {
            $_fromStatesId[] = $this->machine->states()->where('name',
                                                               $fromState)->firstOrFail()->id;
        }

        DB::beginTransaction();

        try {
            $this->update([
                              'name'     => $transitionName,
                              'state_id' => $_toState->id,
                          ]);

            $this->fromStates()->sync($_fromStatesId);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this;
    }


}
