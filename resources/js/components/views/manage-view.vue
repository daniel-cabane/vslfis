<template>
    <v-container>
        <v-tabs fixed-tabs>
            <v-tab @click="getReportByStatus('filable')">
                {{ $t('To file') }}
            </v-tab>
            <v-tab @click="getReportByStatus('unfinalized')">
                {{ $t('Not finalized') }}
            </v-tab>
            <v-tab @click="getReportByStatus('filed')">
                {{ $t('Filed') }}
            </v-tab>
        </v-tabs>
        <report-table :reports="reports" :isLoading="isLoading" @seeReport="seeReport"/>
        <v-dialog v-model="dialog" fullscreen scrollable>
            <report-card :report="report" @close="dialog=false"/>
        </v-dialog>
    </v-container>
</template>
<script setup>
    import { ref } from "vue";
    import { useReportStore } from '@/stores/useReportStore';
    import { storeToRefs } from 'pinia';

    const reportStore = useReportStore();
    const { getReportByStatus } = reportStore;
    const { reports, isLoading } = storeToRefs(reportStore);

    getReportByStatus('filable');
    
    const dialog = ref(false);

    const report = ref(null)
    const seeReport = r => {
        report.value = r;
        dialog.value = true;
    }
</script>