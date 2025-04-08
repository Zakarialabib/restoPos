<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public function log(string $action, Model $subject, ?string $description = null, array $properties = []): ActivityLog
    {
        return ActivityLog::create([
            'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'action' => $action,
            'description' => $description ?? $this->generateDescription($action, $subject),
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public function logOrderModification(Model $order, array $changes, ?string $reason = null): ActivityLog
    {
        return $this->log(
            'modified',
            $order,
            $reason ?? "Order #{$order->id} was modified",
            [
                'changes' => $changes,
                'reason' => $reason,
            ]
        );
    }

    public function logStatusChange(Model $model, string $oldStatus, string $newStatus, ?string $reason = null): ActivityLog
    {
        return $this->log(
            'status_changed',
            $model,
            $reason ?? "Status changed from {$oldStatus} to {$newStatus}",
            [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'reason' => $reason,
            ]
        );
    }

    public function logPriorityChange(Model $model, string $oldPriority, string $newPriority, ?string $reason = null): ActivityLog
    {
        return $this->log(
            'priority_changed',
            $model,
            $reason ?? "Priority changed from {$oldPriority} to {$newPriority}",
            [
                'old_priority' => $oldPriority,
                'new_priority' => $newPriority,
                'reason' => $reason,
            ]
        );
    }

    public function getModelActivities(Model $model, ?int $limit = null): Collection
    {
        $query = $model->activities()->with('causer')->latest();

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function getUserActivities(User $user, ?int $limit = null): Collection
    {
        $query = ActivityLog::where('causer_type', User::class)
            ->where('causer_id', $user->id)
            ->with('subject')
            ->latest();

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    protected function generateDescription(string $action, Model $model): string
    {
        $modelName = class_basename($model);

        return match ($action) {
            'created' => "Created {$modelName} #{$model->id}",
            'updated' => "Updated {$modelName} #{$model->id}",
            'deleted' => "Deleted {$modelName} #{$model->id}",
            'modified' => "Modified {$modelName} #{$model->id}",
            'status_changed' => "Status changed for {$modelName} #{$model->id}",
            'priority_changed' => "Priority changed for {$modelName} #{$model->id}",
            default => "Performed {$action} on {$modelName} #{$model->id}",
        };
    }
}
