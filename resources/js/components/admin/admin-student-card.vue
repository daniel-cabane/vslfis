<template>
    <v-card width="400" :title="fullName">
        <div class="d-flex px-2">
            <v-hover>
                <template v-slot:default="{ isHovering, props }">
                    <div style="position:relative" class="mr-4"  v-bind="props">
                        <v-avatar :image="userPhoto" size="100"/>
                        <v-btn
                            class="centering"
                            size="small"
                            icon="mdi-upload"
                            color="primary"
                            v-if="isHovering"
                            @click="triggerFileInput"
                        />
                        <input
                            ref="fileInput"
                            type="file"
                            accept="image/*"
                            style="display:none"
                            @change="onFileChange"
                        />
                    </div>
                </template>
            </v-hover>
            <v-window style="flex:1" v-model="window">
                <v-window-item value="0">
                    <div class="mb-2">
                        <div class="text-caption text-muted">
                            {{ $t('Email') }}
                        </div>
                        <div>
                            {{ student.email }}
                        </div>
                    </div>
                    <div style="flex:1">
                        <div class="text-caption text-muted">
                            {{ $t('Tag number') }}
                        </div>
                        <div v-if="student.tagNb">
                            {{ student.tagNb }}
                        </div>
                        <div class="text-error font-italic" v-else>
                            {{ $t('Missing') }}
                        </div>
                    </div>
                </v-window-item>
                <v-window-item value="1">
                    <v-card-text class="py-2">
                        {{ $t('Are you sure you want to delete this student ?') }}
                        <v-checkbox hide-details :label="$t('Yes I am sure')" v-model="unlockDelete"/>
                    </v-card-text>
                </v-window-item>
            </v-window>
        </div>
        <v-card-actions class="pt-0">
            <div style="width:100px;" class="d-flex justify-center">
                <v-chip variant="flat" :color="student.status">
                    <span style="color:white">{{ $t(student.status) }}</span>
                </v-chip>
            </div>
            <v-spacer/>
            <div v-if="window==0">
                <v-btn color="error" icon="mdi-delete" @click="window=1"/>
                <v-btn color="primary" icon="mdi-pencil" @click="emit('edit', student)"/>
            </div>
            <div v-else>
                <v-btn variant="tonal" color="primary" :text="$t('Cancel')" class="mr-2" @click="window=0"/>
                <v-btn variant="flat" color="error" :disabled="!unlockDelete" :text="$t('Delete')" @click="emit('delete', student)"/>
            </div>
        </v-card-actions>
    </v-card>
</template>
<script setup>
    import { ref, computed } from "vue";

    const props = defineProps({ student: Object });
    const emit = defineEmits(['edit', 'delete', 'image']);

    const userPhoto = computed(() => props.student.photo ? props.student.photo : '/images/default avatar.png');

    const fullName = computed(() => {
        return `${props.student.firstName} ${props.student.lastName} (${props.student.level}${props.student.section})`
    });

    const window = ref(0);
    const unlockDelete = ref(false);

    const fileInput = ref(null);

    function triggerFileInput() {
        fileInput.value && fileInput.value.click();
    }

    function onFileChange(event) {
        const file = event.target.files[0];
        if (file) {
            if (!file.type.startsWith('image/')) {
                emit('image', {file: null, message: 'File must be an image', id: props.student.id});
                return;
            }
            if (file.size > 500 * 1024) { // 500 KB
                emit('image', {file: null, message: 'Image must be 500KB or less', id: props.student.id});
                return;
            }
            emit('image', { file, id: props.student.id });
        }
    }
</script>
<style scoped>
    .centering {
        position:absolute;
        top:50%;
        left:50%;
        margin-top: -20px;
        margin-left: -20px;
    }
</style>
