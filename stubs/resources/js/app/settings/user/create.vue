<template>
    <div class="max-w-lg mx-auto">
        <page-header title="Create User" back />
        <user-form :roles="roles" :errors="errors" />
    </div>
</template>

<script>
import UserForm from './components/user-form'

export default {
    name: 'UserCreate',
    props: {
        roles: Object,
        errors: Object,
    },
    components: {
        UserForm,
    },
    metaInfo: { title: 'Create User' },
}
</script>
