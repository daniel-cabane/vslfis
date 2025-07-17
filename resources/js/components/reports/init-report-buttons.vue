<template>
    <div class="d-flex flex-wrap justify-center ga-2 pa-2">
        <v-btn 
            v-for="category in categories"
            :text="$t(category.title)"
            :prepend-icon="category.icon"
            :color="category.color"
            stacked
            height="150"
            width="150"
            @click="emit('report', category)"
        />
    </div>
</template>
<script setup>
    import { useReportStore } from '@/stores/useReportStore';

    const { categories } = useReportStore();

    const emit = defineEmits(['report']);
</script>