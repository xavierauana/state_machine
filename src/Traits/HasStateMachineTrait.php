<?php
/**
 * Author: Xavier Au
 * Date: 13/2/2020
 * Time: 4:35 PM
 */

namespace Anacreation\StateMachine\Traits;


use Anacreation\StateMachine\Models\CurrentState;
use Anacreation\StateMachine\Models\State;
use Anacreation\StateMachine\Models\StateMachine;
use Anacreation\StateMachine\Models\TransitionLog;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;

trait HasStateMachineTrait
{
    public function getStateMachine(): ?StateMachine {
        return StateMachine::where('subject_type',
                                   self::class)->first();
    }

    public function currentState(): MorphOne {
        return $this->morphOne(CurrentState::class,
                               'subject');
    }

    public function getCurrentState(): ?State {
        return optional($this->currentState)->state;
    }

    public function setInitialState(string $state): self {
        $state = $this->getStateMachine()
                      ->states()
                      ->where('name',
                              $state)
                      ->firstOrFail();

        $this->currentState()->create(['state_id' => $state->id]);

        TransitionLog::log($this,
                           0);

        return $this;
    }

    public function perform(string $transitionName): void {
        try {
            $transition = $this->getStateMachine()
                               ->transitions()
                               ->where('name',
                                       $transitionName)
                               ->firstOrFail();

        } catch (\Exception $e) {
            throw new InvalidArgumentException('Transition cannot be found!');
        }

        try {
            $transition->fromStates()->where('name',
                                             optional($this->getCurrentState())->name)
                       ->firstOrFail();

            $toState = $transition->toState;

            $this->currentState()->update(['state_id' => $toState->id]);

            TransitionLog::log($this,
                               $transition->id);

        } catch (\Exception $e) {
            throw new InvalidArgumentException('Transition cannot be performed because of invalid state.');
        }
    }

    public static function getCurrentStateWith(string $state): Collection {
        return CurrentState::where('subject_type',
                                   self::class)->where('state_id',
            function($query) use ($state) {
                return $query->select('id')
                             ->from('states')
                             ->where('state_machine_id',
                                     (new self)->getStateMachine()->id)
                             ->where('name',
                                     $state);
            })->get();
    }
}
