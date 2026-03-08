<script setup lang="ts">
import { ref } from 'vue'
import type { PosItem } from '../types'
import { useCurrency } from '../composables/useCurrency'

const props = defineProps<{
  item: PosItem
  currencyCode: string
}>()

const emit = defineEmits<{
  add: [itemId: number, quantity: number]
}>()

const { format } = useCurrency(props.currencyCode)

const flashLeft = ref(false)
const flashRight = ref(false)

function handleClick(quantity: number, side: 'left' | 'right') {
  emit('add', props.item.id, quantity)

  // Trigger flash animation
  const flashRef = side === 'left' ? flashLeft : flashRight
  flashRef.value = false
  void document.body.offsetHeight // force reflow
  flashRef.value = true
  setTimeout(() => { flashRef.value = false }, 300)
}

const isNegative = props.item.pos_can_book_negative_quantities
const isCoupon = props.item.is_coupon_item && isNegative
</script>

<template>
  <div class="pos-item">
    <h2>{{ item.name }}</h2>
    <div class="pos-item-buttons">
      <template v-if="isNegative">
        <button
          class="pos-item-btn pos-item-btn--remove"
          :class="{ 'pos-flash': flashLeft }"
          @click="handleClick(-1, 'left')"
        >
          <template v-if="isCoupon">- {{ format(item.price_with_vat) }}</template>
          <template v-else>-1</template>
        </button>
        <button
          class="pos-item-btn pos-item-btn--remove"
          :class="{ 'pos-flash': flashRight }"
          @click="handleClick(-2, 'right')"
        >
          <template v-if="isCoupon">- {{ format(item.price_with_vat * 2) }}</template>
          <template v-else>-2</template>
        </button>
      </template>
      <template v-else>
        <button
          class="pos-item-btn pos-item-btn--add"
          :class="{ 'pos-flash': flashLeft }"
          @click="handleClick(1, 'left')"
        >+1</button>
        <button
          class="pos-item-btn pos-item-btn--add"
          :class="{ 'pos-flash': flashRight }"
          @click="handleClick(2, 'right')"
        >+2</button>
      </template>
    </div>
  </div>
</template>
