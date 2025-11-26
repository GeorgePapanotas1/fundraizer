<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

/*
|--------------------------------------------------------------------------
| Pest configuration
|--------------------------------------------------------------------------
| Apply base TestCase and RefreshDatabase to all Feature tests.
*/

uses(Tests\TestCase::class, RefreshDatabase::class)->in('Feature');
