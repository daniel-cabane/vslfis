<template>
    <div class="pt-4">
        <v-text-field 
            variant="outlined"
            v-model="userName"
            :label="$t('Name or email')"
            append-inner-icon="mdi-magnify"
            :disabled="isLoading"
            :loading="isLoading"
            clearable 
            @click:clear="getRecentUsers" 
            @keyup="typing"
        />
        <div class="d-flex flex-wrap ga-4">
            <admin-user-card
                v-for="user in users" 
                :user="user" 
                :isLoading="isLoading"
                @update="updateUser"
                @delete="confirmDelete"
            />
        </div>
        <v-dialog width="450" v-model="deleteDialog">
            <v-card :title="`${$t('Delete')} ${focusedUser.name}`">
            <v-card-text>
                <div>
                    Are you sure you want to delete this user ?
                </div>
                <v-checkbox :label="$t('Yes I am sure')" v-model="deleteSafety" hide-details/>
            </v-card-text>
            <v-card-actions>
                <v-spacer/>
                <v-btn variant="tonal" color="primary" :disabled="isLoading" :text="$t('Close')" @click="deleteDialog = false"/>
                <v-btn variant="flat" color="error" :disabled="!deleteSafety" :loading="isLoading" :text="$t('Confirm')" @click="proceedDelete"/>
            </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script setup>
    import { ref } from "vue";
    import { useAdminStore } from '@/stores/useAdminStore';
    import { storeToRefs } from 'pinia';

    const adminStore = useAdminStore();
    const { getRecentUsers, searchUsers, updateUser, deleteUser } = adminStore;
    const { users, isLoading } = storeToRefs(adminStore);

    getRecentUsers();

    const deleteDialog = ref(false);
    const deleteSafety = ref(false);
    const focusedUser = ref(null);
    const confirmDelete = id => {
        focusedUser.value = users.value.find(u => u.id == id);
        deleteDialog.value = true;
    }
    const proceedDelete = async () => {
        await deleteUser(focusedUser.value.id);
        deleteDialog.value = false;
    }

    const userName = ref('');
    let typingTimer;
    const typing = () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            if(userName.value.length == 0){
                getRecentUsers();
            } else {
                searchUsers(userName.value);
            }
        }, 1000);
    }
</script>