<template>
    <div>
        <box
            v-for="group in groups"
            :key="group.name"
            :title="group.name"
        >
            <div class="p-4">
                <div class="font-bold">
                    {{ group.name }}
                </div>
            </div>

            <a 
                v-for="(mod, i) in group.mods"
                :key="mod.name"
                :class="[
                    'py-2 px-4 text-sm flex', 
                    i > 0 && 'border-t',
                ]"
                @click="$emit('select', mod)"
            >
                <div class="font-medium w-40 flex-shrink-0 cursor-pointer text-blue-500">
                    {{ mod.name | titlecase }}
                </div>

                <div class="flex flex-wrap items-center">
                    <template v-for="(ability, i) in mod.abilities">
                        <div class="flex items-center" :key="ability.id">
                            <icon v-if="ability.enabled" name="check" class="text-green-500 mr-1" />
                            <icon v-else name="x" class="mr-1 text-red-400" />

                            <div :class="['text-xs', ability.enabled ? 'font-medium text-gray-500' : 'text-gray-400']">
                                {{ ability.name | titlecase }}
                            </div>
                            
                            <div class="mx-2" v-if="i < mod.abilities.length - 1">
                                &bull;
                            </div>
                        </div>
                    </template>
                </div>
            </a>
        </box>
    </div>
</template>

<script>
export default {
    name: 'RoleAbilityList',
    props: {
        abilities: Array,
    },
    computed: {
        groups () {
            const groups = ['Modules', 'Settings']
            const abilities = _.chain(this.abilities)
                .groupBy('module')
                .map((val, key) => ({ name: key, abilities: val }))
                .value()

            return groups.map(group => {
                return {
                    name: group,
                    mods: abilities
                        .filter(ability => {
                            if (group === 'Settings') return ability.name.startsWith('settings')
                            else if (group === 'Modules') return !ability.name.startsWith('settings')
                        })
                        .map(ability => {
                            if (ability.name.startsWith('settings')) ability.name = ability.name.replace('settings-', '')

                            return ability
                        })
                }
            }).filter(group => (group.mods.length > 0))
        },
    },
}
</script>