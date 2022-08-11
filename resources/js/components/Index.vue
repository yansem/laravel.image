<template>
    <div class="w-25 h-25">
        <input v-model="title" type="text" class="form-control" placeholder="title">
        <div ref="dropzone" class="p-5 bg-black text-center text-light btn d-block">
            Upload
        </div>
        <input @click.prevent="store" type="submit" class="btn btn-primary">
    </div>
</template>

<script>
import Dropzone from 'dropzone'
export default {
    name: "Index",
    data() {
        return {
            dropzone: null,
            title: ''
        }
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: 'api/posts',
            autoProcessQueue: false
        })
    },
    methods: {
        store() {
            const images = new FormData();
            const files = this.dropzone.getAcceptedFiles();
            console.log(files)
            files.forEach( file => {
                images.append('images[]', file)
            })
            axios.post('/api/posts', images)
        }
    }
}
</script>

<style scoped>

</style>
