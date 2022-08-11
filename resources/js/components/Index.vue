<template>
    <div class="w-25 h-25">
        <input v-model="title" type="text" class="form-control" placeholder="title">
        <div ref="dropzone" class="p-5 bg-black text-center text-light btn d-block">
            Upload
        </div>
        <input @click.prevent="store" type="submit" class="btn btn-primary">
        <div v-if="post">
            <div>{{ post.title}}</div>
            <div v-for="image in post.images">
                <img :src="image.preview_url" alt="">
                <img :src="image.url" alt="">
            </div>
        </div>
    </div>
</template>

<script>
import Dropzone from 'dropzone'
export default {
    name: "Index",
    data() {
        return {
            dropzone: null,
            title: '',
            post: null
        }
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: 'api/posts',
            autoProcessQueue: false
        })
        this.getPost();
    },
    methods: {
        store() {
            const data = new FormData();
            const files = this.dropzone.getAcceptedFiles();
            files.forEach( file => {
                data.append('images[]', file)
                this.dropzone.removeFile(file)
            })
            data.append('title', this.title)
            this.title = ''
            axios.post('/api/posts', data)
            .then( res => {
                this.getPost();
            })
        },
        getPost() {
            axios.get('/api/posts')
            .then( res => {
                console.log(res.data.data)
                this.post = res.data.data
            })
        }
    }
}
</script>

<style scoped>

</style>
