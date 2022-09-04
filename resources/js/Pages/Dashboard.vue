<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import axios from 'axios';
</script>

<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form>
                            <table>
                                <tr>
                                    <th>Name</th>
                                    <td>
                                        <input
                                            type="text"
                                            id="name"
                                            v-model="newLink.name"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Link</th>
                                    <td>
                                        <input
                                            type="text"
                                            id="link"
                                            v-model="newLink.link"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <button
                                            type="button"
                                            @click="addNewLink"
                                        >
                                            Add New Link
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2>Links</h2>

                        <table
                            style="width:100%;"
                        >
                            <tr>
                                <th style="width:20%">Name</th>
                                <th style="width:60%">Link</th>
                                <th style="width:10%">Status</th>
                                <th>Edit</th>
                            </tr>
                            <tr class="link-row" v-for="link,i in links" :key="link.id">
                                <td>{{ link.name }}</td>
                                <td>
                                    <span v-if="editStatus[link.id]">{{ link.link }}</span>
                                    <span v-else>
                                        <input
                                            type="text"
                                            id="link"
                                            v-model="links[i].link"
                                        />
                                    </span>
                                </td>
                                <td>{{ statusNames[link.status] }}</td>
                                <td>
                                    <button
                                        v-if="editStatus[link.id]"
                                        @click="edit(link.id);"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        v-else
                                        @click="save(link);"
                                    >
                                        Save
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <button
                            @click="checkLinks"
                        >
                            Check Links
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<style scoped>
    .link-row td {
        padding-left: 5em;
        padding-right: 5em;
    }
</style>

<script>

export default {
    name: 'Links',
    data() {
        return {
            statusNames: [
                "Unverified",
                "Working",
                "Broken",
            ],
            links: [],
            currentPage: 1,
            maxPage: 1,
            newLink: {
                name: '',
                link: '',
                status: 0,
            },
            editStatus: {},
        }
    },
    mounted() {
        this.reloadLinks();
    },
    methods: {
        checkLinks() {
            axios.get('/api/check-links').then(
                () => {
                    this.reloadLinks();
                }
            );
        },
        edit(id) {
            if (this.editStatus[id]) {
                this.editStatus[id] = !this.editStatus[id];
            } else {
                this.editStatus[id] = true;
            }
        },
        save(link) {
            axios.put('/api/links/' + link.id, link)
                .then(() => {
                    this.reloadLinks();
                })
            this.edit(link.id);
        },
        show(id) {
            if (this.editStatus[id]) {
                return this.editStatus[id];
            }

            this.editStatus[id] = true;

            return this.editStatus[id];
        },
        reloadLinks() {
            this.currentPage = 1;
            axios.get('/api/links?page=' + this.currentPage)
            .then((response) => {
                this.links = response.data.data;
                this.currentPage = response.data.current_page;
                this.maxPage = response.data.last_page;
            });
        },
        addNewLink() {
            if (this.newLink.name && this.newLink.link) {
                axios.post('/api/links', this.newLink)
                    .then((response) => {
                        this.reloadLinks();
                    });
            }
        }
    }
};

</script>