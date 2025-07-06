import { reactive } from "vue";

const notifications = reactive([]);

const addNotification = n => {
    if (typeof n === "object" && n !== null && "text" in n && "color" in n) {
        notifications.push({ text: n.text, color: n.color });
    } else if (typeof n === "string") {
        notifications.push({ text: n, color: "primary" });
    }
}

export default function useNotifications () {
    return { notifications, addNotification }
}