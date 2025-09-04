<template>
    <v-dialog scrollable max-width="500">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn
                color="primary" 
                rounded="pill" 
                prepend-icon="mdi-account-group-outline" 
                :text="$t('Students')" 
                v-bind="activatorProps"
                style="flex:1"
            />
        </template>
        <template v-slot:default="{ isActive }">
            <v-card :title="$t('Student history')">
                <v-card-text v-if="history.length">
                    <v-checkbox class="ml-2" hide-details label="Select all" v-model="checkAllBox" @click="toggleAll"/>
                    <v-list>
                        <student-preview v-for="student in history" :student="student" selectable @selected="selectStudent"/>
                    </v-list>
                </v-card-text>
                <v-card-text class="d-flex justify-center text-h6 text-muted" v-else>
                    -- {{ $t('Empty History') }} --
                </v-card-text>
                <v-card-actions>
                    <v-btn variant="flat" color="error" :text="$t('Remove')" :disabled="selectionEmpty" @click="removeFromHistory"/>
                    <v-spacer/>
                    <v-btn variant="tonal" color="primary" :text="$t('Close')" @click="isActive.value = false"/>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
</template>
<script setup>
    import { ref, computed } from 'vue';
    import { useStudentStore } from '@/stores/useStudentStore';
    import { storeToRefs } from 'pinia';

    const studentStore = useStudentStore();
    const { removeFromHistory } = studentStore;
    const { history } = storeToRefs(studentStore);

    const selectStudent = student => {
        student.isSelected = !student.isSelected;
    }

    const selectionEmpty = computed(() => !history.value.some(s => s.isSelected));
    const checkAllBox = ref(false);
    const toggleAll = () => {
        history.value.forEach(s => s.isSelected = !checkAllBox.value);
    }
</script>
