<template>
    <div>
        <div class="d-flex ga-4 align-center pa-3">
            <!-- <v-text-field hide-details variant="outlined" v-model="studentName" :label="$t('Name or email')"/> -->
            <search-input label="Name or email" :isLoading="isLoading" @empty="getBadgelessStudents" @search="searchStudents"/>
            <admin-add-student-dialog :isLoading="isLoading"/>
        </div>
        <div class="px-4">
            <div class="text-h5 text-muted mb-3">
                {{ $t(ressourceStatus) }}
            </div>
            <div class="d-flex ga-2 flex-wrap">
                <admin-student-card 
                    v-for="student in students"
                    :student="student" 
                    @edit="editStudent(student)"
                    @delete="deleteStudent(student)"
                    @image="uploadPhoto"
                    :key="student.id"
                />
            </div>
        </div>
        <v-dialog max-width="650" v-model="editStudentDialog">
            <v-card :title="$t('Edit student')">
                <v-card-text>
                    <div class="d-flex ga-2 mb-4">
                        <std-text-input label="Last Name" v-model="focusedStudent.lastName"/>
                        <std-text-input label="First Name" v-model="focusedStudent.firstName"/>
                    </div>
                    <div class="d-flex ga-2 mb-4">
                        <std-text-input label="Email" v-model="focusedStudent.email"/>
                        <v-number-input hide-details control-variant="hidden" autocomplete="off" variant="outlined" :label="$t('Tag number')" v-model="focusedStudent.tagNb"/>
                    </div>
                    <div class="d-flex ga-2 mb-4">
                        <v-select hide-details variant="outlined" :label="$t('Level')" v-model="focusedStudent.level" :items="levels"/>
                        <v-select hide-details variant="outlined" :label="$t('Section')" v-model="focusedStudent.section" :items="['A', 'B', 'C', 'D', 'E']"/>
                        <v-select hide-details variant="outlined" :label="$t('Status')" v-model="focusedStudent.status" :items="statusOptions"/>
                    </div>
                </v-card-text>
                <dialog-footer @no="editStudentDialog=false" @yes="proceedEditStudent"/>
            </v-card>
        </v-dialog>
    </div>
</template>
<script setup>
    import { ref } from "vue";
    import { useStudentStore } from '@/stores/useStudentStore';
    import { storeToRefs } from 'pinia';
    import { useI18n } from 'vue-i18n';

    const { t } = useI18n();

    const studentStore = useStudentStore();
    const { getBadgelessStudents, searchStudents, updateStudent, deleteStudent, uploadPhoto } = studentStore;
    const { students, ressourceStatus, isLoading } = storeToRefs(studentStore);

    getBadgelessStudents();

    const levels = ['6e', '5e', '4e', '3e', '2nde', '1re', 'Term', 'Y7', 'Y8', 'Y9', 'Y10', 'Y11', 'Y12'];
    const statusOptions = [
        { title: t('Cyan'), value: 'cyan' }, { title: t('Blue'), value: 'blue' }, { title: t('Red'), value: 'red' }, {title: t('Black'), value: 'black'}
    ];
    const focusedStudent = ref(null);
    const editStudentDialog = ref(false);
    const editStudent = student => {
        focusedStudent.value = student;
        editStudentDialog.value = true;
    }
    const proceedEditStudent = async () => {
        const res = await updateStudent(focusedStudent.value);
        editStudentDialog.value = false;
    }
</script>