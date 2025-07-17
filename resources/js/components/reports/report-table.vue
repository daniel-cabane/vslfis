<template>

</template>
<script setup>
    import { ref } from "vue";

    import { useI18n } from 'vue-i18n';

    const { t } = useI18n();
    const props = defineProps({ reports: Array });

    const headers = [
        { title: t('Category'), key: 'category' }
    ];
</script>