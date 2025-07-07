<template>
    <v-container>
        <v-btn color="primary" @click="addOne">Add</v-btn>
        <div v-for="n in notifs">{{ n.text }}</div>
    </v-container>
</template>
<script setup>
    import { ref } from "vue";
    import useNotifications from '@/composables/useNotifications';
    
    const { addNotification } = useNotifications();

    const notifs = ref([]);

    const addOne = () => {
        const text = Math.random().toString(36).substring(2, 10);
        const colors = ['primary', 'error', 'warning', 'info']
        const type = colors[Math.floor(Math.random() * colors.length)];
        notifs.value.push({text, type});
        addNotification({text, type});
    }
</script>