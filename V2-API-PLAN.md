# Partymeister-Accounting — V2 API Implementation Plan

## Overview

Port all partymeister-accounting functionality to V2 API following motor-admin V2 patterns
established in partymeister-core. No behavioral changes — pure port with clean API design.

### Target architecture

```
/api/v2/                                    — RESTful resources (auth:sanctum + V2ErrorHandler)
/api/v2/accounts/{account}/items            — nested: items from pos_configuration JSON
/api/v2/accounts/{account}/pos              — nested: POS layout (GET/PUT)
/api/v2/rpc/accounts/{account}/book         — RPC: process cart into bookings+sales
```

### V2 pattern (per CRUD resource)

- **Controller** extends `Motor\Core\Http\Controllers\Api\V2\ApiController`
- **Resource** extends `Motor\Core\Http\Resources\V2\BaseResource`
- **Collection** extends `Motor\Core\Http\Resources\V2\BaseCollection`
- **Form Requests**: separate Get/Post/Patch per resource
- **Service**: reuse existing services, add `$loadColumns` for eager loading
- **Routes**: kebab-case, `apiResource()`, `auth:sanctum` + `V2ErrorHandler`

### Response envelope

```json
// Success (single)
{ "data": { ... }, "meta": { "api_version": "v2", "message": "..." } }

// Success (collection)
{ "data": [...], "links": { ... }, "meta": { "api_version": "v2", ... } }
```

---

## Database Schema (confirmed from revision-pm-2025)

Verified against production database `revision-pm-2025` on 2026-03-27.

### items table — actual columns
| Column | Type | Notes |
|--------|------|-------|
| `pos_cost_account_id` | bigint unsigned, nullable FK | Exists but NULL for all 7 items in prod |
| `item_type_id` | bigint unsigned, nullable FK | |
| `pos_create_booking_for_item_id` | bigint unsigned, nullable FK | Self-ref for deposit auto-booking (Beer→Deposit) |
| `pos_can_book_negative_quantities` | tinyint(1) | |
| Price fields | decimal(15,4) | `price_with_vat`, `price_without_vat`, `cost_price_with_vat`, `cost_price_without_vat` |

**`pos_earnings_account_id` does NOT exist** — removed by 2017 migration. All code
referencing it (Item model relation, V1 ItemResource, ItemFactory) is dead code.

### accounts table — POS item mapping
Items belong to accounts via `pos_configuration` JSON on the Account model, NOT via FK on items:
```json
{"1": [7], "2": [1, 2, 3, 4, 5], "3": [6], "4": [], "5": [6]}
```
Keys = zone numbers, values = arrays of item IDs.

### Production data volumes (Revision 2025)
- 7 items, 2 accounts, 16 bookings, 27 sales, 1 POS account with 5 zones

---

## Prerequisites

- [x] partymeister-core V2 implementation complete (Phases 1-7)
- [x] motor-core V2 base classes available (ApiController, BaseResource, BaseCollection)
- [x] V2ErrorHandler middleware available
- [x] Pest test helpers available in root `tests/Pest.php`
- [x] phpunit.xml includes `packages/partymeister-accounting/tests/Feature`
- [ ] Pest.php updated to include accounting test path

---

## Phase 1: Test Infrastructure + Fix Factories

### 1.1 Update root `tests/Pest.php`

Add `packages/partymeister-accounting/tests/Feature` to the `uses()` call so Pest
applies TestCase + RefreshDatabase to accounting tests.

**Validation:** `php artisan test --filter=V2AccountType` should find tests (even if empty).

### 1.2 Fix factories

Current issues discovered during audit:

**a) ItemFactory has wrong model class**
```php
// WRONG: protected $model = ItemType::class;
// FIX:   protected $model = Item::class;
```

**b) All factories use `::make()->id` for FKs (returns null)**
Change to `::create()->id` or use lazy closures `fn() => Model::factory()->create()->id`.

