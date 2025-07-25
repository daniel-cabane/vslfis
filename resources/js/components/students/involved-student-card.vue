<template>
    <v-card class="pa-3" :loading="isLoading">
        <div class="d-flex align-center">
            <v-avatar :image="studentPhoto" size="60" style="cursor:pointer" @click="emit('photo', student.photo)"/>
            <div class="ml-2">
                <div>{{ fullName }}</div>
                <v-chip variant="flat" :color="student.status">
                    {{ student.level }}{{ student.section }}
                </v-chip>
            </div>
            <v-spacer/>
            <v-icon icon="mdi-close-circle-outline" color="error" size="x-large" @click="emit('remove', student)"/>
        </div>
        <div class="d-flex align-center ga-2 mt-3" v-if="showActions">
            <v-btn 
                variant="outlined" 
                color="primary" 
                :text="$t('Exit')" 
                :loading="isLoading" 
                style="flex:1"
                @click="emit('access', {direction: 'out', id: student.id})"
            />
            <v-btn 
                variant="outlined" 
                color="secondary" 
                :text="$t('Enter')" 
                :loading="isLoading" 
                style="flex:1"
                @click="emit('access', {direction: 'in', id: student.id})"
            />
            <!-- <v-icon icon="mdi-alert-box"/> -->
        </div>
    </v-card>
</template>
<script setup>
    import { computed } from "vue";

    const props = defineProps({ student: Object, showActions: {type: Boolean, default: false}, isLoading: Boolean });
    const emit = defineEmits(['photo', 'remove', 'access']);

    const fullName = computed(() => `${props.student.firstName} ${props.student.lastName}`);
    const studentPhoto = computed(() => props.student.photo ? props.student.photo : '/images/default avatar.png');
</script>