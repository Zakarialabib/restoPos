<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Update basic info
        $user->fill([
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        // Update password if provided
        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Save changes
        $user->save();

        return response()->json([
            'message' => __('Profile updated successfully'),
            'user' => $user,
        ]);
    }

    public function orders(Request $request): JsonResponse
    {
        $orders = $request->user()
            ->orders()
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($order) => [
                'id' => $order->id,
                'number' => $order->number,
                'status' => $order->status,
                'total' => $order->total,
                'created_at' => $order->created_at,
                'points_earned' => $this->calculatePointsEarned($order->total),
                'achievement' => $this->getOrderAchievement($order->total),
                'items' => $order->items->map(fn ($item) => [
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->quantity * $item->price,
                ]),
            ]);

        // Calculate total points and determine user tier
        $totalPoints = $orders->sum('points_earned');
        $userTier = $this->getUserTier($totalPoints);
        $nextTierThreshold = $this->getNextTierThreshold($totalPoints);

        return response()->json([
            'orders' => $orders,
            'loyalty' => [
                'total_points' => $totalPoints,
                'current_tier' => $userTier,
                'next_tier_threshold' => $nextTierThreshold,
                'points_to_next_tier' => max(0, $nextTierThreshold - $totalPoints),
                'progress_percentage' => min(100, ($totalPoints / $nextTierThreshold) * 100),
            ],
        ]);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        $request->validate([
            'notifications' => ['required', 'array'],
            'notifications.order_updates' => ['required', 'boolean'],
            'notifications.promotions' => ['required', 'boolean'],
            'notifications.points_rewards' => ['required', 'boolean'],
            'language' => ['required', 'string', 'in:en,es,fr'],
            'privacy' => ['required', 'array'],
            'privacy.share_history' => ['required', 'boolean'],
            'privacy.profile_visibility' => ['required', 'boolean'],
        ]);

        $user = $request->user();
        $user->settings = array_merge($user->settings ?? [], $request->all());
        $user->save();

        return response()->json([
            'message' => __('Settings updated successfully'),
            'settings' => $user->settings,
        ]);
    }

    protected function calculatePointsEarned(float $total): int
    {
        return (int) floor($total * 10); // 10 points per currency unit
    }

    protected function getOrderAchievement(float $total): string
    {
        if ($total >= 100) {
            return 'Gold Customer';
        }
        if ($total >= 50) {
            return 'Silver Customer';
        }
        return 'Regular Customer';
    }

    protected function getUserTier(int $points): string
    {
        if ($points >= 5000) {
            return 'Diamond';
        }
        if ($points >= 2500) {
            return 'Platinum';
        }
        if ($points >= 1000) {
            return 'Gold';
        }
        if ($points >= 500) {
            return 'Silver';
        }
        return 'Bronze';
    }

    protected function getNextTierThreshold(int $points): int
    {
        if ($points < 500) {
            return 500; // Silver
        }
        if ($points < 1000) {
            return 1000; // Gold
        }
        if ($points < 2500) {
            return 2500; // Platinum
        }
        if ($points < 5000) {
            return 5000; // Diamond
        }
        return 5000; // Max tier
    }
}
