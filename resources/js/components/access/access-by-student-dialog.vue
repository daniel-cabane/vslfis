<template>
    <v-dialog fullscreen scrollable v-model="dialog">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn :text="$t('By student')" variant="outlined" color="primary" v-bind="activatorProps"/>
        </template>
        <v-card>
            <v-toolbar title="Student access">
                <v-btn icon="mdi-close" @click="closeDialog"/>
            </v-toolbar>
            <v-card-text>
                <search-input label="Name or email" :isLoading="isLoading" @search="searchStudents"/>
                <div class="pa-2" v-if="students.length">
                    <v-list class="py-0">
                        <v-divider/>
                        <student-preview 
                            v-for="student in students" 
                            :student="student" 
                            @picked="accessesByStudent(student.id)"
                            @photo="showPhoto"
                        />
                    </v-list>
                    <div class="d-flex justify-end mt-2">
                        <v-btn variant="outlined" color="error" density="compact" :text="$t('Clear list')" @click="purgeStudents"/>
                    </div>
                </div>
                <div v-if="student.access">
                    <student-preview :student="student" @picked="accessesByStudent(student.id)" @photo="showPhoto"/>
                    <student-access-day-card v-for="(accesses, date) in student.access" :date="date" :accesses="accesses"/>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-spacer/>
                <v-btn variant="tonal" color="error" :text="$t('Close')" @click="closeDialog"/>
            </v-card-actions>
        </v-card>
        <v-dialog v-model="photoDialog">
            <img style="max-width:90vw;max-height:90vh;object-fit:contain;position:relative;" :src="photoUrl"/>
            <v-btn icon="mdi-close" color="error" style="position:fixed;top:10px;right:10px;" @click="photoDialog=false"/>
        </v-dialog>
    </v-dialog>
</template>
<script setup>
    import { ref } from "vue";
    import { useAccessStore } from '@/stores/useAccessStore';
    import { storeToRefs } from 'pinia';
    // import { useI18n } from 'vue-i18n';

    // const { t, locale } = useI18n();

    const accessStore = useAccessStore();
    const { searchStudents, purgeStudents, accessesByStudent, unsetStudent } = accessStore;
    const { accesses, students, student, isLoading } = storeToRefs(accessStore);

    const dialog = ref(false);
    const closeDialog = () => {
        purgeStudents();
        unsetStudent();
        dialog.value = false;
    }

    const photoDialog = ref(false);
    const photoUrl = ref(null);
    const showPhoto = url => {
        if(url){
            photoUrl.value = url;
            photoDialog.value = true;
        }
    }
</script>