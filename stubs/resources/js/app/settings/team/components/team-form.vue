<template>
    <form @submit.prevent="submit()">
        <box>
            <div class="p-5">
                <field label="Team Name" v-model="form.team.name" :error="errors['team.name']" required />
                <field label="Description" type="textarea" v-model="form.team.description" :error="errors['team.description']" />
            </div>

            <template #buttons>
                <btn submit color="green" :loading="form.processing">
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
            form: this.$inertia.form({ team: {
                name: null,
                description: null,
                ..._.pick(this.team, [
                    'id',
                    'name',
                    'description',
                ]),
            }})
        }
    },
    methods: {
        submit () {
            this.form.post(
                this.team?.id
                    ? this.route('team.update', { id: this.team.id })
                    : this.route('team.create'),
                { replace: true }
            )
        },
    }
}
</script>
