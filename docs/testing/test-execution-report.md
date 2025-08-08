# Route Testing Execution Report

**Generated:** 2025-08-05 23:31:18

## Summary

- **Total Test Suites:** 8
- **Passed:** 0 ✅
- **Failed:** 8 ❌
- **Total Duration:** 97.46s

## Test Suite Results

| Test Suite | Status | Duration | Timestamp |
|------------|--------|----------|-----------|
| PublicRoutesTest | ❌ FAILED | 49.31s | 2025-08-05 23:30:30 |
| AdminDashboardTest | ❌ FAILED | 9s | 2025-08-05 23:30:39 |
| AdminProductManagementTest | ❌ FAILED | 3.59s | 2025-08-05 23:30:42 |
| AdminInventoryTest | ❌ FAILED | 3.8s | 2025-08-05 23:30:46 |
| AdminKitchenTest | ❌ FAILED | 3.71s | 2025-08-05 23:30:50 |
| AdminFinanceOrderTest | ❌ FAILED | 4.1s | 2025-08-05 23:30:54 |
| AdminManagementSettingsTest | ❌ FAILED | 4.95s | 2025-08-05 23:30:59 |
| ComprehensiveRouteTest | ❌ FAILED | 19s | 2025-08-05 23:31:18 |

## Screenshots Generated

No screenshots were generated. This may indicate test failures or configuration issues.

## Failed Test Details

### PublicRoutesTest

