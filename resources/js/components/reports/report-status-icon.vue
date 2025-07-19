<template>
    <v-tooltip location="top" :text="text">
        <template v-slot:activator="{ props }">
            <v-icon :icon="icon" :color="color" size="large" v-bind="props"/>
        </template>
    </v-tooltip>
</template>
<script setup>
    import { computed } from "vue";
    import { useI18n } from 'vue-i18n';

    const { t } = useI18n();

    const props = defineProps({ report: Object });

    const icon = computed(() => {
        return props.report.filedBy ? 'mdi-check-circle' : 'mdi-close-circle';
    });
    const color = computed(() => {
        return props.report.filedBy ? 'success' : props.report.finalized ? 'faded' : 'error';
    });
    const text = computed (() => {
        return props.report.filedBy ? `${t('Filed on Pronote by')} ${props.report.filedBy.name}` : props.report.finalized ? t('Not filed yet') : t('Not finalized');
    })
</script>