import { ref, computed } from 'vue'
import type { PosItem, CartEntry } from '../types'
import { useCurrency } from './useCurrency'

export function useCart(allItems: PosItem[], currencyCode: string) {
  const { format } = useCurrency(currencyCode)

  // Map of item ID → quantity
  const quantities = ref<Map<number, number>>(new Map())

  // Index items by ID for fast lookup
  const itemsById = computed(() => {
    const map = new Map<number, PosItem>()
    for (const item of allItems) {
      map.set(item.id, item)
    }
    return map
  })

  function getQuantity(itemId: number): number {
    return quantities.value.get(itemId) ?? 0
  }

  function setQuantity(itemId: number, qty: number) {
    const item = itemsById.value.get(itemId)
    if (!item) return

    if (qty <= 0 && !item.pos_can_book_negative_quantities) {
      quantities.value.delete(itemId)
    } else if (qty === 0) {
      quantities.value.delete(itemId)
    } else {
      quantities.value.set(itemId, qty)
    }
  }

  function addItem(itemId: number, quantity: number) {
    const item = itemsById.value.get(itemId)
    if (!item) return

    const currentQty = getQuantity(itemId)
    let newQty = currentQty + quantity

    // If quantity would go negative and item can't book negative, clamp to 0
    if (newQty < 0 && !item.pos_can_book_negative_quantities) {
      newQty = 0
    }

    setQuantity(itemId, newQty)

    // Handle linked deposit item
    if (item.pos_create_booking_for_item_id) {
      const depositId = item.pos_create_booking_for_item_id
      const depositItem = itemsById.value.get(depositId)
      if (depositItem) {
        const depositQty = getQuantity(depositId)

        // When parent item goes to zero, zero out deposit too
        if (newQty === 0) {
          const depositAdjust = -depositQty
          if (depositAdjust !== 0) {
            setQuantity(depositId, 0)
          }
        } else {
          setQuantity(depositId, depositQty + quantity)
        }
      }
    }

    // Trigger reactivity
    quantities.value = new Map(quantities.value)
  }

  function removeItem(itemId: number) {
    const currentQty = getQuantity(itemId)
    if (currentQty !== 0) {
      addItem(itemId, -currentQty)
    }
  }

  function clearCart() {
    quantities.value = new Map()
  }

  const cartEntries = computed<CartEntry[]>(() => {
    const entries: CartEntry[] = []
    for (const [itemId, qty] of quantities.value) {
      const item = itemsById.value.get(itemId)
      if (item && qty !== 0) {
        entries.push({ item, quantity: qty })
      }
    }
    return entries
  })

  const grandTotal = computed(() => {
    let total = 0
    for (const entry of cartEntries.value) {
      total += entry.item.price_with_vat * entry.quantity
    }
    return total
  })

  const grandTotalFormatted = computed(() => format(grandTotal.value))

  const isEmpty = computed(() => cartEntries.value.length === 0)

  function toBookingPayload(): Record<number, number> {
    const payload: Record<number, number> = {}
    for (const [itemId, qty] of quantities.value) {
      if (qty !== 0) {
        payload[itemId] = qty
      }
    }
    return payload
  }

  return {
    quantities,
    getQuantity,
    addItem,
    removeItem,
    clearCart,
    cartEntries,
    grandTotal,
    grandTotalFormatted,
    isEmpty,
    toBookingPayload,
    format,
  }
}
