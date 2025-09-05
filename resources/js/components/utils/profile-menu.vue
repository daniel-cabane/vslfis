<template>
    <div>
        <v-menu eager :close-on-content-click="false">
            <template v-slot:activator="{ props }">
                <v-btn outline icon="mdi-account" v-bind="props"/>
                <v-badge dot color="error" style="position:absolute;top:12px;right:12px;" v-if="user && (user.unfinalized.length || user.bugreport.length)"/>
            </template>
            <v-list>
                <v-dialog width="350" v-model="newNameDialog" v-if="user">
                    <template v-slot:activator="{ props: activatorProps }">
                        <v-list-item v-bind="activatorProps">
                            <v-list-item-title class="text-title">
                                {{ user.name }}
                            </v-list-item-title>
                        </v-list-item>
                    </template>
                    <template v-slot:default="{ isActive }">
                        <v-card :title="$t('Change your name')">
                            <v-card-text>
                                <v-text-field :label="$t('Name')" variant="outlined" v-model="newName"/>
                            </v-card-text>
                            <dialog-footer :isLoading="isLoading" @no="cancelNewName" @yes="submitNewName"/>
                        </v-card>
                    </template>
                </v-dialog>
                <v-divider v-if="user && user.unfinalized.length"/>
                <v-list-item @click="goToUnfinalized" v-if="user && user.unfinalized.length">
                    <template v-slot:prepend>
                        <v-badge :content="user.unfinalized.length" color="error" v-if="user.unfinalized.length">
                            <v-icon icon="mdi-close-circle"></v-icon>
                        </v-badge>
                    </template>
                    <v-list-item-title>{{ $t('Unfinalized reports') }}</v-list-item-title>
                </v-list-item>
                <v-divider/>
                <theme-and-language-picker />
                <v-divider/>
                <v-list-item @click="goToDashboard" v-if="user &&(user.is.admin || user.is.cpe)">
                    <template v-slot:prepend>
                        <v-badge :content="user.bugreport.length" color="error" v-if="user.bugreport.length">
                            <v-icon icon="mdi-security"/>
                        </v-badge>
                        <v-icon icon="mdi-security" v-else/>
                    </template>
                    <v-list-item-title>Admin</v-list-item-title>
                </v-list-item>
                <bug-report-dialog/>
                <v-list-item @click="logout" v-if="user">
                    <template v-slot:prepend>
                        <v-icon icon="mdi-logout"></v-icon>
                    </template>
                    <v-list-item-title>{{ $t("Sign out") }}</v-list-item-title>
                </v-list-item>
                <v-dialog v-model="loginDialog" persistent width="380" v-else>
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
    
    const testUser = ref('');
    const testUserOptions = ref([
        'Daniel CABANE', 'CPE', 'VS1', 'VS2', 'VS3', 'VS4'
    ]);

    const authStore = useAuthStore();
    const { testLogin, defineUser, logout, updateName } = authStore;
    const { isLoading, user } = storeToRefs(authStore);
    const props = defineProps({ dbuser: Object });

    const loginDialog = ref(!props.dbuser);
    if(props.dbuser){
        defineUser(props.dbuser);
    }

    const router = useRouter();
    const goToDashboard = () => router.push('/admin');
    const goToUnfinalized = () => router.push('/unfinalized');
    const localEnv = computed(() => window.Laravel.env == 'local');

    const newNameDialog = ref(false);
    const newName = ref(props.dbuser ?  props.dbuser.name : '');
    const cancelNewName = () => {
        newName.value = user.name;
        newNameDialog.value = false;
    }
    const submitNewName = async () => {
        await updateName(newName.value);
        newNameDialog.value = false;
    }
</script>