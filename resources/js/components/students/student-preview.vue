<template>
    <v-list-item :title="fullName" @click="emit('picked', student)">
        <v-list-item-subtitle>
            <v-chip variant="flat" :color="student.status">
                {{ student.level }}{{ student.section }}
            </v-chip>
        </v-list-item-subtitle>
        <template v-slot:append>
            <v-avatar :image="studentPhoto" size="80" @click.stop="emit('photo', student.photo)"/>
        </template>
    </v-list-item>
    <v-divider/>
</template>
<script setup>
    import { computed } from "vue";
    const props = defineProps({ student: Object });
    const emit = defineEmits(['picked', 'photo']);

    const fullName = computed(() => `${props.student.firstName} ${props.student.lastName}`);
    const studentPhoto = computed(() => props.student.photo ? props.student.photo : '/images/default avatar.png');
</script>