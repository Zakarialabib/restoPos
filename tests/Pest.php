<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DuskTestCase;

uses(RefreshDatabase::class)->in('Feature');
uses(DatabaseMigrations::class)->in('Browser');
uses(DuskTestCase::class)->in('Browser');
