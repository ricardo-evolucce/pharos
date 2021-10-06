<template>
  <div class="form-group row items-push mb-0">
    <ProfileItemEdit :ref="'profile_' + profile.id" v-for="(profile, index) in profiles" :index="index" :profile="profile" :key="profile.id"  :profiles_select ="profiles_select"  :profiles_ids ="profiles_ids"></ProfileItemEdit>
  </div>
</template>

<script>

import ProfileItemEdit from './ProfileItemEdit';
import ProfileFilter from './ProfileFilter';

export default {
  name: "ProfileListEdit",
  components: {
    ProfileItemEdit,
    ProfileFilter
  },
  props: {
    profiles_select: Array,
    profiles_ids: Array,
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
