import { defineStore } from 'pinia';
import useAPI from '@/composables/useAPI';

const { get, post, patch, del, isLoading } = useAPI();

export const useAdminStore = defineStore('admin', {
    state: () => ({
        isReady: false,
        users: [],
        students: [],
        student: {}
    }),
    actions: {
        async getRecentUsers() {
            this.isReady = false;
            const res = await get('/api/admin/users/recent');
            this.users = res.users;
            this.isReady = true;
        },
        async searchUser(name){
            const res = await get(`/api/admin/users/search?query=Laravel&name=${name}`);
            this.users = res.users;
        },
    },
    getters: {
        isLoading: () => {
            return isLoading.value;
        },
    }
});