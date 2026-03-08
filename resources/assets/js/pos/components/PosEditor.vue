<script setup lang="ts">
import { ref, onMounted } from 'vue'
import draggable from 'vuedraggable'
import { useApi } from '../composables/useApi'
import type { EditorItemType } from '../types'

interface ZoneItem {
  id: number | 'separator'
  name: string
  pos_can_book_negative_quantities: boolean
  _uid: string // unique key for drag-and-drop
}

const { fetchEditorData, saveConfig } = useApi()

const loading = ref(true)
const saving = ref(false)
const saveMessage = ref('')
const accountId = ref(0)

const zones = ref<Record<number, ZoneItem[]>>({
  1: [], 2: [], 3: [], 4: [], 5: [],
})

const itemTypes = ref<EditorItemType[]>([])

// Palette items (separator + all items grouped by type)
const paletteItems = ref<ZoneItem[]>([])

let uidCounter = 0
function makeUid(): string {
  return `uid-${++uidCounter}-${Date.now()}`
}

function toZoneItem(raw: { id: number | 'separator'; name: string; pos_can_book_negative_quantities?: boolean }): ZoneItem {
  return {
    id: raw.id,
    name: raw.name,
    pos_can_book_negative_quantities: raw.pos_can_book_negative_quantities ?? false,
    _uid: makeUid(),
  }
}

onMounted(async () => {
  const data = await fetchEditorData()
  accountId.value = data.account.id
  itemTypes.value = data.itemTypes

  // Build palette
  const palette: ZoneItem[] = [
    toZoneItem({ id: 'separator', name: 'Separator' }),
  ]
  for (const itemType of data.itemTypes) {
    if (itemType.items) {
      for (const item of itemType.items) {
        palette.push(toZoneItem(item))
      }
    }
  }
  paletteItems.value = palette

  // Load existing configuration into zones
  const config = data.account.pos_configuration ?? {}
  for (let i = 1; i <= 5; i++) {
    const zoneConfig = config[i] ?? []
    zones.value[i] = zoneConfig.map((entry: number | 'separator') => {
      if (entry === 'separator') {
        return toZoneItem({ id: 'separator', name: 'Separator' })
      }
      // Find item in item types
      for (const itemType of data.itemTypes) {
        if (itemType.items) {
          const found = itemType.items.find((item: any) => item.id === entry)
          if (found) {
            return toZoneItem(found)
          }
        }
      }
      return toZoneItem({ id: entry, name: 'INVALID ITEM' })
    })
  }

  loading.value = false
})

function removeFromZone(zoneNum: number, index: number) {
  zones.value[zoneNum].splice(index, 1)
}

function cloneItem(item: ZoneItem): ZoneItem {
  return { ...item, _uid: makeUid() }
}

async function handleSave() {
  saving.value = true
  saveMessage.value = ''

  const payload: Record<number, (number | 'separator')[]> = {}
  for (let i = 1; i <= 5; i++) {
    payload[i] = zones.value[i].map((item) => item.id)
  }

  try {
    await saveConfig(payload)
    saveMessage.value = 'Saved!'
    setTimeout(() => { saveMessage.value = '' }, 2000)
  } catch {
    saveMessage.value = 'Error saving!'
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div v-if="loading" class="pos-loading">Loading editor...</div>

  <div v-else class="pos-layout">
    <div class="pos-zones">
      <draggable
        v-for="i in 5"
        :key="i"
        v-model="zones[i]"
        :group="{ name: 'items' }"
        item-key="_uid"
        :animation="100"
        class="pos-editor-zone"
      >
        <template #item="{ element, index }">
          <div
            class="pos-item"
            :class="{ 'pos-separator': element.id === 'separator' }"
          >
            <h2>{{ element.name }}</h2>
            <button class="pos-editor-delete" @click="removeFromZone(i, index)">X</button>
            <div v-if="element.id !== 'separator'" class="pos-item-buttons">
              <button
                v-if="element.pos_can_book_negative_quantities"
                class="pos-item-btn pos-item-btn--remove"
                disabled
              >-1</button>
              <button
                v-if="element.pos_can_book_negative_quantities"
                class="pos-item-btn pos-item-btn--remove"
                disabled
              >-2</button>
              <button
                v-if="!element.pos_can_book_negative_quantities"
                class="pos-item-btn pos-item-btn--add"
                disabled
              >+1</button>
              <button
                v-if="!element.pos_can_book_negative_quantities"
                class="pos-item-btn pos-item-btn--add"
                disabled
              >+2</button>
            </div>
          </div>
        </template>
      </draggable>
    </div>

    <div class="pos-sidebar">
      <div class="pos-card pos-card-lg" style="text-align: center;">
        <h3>Editor</h3>
        <p style="margin: 10px 0;">Drag items from below into the zones on the left.</p>
        <button
          class="pos-btn pos-btn--success"
          :disabled="saving"
          @click="handleSave"
        >
          {{ saveMessage || 'Save' }}
        </button>
      </div>

      <div class="pos-editor-palette">
        <template v-for="itemType in itemTypes" :key="itemType.id">
          <h3 class="pos-editor-section-title">{{ itemType.name }}</h3>
        </template>

        <draggable
          v-model="paletteItems"
          :group="{ name: 'items', pull: 'clone', put: false }"
          :clone="cloneItem"
          item-key="_uid"
          :animation="100"
          :sort="false"
        >
          <template #item="{ element }">
            <div
              class="pos-item"
              :class="{ 'pos-separator': element.id === 'separator' }"
            >
              <h2>{{ element.name }}</h2>
              <button class="pos-editor-delete">X</button>
            </div>
          </template>
        </draggable>
      </div>
    </div>
  </div>
</template>
