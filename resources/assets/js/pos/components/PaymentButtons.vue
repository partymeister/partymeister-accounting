<script setup lang="ts">
import type { PaymentType } from '../types'

defineProps<{
  hasCardPayments: boolean
  hasCouponPayments: boolean
  disabled: boolean
}>()

const emit = defineEmits<{
  book: [type: PaymentType]
}>()
</script>

<template>
  <div class="pos-pay-group">
    <button class="pos-pay-btn" :disabled="disabled" @click="emit('book', 'cash')">
      <i class="fa fa-coins"></i>
      <span class="pos-pay-btn-label">Cash</span>
    </button>
    <button v-if="hasCardPayments" class="pos-pay-btn" :disabled="disabled" @click="emit('book', 'card')">
      <i class="fa fa-credit-card"></i>
      <span class="pos-pay-btn-label">Card</span>
    </button>
    <button v-if="hasCouponPayments" class="pos-pay-btn" :disabled="disabled" @click="emit('book', 'coupon')">
      <i class="fa fa-pencil-alt"></i>
      <span class="pos-pay-btn-label">Coupon</span>
    </button>
  </div>
</template>
