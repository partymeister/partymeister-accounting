# Partymeister-Accounting — V2 API Port Audit

Based on learnings from the partymeister-core V2 API implementation (Phases 1-7).

---

## Current State Summary

### Models (6)
| Model | Relationships | Complexity |
|-------|--------------|------------|
| **AccountType** | ← Account (HasMany) | Simple CRUD |
| **Account** | → AccountType, ← Booking (from/to), ← Item (pos_cost only; items mapped via pos_configuration JSON) | Medium — has balance accessors, POS config |
| **ItemType** | ← Item (HasMany) | Simple CRUD |
| **Item** | → ItemType, → Account (pos_cost only; pos_earnings is dead code), → Item (self-ref) | Medium — has `sell()` method, revenue accessor |
| **Booking** | → Account (from/to), ← Sale | Complex — `createSales()` static method |
| **Sale** | → Booking (earnings/cost), → Item | Medium — created via Item::sell() |

### Existing V1 API Controllers (7)
All in `src/Http/Controllers/Api/`:
- AccountsController, AccountTypesController, BookingsController
- ItemsController, ItemTypesController, SalesController
- **PosInterfacesController** (special — non-CRUD, serves POS data)

### Existing Resources (12 files = 6 Resource + 6 Collection)
All in `src/Http/Resources/`.

### Existing Services (6)
All extend `Motor\Admin\Services\BaseService`. BookingService and ItemService have custom `filters()`.

### Existing Tests (16)
- 14 integration tests (7 API + 7 Backend)
- 2 unit tests (POS interface + Sales)

---

## V2 Port Plan

### Phase 1: Simple CRUD Resources

Port the simple resources first (same pattern as EventTypes in partymeister-core).

#### 1.1 AccountTypes (simplest — single field `name`)

**Files to create:**
```
src/Http/Controllers/Api/V2/AccountTypesController.php
src/Http/Resources/V2/AccountTypeResource.php
src/Http/Resources/V2/AccountTypeCollection.php
src/Http/Requests/Api/V2/AccountTypeGetRequest.php
src/Http/Requests/Api/V2/AccountTypePostRequest.php
src/Http/Requests/Api/V2/AccountTypePatchRequest.php
tests/Feature/V2AccountTypeTest.php
```

**Resource fields:**
```json
{
  "id": "(int)",
  "name": "(string)",
  "created_at": "(ISO 8601)",
  "updated_at": "(ISO 8601)"
}
```

**Validation (Post):**
```php
'name' => 'required|string|max:255'
```

**Validation (Patch):**
```php
'name' => 'sometimes|required|string|max:255'
```

#### 1.2 ItemTypes (simple — name + visibility + sort)

**Files to create:** Same pattern as AccountTypes.

**Resource fields:**
```json
{
  "id": "(int)",
  "name": "(string)",
  "is_visible": "(bool)",
  "sort_position": "(int|null)",
  "created_at": "(ISO 8601)",
  "updated_at": "(ISO 8601)"
}
```

**Validation (Post):**
```php
'name' => 'required|string|max:255',
'is_visible' => 'required|boolean',
'sort_position' => 'nullable|integer'
```

---

### Phase 2: Resources with Relationships

#### 2.1 Accounts

**Resource fields:**
```json
{
  "id": "(int)",
  "name": "(string)",
  "currency_iso_4217": "(string)",
  "has_pos": "(bool)",
  "has_card_payments": "(bool)",
  "has_coupon_payments": "(bool)",
  "pos_configuration": "(object|null)",
  "account_type": "AccountTypeResource (whenLoaded)",
  "created_at": "(ISO 8601)",
  "updated_at": "(ISO 8601)"
}
```

**Validation (Post):**
```php
'name' => 'required|string|max:255',
'account_type_id' => 'required|exists:account_types,id',
'currency_iso_4217' => 'required|string|size:3',
'has_pos' => 'nullable|boolean',
'has_card_payments' => 'nullable|boolean',
'has_coupon_payments' => 'nullable|boolean',
'pos_configuration' => 'nullable|array'
```

