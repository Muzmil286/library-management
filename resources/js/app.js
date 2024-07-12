import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import Layout from './Pages/Layout.vue'
import Multiselect from '@vueform/multiselect'
import NavLink from './Shared/NavLink.vue'
import Form from './Shared/Form.vue'

import Table from './Shared/Table.vue'
import { Bootstrap4Pagination } from 'laravel-vue-pagination'
createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    let page = pages[`./Pages/${name}.vue`]
    page.default.layout = page.default.layout || Layout
    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .component('Multiselect', Multiselect)
      .component('NavLink', NavLink)
      .component('Table', Table)
      .component('Bootstrap4Pagination', Bootstrap4Pagination)
      .component('Form' , Form)
      .mount(el)
  },

})
