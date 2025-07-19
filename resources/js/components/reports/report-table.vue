<template>
    <v-data-table :items-per-page-options="[25, 50, 100]" items-per-page="25" :headers="headers" :items="reports">
        <template v-slot:item="{ item }">
            <tr>
                <td>
                    <v-icon :icon="item.category.icon" :color="item.category.color"/>
                    {{ item.category.title }}
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
    import { useReportStore } from '@/stores/useReportStore';
    import { storeToRefs } from 'pinia';

    const emit = defineEmits(['seeReport']);

    const reportStore = useReportStore();
    const { getReports } = reportStore;
    const { reports } = storeToRefs(reportStore);

    getReports();

    const { t, locale } = useI18n();

    const headers = computed(() => [
        { title: t('Category'), key: 'category.title' },
        { title: t('Nb students'), key: 'students.length', align: 'center' },
        { title: t('Reported by'), key: 'by.name' },
        { title: t('Reported on'), key: 'on' },
        { title: t('Status'), key: 'filedBy.name', align: 'center' },
        { title: t('View'), sortable: false },
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