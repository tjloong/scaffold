<template>
    <div class="max-w-md mx-auto">
        <page-header title="My Account" />

        <form @submit.prevent="submit()">
            <box>
                <div class="p-5">
                    <field label="Name" v-model="form.name" required placeholder="Your full name" />
                    <field label="Login Email" v-model="form.email" required />
                    <field label="Login Password" type="password" v-model="form.password" />
                </div>

                <template #buttons>
                    <btn submit color="green" :loading="form.processing">
                        Update
                    </btn>
                </template>
            </box>
        </form>
    </div>
</template>

<script>
export default {
    name: 'SettingsAccount',
    metaInfo () {
        return { title: 'My Account' }
    },
    data () {
        const { name, email } = this.$page.props.auth.user

        return {
            form: this.$inertia.form({
                name,
                email,
                password: null,
            }),
        }
    },
    methods: {
        submit () {
            this.form
                .transform(data => {
                    if (!data.password) delete data.password
                    return data
                })
                .post(this.route('settings-user.account'), {
                    onSuccess: () => this.$toast('Account Updated', 'success')
                })
        }
    }
}
</script>
