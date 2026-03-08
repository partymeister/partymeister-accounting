<script setup lang="ts">
import { computed } from 'vue'
import type { Booking } from '../types'

const props = defineProps<{
  booking: Booking | null
  format: (n: number) => string
}>()

const descriptionHtml = computed(() =>
  props.booking?.description?.replace(/\n/g, '<br>') ?? ''
)
</script>

<template>
  <Transition name="pos-last-booking">
    <div v-if="booking" :key="booking.id" class="pos-card pos-card-lg">
      <h3>Last booking</h3>
      <small>{{ booking.created_at }}</small>
      <table class="pos-table" style="margin-top: 10px;">
        <thead>
          <tr>
            <td>Item</td>
            <td class="pos-price">Sum</td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td v-html="descriptionHtml"></td>
            <td>&nbsp;</td>
          </tr>
          <tr class="pos-total">
            <td>Total</td>
            <td class="pos-price">{{ format(booking.price_with_vat) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </Transition>
</template>
