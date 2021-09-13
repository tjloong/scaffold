<template>
    <form @submit.prevent="submit()">
        <box>
            <div class="p-5">
                <field label="Name" v-model="form.name" :error="errors.name" required />
                <field label="Login Email" type="email" v-model="form.email" :error="errors.email" required />

                <field label="Role" 
                    type="select" 
                    v-model="form.role_id" 
                    :error="errors.role_id"
                    :options="roles.data.map(val => ({
                        value: val.id,
                        label: val.name,
                    }))"
                    required 
                />

                <field v-if="scope" label="Role Scope">
                    <role-scope-input :value="scope" readonly />
                </field>

                <div class="bg-blue-100 text-blue-800 p-4 rounded text-sm flex" v-if="!user.id">
                    <icon name="info-circle" class="mr-2 flex-shrink-0" />
                    User will receive an invitation email to activate their account once created.
                </div>

                <inertia-link
                    v-else-if="user.is_pending_activate"
                    :href="route('user.invite', { id: user.id })"
                    method="post"
                    as="button"
                    class="text-blue-500 text-sm inline-flex items-center"
                >
                    <icon name="mail-send" class="mr-1" />
                    Resend invitation email
                </inertia-link>
            </div>

            <template #buttons>
                <btn submit color="green" :loading="form.processing">
                    Save User
                </btn>
            </template>
        </box>
    </form>
</template>

<script>
import RoleScopeInput from '@/app/settings/role/components/role-scope-input'

export default {
    name: 'UserForm',
    props: {
        user: {
            type: Object,
            default () {
                return {}
            }
        },
        roles: Object,
        errors: Object,
    },
    components: {
        RoleScopeInput,
    },
    data () {
        return {
            form: this.$inertia.form({
                name: null,
                email: null,
                role_id: null,
                ..._.pick(this.user, [
                    'id', 'name', 'email', 'role_id',
                ]),
            })
        }
    },
    computed: {
        scope () {
            return this.roles.data.find(role => (role.id === parseInt(this.form.role_id)))?.access
        },
    },
    methods: {
        submit () {
            this.form.post(this.route('user.store'), { replace: true })
        },
    }
}
</script>
