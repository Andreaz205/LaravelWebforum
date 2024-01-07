<script>
import {defineComponent} from 'vue'
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link} from "@inertiajs/vue3";

export default defineComponent({
    name: "Create",
    components: {Link},
    layout: MainLayout,
    props: ['sections'],
    data() {
        return {
            title: "",
            section_id: null,
            branches: [],
            parent_id: null
        }
    },
    methods: {
        store() {
            this.$inertia.post('/branches', {
                title: this.title,
                section_id: this.section_id,
                parent_id: this.parent_id,
            })
        },
        getBranches() {
            this.parent_id = null
            axios.get(`/sections/${this.section_id}/branches`)
                .then(res => {
                    this.branches = res.data
                })
        }
    }
})
</script>

<template>
    <div>
        <div class="flex items-center mb-4">
            <h3 class="text-xl mr-4">Добавить ветку</h3>
        </div>
         <div>
             <div v-if="sections?.length > 0" class="mb-4">
                 <select
                     @change="getBranches"
                     v-model="section_id"
                     class="border-gray-300 p-2 w-1/4"
                 >
                     <option value="null" selected disabled>Выберите раздел</option>

                     <template v-for="section in sections">
                         <template v-if="this.$page.props.auth.roles.some(code => [
                                'editor',
                                `editor.${section.id}`,
                            ].includes(code))"
                         >
                             <option :value="section.id">
                                 {{ section.title }}
                             </option>
                         </template>
                     </template>
                 </select>
                 <div v-if="this.$page.props.errors.section_id" class="text-red-600 text-sm">
                     {{ this.$page.props.errors.section_id }}
                 </div>
             </div>
             <div class="mb-4">
                 <select
                     v-if="branches.length > 0"
                     v-model="parent_id"
                     class="border-gray-300 p-2 w-1/4"
                 >
                     <option value="null" selected disabled>Выберите ветку</option>
                     <option
                         v-for="branch in branches"
                         :key="branch.id"
                         :value="branch.id"
                     >{{ branch.title }}</option>
                 </select>
             </div>
             <div class="mb-4">
                 <input type="text" placeholder="Заголовок" v-model="title" class="border-gray-300 p-2 w-1/4">
                 <div v-if="this.$page.props.errors.title" class="text-red-600 text-sm">
                     {{ this.$page.props.errors.title }}
                 </div>
             </div>
             <div>
                 <a class="block w-1/4 py-2 bg-sky-500 text-white text-center border border-sky-600" @click.prevent="store" href="#">Добавить</a>
             </div>
         </div>
    </div>
</template>

<style scoped>

</style>
