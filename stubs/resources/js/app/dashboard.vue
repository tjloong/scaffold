<template>
    <div>
        <page-header title="Dashboard">
            <daterange v-model="date" />
        </page-header>
    </div>
</template>

<script>
import { Daterange } from '@jiannius/ui'

export default {
    name: 'Home',
    components: {
        Daterange,
    },
    metaInfo: { title: 'Dashboard' },
    data () {
        return {
            date: null,
        }
    }
}
</script>
