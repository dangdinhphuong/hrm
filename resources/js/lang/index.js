import { createI18n } from 'vue-i18n';

import viMessage from './vi.json'
import enMessage from './en.json'

const messages = {
    vi: viMessage,
    en: enMessage,
}

const languages = createI18n({
    locale: 'vi',
    messages,
    fallbackLocale: 'vi',
})

const $t = languages.global.t;

export {languages, $t};