**Error Output:**
```
Warning: TTY mode is not supported on Windows platform.

   FAIL  Tests\Browser\PublicRoutesTest
  ⨯ landing page                                9.27s
  ⨯ menu page                                   5.32s
  ⨯ tv menu page                                5.11s
  ⨯ theme showcase                              5.03s
  ⨯ theme demo                                  5.12s
  ⨯ menu grid demo                              5.40s
  ⨯ installation route                          5.01s
  ⨯ composable product route                    7.00s
  ───────────────────────────────────────────────────
   FAILED  Tests\Browse…  SessionNotCreatedException
  session not created: This version of ChromeDriver only supports Chrome version 139
Current browser version is 138.0.7204.184 with binary path C:\Program Files\Google\Chrome\Application\chrome.exe

  at vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
    126▕                     throw new NoSuchShadowRootException($message, $results);
    127▕                 case 'script timeout':
    128▕                     throw new ScriptTimeoutException($message, $results);
    129▕                 case 'session not created':
  ➜ 130▕                     throw new SessionNotCreatedException($message, $results);
    131▕                 case 'stale element reference':
    132▕                     throw new StaleElementReferenceException($message, $results);
    133▕                 case 'detached shadow root':
    134▕                     throw new DetachedShadowRootException($message, $results);

  1   vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
  2   vendor\php-webdriver\webdriver\lib\Remote\HttpCommandExecutor.php:359

  ───────────────────────────────────────────────────
   FAILED  Tests\Browse…  SessionNotCreatedException
  session not created: This version of ChromeDriver only supports Chrome version 139
Current browser version is 138.0.7204.184 with binary path C:\Program Files\Google\Chrome\Application\chrome.exe

  at vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
    126▕                     throw new NoSuchShadowRootException($message, $results);
    127▕                 case 'script timeout':
    128▕                     throw new ScriptTimeoutException($message, $results);
    129▕                 case 'session not created':
  ➜ 130▕                     throw new SessionNotCreatedException($message, $results);
    131▕                 case 'stale element reference':
    132▕                     throw new StaleElementReferenceException($message, $results);
    133▕                 case 'detached shadow root':
    134▕                     throw new DetachedShadowRootException($message, $results);

  1   vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
  2   vendor\php-webdriver\webdriver\lib\Remote\HttpCommandExecutor.php:359

  ───────────────────────────────────────────────────
   FAILED  Tests\Browse…  SessionNotCreatedException
  session not created: This version of ChromeDriver only supports Chrome version 139
Current browser version is 138.0.7204.184 with binary path C:\Program Files\Google\Chrome\Application\chrome.exe

  at vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
    126▕                     throw new NoSuchShadowRootException($message, $results);
    127▕                 case 'script timeout':
    128▕                     throw new ScriptTimeoutException($message, $results);
    129▕                 case 'session not created':
  ➜ 130▕                     throw new SessionNotCreatedException($message, $results);
    131▕                 case 'stale element reference':
    132▕                     throw new StaleElementReferenceException($message, $results);
    133▕                 case 'detached shadow root':
    134▕                     throw new DetachedShadowRootException($message, $results);

  1   vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
  2   vendor\php-webdriver\webdriver\lib\Remote\HttpCommandExecutor.php:359

  ───────────────────────────────────────────────────
   FAILED  Tests\Browse…  SessionNotCreatedException
  session not created: This version of ChromeDriver only supports Chrome version 139
Current browser version is 138.0.7204.184 with binary path C:\Program Files\Google\Chrome\Application\chrome.exe

  at vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
    126▕                     throw new NoSuchShadowRootException($message, $results);
    127▕                 case 'script timeout':
    128▕                     throw new ScriptTimeoutException($message, $results);
    129▕                 case 'session not created':
  ➜ 130▕                     throw new SessionNotCreatedException($message, $results);
    131▕                 case 'stale element reference':
    132▕                     throw new StaleElementReferenceException($message, $results);
    133▕                 case 'detached shadow root':
    134▕                     throw new DetachedShadowRootException($message, $results);

  1   vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
  2   vendor\php-webdriver\webdriver\lib\Remote\HttpCommandExecutor.php:359

  ───────────────────────────────────────────────────
   FAILED  Tests\Browse…  SessionNotCreatedException
  session not created: This version of ChromeDriver only supports Chrome version 139
Current browser version is 138.0.7204.184 with binary path C:\Program Files\Google\Chrome\Application\chrome.exe

  at vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
    126▕                     throw new NoSuchShadowRootException($message, $results);
    127▕                 case 'script timeout':
    128▕                     throw new ScriptTimeoutException($message, $results);
    129▕                 case 'session not created':
  ➜ 130▕                     throw new SessionNotCreatedException($message, $results);
    131▕                 case 'stale element reference':
    132▕                     throw new StaleElementReferenceException($message, $results);
    133▕                 case 'detached shadow root':
    134▕                     throw new DetachedShadowRootException($message, $results);

  1   vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
  2   vendor\php-webdriver\webdriver\lib\Remote\HttpCommandExecutor.php:359

  ───────────────────────────────────────────────────
   FAILED  Tests\Browse…  SessionNotCreatedException
  session not created: This version of ChromeDriver only supports Chrome version 139
Current browser version is 138.0.7204.184 with binary path C:\Program Files\Google\Chrome\Application\chrome.exe

  at vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
    126▕                     throw new NoSuchShadowRootException($message, $results);
    127▕                 case 'script timeout':
    128▕                     throw new ScriptTimeoutException($message, $results);
    129▕                 case 'session not created':
  ➜ 130▕                     throw new SessionNotCreatedException($message, $results);
    131▕                 case 'stale element reference':
    132▕                     throw new StaleElementReferenceException($message, $results);
    133▕                 case 'detached shadow root':
    134▕                     throw new DetachedShadowRootException($message, $results);

  1   vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
  2   vendor\php-webdriver\webdriver\lib\Remote\HttpCommandExecutor.php:359

  ───────────────────────────────────────────────────
   FAILED  Tests\Browse…  SessionNotCreatedException
  session not created: This version of ChromeDriver only supports Chrome version 139
Current browser version is 138.0.7204.184 with binary path C:\Program Files\Google\Chrome\Application\chrome.exe

  at vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
    126▕                     throw new NoSuchShadowRootException($message, $results);
    127▕                 case 'script timeout':
    128▕                     throw new ScriptTimeoutException($message, $results);
    129▕                 case 'session not created':
  ➜ 130▕                     throw new SessionNotCreatedException($message, $results);
    131▕                 case 'stale element reference':
    132▕                     throw new StaleElementReferenceException($message, $results);
    133▕                 case 'detached shadow root':
    134▕                     throw new DetachedShadowRootException($message, $results);

  1   vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
  2   vendor\php-webdriver\webdriver\lib\Remote\HttpCommandExecutor.php:359

  ───────────────────────────────────────────────────
   FAILED  Tests\Browse…  SessionNotCreatedException
  session not created: This version of ChromeDriver only supports Chrome version 139
Current browser version is 138.0.7204.184 with binary path C:\Program Files\Google\Chrome\Application\chrome.exe

  at vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
    126▕                     throw new NoSuchShadowRootException($message, $results);
    127▕                 case 'script timeout':
    128▕                     throw new ScriptTimeoutException($message, $results);
    129▕                 case 'session not created':
  ➜ 130▕                     throw new SessionNotCreatedException($message, $results);
    131▕                 case 'stale element reference':
    132▕                     throw new StaleElementReferenceException($message, $results);
    133▕                 case 'detached shadow root':
    134▕                     throw new DetachedShadowRootException($message, $results);

  1   vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
  2   vendor\php-webdriver\webdriver\lib\Remote\HttpCommandExecutor.php:359


  Tests:    8 failed (0 assertions)
  Duration: 48.10s

```

