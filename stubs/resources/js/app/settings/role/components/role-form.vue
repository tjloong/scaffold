<template>
    <form @submit.prevent="submit()">
        <box v-if="readonly">
            <div class="p-5">
                <field label="Role Name">
                    {{ role.name }}
                </field>

                <field label="Scope">
                    <role-scope-input :value="role.access" readonly />
                </field>
            </div>
        </box>

        <box v-else>
            <div class="p-5">
                <field label="Role Name" v-model="form.role.name" :error="errors['role.name']" required />

                <field 
                    v-if="!form.role.id"
                    label="Clone From Role" 
                    v-model="form.role.clone_from_id" 
                    type="select"
                    placeholder="Select role to clone from"
                    :options="clonables.map(val => ({
                        value: val.id,
                        label: $options.filters.titlecase(val.name),
                    }))"
                />

                <field label="Scope">
                    <role-scope-input v-model="form.role.access" />
                </field>
            </div>

            <template #buttons>
                <btn submit color="green" :loading="form.processing">
                    Save Role
                </btn>
            </template>
        </box>
    </form>
</template>

<script>
import RoleScopeInput from './role-scope-input'

export default {
    name: 'RoleForm',
    props: {
        role: Object,
        errors: Object,
        clonables: Array,
    },
    components: {
        RoleScopeInput,
    },
    data () {
        return {
            form: this.$inertia.form({ role: {
                name: null,
                access: 'global',
                clone_from_id: null,
                ..._.pick(this.role, [
                    'id',
                    'name',
                    'access',
                ]),
            }})
        }
    },
    computed: {
        readonly () {
            return this.role?.id && this.role.is_system
        },
    },
    methods: {
        submit () {
            this.form.post(
                this.role?.id
                    ? this.route('role.update', { id: this.role.id })
                    : this.route('role.create'),
                { replace: true }
            )
        },
    }
}
</script>
