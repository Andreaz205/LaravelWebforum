<script>
import {defineComponent} from 'vue'
import MainLayout from "@/Layouts/MainLayout.vue";
import {Link} from "@inertiajs/vue3";

export default defineComponent({
    name: "Show",
    components: {Link},
    layout: MainLayout,
    props: ['theme'],
    data() {
        return {
            content: "",
        }
    },
    methods: {
        async store() {
            try {
                const response = await axios.post(`/messages`, {
                    content: this.$refs.editor.innerHTML,
                    theme_id: this.theme.id
                })
                this.$refs.editor.innerHTML = ""
                this.theme.messages.push(response.data)
            } catch (e) {
                alert(e.message)
            }
        },
        async toggleLike(message) {
            try {
                await axios.post(`/messages/${message.id}/likes`)
                message.is_liked ? message.likes-- : message.likes++
                message.is_liked = !message.is_liked
            } catch (e) {
                alert(e.message)
            }
        },
        quote(content) {
            if (window.getSelection().toString()) {
                content = window.getSelection().toString()
            }
            const editor = this.$refs.editor
            const oldText = editor.innerHTML
            editor.innerHTML = `${oldText}<br><blockquote> ${content} </blockquote><br>`
        },
        async answer(message) {
            const title = `<div class="w-full bg-gray-200 border border-gray-300 p-2">Ответ пользователю @${message.user.id} ${message.user.name} ${message.time}</div>`
            // try {
                // const response = await axios.post(`/messages/${message.id}/likes`)

                const editor = this.$refs.editor
                const oldText = editor.innerHTML
                editor.innerHTML = `${oldText} ${title}<blockquote> ${message.content} </blockquote><br>`
            // } catch (e) {
            //     alert(e.message)
            // }
        },
        async complaint(message) {
            try {
                const response = await axios.post(`/messages/${message.id}/complaints`, {
                    body: message.body,
                    theme_id: message.theme_id,
                })
                message.body = ""
                message.is_not_solved_complaint = response.data.is_not_solved_complaint

            } catch (e) {
                alert(e.message)
            }
        },
        openComplaint(message) {
            message.body = ""
            message.is_complaint = !message.is_complaint
        },
        async storeImage(e) {
            const file = e.target.files[0]
            const formData = new FormData
            formData.append('image', file)

            try {
                const response = await axios.post('/images', formData)

                const image = `<span hidden>img_id=${response.data.id}</span><img src="${response.data.url}"/>`
                const editor = this.$refs.editor
                const oldText = editor.innerHTML
                editor.innerHTML = `${oldText}<br> ${image} <br>`
            } catch (e) {
                alert(e.message)
            }
        },
        strong() {
            if (!window.getSelection().toString()) return

            const range = window.getSelection().getRangeAt(0)

            const strong = document.createElement('strong')

            range.surroundContents(strong)
        }
    },
    created() {
        window.Echo.channel(`themes.${this.theme.id}`)
            .listen('.store_message', response => {
                this.theme.messages.push(response.data)
            })

        window.Echo.channel(`themes.${this.theme.id}.likes`)
            .listen('.store_like', response => {
                this.theme.messages
                    .filter(message => message.id === response.data.id)
                    .forEach(message => message.likes = response.data.likes)
            })
    }
})
</script>

<template>
    <div>
        <div class="flex items-center mb-4">
            <h3 class="text-xl mr-4">{{ theme.title }}</h3>
        </div>
        <div v-if="theme.messages?.length">
            <div
                v-for="message in theme.messages"
                :key="message.id"
                class="flex bg-white border border-gray-300"
                :id="message.id"
            >
                <div class="p-4 border-r border-gray-300 w-1/6 flex justify-center items-center flex-col">
                    <div class="w-24 h-24 bg-gray-100 rounded-full overflow-hidden mb-2">
                        <img class="w-24 h-24" v-if="message.user.avatar_url" :src="message.user.avatar_url" :alt="message.user.name">
                    </div>
                    <div>
                        <h3>
                            {{ message.user.name }}
                        </h3>
                    </div>
                </div>
                <div class="p-4 w-5/6">
                    <div class="mb-2">
                        <p class="text-sm italic">{{ message.time }}</p>
                    </div>
                    <div>
                        <div class="mb-4" v-if="message.is_not_solved_complaint">
                            <p class="w-full bg-red-100 border border-red-200 p-2">Ваша жалоба в рассмотрении</p>
                        </div>
                        <div class="mb-4">
                            <p v-html="message.content"></p>
                        </div>
                        <div class="mb-4 flex items-center justify-end w-full">
                            <div class="mr-4">
                                <a
                                    @click.prevent="openComplaint(message)"
                                    href="#"
                                    class="text-sm bg-white inline-block py-2 px-3 mr-4 text-center rounded-lg border border-red-800 text-red-800"
                                >Пожаловаться</a>
                            </div>

                            <div class="mr-4">
                                <a
                                    @click.prevent="quote(message.content)"
                                    href="#"
                                    class="text-sm inline-block py-2 px-3 mr-4 text-center text-white rounded-lg bg-sky-600 border border-sky-600"
                                >Цитировать</a>
                            </div>

                            <div class="mr-4">
                                <a
                                    @click.prevent="answer(message)"
                                    href="#"
                                    class="text-sm inline-block py-2 px-3 mr-4 text-center text-white rounded-lg bg-indigo-600 border border-indigo-600"
                                >Ответить</a>
                            </div>
                            <div class="flex items-center">
                            <span class="mr-2">
                                {{ message.likes }}
                            </span>
                                <a href="#" @click.prevent="toggleLike(message)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                         :class="['w-6 h-6 stroke-sky-600', {'fill-sky-600': message.is_liked}]"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="flex" v-if="message.is_complaint">
                            <input v-model="message.body" class="p-2 w-5/6 rounded-r-none rounded-lg border border-gray-300" type="text" placeholder="Ваша жалоба">
                            <a
                                href="#"
                                class="block rounded-l-none w-1/6 text-center bg-red-800 text-white p-2 rounded-lg"
                                @click.prevent="complaint(message)"
                            >
                                Отправить
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white border-gray-300 border p-4">
            <div class="mb-4">
                <h3 class="text-xl mr-4">Добавить сообщение</h3>
            </div>
            <div class="bg-gray-50 border border-gray-100 p-2 flex items-center">
                <div class="mr-2">
                    <a
                        @click.prevent="$refs.image.click()"
                        href="#"
                        class="block w-6"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                    </a>
                    <input
                        hidden
                        type="file"
                        ref="image"
                        @change="storeImage"
                    >
                </div>

                <div>
                    <a
                        href="#"
                        @click.prevent="strong"
                        class="text-lg"
                    >
                        <strong>B</strong>
                    </a>
                </div>
            </div>
            <div class="mb-4">
                <div
                    ref="editor"
                    class="w-full border border-gray-300 p-2"
                    contenteditable="true"
                >
                </div>
            </div>
            <div>
                <a
                    @click.prevent="store"
                    href="#"
                    class="block w-1/4 p-2 bg-sky-600 text-white text-center border border-sky-700"
                >Опубликовать</a>
            </div>
        </div>
    </div>
</template>

<style>
    blockquote {
        display: block;
        padding: 4px;
        border-left: 4px solid #a0aec0;
        background-color: #F6F6F6;
    }
</style>
