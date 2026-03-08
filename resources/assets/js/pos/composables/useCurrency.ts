import { computed } from 'vue'

const localeMap: Record<string, string> = {
  EUR: 'de-DE',
  USD: 'en-US',
  GBP: 'en-GB',
  CHF: 'de-CH',
  SEK: 'sv-SE',
  NOK: 'nb-NO',
  DKK: 'da-DK',
  PLN: 'pl-PL',
  CZK: 'cs-CZ',
  HUF: 'hu-HU',
}

export function useCurrency(currencyCode: string) {
  const locale = computed(() => localeMap[currencyCode] ?? 'de-DE')

  const formatter = computed(() =>
    new Intl.NumberFormat(locale.value, {
      style: 'currency',
      currency: currencyCode,
      currencyDisplay: 'symbol',
    })
  )

  function format(amount: number): string {
    return formatter.value.format(amount)
  }

  return { format }
}
