<script setup lang="ts">
import type { CartEntry } from '../types'

defineProps<{
  entries: CartEntry[]
  grandTotalFormatted: string
  format: (n: number) => string
}>()

const emit = defineEmits<{
  remove: [itemId: number]
}>()
</script>

<template>
  <table class="pos-table">
    <thead>
      <tr>
        <td>Item</td>
        <td>&nbsp;</td>
        <td class="pos-price">Price</td>
      </tr>
    </thead>
    <tbody>
      <TransitionGroup name="cart-row">
        <tr
          v-for="entry in entries"
          :key="entry.item.id"
          class="pos-table-row"
        >
          <td>{{ entry.quantity }}x {{ entry.item.name }}</td>
          <td class="pos-price">{{ format(entry.item.price_with_vat * entry.quantity) }}</td>
          <td style="text-align: right; width: 50px;">
            <button
              v-if="!entry.item.pos_can_book_negative_quantities"
              class="pos-delete-btn"
              @click="emit('remove', entry.item.id)"
            >X</button>
          </td>
        </tr>
      </TransitionGroup>
      <tr class="pos-total">
        <td>Total</td>
        <td class="pos-price">{{ grandTotalFormatted }}</td>
        <td>&nbsp;</td>
      </tr>
    </tbody>
  </table>
</template>
