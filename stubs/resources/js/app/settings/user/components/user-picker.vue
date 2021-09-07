<template>
    <div>
        <div class="cursor-pointer" @click="$refs.picker.open()">
            <slot />
        </div>

        <async-picker 
            ref="picker" 
            :placeholder="placeholder" 
            :url="route('user.list')" 
            @input="$emit('input', $event)" 
        >
            <template #option="val">
                <div class="font-medium">
                    {{ val.option.name }}
                </div>
                <div class="text-sm text-gray-500">
                    {{ val.option.email }}
                </div>
            </template>
        </async-picker>
    </div>
</template>

<script>
export default {
    name: 'UserPicker',
    props: {
        value: [Number, String],
        placeholder: {
            type: String,
            default: 'Select User',
        },
    }
}
</script>
