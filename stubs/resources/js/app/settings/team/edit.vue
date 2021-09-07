<template>
    <div class="max-w-lg mx-auto">
        <page-header :title="team.name" back>
            <btn color="red-500" inverted @click="destroy()">
                <icon name="trash" /> Delete
            </btn>
        </page-header>

        <team-form :team="team" :errors="errors" />

        <div class="flex items-center justify-between mb-4">
            <tabs
                :tabs="[
                    { value: 'users', label: 'Users' },
                ]"
                :value="tab"
            />

            <user-picker @input="assignUser">
                <btn inverted>
                    <icon name="plus" /> Assign User
                </btn>
            </user-picker>
        </div>

        <user-list :users="users" @remove="removeUser" />
    </div>
</template>

<script>
import UserList from '@/app/settings/user/list'
import TeamForm from './components/team-form'
import UserPicker from '@/app/settings/user/components/user-picker'

export default {
    name: 'TeamEdit',
    props: {
        tab: String,
        team: Object,
        users: Object,
        errors: Object,
    },
    components: {
        UserList,
        TeamForm,
        UserPicker,
    },
    metaInfo: { title: 'Edit Team' },
    methods: {
        destroy () {
            this.$confirm({
                title: 'Delete Team',
                message: `Are you sure to delete team ${this.team.name}?`,
                onConfirmed: () => {
                    this.$inertia.delete(this.route('team.delete', { id: this.team.id }))
                }
            })
        },
        assignUser (user) {
            this.$inertia
                .form({
                    id: user.id,
                    teams: _.uniq(_.map(user.teams, 'id').concat([this.team.id])),
                })
                .post(this.route('user.store', { id: user.id }))
        },
        removeUser (user) {
            this.$inertia
                .form({
                    id: user.id,
                    teams: _.map(user.teams, 'id').filter(v => v !== this.team.id)
                })
                .post(this.route('user.store', { id: user.id }))
        }
    }
}
</script>
