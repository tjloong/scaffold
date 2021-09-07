import Vue from 'vue'
import VueMeta from 'vue-meta'
import { Inertia } from '@inertiajs/inertia'
import { InertiaProgress } from '@inertiajs/progress'
import { createInertiaApp, Link } from '@inertiajs/inertia-vue'
import { Plugins } from '@jiannius/ui'
import Layout from '@/app/layout'

Vue.config.productionTip = false

Vue.use(VueMeta)
Vue.use(Plugins)
Vue.component('inertia-link', Link)

Vue.mixin({ 
    computed: {
        $user () {
            return this.$page.props.auth.user
        },
    },
    methods: { 
        route,
        $can (ability) {
            return this.$page.props.auth.perm[ability] || false
        },
        $reload () {
            this.$inertia.reload({ preserveState: false, preserveScroll: false })
            window.removeEventListener('popstate', this.$reload)
        },
        $back () {
            window.addEventListener('popstate', this.$reload)
            window.history.back()
        },
    },
})

InertiaProgress.init()

window.dd = console.log.bind(console)
window._ = require('lodash')
window.moment = require('moment')

createInertiaApp({
    resolve: name => {
        const page = require(`./${name}`).default

        if (page.layout === undefined) page.layout = Layout

        return page
    },
    setup({ el, app, props }) {
        new Vue({
            metaInfo: {
                titleTemplate: title => (title ? `${title} | App` : 'App'),
                themeColor: 'orange-500',
            },
            render: h => h(app, props),
        }).$mount(el)
    },
})