**Service consideration:** AccountService is basic — no eager loading configured. Should add `$loadColumns` for `account_type` relation since the resource references it.

#### 2.2 Items

**Resource fields:**
```json
{
  "id": "(int)",
  "name": "(string)",
  "description": "(string|null)",
  "internal_description": "(string|null)",
  "vat_percentage": "(float)",
  "price_with_vat": "(float)",
  "price_without_vat": "(float)",
  "cost_price_with_vat": "(float)",
  "cost_price_without_vat": "(float)",
  "currency_iso_4217": "(string)",
  "can_be_ordered": "(bool)",
  "is_visible": "(bool)",
  "sort_position": "(int|null)",
  "pos_can_book_negative_quantities": "(bool)",
  "item_type": "ItemTypeResource (whenLoaded)",
  "pos_earnings_account": "AccountResource (whenLoaded)",
  "pos_cost_account": "AccountResource (whenLoaded)",
  "pos_create_booking_for_item": "ItemResource (whenLoaded)",
  "created_at": "(ISO 8601)",
  "updated_at": "(ISO 8601)"
}
```

**Circular reference risk:** Item → Account → (no Item back). Account resource does NOT include items, so this is safe. Item → Item (self-ref via `pos_create_booking_for_item`) could recurse — use `whenLoaded()` and don't eager-load recursively.

**Service consideration:** ItemService has custom `filters()` for item_type_id. Should add `$loadColumns` for `item_type`, `pos_earnings_account`, `pos_cost_account`.

**Validation (Post):**
```php
'name' => 'required|string|max:255',
'item_type_id' => 'required|exists:item_types,id',
'vat_percentage' => 'required|numeric|min:0|max:100',
'price_with_vat' => 'required|numeric|min:0',
'price_without_vat' => 'nullable|numeric|min:0',
'cost_price_with_vat' => 'required|numeric|min:0',
'cost_price_without_vat' => 'nullable|numeric|min:0',
'currency_iso_4217' => 'required|string|size:3',
'can_be_ordered' => 'nullable|boolean',
'is_visible' => 'nullable|boolean',
'sort_position' => 'nullable|integer',
'pos_can_book_negative_quantities' => 'nullable|boolean',
'pos_cost_account_id' => 'nullable|exists:accounts,id',
'pos_create_booking_for_item_id' => 'nullable|exists:items,id',
'pos_earnings_account_id' => 'nullable|exists:accounts,id',
'description' => 'nullable|string',
'internal_description' => 'nullable|string'
```

---

### Phase 3: Complex Resources

#### 3.1 Bookings

**Resource fields:**
```json
{
  "id": "(int)",
  "description": "(string)",
  "vat_percentage": "(float)",
  "price_with_vat": "(float)",
  "price_without_vat": "(float)",
  "currency_iso_4217": "(string)",
  "is_manual_booking": "(bool)",
  "is_card_payment": "(bool)",
  "is_coupon_payment": "(bool)",
  "from_account": "AccountResource (whenLoaded)",
  "to_account": "AccountResource (whenLoaded)",
  "created_at": "(ISO 8601)",
  "updated_at": "(ISO 8601)"
}
```

**Note:** V1 BookingResource includes `sale` relation — but Sale → Booking is the owning side. In V2, omit `sale` from BookingResource to avoid circular refs. Sales reference bookings, not the other way around.

**Service consideration:** BookingService has custom `filters()` (from_account, to_account, is_manual_booking, is_card_payment, is_coupon_payment). Should add `$loadColumns` for `from_account`, `to_account`.

**Custom validation:** The `currency_compatibility` validator checks booking currency matches account currencies. This custom validator is registered in the ServiceProvider. Verify it works with V2 form requests.

**Validation (Post):**
```php
'from_account_id' => 'nullable|exists:accounts,id',
'to_account_id' => 'nullable|exists:accounts,id',
'description' => 'required|string',
'vat_percentage' => 'required|numeric|min:0|max:100',
'price_with_vat' => 'required|numeric',
'price_without_vat' => 'nullable|numeric',
'currency_iso_4217' => 'required|string|size:3|currency_compatibility',
'is_manual_booking' => 'nullable|boolean',
'is_card_payment' => 'nullable|boolean',
'is_coupon_payment' => 'nullable|boolean'
```

