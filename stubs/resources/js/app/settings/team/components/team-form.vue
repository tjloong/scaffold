<template>
    <form @submit.prevent="submit()">
        <box>
            <div class="p-5">
                <field label="Team Name" v-model="form.name" :error="errors.name" required />
                <field label="Description" type="textarea" v-model="form.description" :error="errors.description" />
            </div>

            <template #buttons>
                <btn submit color="green-500" :loading="form.processing">
                    Save Team
                </btn>
            </template>
        </box>
    </form>
</template>

<script>
export default {
    name: 'TeamForm',
    props: {
        team: Object,
        errors: Object,
    },
    data () {
        return {
            form: this.$inertia.form({
                name: null,
                description: null,
                ..._.pick(this.team, [
                    'id',
                    'name',
                    'description',
                ]),
            })
        }
    },
    methods: {
        submit () {
            this.form.post(this.route('team.store'), { replace: true })
        },
    }
}
</script>
