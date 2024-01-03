<script>
import {defineComponent} from 'vue'
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link} from "@inertiajs/vue3";

export default defineComponent({
    name: "Edit",
    components: {Link},
    layout: MainLayout,
    props: ['theme'],
    data() {
        return {
            title: this.theme.title,
            description: this.theme.description,
        }
    },
    methods: {
        update() {
            this.$inertia.patch(`/themes/${this.theme.id}`, {
                title: this.title,
                description: this.description,
            })
        }
    }
})
</script>

<template>
    <div>
        <div class="flex items-center mb-4">
            <h3 class="text-xl mr-4">Редактировать тему</h3>
        </div>
        <div>
            <div class="mb-4">
                <input type="text" placeholder="Заголовок" v-model="title" class="border-gray-300 p-2 w-1/2">
            </div>
            <div class="mb-4">
                <textarea placeholder="Описание" v-model="description" class="border-gray-300 p-2 w-1/2">
                    {{ description }}
                </textarea>
            </div>
            <div>
                <a class="block w-1/4 py-2 bg-sky-500 text-white text-center border border-sky-600" @click.prevent="update" href="#">Сохранить</a>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
