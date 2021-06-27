const { default: Axios } = require("axios");
const { result } = require("lodash");

var app = new Vue({
    el: '#root',
    data: {
        title: 'Lista dei post con Vue',
        posts: []
    },
    mounted() {
        Axios.get('http://127.0.0.1:8000/api/posts')
        .then(result =>{
            this.posts = result.data.posts
            console.log(this.posts);
        });
    }
});