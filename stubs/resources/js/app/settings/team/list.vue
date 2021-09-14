<template>
    <div class="max-w-lg mx-auto">
        <page-header v-if="route().current() === 'settings-team.list'" title="Teams">
            <btn v-if="can.create" :href="route('settings-team.create')">
                New Team
            </btn>
        </page-header>

        <data-table
            :data="teams.data"
            :meta="teams.meta"
            :fields="fields"
        />
    </div>
</template>

<script>
export default {
    name: 'TeamList',
    props: {
        can: Object,
        teams: Object,
    },
    metaInfo: { title: 'Teams' },
    computed: {
        fields () {
            return [
                {
                    key: 'name',
                    sort: 'name',
                    link: (team) => (team.can.edit && this.route('settings-team.edit', { id: team.id })),
                },
                this.route().current() === 'user.edit' && {
                    key: 'actions',
                    actions: (team) => ([
                        {
                            name: 'Remove',
                            icon: 'minus-circle',
                            action: (team) => this.$emit('remove', team),
                        },
                    ]),
                },
            ].filter(Boolean)
        },
    }
}
</script>