### AdminDashboardTest

**Error Output:**
```
Warning: TTY mode is not supported on Windows platform.

   FAIL  Tests\Browser\AdminDashboardTest
  ⨯ admin dashboard                             6.31s
  ⨯ admin p o s                                 0.06s
  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminDashb…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Ernestine Rohan, admin@test.com, 2025-08-05 23:30:35, $2y$12$irwL8lU9D1G0CXhzQQmYCuwvhrY3rkC8.SBvafqGlGs.6V6z6Y/XS, He2HEjLhPl, admin, 01987c92-965d-73ca-a6a5-aad7ef5f587a, 2025-08-05 23:30:38, 2025-08-05 23:30:38))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminDashb…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Prof. Lenore Lind DDS, admin@test.com, 2025-08-05 23:30:38, $2y$12$irwL8lU9D1G0CXhzQQmYCuwvhrY3rkC8.SBvafqGlGs.6V6z6Y/XS, MLkUd9Z2na, admin, 01987c92-9898-7373-904c-f638f05f11fc, 2025-08-05 23:30:38, 2025-08-05 23:30:38))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811


  Tests:    2 failed (0 assertions)
  Duration: 6.97s

```

### AdminProductManagementTest

**Error Output:**
```
Warning: TTY mode is not supported on Windows platform.

   FAIL  Tests\Browser\AdminProductManagementTest
  ⨯ admin products index                        1.04s
  ⨯ admin composable products                   0.04s
  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminProdu…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Dr. Brisa Daniel V, admin@test.com, 2025-08-05 23:30:41, $2y$12$ybhfhdbjI1o0GHie2KCPu.LzfyP7Ho08P5lsMl71ikUPjO2dXhECm, 8NIQeetuOo, admin, 01987c92-a5a5-7287-bcf5-b9198524cd9f, 2025-08-05 23:30:42, 2025-08-05 23:30:42))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminProdu…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Wava Renner, admin@test.com, 2025-08-05 23:30:42, $2y$12$ybhfhdbjI1o0GHie2KCPu.LzfyP7Ho08P5lsMl71ikUPjO2dXhECm, qYxqffjLXq, admin, 01987c92-a603-72e3-9126-901d62c60eac, 2025-08-05 23:30:42, 2025-08-05 23:30:42))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811


  Tests:    2 failed (0 assertions)
  Duration: 1.75s

```

### AdminInventoryTest

