<?php namespace Anacreation\StateMachine\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Author: Xavier Au
 * Date: 13/2/2020
 * Time: 1:39 PM
 */
class TransitionLog extends Model
{
    protected $table = 'transition_log';

    protected $fillable = [
        'subject_type',
        'subject_id',
        'transition_id',
        'user_id',
    ];

    public static function log($subject, int $transitionId, string $guard = null): void {
        self::create([
                         'subject_type'  => get_class($subject),
                         'subject_id'    => $subject->id,
                         'transition_id' => $transitionId,
                         'user_id'       => optional(auth($guard)->user())->id,
                     ]);
    }
}
