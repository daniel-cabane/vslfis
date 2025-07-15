<template>
    <v-container>
        <report-buttons @report="initReport"/>
        <v-dialog v-model="dialog" fullscreen scrollable>
            <report-card :type="reportType" @changeType="changeType" @cancel="dialog=false"/>
        </v-dialog>
    </v-container>
</template>
<script setup>
    import { ref } from "vue";

    const dialog = ref(false);

    const reportType = ref(null);
    const changeType = type => {
        reportType.value = type;
    }
    const initReport = type => {
        reportType.value = type;
        dialog.value = true;
    }
</script>