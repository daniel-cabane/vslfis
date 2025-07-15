<template>
    <v-btn size="large" icon="mdi-credit-card-wireless-outline" @click="scan"/>
</template>
<script setup>
    const emit = defineEmits(['scanSuccessful']);

    const scan = () => {
        emit('scanSuccessful', 12345);
    }
</script>