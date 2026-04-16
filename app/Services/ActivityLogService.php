<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public function log(
        User $user,
        string $action,
        string $description,
        Model|string|null $subject = null,
        array $properties = []
    ): ActivityLog {
        $attributes = [
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description,
            'properties' => $properties,
        ];

        if ($subject instanceof Model) {
            $attributes['subject_type'] = $subject->getMorphClass();
            $attributes['subject_id'] = $subject->getKey();
        } elseif (is_string($subject)) {
            $attributes['subject_type'] = $subject;
        }

        return ActivityLog::create($attributes);
    }
}
