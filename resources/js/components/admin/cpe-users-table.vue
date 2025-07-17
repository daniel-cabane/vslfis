<template>
    <v-data-table :headers="headers" :items="userItems" v-if="isReady">
        <template v-slot:item.isCPE="{ value }">
          <v-icon icon="mdi-check" color="success" v-if="value"/>
      </template>
    </v-data-table>
</template>
<script setup>
    import { computed } from "vue";
    import { useAdminStore } from '@/stores/useAdminStore';
    import { storeToRefs } from 'pinia';
    import { useI18n } from 'vue-i18n';

    const { t } = useI18n();

    const adminStore = useAdminStore();
    const { getUsers } = adminStore;
    const { users, isLoading, isReady } = storeToRefs(adminStore);

    const headers = computed(() => [
        { title: t('Name'), key: 'name' },
        { title: t('Email'), key: 'email' },
        { title: t('Registered on'), key: 'registered', align: 'center' },
        { title: 'CPE', key: 'isCPE', align: 'center' }
    ]);

    getUsers();

    const userItems = computed(() => {
        if(users){
            return users.value.map(u => ({
                name: u.name,
                email: u.email,
                registered: formatDate(u.created_at),
                isCPE: u.is.cpe
            }));
        }
        return [];
    });

    const formatDate = dateStr => {
        const date = new Date(dateStr);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = String(date.getFullYear()).slice(-2);
        return `${day}-${month}-${year}`;
    }
</script>