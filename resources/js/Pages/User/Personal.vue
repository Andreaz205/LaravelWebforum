<script>
import {defineComponent} from 'vue'
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link} from "@inertiajs/vue3";

export default defineComponent({
    name: "Personal",
    components: {Link},
    layout: MainLayout,
    props: ['user'],
    data() {
        return {
            content: "",
        }
    },
    methods: {
        async storeAvatar(e) {
            const file = e.target.files[0]
            const formData = new FormData
            formData.append('avatar', file)
            formData.append('_method', 'patch')
            try {
                const response = await axios.post(`/users/personal`, formData)
                this.user.avatar_url = response.data.avatar_url
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
            <h3 class="text-xl mr-4">Личный кабинет</h3>
        </div>
        <div class="bg-white border-gray-300 border p-4">
            <div class="mb-4">
                <p>Приветствую, {{ user.name }}</p>
            </div>
            <div class="mb-4">
                <p class="mb-2">Сменить аватар</p>
                <a
                    @click.prevent="this.$refs.avatar_load.click()"
                    href="#"
                    class="block w-24 h-24 bg-gray-300 rounded-full overflow-hidden"
                >
                    <img class="w-24 h-24" v-if="user.avatar_url" :src="user.avatar_url" :alt="user.name">
                </a>
                <div hidden>
                    <input @change="storeAvatar" type="file" ref="avatar_load">
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