Fix all 6 factories:
- `AccountTypeFactory` — leaf model, likely fine
- `AccountFactory` — fix `account_type_id`
- `ItemTypeFactory` — leaf model, likely fine
- `ItemFactory` — fix model class + `item_type_id`, `pos_cost_account_id`, **remove `pos_earnings_account_id`** (column doesn't exist)
- `BookingFactory` — fix `from_account_id`, `to_account_id`
- `SaleFactory` — fix `item_id`, `earnings_booking_id`, `cost_booking_id`

**Validation:** `AccountType::factory()->create()` works for all 6 models in tinker.

### 1.3 Clean up dead code referencing pos_earnings_account_id

The `pos_earnings_account_id` column was removed in the 2017 migration. Remove dead references:

- **Item model:** Remove `pos_earnings_account()` BelongsTo relation (dead — column doesn't exist)
- **V1 ItemResource:** Remove `pos_earnings_account_id` field reference
- **ItemFactory:** Remove `pos_earnings_account_id` field

---

## Phase 2: Simple CRUD Resources (AccountTypes + ItemTypes)

Simplest resources — no relations. Proves the V2 stack works for this package.

### 2.1 AccountTypes (single field: name)

**Files to create (6):**
```
src/Http/Controllers/Api/V2/AccountTypesController.php
src/Http/Resources/V2/AccountTypeResource.php
src/Http/Resources/V2/AccountTypeCollection.php
src/Http/Requests/Api/V2/AccountTypeGetRequest.php
src/Http/Requests/Api/V2/AccountTypePostRequest.php
src/Http/Requests/Api/V2/AccountTypePatchRequest.php
```

**Resource output:**
```json
{ "id": 1, "name": "Cash", "created_at": "...", "updated_at": "..." }
```

**Validation:**
- Post: `name` required|string|max:255
- Patch: `name` sometimes|required|string|max:255

### 2.2 ItemTypes (name + visibility + sort)

**Files to create (6):** Same pattern.

**Resource output:**
```json
{ "id": 1, "name": "Beverages", "is_visible": true, "sort_position": 1, "created_at": "...", "updated_at": "..." }
```

**Validation:**
- Post: `name` required, `is_visible` required|boolean, `sort_position` nullable|integer
- Patch: all fields `sometimes`

### 2.3 Register V2 routes

Add to `routes/api.php`:
```php
Route::prefix('api/v2')
    ->name('v2.')
    ->middleware([V2ErrorHandler::class, 'auth:sanctum', 'bindings'])
    ->group(function () {
        Route::apiResource('account-types', V2\AccountTypesController::class);
        Route::apiResource('item-types', V2\ItemTypesController::class);
    });
```

### 2.4 Tests

**Files to create (2):**
```
tests/Feature/V2AccountTypeTest.php
tests/Feature/V2ItemTypeTest.php
```

Test coverage per resource:
- `api_version` v2 in meta
- CRUD: index, show, create (201), update, delete (204)
- Validation: empty POST → 422

**Validation:** `php artisan test --group=V2` — all accounting tests pass.

---

## Phase 3: Resources with Relationships (Accounts + Items)

### 3.1 Accounts

**Files to create (6):** Controller, Resource, Collection, Get/Post/Patch requests.

**Resource output:**
```json
{
  "id": 1,
  "name": "Bar",
  "currency_iso_4217": "EUR",
  "has_pos": true,
  "has_card_payments": true,
  "has_coupon_payments": false,
  "pos_configuration": { ... },
  "cash_balance": 1250.00,
  "card_balance": 340.00,
  "coupon_balance": 50.00,
  "account_type": { "id": 1, "name": "Cash" },
  "created_at": "...",
  "updated_at": "..."
}
```

**Key decisions:**
- `account_type` uses `whenLoaded()` — eager loaded via service
- `cash_balance`, `card_balance`, `coupon_balance` always included (computed from accessors, max 5 accounts so 15 extra queries is fine)

**Service update:** Add `$loadColumns = ['account_type']` to AccountService.

**Validation:**
- Post: `name` required, `account_type_id` required|exists, `currency_iso_4217` required|string|size:3, booleans nullable
- Patch: all `sometimes`

### 3.2 Items

**Files to create (6):** Controller, Resource, Collection, Get/Post/Patch requests.

**Resource output:**
```json
{
  "id": 1,
  "name": "Water",
  "description": "1L PET",
  "internal_description": "Adults only",
  "vat_percentage": 19.00,
  "price_with_vat": 2.50,
  "price_without_vat": 2.10,
  "cost_price_with_vat": 0.50,
  "cost_price_without_vat": 0.42,
  "currency_iso_4217": "EUR",
  "can_be_ordered": true,
  "is_visible": true,
  "sort_position": 1,
  "pos_can_book_negative_quantities": false,
  "item_type": { ... },
  "pos_cost_account": { ... },
  "pos_create_booking_for_item": { ... },
  "created_at": "...",
  "updated_at": "..."
}
```

**Key decisions:**
- **No `pos_earnings_account`** — column was removed in 2017 migration; dead code in Item model to be cleaned up
- All relations use `whenLoaded()` — no circular refs (Account doesn't include items)
- `pos_create_booking_for_item` (self-ref) uses `whenLoaded()` — won't recurse

**Service update:** Add `$loadColumns = ['item_type', 'pos_cost_account']` to ItemService.
Note: intentionally NOT eager-loading `pos_create_booking_for_item` to avoid recursion.

**Validation:**
- Post: `name` required, `item_type_id` required|exists, prices required|numeric, `currency_iso_4217` required|string|size:3
- Patch: all `sometimes`

### 3.3 Add routes

Add `accounts` and `items` to the V2 route group.

### 3.4 Tests

**Files to create (2):**
```
tests/Feature/V2AccountTest.php
tests/Feature/V2ItemTest.php
```

Additional test cases:
- Account: verify balance fields present on index and show
- Item: verify related resources included when eager-loaded

**Validation:** All V2 tests pass.

---

## Phase 4: Complex Resources (Bookings + Sales)

### 4.1 Bookings

**Files to create (6):** Controller, Resource, Collection, Get/Post/Patch requests.

**Resource output:**
```json
{
  "id": 1,
  "description": "2x Water, 1x Beer",
  "vat_percentage": 19.00,
  "price_with_vat": 7.50,
  "price_without_vat": 6.30,
  "currency_iso_4217": "EUR",
  "is_manual_booking": false,
  "is_card_payment": false,
  "is_coupon_payment": false,
  "from_account": { ... },
  "to_account": { ... },
  "created_at": "...",
  "updated_at": "..."
}
```

**Key decisions:**
- Omit `sale` relation from BookingResource (Sale references Booking, not vice versa)
- Keep `currency_compatibility` custom validator — verify it fires with V2 FormRequest

**Service update:** Add `$loadColumns = ['from_account', 'to_account']` to BookingService.

**Validation rules:**
- Post: `description` required, `vat_percentage` required|numeric, `price_with_vat` required|numeric, `currency_iso_4217` required|string|size:3|currency_compatibility, account IDs nullable|exists
- Patch: all `sometimes`

### 4.2 Sales

**Files to create (6):** Controller, Resource, Collection, Get/Post/Patch requests.

**Resource output:**
```json
{
  "id": 1,
  "quantity": 2,
  "vat_percentage": 19.00,
  "price_with_vat": 5.00,
  "price_without_vat": 4.20,
  "currency_iso_4217": "EUR",
  "item": { ... },
  "earnings_booking": { ... },
  "cost_booking": { ... },
  "created_at": "...",
  "updated_at": "..."
}
```

**Bug fix:** V1 SaleResource passes `$this->earnings_booking_id` (an ID) instead of
`$this->whenLoaded('earnings_booking')` (the relation). Fix in V2.

**Service update:** Add `$loadColumns = ['item', 'earnings_booking', 'cost_booking']` to SaleService.

### 4.3 Add routes

Add `bookings` and `sales` to the V2 route group.

### 4.4 Tests

**Files to create (2):**
```
tests/Feature/V2BookingTest.php
tests/Feature/V2SaleTest.php
```

Additional test cases:
- Booking: test `currency_compatibility` validator rejects mismatched currencies
- Sale: verify FK cascade — deleting a booking cascades to its sales
- Sale: verify earnings_booking and cost_booking relations render correctly

**Validation:** All V2 tests pass.

---

## Phase 5: Nested Account Endpoints + RPC

### 5.1 Nested: Account Items

**Endpoint:** `GET /api/v2/accounts/{account}/items`

Returns items associated with this account via `pos_configuration` JSON. Extracts all
item IDs from the account's `pos_configuration` (across all zones), then queries items
by those IDs, ordered by sort_position.

**Implementation:** Parse `$account->pos_configuration` to collect all unique item IDs
across zones, then `Item::whereIn('id', $itemIds)->orderBy('sort_position')->get()`.

**Controller:** `src/Http/Controllers/Api/V2/Accounts/ItemsController.php`
- Single `index()` method returning `ItemCollection`

### 5.2 Nested: POS Layout

**Endpoints:**
- `GET /api/v2/accounts/{account}/pos` — configured zone layout with resolved items
- `PUT /api/v2/accounts/{account}/pos` — save zone layout

**Controller:** `src/Http/Controllers/Api/V2/Accounts/PosLayoutController.php`
- `show()` — returns zones with resolved item data + account info + last booking
- `update()` — saves `pos_configuration` on Account

**Request:** `src/Http/Requests/Api/V2/PosLayoutPutRequest.php`
- `pos_configuration` required|array

### 5.3 RPC: Book (Sell)

**Endpoint:** `POST /api/v2/rpc/accounts/{account}/book`

Processes a cart into bookings + sales via `Booking::createSales()`.

**Controller:** `src/Http/Controllers/Api/V2/Rpc/Accounts/BookController.php`
- Invokable `__invoke()` method
- Accepts `items` (array of `item_id => quantity`), `is_card_payment`, `is_coupon_payment`
- Returns the created Booking wrapped in BookingResource

**Request:** `src/Http/Requests/Api/V2/BookPostRequest.php`
- `items` required|array
- `items.*` required|integer (quantities)
- `is_card_payment` nullable|boolean
- `is_coupon_payment` nullable|boolean

### 5.4 Register routes

```php
// Nested account routes (inside V2 CRUD group)
Route::get('accounts/{account}/items', [V2\Accounts\ItemsController::class, 'index'])
    ->name('accounts.items.index');
Route::get('accounts/{account}/pos', [V2\Accounts\PosLayoutController::class, 'show'])
    ->name('accounts.pos.show');
Route::put('accounts/{account}/pos', [V2\Accounts\PosLayoutController::class, 'update'])
    ->name('accounts.pos.update');

// RPC group
Route::prefix('api/v2/rpc')
    ->name('v2.rpc.')
    ->middleware([V2ErrorHandler::class, 'auth:sanctum', 'bindings'])
    ->group(function () {
        Route::post('accounts/{account}/book', Rpc\Accounts\BookController::class)
            ->name('accounts.book');
    });
```

### 5.5 Tests

**Files to create (2):**
```
tests/Feature/V2AccountItemsTest.php
tests/Feature/V2PosTest.php  (covers POS layout + book endpoint)
```

Test cases:
- Account items: returns only items for the given account, not others
- POS layout show: returns zones with resolved items
- POS layout update: saves and returns updated config
- Book: creates booking + sales for multiple items
- Book: handles card/coupon payment flags
- Book: returns 422 for empty items array

**Validation:** All V2 tests pass.

---

## Phase 6: Quality & Verification

### 6.1 Code formatting
- Run Pint on all new V2 files
- `vendor/bin/pint packages/partymeister-accounting/src/Http/Controllers/Api/V2`
- `vendor/bin/pint packages/partymeister-accounting/src/Http/Resources/V2`
- `vendor/bin/pint packages/partymeister-accounting/src/Http/Requests/Api/V2`

### 6.2 Envelope audit
- Every V2 response has `meta.api_version = 'v2'`
- Index: 200, Store: 201, Update: 200, Destroy: 204, Validation: 422

### 6.3 N+1 prevention
- Verify `$loadColumns` on: AccountService, ItemService, BookingService, SaleService
- Run test suite and confirm no lazy-loading violations

### 6.4 Full test run
- `php artisan test --group=V2` — all tests pass
- `php artisan test` — full suite passes (no regressions)

### 6.5 Eager loading for balance accessors
- Verify Account balance accessors work correctly on index (multiple accounts)
- Monitor query count — should be acceptable for <=5 accounts

**Validation:** All quality gates pass.

---

## File Summary

| Phase | Category | Files |
|-------|----------|-------|
| 1 | Factory fixes | 6 (modify existing) |
| 1 | Pest config | 1 (modify existing) |
| 2 | AccountTypes V2 | 6 new + 1 test |
| 2 | ItemTypes V2 | 6 new + 1 test |
| 2 | Routes | 1 (modify existing) |
| 3 | Accounts V2 | 6 new + 1 test + 1 service update |
| 3 | Items V2 | 6 new + 1 test + 1 service update |
| 4 | Bookings V2 | 6 new + 1 test + 1 service update |
| 4 | Sales V2 | 6 new + 1 test + 1 service update |
| 5 | Nested controllers | 2 new |
| 5 | RPC controller | 1 new |
| 5 | Requests | 2 new |
| 5 | Tests | 2 new |
| **Total** | | **~40 new + ~10 modified** |

---

## Commit Strategy

One commit per phase, inside the submodule first, then update parent repo:
1. `Fix factories and update Pest config for V2 accounting tests`
2. `V2 API: AccountTypes and ItemTypes CRUD endpoints`
3. `V2 API: Accounts and Items with relationships and eager loading`
4. `V2 API: Bookings and Sales with currency validation`
5. `V2 API: Nested account endpoints and POS book RPC`
6. `V2 API: Quality pass — Pint, envelope audit, N+1 verification`
