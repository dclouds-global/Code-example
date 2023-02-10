// Обёртка для date-fns
// https://date-fns.org/docs/Getting-Started

import { format } from 'date-fns'
import { DATE_FORMAT } from '@/constants'

/**
 * @description Перевод секунд в 00:00:00 или 00:00
 */
export const secondsToHMS = (seconds: number, isShowHours?: boolean) => {
  const hour = 60 * 60
  const day = hour * 24
  const resultFormat = isShowHours || seconds >= hour ? DATE_FORMAT.time : DATE_FORMAT.timeHour

  return seconds >= day ? 'больше 1 дня...' : format(new Date(0, 0, 0, 0, 0, seconds), resultFormat)
}
