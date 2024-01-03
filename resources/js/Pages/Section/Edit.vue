<script>
import {defineComponent} from 'vue'
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link} from "@inertiajs/vue3";

export default defineComponent({
    name: "Create",
    components: {Link},
    layout: MainLayout,
     props: ['section'],
    data() {
        return {
            title: this.section.title
        }
    },
    methods: {
        store() {
            this.$inertia.patch(`/sections/${this.section.id}`, {title: this.title})
        }
    }
})
</script>

<template>
    <div>
        <div class="flex items-center mb-4">
            <h3 class="text-xl mr-4">Редактировать раздел</h3>
        </div>
         <div>
             <div class="mb-4">
                 <input type="text" placeholder="Заголовок" v-model="title" class="border-gray-300 p-2 w-1/4">
             </div>
             <div>
                 <a
                    class="block w-1/4 py-2 bg-sky-500 text-white text-center border border-sky-600"
                    @click.prevent="store"
                    href="#"
                 >
                     Сохранить
                 </a>
             </div>
         </div>
    </div>
</template>

<style scoped>

</style>
