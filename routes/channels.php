<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', fn ($user, $id) => (int) $user->id === (int) $id);

Broadcast::channel('kitchen', fn ($user) => $user->hasRole('kitchen_staff') || $user->hasRole('admin'));

Broadcast::channel('kitchen.order.{orderId}', fn ($user, $orderId) => $user->hasRole('kitchen_staff') || $user->hasRole('admin'));
