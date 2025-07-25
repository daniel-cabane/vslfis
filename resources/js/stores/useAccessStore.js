import { defineStore } from 'pinia';
import useAPI from '@/composables/useAPI';

const { get, post, patch, del, isLoading } = useAPI();

export const useAccessStore = defineStore('access', {
    state: () => ({
        isReady: false,
        accesses: [],
        students: [],
        student: {}
    }),
    actions: {
        async recordAccess(data) {
            const res = await post(`/api/access/${data.id}`, data);
        },
        async getAccesses(date){
            const res = await get(`/api/access?query=Laravel&date=${date}`);
            this.accesses = res.accesses;
        },
        async deleteAccess(id) {
            const res = await del(`/api/access/${id}`);
            if(res.success){
                this.accesses = this.accesses.filter(a => a.id != id);
            }
            return res.success;
        },
        purgeStudents() {
            this.students = [];
        },
        unsetStudent() {
            this.student = {};
        },
        async searchStudents(name){
            this.students = [];
            this.ressourceStatus = 'Query result';
            const res = await get(`/api/students/search?query=Laravel&name=${name}`);
            this.students = res.students;
        },
        async accessesByStudent(id) {
            const res = await get(`/api/access/students/${id}`);
            this.purgeStudents();
            this.student = res.student;
        }
    },
    getters: {
        isLoading: () => {
            return isLoading.value;
        },
    }
});