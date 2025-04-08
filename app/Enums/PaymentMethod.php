<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case CreditCard = 'credit_card';
    case DebitCard = 'debit_card';
    case BankTransfer = 'bank_transfer';
    case MobileMoney = 'mobile_money';
    case GCash = 'gcash';
    case PayMaya = 'paymaya';

    public function label(): string
    {
        return match($this) {
            self::Cash => 'Cash',
            self::CreditCard => 'Credit Card',
            self::DebitCard => 'Debit Card',
            self::BankTransfer => 'Bank Transfer',
            self::MobileMoney => 'Mobile Money',
            self::GCash => 'GCash',
            self::PayMaya => 'PayMaya',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Cash => 'cash',
            self::CreditCard => 'credit-card',
            self::DebitCard => 'credit-card',
            self::BankTransfer => 'bank',
            self::MobileMoney => 'mobile',
            self::GCash => 'wallet',
            self::PayMaya => 'wallet',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::Cash => 'Pay with physical cash',
            self::CreditCard => 'Pay with credit card',
            self::DebitCard => 'Pay with debit card',
            self::BankTransfer => 'Pay via bank transfer',
            self::MobileMoney => 'Pay using mobile money',
            self::GCash => 'Pay using GCash',
            self::PayMaya => 'Pay using PayMaya',
        };
    }

    public function requiresVerification(): bool
    {
        return match($this) {
            self::Cash => false,
            default => true,
        };
    }

    public function isDigital(): bool
    {
        return match($this) {
            self::Cash => false,
            default => true,
        };
    }

    public function processingTime(): string
    {
        return match($this) {
            self::Cash => 'Instant',
            self::CreditCard, self::DebitCard => '1-2 minutes',
            self::BankTransfer => '1-3 business days',
            self::MobileMoney, self::GCash, self::PayMaya => '5-15 minutes',
        };
    }
}
