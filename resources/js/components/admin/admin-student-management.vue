<template>
    <div>
        <div class="d-flex ga-4 align-center pa-3">
            <v-text-field hide-details variant="outlined" v-model="studentName" :label="$t('Name or email')"/>
            <admin-add-student-dialog :isLoading="isLoading"/>
        </div>
    </div>
</template>
<script setup>
    import { ref } from "vue";
    import { useStudentStore } from '@/stores/useStudentStore';
    import { storeToRefs } from 'pinia';

    const studentStore = useStudentStore();
    const { getStudents } = studentStore;
    const { students, isLoading } = storeToRefs(studentStore);

    getStudents();

    const studentName = ref('');
</script>