<script>
import {defineComponent} from 'vue'
import {Link} from '@inertiajs/vue3'

export default defineComponent({
    name: "MainLayout",
    components: {
        Link
    }
})
</script>

<template>
    <div>
        <div class="bg-white border-b border-gray-300 p-4">
            <div class="w-3/4 mx-auto">
                <Link :href="route('sections.index')" class="mr-4">Форум</Link>
                <Link :href="route('users.personal')" class="mr-4">Личный кабинет</Link>
                <Link :href="route('admin.main.index')">Админ панель</Link>
            </div>
        </div>
        <div class="w-3/4 mx-auto py-4">
            <slot />
        </div>
    </div>
</template>

<style scoped>

</style>
