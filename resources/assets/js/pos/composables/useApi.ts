import axios, { type AxiosInstance } from 'axios'
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
  const config = window.POS_CONFIG
  const client: AxiosInstance = axios.create({
    baseURL: config.baseUrl,
    headers: {
      'X-CSRF-TOKEN': config.csrfToken,
      'Accept': 'application/json',
    },
  })

  async function fetchViewerData(): Promise<ViewerData> {
    const { data } = await client.get(`/backend/pos/${config.accountId}/configured`)

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
    const { data } = await client.get(`/backend/pos/${config.accountId}/editor`)
    return {
      account: data.account,
      itemTypes: data.item_types,
    }
  }

  async function bookSale(
    items: Record<number, number>,
    isCardPayment: boolean,
    isCouponPayment: boolean
  ): Promise<Booking> {
    const payload = {
      items,
      is_card_payment: isCardPayment ? '1' : '0',
      is_coupon_payment: isCouponPayment ? '1' : '0',
    }
    const { data } = await client.post(`/backend/pos/${config.accountId}`, payload)
    return data
  }

  async function saveConfig(posConfiguration: Record<number, (number | 'separator')[]>): Promise<void> {
    await client.patch(`/backend/pos/edit/${config.accountId}`, {
      pos_configuration: posConfiguration,
    })
  }

  return { fetchViewerData, fetchEditorData, bookSale, saveConfig }
}
