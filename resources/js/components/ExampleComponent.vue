<template>
</template>

<script>
    export default {
        data(){
            return {
                messages: [],
                message: '',
            }
        },
        mounted() {
            Echo.channel('messages')
                .listen('.newMessage', (message) => {
                    this.messages.push(message);
                    console.log(message);
                });
        }
    }
</script>
