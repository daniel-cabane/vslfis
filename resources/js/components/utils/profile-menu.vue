<template>
    <div>
        <v-menu :close-on-content-click="false">
            <template v-slot:activator="{ props }">
                <v-btn outline icon="mdi-account" v-bind="props"/>
            </template>
            <v-list>
                <v-list-subheader v-if="user">
                    {{ user.name }}
                </v-list-subheader>
                <v-divider />
                <theme-and-language-picker />
                <v-divider />
                <v-list-item @click="goToDashboard" v-if="user && (user.is.admin || user.is.cpe)">
                    <template v-slot:prepend>
                        <v-icon icon="mdi-security"></v-icon>
                    </template>
                    <v-list-item-title>Admin</v-list-item-title>
                </v-list-item>
                <v-list-item @click="logout" v-if="user">
                    <template v-slot:prepend>
                        <v-icon icon="mdi-logout"></v-icon>
                    </template>
                    <v-list-item-title>{{ $t("Sign out") }}</v-list-item-title>
                </v-list-item>
                <v-dialog v-model="loginDialog" width="380" v-else>
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props">
                            <template v-slot:prepend>
                                <v-icon icon="mdi-login-variant"></v-icon>
                            </template>
                            <v-list-item-title>{{ $t("Sign in") }}</v-list-item-title>
                        </v-list-item>
                    </template>
                    <v-card class="pa-4">
                        <div class="text-center">
                            <a href="/auth/google">
                            <v-img max-width='90%' min-width='90%' style="margin-left:5%;cursor:pointer;" src="/images/google signin.png"/>
                            </a>
                        </div>
                        <div class="py-5 text-caption text-captionColor text-center">
                            <span>
                                {{ $t('Registered accounts only') }}
                            </span>
                        </div>
                        <div v-if="localEnv">
                             <div>
                                <v-select label="User" :items="testUserOptions" variant="outlined" v-model="testUser"/>
                            </div>
                            <div class="d-flex justify-end">
                                <v-btn color="primary" min-width="200" :loading="isLoading" @click="testLogin(testUser)">
                                    {{ $t('Sign in') }}
                                </v-btn>
                            </div>
                        </div>
                    </v-card>
                </v-dialog>
            </v-list>
        </v-menu>
    </div>
</template>
<script setup>
    import { ref, computed } from "vue";
    import { useAuthStore } from '@/stores/useAuthStore';
    import { storeToRefs } from 'pinia';
    import { useRouter } from 'vue-router';
    
    const loginDialog = ref(false);
    const testUser = ref('');
    const testUserOptions = ref([
        'Daniel CABANE', 'CPE', 'VS1', 'VS2', 'VS3', 'VS4'
    ]);

    const authStore = useAuthStore();
    const { testLogin, defineUser, logout } = authStore;
    const { isLoading } = storeToRefs(authStore);
    const props = defineProps({ user: Object });
    if(props.user){
        defineUser(props.user);
    }

    const router = useRouter();
    const goToDashboard = () => {
        router.push('/admin');
    }
    const localEnv = computed(() => window.Laravel.env == 'local');
</script>