<template>
    <v-container>
        <v-tabs v-model="tab">
            <v-tab value="users">{{ $t('Users') }}</v-tab>
            <v-tab value="usersplus" v-if="user.is.admin">{{ $t('Users') }}+</v-tab>
            <v-tab value="students">{{ $t('Students') }}</v-tab>
        </v-tabs>
        <v-tabs-window v-model="tab">
            <v-tabs-window-item value="users">
                <cpe-users-table/>
            </v-tabs-window-item>
            <v-tabs-window-item value="usersplus">
                <admin-users-management/>
            </v-tabs-window-item>
            <v-tabs-window-item value="students">
                <admin-student-management/>
            </v-tabs-window-item>
        </v-tabs-window>
    </v-container>
</template>
<script setup>
    import { ref } from "vue";
    import { useAuthStore } from '@/stores/useAuthStore';
    import { storeToRefs } from 'pinia';

    const authStore = useAuthStore();
    const { user } = storeToRefs(authStore);

    const tab = ref("students");
</script>