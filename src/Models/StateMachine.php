<?php namespace Anacreation\StateMachine\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Author: Xavier Au
 * Date: 13/2/2020
 * Time: 1:39 PM
 */
class StateMachine extends Model
{
    protected $fillable = [
        'name',
        'subject_type',
    ];

    // Relation

    public function states(): HasMany {
        return $this->hasMany(State::class);
    }

    public function transitions(): HasMany {
        return $this->hasMany(Transition::class);
    }

    public function subject(): MorphTo {
        return $this->morphTo();
    }

    public function addState(string $stateName): StateMachine {
        $this->states()->create(['name' => $stateName]);

        return $this;
    }

    public function getStates(): Collection {
        return $this->states;
    }

    public function addTransition(string $transitionName, array $fromStates, string $toState
    ): StateMachine {
        $_fromStates = [];

        foreach($fromStates as $fromState) {
            $_fromStates[] = $this->states()
                                  ->where('name',
                                          $fromState)
                                  ->firstOrFail();
        }

        $_toState = $this->states()->where('name',
                                           $toState)->firstOrFail();;

        DB::beginTransaction();

        try {
            $transition = $this->transitions()->create([
                                                           'name'        => $transitionName,
                                                           'to_state_id' => $_toState->id,
                                                       ]);
            foreach($_fromStates as $state) {
                $transition->transitionFromStates()->create([
                                                                'state_id' => $state->id,
                                                            ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


        return $this;
    }

    public function getTransitions(): Collection {
        return $this->transitions()->with('fromStates',
                                          'toState')->get();
    }
}
