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
        <div class="flex items-center mb-4">
            <h3 class="text-xl mr-4">Добавить роль</h3>
        </div>
        <div>
            <div class="border border-gray-200 rounded-lg">

            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