**Error Output:**
```
Warning: TTY mode is not supported on Windows platform.

   FAIL  Tests\Browser\AdminInventoryTest
  ⨯ admin inventory overview                    1.25s
  ⨯ admin ingredients                           0.10s
  ⨯ admin recipes                               0.07s
  ⨯ admin waste tracking                        0.09s
  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminInven…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Prof. Lyric Schuppe IV, admin@test.com, 2025-08-05 23:30:45, $2y$12$9AYh53jcZbIecoQLQcGYL.clvIRr1HwI/fgJEEZbHcIFkgiMTlORW, y4U9GBfuAj, admin, 01987c92-b3ac-7134-9129-41f3c8398249, 2025-08-05 23:30:45, 2025-08-05 23:30:45))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminInven…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Lucile Crist, admin@test.com, 2025-08-05 23:30:45, $2y$12$9AYh53jcZbIecoQLQcGYL.clvIRr1HwI/fgJEEZbHcIFkgiMTlORW, Xeq6dLDVbQ, admin, 01987c92-b454-72aa-83c1-9d4fa487b05f, 2025-08-05 23:30:45, 2025-08-05 23:30:45))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminInven…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Murray Schmitt, admin@test.com, 2025-08-05 23:30:46, $2y$12$9AYh53jcZbIecoQLQcGYL.clvIRr1HwI/fgJEEZbHcIFkgiMTlORW, EFt9kxebfM, admin, 01987c92-b4a1-724c-9f68-a75c3bdfc38d, 2025-08-05 23:30:46, 2025-08-05 23:30:46))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminInven…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Karolann Schneider, admin@test.com, 2025-08-05 23:30:46, $2y$12$9AYh53jcZbIecoQLQcGYL.clvIRr1HwI/fgJEEZbHcIFkgiMTlORW, 6fifvklJEU, admin, 01987c92-b4f2-71b0-a798-6f1b4ea4d3f9, 2025-08-05 23:30:46, 2025-08-05 23:30:46))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811


  Tests:    4 failed (0 assertions)
  Duration: 2.18s

```

### AdminKitchenTest

**Error Output:**
```
Warning: TTY mode is not supported on Windows platform.

   FAIL  Tests\Browser\AdminKitchenTest
  ⨯ admin kitchen index                         0.93s
  ⨯ admin kitchen display                       0.06s
  ⨯ admin kitchen dashboard                     0.06s
  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminKitch…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Gonzalo Quitzon, admin@test.com, 2025-08-05 23:30:49, $2y$12$KWm7GapIKECGMNT.xYubjO7mv5VNeyej3utf6wnUNvcZBzRPnA9Be, IncyTTTiqi, admin, 01987c92-c1d9-72b8-830f-11d5c950d6a1, 2025-08-05 23:30:49, 2025-08-05 23:30:49))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminKitch…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Dr. Elton Hauck, admin@test.com, 2025-08-05 23:30:49, $2y$12$KWm7GapIKECGMNT.xYubjO7mv5VNeyej3utf6wnUNvcZBzRPnA9Be, gkXYtP1i1H, admin, 01987c92-c23b-71de-b157-e226cc48d225, 2025-08-05 23:30:49, 2025-08-05 23:30:49))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminKitch…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Albina Quigley, admin@test.com, 2025-08-05 23:30:49, $2y$12$KWm7GapIKECGMNT.xYubjO7mv5VNeyej3utf6wnUNvcZBzRPnA9Be, J7xnf1Rx8c, admin, 01987c92-c278-7060-80df-035bbf4cd2a0, 2025-08-05 23:30:49, 2025-08-05 23:30:49))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811


  Tests:    3 failed (0 assertions)
  Duration: 1.94s

```

### AdminFinanceOrderTest

**Error Output:**
```
Warning: TTY mode is not supported on Windows platform.

   FAIL  Tests\Browser\AdminFinanceOrderTest
  ⨯ admin orders                                1.04s
  ⨯ admin cash register                         0.06s
  ⨯ admin purchases                             0.16s
  ⨯ admin expenses                              0.05s
  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminFinan…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Arianna Bruen, admin@test.com, 2025-08-05 23:30:53, $2y$12$ZiAu9WQEiSJKvc4Ba8c4CeiwCjYLFSSgMDEDumpmIeRTqTRNBzHQS, MUSGldYMX7, admin, 01987c92-d217-70ed-be91-2af7345a2238, 2025-08-05 23:30:53, 2025-08-05 23:30:53))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminFinan…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Johnpaul Rogahn, admin@test.com, 2025-08-05 23:30:53, $2y$12$ZiAu9WQEiSJKvc4Ba8c4CeiwCjYLFSSgMDEDumpmIeRTqTRNBzHQS, F5i8GiDd2i, admin, 01987c92-d27f-73c3-9eaa-1bfeeca85660, 2025-08-05 23:30:53, 2025-08-05 23:30:53))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminFinan…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Dr. Wiley Fahey DVM, admin@test.com, 2025-08-05 23:30:53, $2y$12$ZiAu9WQEiSJKvc4Ba8c4CeiwCjYLFSSgMDEDumpmIeRTqTRNBzHQS, xGvIunIBpb, admin, 01987c92-d317-72c9-b348-36a22b475905, 2025-08-05 23:30:53, 2025-08-05 23:30:53))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminFinan…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Luther Gusikowski, admin@test.com, 2025-08-05 23:30:53, $2y$12$ZiAu9WQEiSJKvc4Ba8c4CeiwCjYLFSSgMDEDumpmIeRTqTRNBzHQS, rPqNyLNzsy, admin, 01987c92-d34d-72d3-aec5-b714949e4d8c, 2025-08-05 23:30:53, 2025-08-05 23:30:53))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811


  Tests:    4 failed (0 assertions)
  Duration: 2.00s

```

