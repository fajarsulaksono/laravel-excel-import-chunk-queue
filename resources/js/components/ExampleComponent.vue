<template>
<div id="progress_upload" class="progress">
    <div class="progress-bar" role="progressbar"
        v-bind:style="{ width: message.current_progress }"
        aria-valuenow="0"
        aria-valuemin="0"
        aria-valuemax="100">{{ message.current_progress }}</div>
</div>
</template>

<script>
    export default {
        data(){
            return {
                message: ''
            }
        },
        mounted() {
            Echo.channel('messages')
                .listen('.newMessage', (message) => {
                    this.message = message;
                    //message.current_progress
                    console.log(message.current_progress);
                });
        }
    }
</script>
