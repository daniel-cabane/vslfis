<template>
    <v-container class="pa-0">
        <div class="mb-2 d-flex justify-space-between align-center">
            <v-btn prepend-icon="mdi-chevron-left" :text="$t('Home')" rounded="pill" :to="'/'"/>
            <access-by-student-dialog/>
        </div>
        <v-data-table 
            :loading="isLoading"
            :items-per-page-options="[25, 50, 100]"
            items-per-page="25"
            :headers="headers"
            :items="items"
            :search="search"
        >
            <template v-slot:loading>
                <div class="text-h6 text-muted text-center">
                    {{ $t('Loading') }}...
                </div>
            </template>
            <template v-slot:top>
                <v-toolbar flat>
                <v-toolbar-title>
                    <input type="date" :lang="locale" @change="dateChange"/>
                </v-toolbar-title>
                <std-text-input density="compact" v-model="search" label="Search name"/>
                </v-toolbar>
            </template>
            <template v-slot:item.student="{ value }">
                <v-chip variant="flat" :text="value.split('-||-')[0]" :color="value.split('-||-')[1]"/>
            </template>
            <template v-slot:item.direction="{ value }">
                <v-icon icon="mdi-login" style="transform: rotateY(180deg);" color="success" v-if="value=='in'"/>
                <v-icon icon="mdi-logout" color="error" v-else/>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon icon="mdi-delete" color="error" @click="prepareDeleteAccess(item)" v-if="item.deletable"/>
                <v-icon icon="mdi-delete" color="muted" v-else/>
            </template>
        </v-data-table>
        <v-dialog v-model="deleteDialog" width="450">
            <v-card :loading="isLoading" :title="$t('Are you sure ?')">
                <v-card-text>
                    <v-checkbox v-model="confirmDelete" :label="$t(focusedAccess.direction == 'in' ? 'Yes, delete this entry' : 'Yes, delete this exit')" hide-details/>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn variant="tonal" color="primary" :text="$t('Cancel')" :disabled="isLoading" @click="deleteDialog=false"/>
                    <v-btn variant="flat" color="error" :text="$t('Delete')" :disabled="!confirmDelete" :loading="isLoading" @click="proceedDelete"/>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>
<script setup>
    import { ref, computed } from "vue";
    import { useAccessStore } from '@/stores/useAccessStore';
    import { storeToRefs } from 'pinia';
    import { useI18n } from 'vue-i18n';

    const { t, locale } = useI18n();

    const accessStore = useAccessStore();
    const { getAccesses, deleteAccess } = accessStore;
    const { accesses, isLoading } = storeToRefs(accessStore);

    const today = new Date();
    const day = String(today.getDate()).padStart(2, '0');
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const year = today.getFullYear();

    getAccesses(`${year}-${month}-${day}`);

    const search = ref('');
    const formatTime = dt => {
        const date = new Date(dt);
        const options = {
            hour: '2-digit',
            minute: '2-digit'
        };

        const formatter = new Intl.DateTimeFormat(locale.value, options);
        return  formatter.format(date);
    }
    const formatStudentName = student => `${student.firstName} ${student.lastName} (${student.level}${student.section})`;
    const headers = computed(() => [
        { title: t('Student'), key: 'student' },
        { title: t('Authorized by'), key: 'by' },
        { title: t('Time'), key: 'time' },
        { title: t('Direction'), key: 'direction', align: 'center' },
        { title: t('Delete'), key: 'actions', align: 'center', sortable: false },
    ]);
    const items = computed(() => accesses.value.map(a => ({
        id: a.id,
        student: `${formatStudentName(a.student)}-||-${a.student.status}`,
        by: a.by.name,
        time: formatTime(a.on),
        direction: a.direction,
        deletable: a.deletable
    })));

    const dateChange = e => {
        getAccesses(e.target.value);
    }

    const focusedAccess = ref(null);
    const confirmDelete = ref(false);
    const deleteDialog = ref(false);
    const prepareDeleteAccess = a => {
        focusedAccess.value = a;
        deleteDialog.value = true;
    }
    const proceedDelete = async () => {
        const res = await deleteAccess(focusedAccess.value.id);
        if(res){
            confirmDelete.value = false;
            deleteDialog.value = false;
        }
    }
</script>