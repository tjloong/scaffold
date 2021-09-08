<template>
    <admin-panel brand="NEW::APP" :navs="$page.props.navs" support>
        <template #navbar-right>
            <dropdown>
                <div class="flex items-center justify-center font-medium h-12">
                    <icon name="user-circle" /> {{ $user.name }} <icon name="chevron-down" />
                </div>

                <template #items>
                    <inertia-link :href="route('user.account')">
                        <icon name="user-circle" /> My Account
                    </inertia-link>

                    <inertia-link :href="route('logout')" method="post" as="div">
                        <icon name="log-out" /> Logout
                    </inertia-link>

                    <div>
                        <icon name="rocket" /> Version {{ $page.props.app_version }}
                    </div>
                </template>
            </dropdown>
        </template>

        <template #default>
            <slot />
        </template>
    </admin-panel>
</template>

<script>
export default {
    name: 'Layout',
}
</script>
