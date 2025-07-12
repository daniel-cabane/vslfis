<template>
    <v-text-field autocomplete="off" :hide-details="!showDetails" variant="outlined" v-model="text" :label="$t(label)"/>
</template>
<script setup>
    const text = defineModel();
    const props = defineProps({ label: String, showDetails: {type: Boolean, default: false} });
</script>