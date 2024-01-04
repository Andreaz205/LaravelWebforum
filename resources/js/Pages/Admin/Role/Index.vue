<script>
import {defineComponent} from 'vue'
import {Link} from '@inertiajs/vue3'
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default defineComponent({
    name: "Index",
    layout: AdminLayout,
    components: {
        Link
    },
    methods: {
    }
})
</script>

<template>
    <div>
        <div class="flex items-center mb-4 whitespace-nowrap">
            <h3 class="text-xl mr-4">Роли</h3>
            <Link :href="route('admin.roles.create')" class="block w-1/6 mr-4 px-2 py-1 bg-white border border-gray-300 rounded-lg text-center">+ Роль</Link>
        </div>
        <div>
            <div class="border border-gray-200 rounded-lg">

            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
