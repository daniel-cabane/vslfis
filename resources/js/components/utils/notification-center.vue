<template>
    <v-snackbar-queue v-model="translatedNotifs" :timeout="3000"/>
</template>
<script setup>
    import { ref, watch } from "vue";
    import { useI18n } from 'vue-i18n';
    import useNotifications from '@/composables/useNotifications';
    
    const { t } = useI18n();
    
    const { notifications } = useNotifications();

    const translatedNotifs = ref([]);
    let lastLength = 0;
    watch(
        notifications,
        (newNotifs) => {
            if (newNotifs.length > lastLength) {
                const added = newNotifs.slice(lastLength);
                translatedNotifs.value.push(
                    ...added.map(n => ({
                        text: t(n.text),
                        color: n.color
                    }))
                );
            }
            lastLength = newNotifs.length;
        },
        { deep: true }
    );
</script>