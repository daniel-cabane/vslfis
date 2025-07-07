import { reactive } from "vue";

const notifications = reactive([]);

const addNotification = n => {
    if (typeof n === "object" && n !== null && "text" in n && "type" in n) {
        notifications.push({ text: n.text, type: n.type });
    } else if (typeof n === "string") {
        notifications.push({ text: n, type: "primary" });
    }
}

export default function useNotifications () {
    return { notifications, addNotification }
}