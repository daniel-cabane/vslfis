<template>
    <div v-if="focusedStudent">
            <involved-student-card 
                class="my-2"
                show-actions
                :student="focusedStudent" 
                @photo="showPhoto" 
                @remove="focusedStudent=null"
                @access="recordAccess"
            />
        </div>
        <div class="d-flex align-center ga-4 my-2 pr-2">
            <search-input label="Name or email" :isLoading="isLoading" @search="searchStudents"/>
            <scan-card @scanSuccessful="getStudentFromScan"/>
        </div>
        <div class="pa-2" v-if="students.length">
            <v-list class="py-0">
                <v-divider/>
                <student-preview 
                    v-for="student in students" 
                    :student="student" 
                    @picked="involveStudent(student)"
                    @photo="showPhoto"
                />
            </v-list>
            <div class="d-flex justify-end mt-2">
                <v-btn variant="outlined" color="error" density="compact" :text="$t('Clear list')" @click="purgeStudents"/>
            </div>
        </div>
        <v-dialog v-model="photoDialog">
            <img style="max-width:90vw;max-height:90vh;object-fit:contain;position:relative;" :src="photoUrl"/>
            <v-btn icon="mdi-close" color="error" style="position:fixed;top:10px;right:10px;" @click="photoDialog=false"/>
        </v-dialog>
</template>
<script setup>
import { ref } from "vue";
    import { useReportStore } from '@/stores/useReportStore';
    import { useAccessStore } from '@/stores/useAccessStore';
    import { storeToRefs } from 'pinia';

    const reportStore = useReportStore();
    const { searchStudents, purgeStudents } = reportStore;
    const { students, isLoading } = storeToRefs(reportStore);

    const { recordAccess } = useAccessStore();

    const photoDialog = ref(false);
    const photoUrl = ref(null);
    const showPhoto = url => {
        if(url){
            photoUrl.value = url;
            photoDialog.value = true;
        }
    }

    const focusedStudent = ref(null);
    const involveStudent = student => {
        focusedStudent.value = student;
    }

    const getStudentFromScan = student => {
        focusedStudent.value = student;
    }
</script>