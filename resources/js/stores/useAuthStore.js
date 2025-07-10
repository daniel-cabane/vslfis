import { defineStore } from 'pinia';
import useAPI from '@/composables/useAPI';

const { get, post, patch, del, isLoading } = useAPI();

export const useAuthStore = defineStore('auth', {
    state: () => ({
        isReady: false,
        user: null
    }),
    actions: {
        defineUser(u) {
            this.user = u;
        },
        async testLogin(testUser) {
            axios.get('/sanctum/csrf-cookie').then(async response => {
            // Login...
                const res = await post('/testLogin', { user: testUser });
                if(res.user){
                    this.user = res.user;
                    window.location.reload();
                }
            });
        },
        async logout() {
            const res = await post('/logout');
            console.log(res);
            if(res.loggedOut){
                this.user = null;
                window.location.replace('/');
            }
        },
        async updateName(name){
            const res = await patch('/api/user/name', { name });
            this.user = res.user;
        }
    },
    getters: {
        isLoading: () => {
            return isLoading.value;
        },
    }
});