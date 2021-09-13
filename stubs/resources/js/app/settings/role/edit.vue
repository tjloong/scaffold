<template>
    <div class="max-w-lg mx-auto">
        <page-header :title="role.name" back>
            <btn 
                v-if="role.can.delete" 
                color="red" 
                inverted 
                @click="destroy()"
            >
                <icon name="trash" /> Delete
            </btn>
        </page-header>

        <role-form :role="role" :errors="errors" />

        <div class="flex items-center justify-between mb-4">
            <tabs
                :tabs="[
                    { value: 'abilities', label: 'Permissions' },
                    { value: 'users', label: 'Users' },
                ]"
                :value="tab"
                @input="$inertia.visit(route('role.edit', { id: role.id, tab: $event }), { replace: true })"
            />

            <btn v-if="tab === 'users'" inverted @click="$refs.userPicker.open()">
                <icon name="plus" /> Assign User
            </btn>
        </div>

        <template v-if="tab === 'abilities'">
            <div v-if="role.name === 'Administrator'" class="bg-blue-50 text-blue-800 text-sm p-4 flex">
                <icon name="info-circle" class="mr-1 flex-shrink-0" />
                Administrator can access all modules in the system.
            </div>

            <template v-else>
                <ability-list :abilities="abilities" @select="$refs.abilityForm.open($event)" />
                <ability-form ref="abilityForm" :role="role" @submit="updateAbilities" />
            </template>
        </template>

        <template v-if="tab === 'users'">
            <user-list :users="users" />
            <async-picker ref="userPicker" placeholder="Assign User" :url="route('user.list')" @input="assignUser" />
        </template>

    </div>
</template>

<script>
import UserList from '@/app/settings/user/list'
import RoleForm from './components/role-form'
import AbilityList from '@/app/shared/ability-list'
import AbilityForm from '@/app/shared/ability-form'

export default {
    name: 'RoleEdit',
    props: {
        tab: String,
        role: Object,
        users: Object,
        errors: Object,
        abilities: Array,
    },
    components: {
        UserList,
        RoleForm,
        AbilityList,
        AbilityForm,
    },
    metaInfo: { title: 'Edit Role' },
    methods: {
        destroy () {
            this.$confirm({
                title: 'Delete Role',
                message: `Are you sure to delete role ${this.role.name}?`,
                onConfirmed: () => {
                    this.$inertia.delete(this.route('role.delete', { id: this.role.id }))
                }
            })
        },
        updateAbilities (updated) {
            const abilities = _.map(this.abilities, (ability) => {
                return updated.find(val => (val.id === ability.id)) || ability
            })

            this.$inertia
                .form({
                    id: this.role.id,
                    abilities: _.map(abilities.filter(val => (val.enabled)), 'id'),
                })
                .post(this.route('role.store', { id: this.role.id }))
        },
        assignUser (user) {
            this.$inertia
                .form({
                    id: user.id,
                    role_id: this.role.id,
                })
                .post(this.route('user.store', { id: user.id }))
        },
    }
}
</script>
