import { defineStore } from 'pinia';
import useAPI from '@/composables/useAPI';

const { get, post, patch, del, isLoading } = useAPI();

export const useReportStore = defineStore('report', {
    state: () => ({
        isReady: false,
        students: [],
        reports: [],
        report: {},
        types: [
            { title: 'phone', description: 'Using phone outside of designated areas', icon: 'mdi-cellphone', color: 'blue'},
            { title: 'computer', description: 'Using computer outside of designated areas', icon: 'mdi-laptop', color: 'purple'},
            { title: 'behaviour', description: 'Problematic behaviour', icon: 'mdi-human-handsup', color: 'red'},
            { title: 'badge defect', description: 'Badge missing or not apparent', icon: 'mdi-badge-account-horizontal-outline', color: 'green'},
        ]
    }),
    actions: {
        purgeStudents() {
            this.students = [];
        },
        addStudent(student) {
            this.students.unshift(student);
        },
        removeStudent(student) {
            this.students = this.students.filter(s => s.id != student.id);
        },
        async searchStudents(name){
            this.students = [];
            this.ressourceStatus = 'Query result';
            const res = await get(`/api/students/search?query=Laravel&name=${name}`);
            this.students = res.students;
        },
        async saveReport(data) {
            const res = await post('/api/reports', data);
            if(res.success){
                this.reports.unshift(res.report);
            }
            return res.success
        }
    },
    getters: {
        isLoading: () => {
            return isLoading.value;
        },
    }
});