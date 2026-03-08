export interface PosItem {
  id: number
  name: string
  price_with_vat: number
  pos_can_book_negative_quantities: boolean
  pos_create_booking_for_item_id: number | null
  is_coupon_item: boolean
}

export interface PosAccount {
  id: number
  name: string
  currency_iso_4217: string
  has_card_payments: boolean
  has_coupon_payments: boolean
}

export interface CartEntry {
  item: PosItem
  quantity: number
}

export interface Booking {
  id: number
  description: string
  price_with_vat: number
  price_without_vat: number
  currency_iso_4217: string
  created_at: string
  is_card_payment: boolean
  is_coupon_payment: boolean
}

export interface EditorItemType {
  id: number
  name: string
  sort_position: number
  items: EditorItem[]
}

export interface EditorItem {
  id: number | 'separator'
  name: string
  pos_can_book_negative_quantities: boolean
}

export type ZoneEntry = PosItem | 'separator'
export type ZoneConfig = Record<number, ZoneEntry[]>

export type PaymentType = 'cash' | 'card' | 'coupon'

declare global {
  interface Window {
    POS_CONFIG: {
      accountId: number
      csrfToken: string
      baseUrl: string
      mode: 'viewer' | 'editor'
      backUrl: string
    }
  }
}
