<script setup lang="ts">
import { ref, onMounted } from 'vue'
import type { PosAccount, PosItem, Booking, ZoneConfig, PaymentType, ZoneEntry } from '../types'
import { useApi } from '../composables/useApi'
import { useCart } from '../composables/useCart'
import PosItemTile from './PosItemTile.vue'
import CartTable from './CartTable.vue'
import LastBooking from './LastBooking.vue'
import PaymentButtons from './PaymentButtons.vue'

const { fetchViewerData, bookSale } = useApi()

const loading = ref(true)
const booking = ref(false)
const bookingSuccess = ref(false)
const account = ref<PosAccount | null>(null)
const zones = ref<ZoneConfig>({})
const lastBooking = ref<Booking | null>(null)

// Cart is initialized after data loads
let cart: ReturnType<typeof useCart> | null = null

// Expose cart reactively for the template
const cartEntries = ref<ReturnType<typeof useCart>['cartEntries']['value']>([])
const grandTotalFormatted = ref('')
const isEmpty = ref(true)
let formatCurrency: (n: number) => string = (n) => String(n)

onMounted(async () => {
  const data = await fetchViewerData()
  account.value = data.account
  zones.value = data.zones
  lastBooking.value = data.lastBooking

  // Collect all unique items from zones for cart initialization
  const allItems: PosItem[] = []
  const seen = new Set<number>()
  for (const zoneEntries of Object.values(data.zones)) {
    for (const entry of zoneEntries as ZoneEntry[]) {
      if (entry !== 'separator' && !seen.has(entry.id)) {
        seen.add(entry.id)
        allItems.push(entry)
      }
    }
  }

  cart = useCart(allItems, data.account.currency_iso_4217)
  formatCurrency = cart.format

  // Keep template refs in sync with cart computed values
  // We use a watchEffect-like pattern via the cart's computed refs
  updateCartDisplay()

  loading.value = false
})

function updateCartDisplay() {
  if (!cart) return
  cartEntries.value = cart.cartEntries.value
  grandTotalFormatted.value = cart.grandTotalFormatted.value
  isEmpty.value = cart.isEmpty.value
}

function handleAddItem(itemId: number, quantity: number) {
  if (!cart) return
  cart.addItem(itemId, quantity)
  updateCartDisplay()
}

function handleRemoveItem(itemId: number) {
  if (!cart) return
  cart.removeItem(itemId)
  updateCartDisplay()
}

function handleClear() {
  if (!cart) return
  cart.clearCart()
  updateCartDisplay()
}

async function handleBook(paymentType: PaymentType) {
  if (!cart || cart.isEmpty.value) return

  booking.value = true
  try {
    const result = await bookSale(
      cart.toBookingPayload(),
      paymentType === 'card',
      paymentType === 'coupon'
    )
    lastBooking.value = result
    cart.clearCart()
    updateCartDisplay()
    bookingSuccess.value = true
    setTimeout(() => { bookingSuccess.value = false }, 1500)
  } finally {
    booking.value = false
  }
}

const config = window.POS_CONFIG
</script>

<template>
  <div v-if="loading" class="pos-loading">Loading POS...</div>

  <div v-else-if="account" class="pos-layout">
    <div class="pos-zones">
      <div
        v-for="i in 5"
        :key="i"
        class="pos-zone"
      >
        <template v-if="zones[i]">
          <template v-for="(entry, idx) in zones[i]" :key="idx">
            <div v-if="entry === 'separator'" class="pos-separator"></div>
            <PosItemTile
              v-else
              :item="entry"
              :currency-code="account.currency_iso_4217"
              @add="handleAddItem"
            />
          </template>
        </template>
      </div>
    </div>

    <div class="pos-sidebar">
      <div class="pos-card pos-card-lg" :class="{ 'pos-booking-success': bookingSuccess }">
        <div class="pos-header">
          <h3>Current order</h3>
          <button class="pos-btn pos-btn--danger" @click="handleClear">Clear</button>
        </div>
        <CartTable
          :entries="cartEntries"
          :grand-total-formatted="grandTotalFormatted"
          :format="formatCurrency"
          @remove="handleRemoveItem"
        />
        <PaymentButtons
          :has-card-payments="account.has_card_payments"
          :has-coupon-payments="account.has_coupon_payments"
          :disabled="isEmpty || booking"
          @book="handleBook"
        />
      </div>

      <LastBooking
        :booking="lastBooking"
        :format="formatCurrency"
      />

      <a :href="config.backUrl">
        <button class="pos-btn pos-btn--primary pos-btn--lg" style="float: right;">Back</button>
      </a>
    </div>
  </div>
</template>
