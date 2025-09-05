import { defineStore } from 'pinia';
import useAPI from '@/composables/useAPI';

const { get, post, patch, del, isLoading } = useAPI();

export const useBugreportStore = defineStore('bugreport', {
    state: () => ({
        isReady: false,
        reports: []
    }),
    actions: {
        async getReports(){
            const res = await get('/api/admin/bugreports');
            this.reports = res.reports;
        },
        async postReport(data){
            const res = await post('/api/bugreport', data);
            return !!res.success;
        },
        async closeReport(id){
            const res = await patch(`/api/admin/bugreport/${id}`);
            if(res.success){
                this.reports = this.reports.filter(r => r.id != id);
            }
            return !!res.success;
        },
        async deleteReport(id){
            const res = await del(`/api/admin/bugreport/${id}`);
            if(res.success){
                this.reports = this.reports.filter(r => r.id != id);
            }
            return !!res.success;
        }
    },
    getters: {
        isLoading: () => {
            return isLoading.value;
        },
    }
});