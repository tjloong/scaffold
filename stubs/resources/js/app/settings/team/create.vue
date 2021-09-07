<template>
    <div class="max-w-lg mx-auto">
        <page-header title="Create Team" back />
        <team-form :errors="errors" />
    </div>
</template>

<script>
import TeamForm from './components/team-form'

export default {
    name: 'TeamCreate',
    props: {
        errors: Object,
    },
    components: {
        TeamForm,
    },
    metaInfo: { title: 'Create Team' },
}
</script>
