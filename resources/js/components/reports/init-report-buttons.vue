<template>
    <div class="d-flex flex-wrap justify-center ga-3 pa-2" style="max-width: 500px;margin:auto;">
        <v-btn 
            v-for="category in categories"
            :text="$t(category.title)"
            :prepend-icon="category.icon"
            :color="category.color"
            stacked
            height="145"
            width="145"
            @click="emit('report', category)"
        />
    </div>
</template>
<script setup>
    import { useReportStore } from '@/stores/useReportStore';

    const { categories } = useReportStore();

    const emit = defineEmits(['report']);
</script>