#### 3.2 Sales

**Resource fields:**
```json
{
  "id": "(int)",
  "quantity": "(int)",
  "vat_percentage": "(float)",
  "price_with_vat": "(float)",
  "price_without_vat": "(float)",
  "currency_iso_4217": "(string)",
  "item": "ItemResource (whenLoaded)",
  "earnings_booking": "BookingResource (whenLoaded)",
  "cost_booking": "BookingResource (whenLoaded, nullable)",
  "created_at": "(ISO 8601)",
  "updated_at": "(ISO 8601)"
}
```

**Bug in V1:** `SaleResource` currently does `new BookingResource($this->earnings_booking_id)` — passes an ID instead of the relation. Must fix in V2 to use `$this->whenLoaded('earnings_booking')`.

**Service consideration:** SaleService is basic. Should add `$loadColumns` for `item`, `earnings_booking`, `cost_booking`.

---

### Phase 4: RPC / Special Endpoints

#### 4.1 POS Interface

The POS interface is the main non-CRUD endpoint. It serves the Point of Sale app.

**Current V1 endpoints:**
- `GET /api/pos/{account}` — account info + items grouped by earnings account + last booking
- `GET /api/pos/{account}/configured` — items grouped by POS zone configuration
- `POST /api/pos/{account}` — create sale (processes cart items)

**V2 equivalent — place under RPC:**
```
GET  /api/v2/rpc/pos/{account}/items       → POS item listing
GET  /api/v2/rpc/pos/{account}/configured  → POS configured layout
POST /api/v2/rpc/pos/{account}/sell         → Process sale
```

**Controllers:** Single-action invokable controllers in `src/Http/Controllers/Api/V2/Rpc/Pos/`:
```
ItemsController.php      (__invoke)
ConfiguredController.php  (__invoke)
SellController.php        (__invoke)
```

**Response format for Items:**
```json
{
  "data": {
    "account": "AccountResource",
    "items": "ItemResource[]",
    "last_booking": "BookingResource|null"
  },
  "meta": { "api_version": "v2" }
}
```

**Business logic in SellController:**
- Calls `Booking::createSales()` which handles multi-item cart processing
- Auto-generates cost bookings via `Item::sell()`
- Handles card/coupon payment flags

#### 4.2 Account Balance (optional RPC)

Currently a backend-only route (`GET /backend/accounts/{account}/balance`). Consider exposing as:
```
GET /api/v2/rpc/accounts/{account}/balance
```
Returns computed `cash_balance`, `card_balance`, `coupon_balance` accessors.

---

### Phase 5: Routes Setup

**Add to `routes/api.php`:**
```php
// V2 CRUD resources
Route::prefix('api/v2')
    ->name('v2.')
    ->middleware([V2ErrorHandler::class, 'auth:sanctum', 'bindings'])
    ->group(function () {
        Route::apiResource('account-types', V2\AccountTypesController::class);
        Route::apiResource('accounts', V2\AccountsController::class);
        Route::apiResource('bookings', V2\BookingsController::class);
        Route::apiResource('item-types', V2\ItemTypesController::class);
        Route::apiResource('items', V2\ItemsController::class);
        Route::apiResource('sales', V2\SalesController::class);
    });

// V2 RPC endpoints
Route::prefix('api/v2/rpc')
    ->name('v2.rpc.')
    ->middleware([V2ErrorHandler::class, 'auth:sanctum', 'bindings'])
    ->group(function () {
        Route::get('pos/{account}/items', Rpc\Pos\ItemsController::class);
        Route::get('pos/{account}/configured', Rpc\Pos\ConfiguredController::class);
        Route::post('pos/{account}/sell', Rpc\Pos\SellController::class);
        Route::get('accounts/{account}/balance', Rpc\Accounts\BalanceController::class);
    });
```

