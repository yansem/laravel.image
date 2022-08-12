<template>
    <div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="post in posts">
                <th scope="row">{{ post.id }}</th>
                <td><router-link :to="{name: 'edit', params: {id: post.id}}">{{ post.title }}</router-link></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "Index",
    data() {
        return {
            posts: null
        }
    },
    mounted() {
        this.getPost()
    },
    methods: {
        getPost() {
            axios.get('/api/posts')
                .then( res => {
                    this.posts = res.data.data
                })
        },
    }
}
</script>

<style scoped>

</style>
