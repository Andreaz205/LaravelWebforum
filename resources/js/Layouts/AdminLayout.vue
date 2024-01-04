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
            <div class="w-1/2 mx-auto">
                <Link :href="route('sections.index')" class="mr-4">Форум</Link>
                <Link :href="route('users.personal')">Личный кабинет</Link>
            </div>
        </div>
        <div class="w-1/2 flex items-start mx-auto py-4">
            <div class="w-1/4 bg-white mr-4 border-gray-300 border">
                <h3 class="text-xl mr-4 p-4">Меню</h3>
                <div>
                    <div>
                        <Link class="block p-4 w-full border-t border-b border-gray-300" :href="route('admin.main.index')">Статистика</Link>
                    </div>
                    <div>
                        <Link class="block p-4 w-full border-b border-gray-300" :href="route('admin.complaints.index')">Жалобы</Link>
                    </div>
                </div>
            </div>
            <div class="w-3/4 bg-white p-4 border-gray-300 border">
                <slot/>
            </div>

        </div>
    </div>
</template>

<style scoped>

</style>
