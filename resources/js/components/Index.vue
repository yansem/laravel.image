<template>
    <div class="w-25 h-25">
        <input v-model="title" type="text" class="form-control" placeholder="title">
        <div ref="dropzone" class="p-5 bg-black text-center text-light btn d-block">
            Upload
        </div>
        <vue-editor useCustomImageHandler @image-added="handleImageAdded" v-model="content" />
        <input @click.prevent="store" type="submit" class="btn btn-primary">
        <div v-if="post">
            <div>{{ post.title}}</div>
            <div v-for="image in post.images">
                <img :src="image.preview_url" alt="">
                <img :src="image.url" alt="">
            </div>
            <div class="ql-editor" v-html="post.content"></div>
        </div>
    </div>
</template>

<script>
import { VueEditor } from "vue2-editor";
import Dropzone from 'dropzone'
export default {
    name: "Index",
    components: { VueEditor },
    data() {
        return {
            dropzone: null,
            title: '',
            post: null,
            content: null
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
            data.append('content', this.content)
            this.content = ''
            axios.post('/api/posts', data)
            .then( res => {
                this.getPost();
            })
        },
        getPost() {
            axios.get('/api/posts')
            .then( res => {
                this.post = res.data.data
            })
        },
        handleImageAdded(file, Editor, cursorLocation, resetUploader) {
            const formData = new FormData();
            formData.append("image", file);

            axios.post("/api/posts/images", formData)
                .then(result => {
                    const url = result.data.url; // Get url from response
                    Editor.insertEmbed(cursorLocation, "image", url);
                    resetUploader();
                })
                .catch(err => {
                    console.log(err);
                });
        }
    }
}
</script>

<style scoped>

</style>
