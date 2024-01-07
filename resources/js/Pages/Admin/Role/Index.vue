<script>
import {defineComponent} from 'vue'
import {Link} from '@inertiajs/vue3'
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default defineComponent({
    name: "Index",
    layout: AdminLayout,
    props: ['roles'],
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
                <table class="text-center w-full">
                    <thead class="w-full bg-gray-100">
                    <tr class="text-gray-700">
                        <th class="p-4">ID</th>
                        <th class="p-4">Название</th>
                        <th class="p-4">Код</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="role in roles" class="text-gray-500">
                        <td class="p-4">{{ role.id }}</td>
                        <td class="p-4">{{ role.title }}</td>
                        <td class="p-4">{{ role.code }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
