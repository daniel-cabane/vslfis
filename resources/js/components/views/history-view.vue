<template>
    <v-container class="pt-0">
        <div class="mb-2">
            <v-btn prepend-icon="mdi-chevron-left" :text="$t('Home')" rounded="pill" :to="'/'"/>
        </div>
        <div class="d-flex flex-wrap ga-2">
            <report-preview-card v-for="report in reports" :report="report" @reportDetails="showDetailsDialog"/>
        </div>
        <v-dialog v-model="detailsDialog" fullscreen scrollable>
            <report-card :report="focusedReport" @close="detailsDialog=false"/>
        </v-dialog>
    </v-container>
</template>
<script setup>
    import { ref } from "vue";

    import { useReportStore } from '@/stores/useReportStore';
    import { storeToRefs } from 'pinia';

    const reportStore = useReportStore();
    const { myReports } = reportStore;
    const { reports } = storeToRefs(reportStore);

    myReports();

    const focusedReport = ref(null);
    const detailsDialog = ref(false);
    const showDetailsDialog = report => {
        focusedReport.value = report;
        detailsDialog.value = true;
    }
</script>