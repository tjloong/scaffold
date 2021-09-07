<template>
    <div class="max-w-lg mx-auto">
        <page-header :title="user.name" back>
            <btn v-if="$can('settings-user.manage') && user.id !== $user.id" color="red-500" inverted @click="destroy()">
                <icon name="trash" /> Delete
            </btn>
        </page-header>

        <user-form :user="user" :roles="roles" :errors="errors" />

        <div class="flex items-center justify-between mb-4">
            <tabs
                :tabs="[
                    { value: 'abilities', label: 'Permissions' },
                    { value: 'teams', label: 'Teams' },
                ]"
                :value="tab"
                @input="$inertia.visit(route('user.edit', { id: user.id, tab: $event }), { replace: true })"
            />

            <team-picker v-if="tab === 'teams'" @input="joinTeam">
                <btn inverted>
                    <icon name="plus" /> Join Team
                </btn>
            </team-picker>
        </div>

        <template v-if="tab === 'abilities'">
            <div v-if="user.is_admin" class="bg-blue-50 text-blue-800 text-sm p-4 flex">
                <icon name="info-circle" class="mr-1 flex-shrink-0" />
                Administrator can access all modules in the system.
            </div>

            <template v-else>
                <div v-if="user.abilities.length" class="bg-blue-100 text-blue-800 rounded p-4 mb-4">
                    <div class="text-sm flex mb-1">
                        <icon name="info-circle" class="flex-shrink-0 mr-1" />
                        <div>
                            User's permissions were edited and is different from the role's preset.<br>
                            <a class="text-blue-500 text-xs" @click="resetAbilities()">
                                Reset to role's preset
                            </a>
                        </div>
                    </div>
                </div>

                <ability-list :abilities="abilities" @select="$refs.abilityForm.open($event)" />
                <ability-form ref="abilityForm" @submit="updateAbilities" />
            </template>
        </template>

        <team-list v-if="tab === 'teams'" :teams="teams" @remove="leaveTeam" />
    </div>
</template>

<script>
import UserForm from './components/user-form'
import TeamList from '@/app/settings/team/list'
import TeamPicker from '@/app/settings/team/components/team-picker'
import AbilityList from '@/app/shared/ability-list'
import AbilityForm from '@/app/shared/ability-form'

export default {
    name: 'UserEdit',
    props: {
        tab: String,
        user: Object,
        roles: Object,
        teams: Object,
        errors: Object,
        abilities: Array,
    },
    components: {
        UserForm,
        TeamList,
        TeamPicker,
        AbilityList,
        AbilityForm,
    },
    metaInfo: { title: 'Edit User' },
    methods: {
        destroy () {
            this.$confirm({
                title: 'Delete User',
                message: `Are you sure to delete ${this.user.name}?`,
                onConfirmed: () => {
                    this.$inertia.delete(this.route('user.delete', { id: this.user.id }))
                }
            })
        },
        updateAbilities (updated) {
            updated.forEach(update => {
                const index = this.user.abilities.findIndex(v => (v.id === update.id))
                const access = update.enabled ? 'grant' : 'forbid'

                if (index === -1) this.user.abilities.push({ ...update, pivot: { access }})
                else this.user.abilities[index].pivot = { access }
            })

            this.$inertia
                .form({
                    id: this.user.id,
                    abilities: _.chain(this.user.abilities)
                        .groupBy('id')
                        .mapValues(val => (_.head(val).pivot))
                        .value()
                })
                .post(this.route('user.store', { id: this.user.id }))
        },
        resetAbilities () {
            this.$inertia
                .form({
                    id: this.user.id,
                    abilities: [],
                })
                .post(this.route('user.store', { id: this.user.id }))
        },
        joinTeam (team) {
            this.$inertia
                .form({
                    id: this.user.id,
                    teams: _.uniq(_.map(this.user.teams, 'id').concat([team.id])),
                })
                .post(this.route('user.store', { id: this.user.id }))
        },
        leaveTeam (team) {
            this.$inertia
                .form({
                    id: this.user.id,
                    teams: _.map(this.user.teams, 'id').filter(v => (v !== team.id))
                })
                .post(this.route('user.store', { id: this.user.id }))
        },
    }
}
</script>
