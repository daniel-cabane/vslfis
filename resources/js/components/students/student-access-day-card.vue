<template>
    <div class="mt-8">
        <v-divider>
            <span class="text-caption text-muted">
                {{ date }}
            </span>
        </v-divider>
        <div class="d-flex flex-wrap ga-4 mt-2">
            <v-chip
                variant="elevated"
                v-tooltip:top="`${$t('Authorized by')} ${access.by.name}`"
                v-for="access in accesses"
                :text="formatTime(access.on)"
                :prepend-icon="access.direction == 'in' ? 'mdi-login' : 'mdi-logout'"
                :color="access.direction == 'in' ? 'success' : 'error'"
            />
        </div>
    </div>
</template>
<script setup>
    import { useI18n } from 'vue-i18n';

    const { locale } = useI18n();

    const props = defineProps({ accesses: Array, date: String });

    const formatTime = dt => {
        const date = new Date(dt);
        const options = {
            hour: '2-digit',
            minute: '2-digit'
        };

        const formatter = new Intl.DateTimeFormat(locale.value, options);
        return  formatter.format(date);
    }
</script>