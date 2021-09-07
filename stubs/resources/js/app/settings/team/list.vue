<template>
    <div class="max-w-lg mx-auto">
        <page-header v-if="route().current() === 'team.list'" title="Teams">
            <btn :href="route('team.create')">
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
        teams: Object,
    },
    metaInfo: { title: 'Teams' },
    computed: {
        fields () {
            return [
                {
                    key: 'name',
                    sort: 'name',
                    link: (team) => (this.route('team.edit', { id: team.id })),
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
