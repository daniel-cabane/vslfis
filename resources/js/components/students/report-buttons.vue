<template>
    <div class="d-flex flex-wrap justify-center ga-2 pa-2">
        <v-btn 
            v-for="type in types"
            :text="$t(type.title)"
            :prepend-icon="type.icon"
            :color="type.color"
            stacked
            height="150"
            width="150"
            @click="emit('report', type)"
        />
    </div>
</template>
<script setup>
    import { useReportStore } from '@/stores/useReportStore';

    const { types } = useReportStore();

    const emit = defineEmits(['report']);

    // const props = defineProps({ type: Object, types: Array });
</script>