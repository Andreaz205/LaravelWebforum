<script>
import {defineComponent} from 'vue'
import {Link} from '@inertiajs/vue3'
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default defineComponent({
    name: "Index",
    layout: AdminLayout,
    props: ['users', 'roles'],
    components: {
        Link
    },
    methods: {
        async toggleRole(user, roleId) {
            try {
                const response = await axios.post(`/admin/users/${user.id}/roles`, {
                    role_id: roleId
                })
                user.roles = response.data.roles
            } catch (e) {
                alert(e.message)
            }
        },
        roleChange(user) {
            this.users.forEach(u => u.is_role_changing && u.id !== user.id ? u.is_role_changing = false : null)
            user.is_role_changing = !user.is_role_changing
        }
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
                        <th class="p-4">Имя</th>
                        <th class="p-4">Роли</th>
                        <th class="p-4">Изменить роль</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="user in users" class="text-gray-500">
                        <td class="p-4">{{ user.id }}</td>
                        <td class="p-4">{{ user.name }}</td>
                        <td class="p-4">
                            <p v-for="role in user.roles">
                                {{ role.code }}
                            </p>
                        </td>
                        <td class="p-4 relative">
                            <a @click.prevent="roleChange(user)" href="#">
                                {{ user.is_role_changing ? 'Закрыть' : 'Выбрать' }}
                            </a>
                            <div class="text-left absolute left-0 bg-white p-4 border border-gray-300 z-10" v-if="user.is_role_changing">
                                <div v-for="role in roles" class="mb-2">
                                    <input
                                        class="mr-2"
                                        @change="toggleRole(user, role.id)"
                                        :checked="user.roles.some(r => r.id === role.id)"
                                        type="checkbox"
                                        :value="role.id"
                                        :id="`${role.id}_${user.id}`"
                                    >
                                    <label class="cursor-pointer" :for="`${role.id}_${user.id}`">{{ role.code }}</label>
                                </div>
                            </div>
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
