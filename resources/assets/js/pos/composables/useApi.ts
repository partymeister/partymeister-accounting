import { inject } from 'vue'
import { apiClient } from '@auth'
import type { PosAccount, PosItem, Booking, EditorItemType, ZoneConfig } from '../types'

interface ViewerData {
  account: PosAccount
  zones: ZoneConfig
  lastBooking: Booking | null
}

interface EditorData {
  account: { id: number; name: string; pos_configuration: Record<number, (number | 'separator')[]> }
  itemTypes: EditorItemType[]
}

export function useApi() {
  const posConfig = inject<{ mode: string; accountId: string }>('posConfig')!
  const accountId = posConfig.accountId

  async function fetchViewerData(): Promise<ViewerData> {
    const { data: response } = await apiClient.get(`/api/v2/accounts/${accountId}/pos`)
    const data = response.data

    // Parse zones: convert raw zone data to typed PosItem | 'separator'
    const zones: ZoneConfig = {}
    for (const [zoneNum, entries] of Object.entries(data.zones)) {
      zones[Number(zoneNum)] = (entries as any[]).map((entry: any) =>
        entry === 'separator' ? 'separator' : (entry as PosItem)
      )
    }

    return {
      account: data.account,
      zones,
      lastBooking: data.last_booking ?? null,
    }
  }

  async function fetchEditorData(): Promise<EditorData> {
    // Fetch POS layout and all items in parallel
    const [posResponse, itemTypesResponse, itemsResponse] = await Promise.all([
      apiClient.get(`/api/v2/accounts/${accountId}/pos`),
      apiClient.get('/api/v2/item-types', { params: { per_page: -1 } }),
      apiClient.get('/api/v2/items', { params: { per_page: -1 } }),
    ])

    const posData = posResponse.data.data
    const itemTypesRaw = itemTypesResponse.data.data as any[]
    const itemsRaw = itemsResponse.data.data as any[]

    // Group items by item_type and build EditorItemType[]
    const itemTypes: EditorItemType[] = itemTypesRaw.map((it: any) => ({
      id: it.id,
      name: it.name,
      sort_position: it.sort_position,
      items: itemsRaw
        .filter((item: any) => item.item_type?.id === it.id)
        .map((item: any) => ({
          id: item.id,
          name: item.name,
          pos_can_book_negative_quantities: item.pos_can_book_negative_quantities,
        })),
    }))

    // Convert expanded zone objects back to raw config (IDs and 'separator')
    const posConfiguration: Record<number, (number | 'separator')[]> = {}
    for (const [zoneNum, entries] of Object.entries(posData.zones)) {
      posConfiguration[Number(zoneNum)] = (entries as any[]).map((entry: any) =>
        entry === 'separator' ? 'separator' : entry.id
      )
    }

    return {
      account: {
        id: posData.account.id,
        name: posData.account.name,
        pos_configuration: posConfiguration,
      },
      itemTypes,
    }
  }

  async function bookSale(
    items: Record<number, number>,
    isCardPayment: boolean,
    isCouponPayment: boolean
  ): Promise<Booking> {
    // Expand item quantities to repeated IDs: {3: 2} becomes [3, 3]
    const expandedItems: number[] = []
    for (const [itemId, qty] of Object.entries(items)) {
      const id = Number(itemId)
      const count = Math.abs(qty)
      for (let i = 0; i < count; i++) {
        expandedItems.push(id)
      }
    }

    const payload = {
      items: expandedItems,
      is_card_payment: isCardPayment,
      is_coupon_payment: isCouponPayment,
    }
    const { data: response } = await apiClient.post(`/api/v2/rpc/accounts/${accountId}/book`, payload)
    return response.data
  }

  async function saveConfig(posConfiguration: Record<number, (number | 'separator')[]>): Promise<void> {
    await apiClient.put(`/api/v2/accounts/${accountId}/pos`, {
      pos_configuration: posConfiguration,
    })
  }

  return { fetchViewerData, fetchEditorData, bookSale, saveConfig }
}
