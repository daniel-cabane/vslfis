<template>
    <v-container>
        <v-card>
            <v-tabs v-model="tab">
            <v-tab value="users">{{ $t('Users') }}</v-tab>
            <v-tab value="students">{{ $t('Students') }}</v-tab>
            </v-tabs>

            <v-card-text>
                <v-tabs-window v-model="tab">
                    <v-tabs-window-item value="users">
                        <admin-users-management/>
                    </v-tabs-window-item>
                    <v-tabs-window-item value="students">
                    Two
                    </v-tabs-window-item>
                </v-tabs-window>
            </v-card-text>
        </v-card>
    </v-container>
</template>
<script setup>
    import { ref } from "vue";

    const tab = ref("users");
    
</script>