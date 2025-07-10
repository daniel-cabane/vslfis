import { defineStore } from 'pinia';
import useAPI from '@/composables/useAPI';

const { get, post, patch, del, isLoading } = useAPI();

export const useStudentStore = defineStore('student', {
    state: () => ({
        isReady: false,
        students: [],
        student: {}
    }),
    actions: {
        async getStudents() {
            console.log('students...')
        },
        async saveStudents(students) {
            const res = await post('/api/admin/students', { students });
            if(res.success){
                return true;
            }
            return false;
        }
    },
    getters: {
        isLoading: () => {
            return isLoading.value;
        },
    }
});