<template>
    <v-progress-linear height="25" color="grey-lighten-1" indeterminate class='ma-0' v-if="processes.length"></v-progress-linear>
</template>
<script setup>
    import useLoading from '@/composables/useLoading';

    const { processes } = useLoading();
</script>