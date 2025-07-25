<template>
    <v-card-actions @keydown.enter="emit('yes')">
        <v-spacer/>
        <v-btn
            :variant="cancelVariant"
            :color="invertColors ? confirmColor : cancelColor"
            :text="$t(cancelText)"
            :disabled="isLoading"
            @click="emit('no')"
        />
        <v-btn
            :variant="confirmVariant"
            :color="invertColors ? cancelColor : confirmColor"
            :text="$t(confirmText)"
            :loading="isLoading"
            @click="emit('yes')"
        />
    </v-card-actions>
</template>
<script setup>
    const props = defineProps({
        cancelText: {type: String, default: 'Cancel'},
        cancelVariant: {type: String, default: 'tonal'},
        cancelColor: {type: String, default: 'error'},
        confirmText: {type: String, default: 'Confirm'},
        confirmVariant: {type: String, default: 'flat'},
        confirmColor: {type: String, default: 'primary'},
        invertColors: {type: Boolean, default: false},
        isLoading: Boolean
    });

    const emit = defineEmits(['no', 'yes']);
</script>