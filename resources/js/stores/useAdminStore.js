import { defineStore } from 'pinia';
import useAPI from '@/composables/useAPI';

const { get, post, patch, del, isLoading } = useAPI();

export const useAdminStore = defineStore('admin', {
    state: () => ({
        isReady: false,
        users: []
    }),
    actions: {
        async getRecentUsers() {
            this.isReady = false;
            this.users = [];
            const res = await get('/api/admin/users/recent');
            this.users = res.users;
            this.isReady = true;
        },
        async searchUsers(name){
            this.users = [];
            const res = await get(`/api/admin/users/search?query=Laravel&name=${name}`);
            this.users = res.users;
        },
        async updateUser(data) {
            const res = await patch(`/api/admin/users/${data.id}/update`, data);
            if(res.user){
                this.users = this.users.map(u => u.id == data.id ? res.user : u);
            }
        },
        async deleteUser(id) {
            const res = await del(`/api/admin/users/${id}`);
            if(res.success){
                this.users = this.users.filter(u => u.id != id);
            }
        }
    },
    getters: {
        isLoading: () => {
            return isLoading.value;
        },
    }
});