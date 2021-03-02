import Vue from 'vue';
import VueI18n from 'vue-i18n';
import en from './en/en';
import es from './es/es';
import pt from './pt/pt';
import de from './de/de';
import el from './el/el';
import it from './it/it';
import ca from './ca/ca';

Vue.use(VueI18n);

const locale = 'en';
const messages = {
  en,
  es,
  ca,
  pt,
  de,
  el,
  it
};

const i18n = new VueI18n({
  locale,
  messages,
});

export default i18n;
