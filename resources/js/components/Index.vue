<template>
    <div class="w-25 h-25">
        <div ref="dropzone" class="p-5 bg-black text-center text-light btn d-block">
            Upload
        </div>
    </div>
</template>

<script>
import Dropzone from 'dropzone'
export default {
    name: "Index",
    data() {
        return {
            dropzone: null
        }
    },
    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: 'qwe'
        })
    }
}
</script>

<style scoped>

</style>
