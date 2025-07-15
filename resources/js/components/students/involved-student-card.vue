<template>
    <v-card class="d-flex pa-3 align-center">
        <v-avatar :image="studentPhoto" size="60" style="cursor:pointer" @click="emit('photo', student.photo)"/>
        <div class="ml-2">
            <div>{{ fullName }}</div>
            <v-chip variant="flat" :color="student.status">
                {{ student.level }}{{ student.section }}
            </v-chip>
        </div>
        <v-spacer/>
        <v-icon icon="mdi-close-circle-outline" color="error" size="x-large" @click="emit('remove', student)"/>
    </v-card>
</template>
<script setup>
    import { computed } from "vue";

    const props = defineProps({ student: Object });
    const emit = defineEmits(['photo', 'remove']);

    const fullName = computed(() => `${props.student.firstName} ${props.student.lastName}`);
    const studentPhoto = computed(() => props.student.photo ? props.student.photo : '/images/default avatar.png');
</script>