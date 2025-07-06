<template>
    <div class="pt-2">
        <v-text-field 
            variant="outlined"
            v-model="userName"
            :label="$t('Name or email')"
            append-inner-icon="mdi-magnify"
            :disabled="isLoading"
            :loading="isLoading"
        />
    </div>
</template>
<script setup>
    import { ref } from "vue";
    import { useAdminStore } from '@/stores/useAdminStore';
    import { storeToRefs } from 'pinia';

    const adminStore = useAdminStore();
    const { getRecentUsers, searchUsers } = adminStore;
    const { users, isLoading } = storeToRefs(adminStore);

    getRecentUsers();

    const userName = ref('');
</script>