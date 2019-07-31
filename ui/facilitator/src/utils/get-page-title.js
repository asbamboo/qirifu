import defaultSettings from '@/settings'

const title = defaultSettings.title || '七日付'

export default function getPageTitle(pageTitle) {
  if (pageTitle) {
    return `${pageTitle} - ${title}`
  }
  return `${title}`
}
