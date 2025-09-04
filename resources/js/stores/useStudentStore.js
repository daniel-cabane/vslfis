import { defineStore } from 'pinia';
import useAPI from '@/composables/useAPI';
import useNotifications from '@/composables/useNotifications';

const { addNotification } = useNotifications();

const { get, post, patch, del, isLoading } = useAPI();

export const useStudentStore = defineStore('student', {
    state: () => ({
        isReady: false,
        ressourceStatus: 'Badgeless students',
        students: [],
        student: {},
        history: JSON.parse(localStorage.getItem('studentHistory') || '[]')
    }),
    actions: {
        async getBadgelessStudents() {
            this.students = [];
            this.ressourceStatus = 'Badgeless students';
            const res = await get('/api/admin/students/badgeless');
            this.students = res.students;
        },
        async searchStudents(name){
            this.students = [];
            this.ressourceStatus = 'Query result';
            const res = await get(`/api/admin/students/search?query=Laravel&name=${name}`);
            this.students = res.students;
        },
        async saveStudents(students) {
            const res = await post('/api/admin/students', { students });
            if(res.success){
                return true;
            }
            return false;
        },
        async updateStudent(student) {
            const res = await patch(`/api/admin/students/${student.id}`, student);
            if(this.ressourceStatus == 'Badgeless students' && res.student.tagNb){
                this.students = this.students.filter(s => s.id != student.id);
            } else {
                this.students = this.students.map(s => s.id == student.id ? res.student : s);
            }

            if(res.success){
                return true;
            }
            return false;
        },
        async uploadPhoto(data) {
            if (!data.file || !data.id) {
                addNotification({ text: data.msg, type: 'error'});
                return false;
            }
            const formData = new FormData();
            formData.append('photo', data.file);

            const res = await post(`/api/admin/students/${data.id}/photo`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });

            if (res.student) {
                this.students = this.students.map(s => s.id == data.id ? res.student : s);
                return true;
            }
            return false;
        },
        async deleteStudent(student) {
            const res = await del(`/api/admin/students/${student.id}`);
            this.students = this.students.filter(s => s.id != student.id);
        },
        async findByTag(nb) {
            const res = await get(`/api/students/tag?query=Laravel&tag=${nb}`);
            if(res.student){
                addToHistory(res.student);
                this.student = res.student;
                return res.student;
            }
            return false;
        },
        addToHistory(student) {
            this.history = this.history.filter(s => s.id != student.id);
            this.history.unshift(student);
            if (this.history.length > 15) {
                this.history.pop();
            }
            localStorage.setItem('studentHistory', JSON.stringify(this.history));
        },
        removeFromHistory(){
            this.history = this.history.filter(s => !s.isSelected);
            localStorage.setItem('studentHistory', JSON.stringify(this.history));
        },
        clearHistory() {
            this.history = [];
            localStorage.removeItem('studentHistory');
        }
    },
    getters: {
        isLoading: () => {
            return isLoading.value;
        },
    }
});