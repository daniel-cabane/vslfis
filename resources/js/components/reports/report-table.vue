<template>
    <v-data-table 
        :loading="isLoading"
        :items-per-page-options="[25, 50, 100]"
        items-per-page="25"
        :headers="headers"
        :items="reports"
    >
        <template v-slot:loading>
            <div class="text-h6 text-muted text-center">
                {{ $t('Loading') }}...
            </div>
        </template>
        <template v-slot:item="{ item }">
            <tr>
                <td>
                    <v-icon class="mr-1" :icon="item.category.icon" :color="item.category.color"/>
                    {{ cap(item.category.title) }}
                </td>
                <td class="text-center">{{ item.students.length }}</td>
                <td>{{ item.by.name }}</td>
                <td>{{ formatDateTime(item.on) }}</td>
                <td class="text-center"><report-status-icon :report="item"/></td>
                <td class="text-center">
                    <v-icon icon="mdi-eye" color="primary" @click="emit('seeReport', item)"/>
                </td>
            </tr>
        </template>
    </v-data-table>
</template>
<script setup>
    import { computed } from "vue";
    import { useI18n } from 'vue-i18n';

    const emit = defineEmits(['seeReport']);

    const props = defineProps({ reports: Array, isLoading: Boolean });

    const { t, locale } = useI18n();
    const cap = string => string.charAt(0).toUpperCase() + string.slice(1);

    const headers = computed(() => [
        { title: t('Category'), key: 'category.title' },
        { title: t('Nb students'), key: 'students.length', align: 'center' },
        { title: t('Reported by'), key: 'by.name' },
        { title: t('Reported on'), key: 'on' },
        { title: t('Status'), sortable: false, align: 'center' },
        { title: t('View'), sortable: false, align: 'center' },
    ]);

    const formatDateTime = dt => {
        const date = new Date(dt);
        const options = {
            month: 'short',
            day: 'numeric',
            weekday: 'short',
            hour: '2-digit',
            minute: '2-digit'
        };

        const formatter = new Intl.DateTimeFormat(locale.value, options);
        return  formatter.format(date);
    }
</script>