### AdminManagementSettingsTest

**Error Output:**
```
Warning: TTY mode is not supported on Windows platform.

   FAIL  Tests\Browser\AdminManagementSettingsTest
  ⨯ admin customers                             1.64s
  ⨯ admin suppliers                             0.11s
  ⨯ admin settings                              0.11s
  ⨯ admin languages                             0.08s
  ⨯ admin theme showcase                        0.08s
  ⨯ admin schedule manager                      0.10s
  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminManag…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Velva Schiller, admin@test.com, 2025-08-05 23:30:57, $2y$12$ZNkQwf2XNy5pmDnrZPX9bu/IFkS5itqutkc5lO0mRLXrBMNLIvJ5a, 7ipIIKwU3t, admin, 01987c92-e414-7001-a260-2e2fe269695f, 2025-08-05 23:30:58, 2025-08-05 23:30:58))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminManag…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Cordie Stamm III, admin@test.com, 2025-08-05 23:30:58, $2y$12$ZNkQwf2XNy5pmDnrZPX9bu/IFkS5itqutkc5lO0mRLXrBMNLIvJ5a, VB4zri2aTm, admin, 01987c92-e4c0-72c5-b2ff-c98e1b59ff04, 2025-08-05 23:30:58, 2025-08-05 23:30:58))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminManag…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Anastacio Bins, admin@test.com, 2025-08-05 23:30:58, $2y$12$ZNkQwf2XNy5pmDnrZPX9bu/IFkS5itqutkc5lO0mRLXrBMNLIvJ5a, 8Z3M7h2AGl, admin, 01987c92-e52f-736e-9a22-af13a9999281, 2025-08-05 23:30:58, 2025-08-05 23:30:58))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminManag…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Carli Yundt, admin@test.com, 2025-08-05 23:30:58, $2y$12$ZNkQwf2XNy5pmDnrZPX9bu/IFkS5itqutkc5lO0mRLXrBMNLIvJ5a, cwin2sZERq, admin, 01987c92-e57f-72ea-ae8a-99499d197425, 2025-08-05 23:30:58, 2025-08-05 23:30:58))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminManag…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Graham Goldner, admin@test.com, 2025-08-05 23:30:58, $2y$12$ZNkQwf2XNy5pmDnrZPX9bu/IFkS5itqutkc5lO0mRLXrBMNLIvJ5a, BntJxJ3lyC, admin, 01987c92-e5d0-7000-a2e0-60934b3fc086, 2025-08-05 23:30:58, 2025-08-05 23:30:58))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\AdminManag…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Jenifer Shields PhD, admin@test.com, 2025-08-05 23:30:58, $2y$12$ZNkQwf2XNy5pmDnrZPX9bu/IFkS5itqutkc5lO0mRLXrBMNLIvJ5a, vbORzsVJpQ, admin, 01987c92-e629-71e7-a797-dfbc896b340e, 2025-08-05 23:30:58, 2025-08-05 23:30:58))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811


  Tests:    6 failed (0 assertions)
  Duration: 2.89s

```

### ComprehensiveRouteTest

