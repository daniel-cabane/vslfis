<template>
    <v-data-table :items="userItems" v-if="isReady">
        <template v-slot:item.CPE="{ value }">
          <v-icon icon="mdi-check" color="success" v-if="value"/>
      </template>
    </v-data-table>
</template>
<script setup>
    import { computed } from "vue";
    import { useAdminStore } from '@/stores/useAdminStore';
    import { storeToRefs } from 'pinia';

    const adminStore = useAdminStore();
    const { getUsers } = adminStore;
    const { users, isLoading, isReady } = storeToRefs(adminStore);

    getUsers();

    const userItems = computed(() => {
        if(users){
            return users.value.map(u => ({
                name: u.name,
                email: u.email,
                Registered: formatDate(u.created_at),
                CPE: u.is.cpe
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