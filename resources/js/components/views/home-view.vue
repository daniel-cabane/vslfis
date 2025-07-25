<template>
    <v-container class="pa-0">
        <v-tabs fixed-tabs v-model="tab">
            <v-tab value="reports">{{ $t('Incidents') }}</v-tab>
            <v-tab value="exits">{{ $t('Exits') }}</v-tab>
        </v-tabs>
        <v-tabs-window v-model="tab">
            <v-tabs-window-item value="reports">
                <init-report-buttons @report="initReport"/>
                <div class="d-flex justify-end mt-4">
                    <v-btn
                        color="primary" 
                        rounded="pill" 
                        prepend-icon="mdi-file-document-outline" 
                        :text="$t('My reports')" 
                        :to="'/history'"
                    />
                </div>
                <!-- <div class="text-h6 text-muted mt-8">
                    {{ $t('Students') }}
                </div>
                <v-btn block color="secondary" :text="`Identification / ${$t('Exit')}`" :to="'/identification'"/>
                <div class="d-flex justify-end mt-4">
                </div> -->
            </v-tabs-window-item>
            <v-tabs-window-item value="exits">
                <student-exits/>
                <div class="d-flex justify-end mt-4">
                    <v-btn
                        color="primary" 
                        rounded="pill" 
                        prepend-icon="mdi-history" 
                        :text="$t('History')" 
                        :to="'/exits'"
                    />
                </div>
            </v-tabs-window-item>
        </v-tabs-window>
        <v-dialog v-model="dialog" fullscreen scrollable>
            <report-card :report="report" @close="dialog=false"/>
        </v-dialog>
        <!-- <theme-tester/> -->
    </v-container>
</template>
<script setup>
    import { ref } from "vue";

    const tab = ref('reports');
    const dialog = ref(false);

    const report = ref(null);
    const initReport = category => {
        report.value = {category, comment: '', location: '', finalized: true, students: []};
        dialog.value = true;
    }
</script>