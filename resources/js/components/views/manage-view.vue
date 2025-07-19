<template>
    <v-container>
        <report-table @seeReport="seeReport"/>
        <v-dialog v-model="dialog" fullscreen scrollable>
            <report-card :report="report" @close="dialog=false"/>
        </v-dialog>
    </v-container>
</template>
<script setup>
    import { ref } from "vue";
    
    const dialog = ref(false);

    const report = ref(null)
    const seeReport = r => {
        report.value = r;
        dialog.value = true;
    }
</script>