<script>
import {defineComponent} from 'vue'
import {Link} from '@inertiajs/vue3'
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default defineComponent({
    name: "Index",
    layout: AdminLayout,
    props: ['complaints'],
    components: {
        Link
    },
    methods: {
        async update(complaint) {
            try {
                const response = await axios.patch(`/admin/complaints/${complaint.id}`)
                complaint.is_solved = response.data.is_solved
            } catch (e) {
                alert(e.message)
            }
        }
    }
})
</script>

<template>
    <div>
        <div class="flex items-center mb-4">
            <h3 class="text-xl mr-4">Жалобы</h3>
        </div>
        <div>
            <div class="border border-gray-200 rounded-lg">
                <table class="text-center w-full">
                    <thead class="w-full bg-gray-100">
                        <tr class="text-gray-700">
                            <th class="p-4">ID</th>
                            <th class="p-4">Текст</th>
                            <th class="p-4">Пользователь</th>
                            <th class="p-4">Ссылка(Сообщения)</th>
                            <th class="p-4">Статус</th>
                            <th class="p-4">Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="complaint in complaints" class="text-gray-500">
                            <td class="p-4">{{ complaint.id }}</td>
                            <td class="p-4">{{ complaint.body }}</td>
                            <td class="p-4">{{ complaint.user.name }}</td>
                            <td class="p-4">
                                <a target="_blank" :href="route('themes.show', complaint.theme_id) + `#${complaint.message_id}`">
                                    Ссылка
                                </a>
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                {{  complaint.is_solved ? 'Решено' : "В работе" }}
                            </td>
                            <td class="p-4">
                                <a @click.prevent="update(complaint)" href="#" class="block w-6 h-6 mx-auto">
                                    <svg :class="['w-6 h-6', {'stroke-red-700': !complaint.is_solved, 'stroke-green-700': complaint.is_solved}]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
