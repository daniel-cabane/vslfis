<template>
    <v-list-item :title="fullName" @click="pickStudent">
        <template v-slot:prepend v-if="selectable">
          <v-list-item-action start>
            <v-checkbox-btn :model-value="student.isSelected" @click.stop="emit('selected', student)"/>
          </v-list-item-action>
        </template>
        <v-list-item-subtitle>
            <v-chip class="mt-1" variant="flat" :color="student.status">
                <span style="color:white">
                    {{ student.level }}{{ student.section }}
                </span>
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
    import { useStudentStore } from '@/stores/useStudentStore';

    const { addToHistory } = useStudentStore();

    const props = defineProps({ student: Object, selectable: { type: Boolean, default: false } });
    const emit = defineEmits(['picked', 'photo', 'selected']);

    const fullName = computed(() => `${props.student.firstName} ${props.student.lastName}`);
    const studentPhoto = computed(() => props.student.photo ? props.student.photo : '/images/default avatar.png');

    const pickStudent = () => {
        addToHistory(props.student);
        emit('picked', props.student);
    }
</script>