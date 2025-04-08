<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }
    protected static function bootLogsActivity(): void
    {
        static::created(function (Model $model): void {
            static::logActivity('created', $model);
        });

        static::updated(function (Model $model): void {
            static::logActivity('updated', $model);
        });

        static::deleted(function (Model $model): void {
            static::logActivity('deleted', $model);
        });
    }

    protected static function logActivity(string $action, Model $model): void
    {
        $changes = 'updated' === $action ? $model->getChanges() : null;
        $original = 'updated' === $action ? $model->getOriginal() : null;

        ActivityLog::create([
            'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
            'action' => $action,
            'description' => static::getActivityDescription($action, $model),
            'properties' => static::getActivityProperties($model),
            'old_values' => $original,
            'new_values' => $changes,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    protected static function getActivityDescription(string $action, Model $model): string
    {
        $modelName = class_basename($model);

        return match ($action) {
            'created' => "Created {$modelName} #{$model->id}",
            'updated' => "Updated {$modelName} #{$model->id}",
            'deleted' => "Deleted {$modelName} #{$model->id}",
            default => "Performed {$action} on {$modelName} #{$model->id}",
        };
    }

    protected static function getActivityProperties(Model $model): array
    {
        return [
            'model' => get_class($model),
            'id' => $model->id,
            'changes' => $model->getChanges(),
        ];
    }
}
