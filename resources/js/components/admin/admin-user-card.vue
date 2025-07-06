<template>
    <v-card :title="user.name">

    </v-card>
</template>
<script setup>
    import { ref } from "vue";
    const props = defineProps({ user: Object });
</script>