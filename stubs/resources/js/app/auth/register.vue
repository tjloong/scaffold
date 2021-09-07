<template>
    <div class="min-h-screen relative px-4 py-4 md:py-20">
        <div class="absolute inset-0">
            <img src="/storage/img/blob-bg.svg" class="w-full h-full object-cover object-center opacity-50">
        </div>

        <div class="relative max-w-md mx-auto">
            <a href="/" class="mb-4">
                <img src="/storage/img/logo.svg" class="w-20 mb-6">
            </a>

            <form class="mb-6" @submit.prevent="submit()">
                <box>
                    <div class="p-5 md:p-10">
                        <div class="text-2xl font-bold mb-8 text-gray-600">
                            Create your account
                        </div>

                        <div v-if="errors.agree_tnc" class="bg-red-100 text-red-800 p-4 rounded my-4 text-sm">
                            {{ errors.agree_tnc }}
                        </div>

                        <field label="Your Full Name" v-model="form.name" :error="errors.name" required />
                        <field label="Login Email" type="email" v-model="form.email" :error="errors.email" required />
                        <field label="Login Password" type="password" v-model="form.password" :error="errors.password" required />

                        <div class="mt-6 mb-3">
                            <checkbox v-model="form.agree_tnc">
                                <div class="text-gray-500">
                                    By signing up, I have read and agreed to the app's
                                    <a href="/terms" target="_blank" class="text-blue-500 font-medium">
                                        terms and conditions
                                    </a> and 
                                    <a href="/privacy" target="_blank" class="text-blue-500 font-medium">
                                        privacy policy
                                    </a>.
                                </div>
                            </checkbox>
                        </div>

                        <div class="mb-6">
                            <checkbox v-model="form.agree_marketing">
                                <div class="text-gray-500">
                                    I agree to be part of the app's database for future newsletter, marketing and promotions opportunities.
                                </div>
                            </checkbox>
                        </div>

                        <btn submit size="md" :loading="form.processing" class="w-full mb-4">
                            Create Account
                        </btn>

                        <div class="text-center text-sm">
                            Have an account? 
                            <inertia-link :href="route('login')" class="text-blue-500 font-medium">
                                Sign In
                            </inertia-link>
                        </div>
                    </div>
                </box>
            </form>

            <auth-footer />
        </div>
    </div>
</template>

<script>
import AuthFooter from './footer'

export default {
    name: 'Register',
    layout: null,
    props: {
        errors: Object,
        referral: String,
    },
    components: {
        AuthFooter,
    },
    data () {
        return {
            form: this.$inertia.form({
                name: null,
                email: null,
                password: null,
                agree_tnc: false,
                agree_marketing: true,
            }),
        }
    },
    methods: {
        submit () {
            this.form.post(this.route('register'))
        }
    }
}
</script>