**Note:** URLs use kebab-case (`account-types`, `item-types`) per V2 convention.

---

### Phase 6: Testing

#### Test files to create:
```
tests/Feature/V2AccountTypeTest.php
tests/Feature/V2AccountTest.php
tests/Feature/V2ItemTypeTest.php
tests/Feature/V2ItemTest.php
tests/Feature/V2BookingTest.php
tests/Feature/V2SaleTest.php
tests/Feature/V2PosTest.php
tests/Feature/V2AccountBalanceTest.php
```

#### Test patterns (from partymeister-core):
- Use `pest()->group('V2', 'ResourceName')`
- `beforeEach` sets up admin user with SuperAdmin role + Sanctum
- Use `assertV2CrudIndex()`, `assertV2CrudCreate()`, etc. helpers
- Test validation (422) for required fields
- Test relationship inclusion
- Test POS sell flow end-to-end

#### Factory requirements:
Existing factories cover all models:
- `AccountFactory`, `AccountTypeFactory`, `BookingFactory`
- `ItemFactory`, `ItemTypeFactory`, `SaleFactory`

Verify they work with current model fillable/casts.

---

### Phase 7: Quality & Verification

Apply same checks as partymeister-core Phase 7:

- [ ] Response envelope validation (`meta.api_version` = `v2` on all responses)
- [ ] HTTP status codes: 200 (read), 201 (create), 204 (delete), 422 (validation)
- [ ] Pint code formatting on all V2 files
- [ ] N+1 query prevention: add `$loadColumns` to services
- [ ] No circular references in resources
- [ ] PHPDoc `@mixin` on all resources
- [ ] Full test suite passes

---

## Known Issues to Fix During Port

### 1. SaleResource circular reference bug
**V1 code:** `new BookingResource($this->earnings_booking_id)` — passes ID, not model.
**V2 fix:** Use `BookingResource::make($this->whenLoaded('earnings_booking'))`.

### 2. Missing eager loading on services
No service currently configures `$loadColumns`. All resources reference relations that will cause N+1 queries without eager loading.

**Fix:**
```php
// AccountService
protected array $loadColumns = ['account_type'];

// ItemService
protected array $loadColumns = ['item_type', 'pos_earnings_account', 'pos_cost_account'];

// BookingService
protected array $loadColumns = ['from_account', 'to_account'];

// SaleService
protected array $loadColumns = ['item', 'earnings_booking', 'cost_booking'];
```

### 3. Custom validator registration
The `currency_compatibility` validator is registered in the ServiceProvider. Verify it fires correctly with V2 form requests (which extend `FormRequest`, not `Motor\Admin\Http\Requests\Request`).

### 4. Booking::createSales() business logic
This static method on the model creates bookings + sales in one transaction. For V2, this logic should either:
- Stay on the model (pragmatic, already works)
- Move to BookingService (cleaner separation)

Recommendation: Keep on model for now, wrap in service method for the controller to call.

### 5. POS configuration format
`pos_configuration` is stored as JSON (cast to array on Account model). The POS editor and configured endpoints read/write this. Ensure V2 resource properly serializes it and V2 POST endpoint accepts the same structure.

---

## File Count Estimate

| Category | Files |
|----------|-------|
| V2 Controllers (6 CRUD + 4 RPC) | 10 |
| V2 Resources (6 Resource + 6 Collection) | 12 |
| V2 Form Requests (6 × 3 = Get/Post/Patch) | 18 |
| V2 Tests | 8 |
| Route updates | 1 |
| **Total new files** | **~49** |

---

## Dependency on partymeister-core

partymeister-accounting has no direct dependency on partymeister-core models or services. The V2 port can proceed independently. The only shared dependency is:
- `Motor\Core\Http\Controllers\Api\V2\ApiController` (base controller)
- `Motor\Core\Http\Resources\V2\BaseResource` / `BaseCollection` (base resources)
- `Motor\Core\Http\Middleware\V2ErrorHandler` (error handling middleware)

These are all in motor-core, which is already on the correct branch.
