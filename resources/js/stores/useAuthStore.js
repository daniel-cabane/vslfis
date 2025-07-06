import { defineStore } from 'pinia';
import useAPI from '@/composables/useAPI';

const { get, post, patch, del, isLoading } = useAPI();

export const useAuthStore = defineStore('auth', {
    state: () => ({
        isReady: false,
        user: {}
    }),
    actions: {
        defineUser(u) {
            this.user = u;
        },
        async testLogin(testUser) {
            const res = await post('/testLogin', { user: testUser });
            if(res.user){
                this.user = res.user;
                window.location.reload();
            }
        },
        async logout() {
            let res = await post('/logout');
            console.log(res);
            if(res.loggedOut){
                this.user = null;
                window.location.replace('/');
            }
        }
    },
    getters: {
        isLoading: () => {
            return isLoading.value;
        },
    }
});