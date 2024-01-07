<script>
import {defineComponent} from 'vue'
import {Link} from '@inertiajs/vue3'
import {toNumber} from "@vue/shared";

export default defineComponent({
    name: "MainLayout",
    data () {
        return {
            isOpen: false
        }
    },
    methods: {
        async openNotifications() {
            if (this.$page.props.auth.notifications.length) {
                this.isOpen = !this.isOpen
                const ids = this.$page.props.auth.notifications.map(n => n.id)

                try {
                    const response = await axios.patch(`/notifications/update_collection`, {
                        ids: ids
                    })
                    this.$page.props.auth.notification_count = response.data.count
                } catch (e) {
                    alert(e.message)
                }
            }
        }
    },
    components: {
        Link
    },
    created() {
        window.Echo.private(`users.notifications.${this.$page.props.auth.user.id}`)
            .listen('.user_notifications', response => {
                this.$page.props.auth.notifications.unshift(response.data)
                this.$page.props.auth.notification_count++
            })
    }
})
</script>

<template>
    <div>
        <div class="bg-white border-b border-gray-300 p-4">
            <div class="w-3/4 mx-auto flex items-center justify-between">
                <div>
                    <Link :href="route('sections.index')" class="mr-4">Форум</Link>
                    <Link :href="route('users.personal')" class="mr-4">Личный кабинет</Link>
                    <Link :href="route('admin.main.index')">Админ панель</Link>
                </div>
                <div class="w-1/4 text-right">
                    <div class="relative">
                        <a href="#" @click="openNotifications">
                            <span class="mr-2">Оповещения</span>
                            <span>{{ this.$page.props.auth.notification_count }}</span>
                        </a>
                        <div v-if="this.$page.props.auth.notifications.length && isOpen" class="absolute w-full">
                            <div
                                class="border p-4 border-b border-gray-300 bg-white text-left"
                                v-for="notification in this.$page.props.auth.notifications"
                            >
                                <p>{{ notification.title }}</p>
                                <Link class="text-sky-600" :href="notification.url">Перейти</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-3/4 mx-auto py-4">
            <slot />
        </div>
    </div>
</template>

<style scoped>

</style>
