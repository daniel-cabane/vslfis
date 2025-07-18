<template>
    <v-container>
        <init-report-buttons @report="initReport"/>
        <v-dialog v-model="dialog" fullscreen scrollable>
            <report-card :report="report" @close="dialog=false"/>
        </v-dialog>
        <v-fab 
            app 
            location="right bottom" 
            color="primary" 
            extended 
            prepend-icon="mdi-history" 
            :text="$t('History')" 
            :to="'/history'"
        />
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