<template>
    <div class="w-25 h-25">
        <input v-model="post.title" type="text" class="form-control" placeholder="title">
        <div ref="dropzone" class="p-5 bg-black text-center text-light btn d-block">
            Upload
        </div>
        <vue-editor useCustomImageHandler @image-added="handleImageAdded" v-model="post.content" />
        <input @click.prevent="update" type="submit" class="btn btn-primary" value="Update">
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
            post: {
                title: null,
                content: null
            },
            imageIdsForDelete: []
        }
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: 'api/posts',
            autoProcessQueue: false,
            addRemoveLinks: true
        })
        this.dropzone.on('removedfile', file => {
            this.imageIdsForDelete.push(file.id)
        })
        this.getPost();
    },
    methods: {
        update() {
            const data = new FormData();
            const files = this.dropzone.getAcceptedFiles();
            files.forEach( file => {
                data.append('images[]', file)
                // this.dropzone.removeFile(file)
            })
            data.append('title', this.post.title)
            // this.title = ''
            data.append('content', this.post.content)
            // this.content = ''
            this.imageIdsForDelete.forEach( url => {
                data.append('imageIdsForDelete[]', url)
            })
            data.append('_method', 'PATCH')
            axios.post(`/api/posts/${this.$route.params.id}`, data)
            .then( res => {
                let $previews = this.dropzone.previewsContainer.querySelectorAll('.dz-preview')
                $previews.forEach(preview => {
                    preview.remove()
                })
                this.getPost()
            })
        },
        getPost() {
            axios.get(`/api/posts/${this.$route.params.id}/edit`)
            .then( res => {
                this.post = res.data.data
                const images = this.post.images
                images.forEach( image => {
                    let file = { id: image.id, name: image.title, size: image.size };
                    this.dropzone.displayExistingFile(file, image.preview_url);
                })
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

<style>
.dz-success-mark,
.dz-error-mark {
    display: none;
}

</style>
