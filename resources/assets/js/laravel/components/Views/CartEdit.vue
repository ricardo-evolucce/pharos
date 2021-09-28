<template>
  <div class="content content-full">
    <div class="block block-fx-pop">
      <div class="block-content">
          <form :action="action" method="POST">
            <input type="hidden" name="_token" :value="csrf_token">
              <div class="col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label for="name">
                      Nome <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="name" name="name" :value="cart.name">
                  </div>
                  <select class="custom-select" id="client_id" name="client_id">
                    <option
                      v-for="(client, key) in clients"
                      :selected= "cart.client_id == client.id"
                      :value="client.id"
                      :key="key"
                    >{{client.contact}}</option>
                  </select>
                <br>
                <!-- People -->
                <h2 class="content-heading pt-0">Agenciados</h2>
                <div class="row">
                <div class="col-12" v-for="(profile, key, index) in profiles" :key="index" >
                  <input
                    type="checkbox"
                    id="key"
                    :name="'profile_ids[]'"
                    :value="profile.id"
                    :checked="profiles_select.includes(profile.id)"
                  />
                  <label :for="key">{{profile.fancy_name}}</label>
                </div>
                </div>
                <!-- END People -->
                  <div class="form-group">
                    <button
                        type="submit"
                        name="action"
                        class="btn btn-success"
                        value="save">
                        <i class="fa fa-check-circle mr-1"></i> Salvar
                    </button>
                  </div>
              </div>
          </form>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    csrf_token: String,
    action: String,
    cart: Object,
    clients: Array,
    profiles: Array,
    profiles_select: Array,
  },
}
</script>

<style scoped>

</style>