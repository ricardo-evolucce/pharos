<template>
    <div class="col-md-4 col-xl-4">
        <div class="custom-control custom-block custom-control-primary">
            <input type="checkbox" class="custom-control-input" :id="'profile_' + profile.id" name="profile_id[]" :value="profile.id">
            <label class="custom-control-label" v-bind:for="'profile_' + profile.id " @click="toggleItemNewCart(profile)">
                <span class="d-flex align-items-center">
                    <img class="img-avatar img-avatar48" :src="getAvatarSrc(profile.avatar)" alt="">
                    <span class="ml-2">
                        <span class="font-w700">{{ profile.fancy_name }}, {{ profile.years_old }} anos.</span>
                    </span>
                </span>
            </label>
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
    name: 'ProfileItem',
    props: ['index', 'profile'],
    methods: {
        ...mapActions(['toggleItemNewCart']),
        getAvatarSrc(src){
            let aux = ''
            let index = src.indexOf('uploads')
            if(index>-1){
                aux = src.substring(index-1)
            }
            else {
                aux = src
            }
            return aux
            
        }
    },

}
</script>
