<template>
    <div class="form-group row items-push mb-0">
      <ProfileItem :ref="'profile_' + profile.id" v-for="(profile, index) in profiles" :index="index" :profile="profile" :key="profile.id"  :profiles_select = "profiles_select"></ProfileItem>
    </div>
</template>

<script>

import ProfileItem from './ProfileItem';
import ProfileFilter from './ProfileFilter';

export default {
    name: 'ProfileList',
    components: {
        ProfileItem,
        ProfileFilter
    },
    props: {
      profiles_select: Array,
    },
    created() {
        this.$store.dispatch('getProfiles');
    },
    computed: {
        profiles() {
            return this.$store.state.profileList
        }
    }
}
</script>
