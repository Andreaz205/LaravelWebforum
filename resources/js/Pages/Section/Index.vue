<script>
import {defineComponent} from 'vue'
import {Link} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";

export default defineComponent({
    name: "Index",
    layout: MainLayout,
    components: {
        Link
    },
    props: [
        'sections'
    ],
    methods: {
        checkSectionAuth(section) {
            return this.$page.props.auth.roles.some(code => [
                'editor',
                `editor.${section.id}`,
            ].includes(code))
        },
        checkGlobalAuth() {
            return this.$page.props.auth.roles.some(code => [
                'editor',
            ].includes(code))
        },
        checkBranchAuth(section, branch) {
            return this.$page.props.auth.roles.some(code => [
                'editor',
                `editor.${section.id}`,
                `editor.${section.id}.${branch.id}`,
            ].includes(code))
        }
    }
})
</script>

<template>
    <div>
        <div class="flex items-center mb-4">
            <h3 class="text-xl mr-4">Разделы</h3>
            <template v-if="checkGlobalAuth()">
                <Link :href="route('sections.create')" class="block w-1/6 mr-4 px-2 py-1 bg-white border border-gray-300 rounded-lg text-center">+ Раздел</Link>
            </template>
            <template v-if="sections.filter(section => checkSectionAuth(section)).length">
                <Link :href="route('branches.create')" class="block w-1/6 px-2 py-1 bg-white border border-gray-300 rounded-lg text-center">+ Ветка</Link>
            </template>
        </div>
        <div v-if="sections">
            <template v-for="section in sections">
                <div
                    v-if="section.branches && section.branches.length"
                    :key="section.id"
                    class="mb-8"
                >
                    <div class="mb-4 flex items-center">
                        <h3 class="text-lg mr-4">{{ section.title }}</h3>
                        <template v-if="checkSectionAuth(section)">
                            <Link :href="route('sections.edit', section.id)" class="mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </Link>
                            <Link :href="route('sections.destroy', section.id)" method="delete">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </Link>
                        </template>

                    </div>
                    <div
                        v-for="branch in section.branches"
                        :key="branch.id"
                        class="flex items-center"
                    >
                        <Link
                            class="block bg-white p-4 border border-gray-300 w-full"
                            :href="route('branches.show', branch.id)"
                        >
                            <h3>{{ branch.title }}</h3>
                        </Link>

                        <template v-if="checkBranchAuth(section, branch)">
                            <Link :href="route('branches.edit', branch.id)" class="block bg-sky-600 p-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 stroke-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </Link>
                            <Link :href="route('branches.destroy', branch.id)" method="delete" class="block bg-red-600 p-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 stroke-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </Link>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<style scoped>

</style>