**Error Output:**
```
Warning: TTY mode is not supported on Windows platform.

   FAIL  Tests\Browser\ComprehensiveRouteTest
  ⨯ all public routes                           7.70s
  ⨯ all admin routes                            0.61s
  ⨯ composable product routes                   7.15s
  ───────────────────────────────────────────────────
   FAILED  Tests\Browse…  SessionNotCreatedException
  session not created: This version of ChromeDriver only supports Chrome version 139
Current browser version is 138.0.7204.184 with binary path C:\Program Files\Google\Chrome\Application\chrome.exe

  at vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
    126▕                     throw new NoSuchShadowRootException($message, $results);
    127▕                 case 'script timeout':
    128▕                     throw new ScriptTimeoutException($message, $results);
    129▕                 case 'session not created':
  ➜ 130▕                     throw new SessionNotCreatedException($message, $results);
    131▕                 case 'stale element reference':
    132▕                     throw new StaleElementReferenceException($message, $results);
    133▕                 case 'detached shadow root':
    134▕                     throw new DetachedShadowRootException($message, $results);

  1   vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
  2   vendor\php-webdriver\webdriver\lib\Remote\HttpCommandExecutor.php:359

  ───────────────────────────────────────────────────
   FAILED  Tests\Browser\Comprehens…  QueryException
  SQLSTATE[42S22]: Column not found: 1054 Unknown column 'role' in 'field list' (Connection: mysql, SQL: insert into `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `id`, `updated_at`, `created_at`) values (Abbigail Leannon Sr., admin@test.com, 2025-08-05 23:31:10, $2y$12$Zs/LRJgPDTJX/xmlVfqPG.XekJxx0fB.SISJhIMlpKWwsFNAg7z82, mUT8ZvUYAg, admin, 01987c93-144d-735e-8f15-37d58b7c9bf6, 2025-08-05 23:31:10, 2025-08-05 23:31:10))

  at vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
     43▕             if ($this->pretending()) {
     44▕                 return true;
     45▕             }
     46▕
  ➜  47▕             $statement = $this->getPdo()->prepare($query);
     48▕
     49▕             $this->bindValues($statement, $this->prepareBindings($bindings));
     50▕
     51▕             $this->recordsHaveBeenModified();

  1   vendor\laravel\framework\src\Illuminate\Database\MySqlConnection.php:47
  2   vendor\laravel\framework\src\Illuminate\Database\Connection.php:811

  ───────────────────────────────────────────────────
   FAILED  Tests\Browse…  SessionNotCreatedException
  session not created: This version of ChromeDriver only supports Chrome version 139
Current browser version is 138.0.7204.184 with binary path C:\Program Files\Google\Chrome\Application\chrome.exe

  at vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
    126▕                     throw new NoSuchShadowRootException($message, $results);
    127▕                 case 'script timeout':
    128▕                     throw new ScriptTimeoutException($message, $results);
    129▕                 case 'session not created':
  ➜ 130▕                     throw new SessionNotCreatedException($message, $results);
    131▕                 case 'stale element reference':
    132▕                     throw new StaleElementReferenceException($message, $results);
    133▕                 case 'detached shadow root':
    134▕                     throw new DetachedShadowRootException($message, $results);

  1   vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:130
  2   vendor\php-webdriver\webdriver\lib\Remote\HttpCommandExecutor.php:359


  Tests:    3 failed (0 assertions)
  Duration: 16.52s

```

## Next Steps

### Issues to Address

1. **Review Failed Tests**: Check the error output above for specific issues
2. **Database Setup**: Ensure test database is properly seeded
3. **Authentication**: Verify admin user creation is working
4. **Dependencies**: Check if all required services are running
5. **Environment**: Verify test environment configuration

### Recommendations

1. **Review Screenshots**: Check generated screenshots for visual issues
2. **Add Test Data**: Create comprehensive seeders for better testing
3. **Error Handling**: Implement proper error pages and fallbacks
4. **Performance**: Monitor page load times and optimize slow routes
5. **Documentation**: Update route documentation based on test results

## Files Generated

- **Screenshots**: `tests/Browser/screenshots/`
- **JSON Report**: `docs/testing/test-execution-results.json`
- **Main Documentation**: `docs/testing/route-testing-report.md`

---

*This report was automatically generated by the Route Test Runner*
