<template>
    <v-container>
        <div class="text-h6 text-muted">
            {{ $t('Incident report') }}
        </div>
        <init-report-buttons @report="initReport"/>
        <div class="d-flex justify-end mt-4">
            <v-btn
                color="primary" 
                rounded="pill" 
                prepend-icon="mdi-history" 
                :text="$t('History')" 
                :to="'/history'"
            />
        </div>
        <div class="text-h6 text-muted mt-8">
            {{ $t('Students') }}
        </div>
        <v-btn block color="secondary" text="Identification" :to="'/identification'"/>
        <v-dialog v-model="dialog" fullscreen scrollable>
            <report-card :report="report" @close="dialog=false"/>
        </v-dialog>
        <!-- <theme-tester/> -->
    </v-container>
</template>
<script setup>
    import { ref } from "vue";

    const dialog = ref(false);

    const report = ref(null);

    const initReport = category => {
        report.value = {category, comment: '', location: '', finalized: true, students: []};
        dialog.value = true;
    }
</script>