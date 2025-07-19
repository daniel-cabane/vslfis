import { defineStore } from 'pinia';
import useAPI from '@/composables/useAPI';

const { get, post, patch, del, isLoading } = useAPI();

export const useReportStore = defineStore('report', {
    state: () => ({
        isReady: false,
        students: [],
        reports: [],
        report: {},
        categories: [
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
        fillCategory(report){
            report.category = this.categories.find(c => c.title == report.category);
            return report;
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
                this.reports.unshift(this.fillCategory(res.report));
            }
            return res.success
        },
        async updateReport(data) {
            const res = await patch(`/api/reports/${data.id}`, data);
            if(res.success){
                this.reports = this.reports.map(r => r.id == data.id ? this.fillCategory(res.report) : r);
            }
            return res.success;
        },
        async fileReport(id){
            const res = await post(`/api/reports/${id}/file`);
            if(res.success){
                this.reports = this.reports.map(r => r.id == id ? this.fillCategory(res.report) : r);
            }
            return res.success;
        },
        async unfileReport(id){
            const res = await post(`/api/reports/${id}/unfile`);
            if(res.success){
                this.reports = this.reports.map(r => r.id == id ? this.fillCategory(res.report) : r);
            }
            return res.success;
        },
        async deleteReport(id){
            const res = await del(`/api/reports/${id}`);
            if(res.success){
                this.reports = this.reports.filter(r => r.id != id);
            }
            return res.success
        },
        async myReports() {
            const res = await get('/api/reports/myReports');
            this.reports = res.reports.map(r => this.fillCategory(r));
        },
        async getReports() {
            const res = await get('/api/reports');
            this.reports = res.reports.map(r => this.fillCategory(r));
            console.log(this.reports);
        }
    },
    getters: {
        isLoading: () => {
            return isLoading.value;
        },
    }
});