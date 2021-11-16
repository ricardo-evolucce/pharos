<template>
  <div class="content content-full">
    <div class="block block-fx-pop">
      <div class="block-content">
          <form
            :action="action"
            method="POST"
            @submit="updateForm"
          >
            <input
                type="hidden"
                name="_token"
                :value="csrf_token"
            >
              <div class="col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label for="name">
                      Nome <span class="text-danger">*</span>
                    </label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="cart.name"
                    >
                  </div>
                  <label for="name">
                      Produtora selecionada<span class="text-danger">*</span>
                  </label><br>
                   <select class="custom-select" v-model="cart.client_id">
                    <option
                      v-for="(client, key) in clients"
                      :selected= "cart.client_id == client.id"
                      :value="client.id"
                      :key="key"
                    >{{client.contact}}</option>
                  </select>
                  <br>
                  <label for="name">
                      Enviar para outras produtoras<span class="text-danger">*</span>
                  </label><br>
                  <div v-for="(client, key, index) in clients" :key="index">
                    <input type="checkbox"
                       :id="key"
                       :selected= "cart.client_id == client.id"
                       :name="'client_ids[]'"
                       :value="client.id"
                       v-model="client_ids">
                       <label
                           :for="key">
                           {{ client.contact }}
                       </label>
                  </div>
                <br>
                <!-- People -->
                <h2 class="content-heading pt-0">Agenciados</h2>
                <div class="row push">
                  <div class="col-lg-12">
                    <profile-list-edit ref="profile-list-edit" :profiles_select = "profiles_select" :profiles_ids = "profiles_ids"></profile-list-edit>
                  </div>
                </div>
                <!-- END People -->

                <!-- Submit -->
                <div class="row push">
                  <!-- Modal escolher fotos -->
                  <div
                      class="modal fade"
                      id="modal"
                      tabindex="-1"
                      role="dialog"
                      aria-labelledby="modalLabel"
                      aria-hidden="true"
                  >
                    <div
                        class="modal-dialog modal-dialog-centered"
                        style="max-width: max-content;min-width: 500px;"
                        role="document"
                    >
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5
                              class="modal-title"
                              id="modalLabel"
                          >Fotos dos atores</h5>
                          <button
                              type="button"
                              class="close"
                              data-dismiss="modal"
                              aria-label="Close"
                          >
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div
                            class="modal-body"
                            style="max-width:1200px"
                        >
                          <div class="row">
                            <div
                                v-if="loading"
                                class="col-12 text-center"
                            >
                              <div class="fa-3x text-primary">
                                <i class="fas fa-spinner fa-spin"></i>
                              </div>
                            </div>
                            <div
                                v-else
                                class="col-12"
                            >
                             <!-- {{arrayFotos}} -->
                             <!--{{arrayFotosSelecionadasTabs}}-->
                             <!-- {{selectedImages}} -->
                             <!-- TABS -->
                              <nav>
                                <div
                                    class="nav nav-tabs"
                                    id="nav-tab"
                                    role="tablist"
                                >
                                  <a
                                      @click="clickTab(profile)"
                                      v-for="(profile, key) in new_cart"
                                      :key="key"
                                      :class="`nav-item nav-link ${key===0?'active':''}`"
                                      :id="`nav-${profile.id}-tab`"
                                      data-toggle="tab"
                                      :href="`#nav-${profile.id}`"
                                      role="tab"
                                  >{{profile.fancy_name}}</a>
                                </div>
                              </nav>
                              <div
                                  class="tab-content"
                                  id="nav-tabContent"
                              >
                                <div
                                    v-for="(profile, key) in new_cart"
                                    :key="key"
                                    :class="`tab-pane fade ${key===0?'show active':''}`"
                                    :id="`nav-${profile.id}`"
                                    role="tabpanel"
                                >
                                  <div class="row">
                                    <div class="col col-12">
                                      <vue-select-image
                                          w="175"
                                          isMultiple
                                          :dataImages="arrayFotosTab"
                                          :selectedImages="selectedImages"
                                          @onselectmultipleimage="onselectmultipleimage"
                                      >
                                      </vue-select-image>
                                    </div>
                                  </div>

                                </div>
                              </div>
                              <!-- END TABS -->
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button
                              type="submit"
                              name="action"
                              class="btn btn-success"
                              value="edit_send"
                              v-on:click="btnOnClickSelect('edit_send')"
                          >
                            <i class="fa fa-check-circle mr-1"></i> Salvar e enviar
                          </button>
                          <button
                              type="submit"
                              name="action"
                              class="btn btn-success"
                              value="save"
                              v-on:click="btnOnClickSelect('save')"
                          >
                            <i class="fa fa-check-circle mr-1"></i> Salvar
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-8 col-xl-5 offset-lg-4">
                    <div class="form-group">
                      <button
                          type="button"
                          class="btn btn-primary"
                          data-toggle="modal"
                          data-target="#modal"
                          value="escolherfotos_continuar"
                          :disabled="!new_cart.length"
                          @click="escolherFotosContinuar()"
                      >
                        <i class="fa fa-arrow-right mr-1"></i> Escolher fotos e continuar
                      </button>
                    </div>
                  </div>
                </div>
                <!-- END Submit -->
                <!--{{ arrayFotosSelecionadasTabs}}-->
                <template v-for="(fotos, profile_id) in arrayFotosSelecionadasTabs">
                  <input
                      v-for="foto in fotos"
                      :key="foto.src"
                      type="checkbox"
                      class="custom-control-input"
                      :name="`fotos[${profile_id}][]`"
                      :value="foto.src"
                      checked
                  >
                </template>
              </div>
          </form>
      </div>
    </div>
    <!-- messages error request-->
    <div class="row">
      <div
          class="modal"
          id="modal-messages-error"
          tabindex="-1"
          role="dialog"
          aria-labelledby="modalLabel"
          aria-hidden="true"
      >
        <div
            class="modal-dialog modal-dialog-centered"
            style="max-width: max-content;min-width: 500px;"
            role="document"
        >
          <div class="modal-content">
            <div class="modal-header">
              <p>Atenção!!!</p>
              <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div
                class="modal-body alert alert-danger"
                style="max-width:1200px"
            >
              <!-- erros do request -->
              <p v-if="errors.name">
                {{errors.name[0]}}
              </p>
              <p v-if="errors.client_ids">
                {{errors.client_ids[0]}}
              </p>
              <p v-if="errors.fotos">
                {{errors.fotos[0]}}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- messages succes-->
    <div class="row">
      <div
          class="modal"
          id="modal-messages-sucess"
          tabindex="-1"
          role="dialog"
          aria-labelledby="modalLabel"
          aria-hidden="true"
      >
        <div
            class="modal-dialog modal-dialog-centered"
            style="max-width: max-content;min-width: 500px;"
            role="document"
        >
          <div class="modal-content">
            <div class="modal-header">
              <p>Sucesso</p>
              <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div
                class="modal-body alert alert-success"
                style="max-width:1200px"
            >
              <p v-if="messages">
                {{messages}}
              </p>
            </div>
            <div v-if="status == 2" class="modal-footer">
              <button
                  type="submit"
                  name="action"
                  class="btn btn-success"
                  value="create_new"
                  v-on:click="btnOnClickSelect('create_new')"
              >
                <i class="fa fa-check-circle mr-1"></i> Novo carrinho
              </button>
              <button
                  type="submit"
                  name="action"
                  class="btn btn-success"
                  value="back_lists"
                  v-on:click="btnOnClickSelect('back_lists')"
              >
                <i class="fa fa-check-circle mr-1"></i> Ver todos
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import {mapActions, mapGetters} from "vuex";
import ProfileListEdit from "../ProfileListEdit";

export default {
  components: {ProfileListEdit},
  props: {
    csrf_token: String,
    action: String,
    cart: Object,
    clients: Array,
    profiles: Array,
    profiles_select: Array,
    profiles_ids: Array,
  },
  data () {
    return {
      loading: false,
      arrayFotos: {},
      arrayFotosTab: [],
      idsProfiles: [],
      arrayFotosSelecionadasTabs: {},
      arrayFotosSelecionadasSendTabs: {},
      selectedImages: [],
      errors: [],
      messages: '',
      status: 0,
      actionSelect: '',
      name: '',
      client_id: '',
      client_ids: [],
      profile_id: [],
      tab: {
        profile: null
      }
    }
  },
  computed: {
    ...mapGetters(['new_cart','DIRECTORY_SEPARATOR']),
  },
  created() {
    this.fotosEscolhidas();
  },
  methods: {
    ...mapActions(['getUserPhotos','getUserPhotosCarts','addItemEditCart']),
    fotoParaObjetoFoto(foto){
      return {
        id: `${foto}`,
        src: `${foto}`,
      }
    },
    escolherFotosContinuar () {
      this.loading = true
      this.arrayFotosTab = []
      this.selectedImages = []
      this.arrayFotos = {}
      console.log(this.new_cart[0])

      let ids = []
      this.profile_id = []
      let objeto_fotos_select = []
      
      // traz as fotos cadastradas dos agenciados
      for (const key in this.new_cart) {
        const profile = this.new_cart[key];
        ids.push(profile.user_id)
        this.profile_id.push(profile.id)
      }
      this.getUserPhotos(ids)
      .then(perfis_fotos => {
        this.arrayFotos = perfis_fotos
      })
      // traz as fotos selecionadas do carrinho
      this.getUserPhotosCarts(this.cart.id)
          .then(cart_select_photos => {
            for (const i in cart_select_photos) {
              const fotos = cart_select_photos[i];
              for (const j in fotos) {
                const foto = fotos[j];
                this.selectedImages.push(this.fotoParaObjetoFoto(foto))
              }
            }
      
      this.montarArrayFotosSelecionadas(this.selectedImages)

      }).finally(() => {
         this.loading = false
      })
    },

    fotosEscolhidas () {
     for(const pro in this.profiles_select){
          this.addItemEditCart(this.profiles_select[pro])
          this.idsProfiles.push(this.profiles_select[pro].user_id)
        }
    },
    montarArrayFotosSelecionadas(fotos){

      this.arrayFotosSelecionadasSendTabs = {}
      for (const y in fotos) {
          let user_id_2 = this.pegarUserIdPelaFoto(JSON.stringify(fotos[y].src).replace(/"/g, ""))
          
          if (!this.arrayFotosSelecionadasSendTabs.hasOwnProperty(user_id_2)) {
            this.arrayFotosSelecionadasSendTabs[user_id_2] = []
          }
          this.arrayFotosSelecionadasSendTabs[user_id_2].push(fotos[y])
          this.arrayFotosSelecionadasTabs = this.arrayFotosSelecionadasSendTabs
      }
    },
    onselectmultipleimage (fotos) {
      this.arrayFotosSelecionadasTabs = {}
      this.montarArrayFotosSelecionadas(fotos)

     this.addRemoveItemPhotoCart(this.arrayFotosSelecionadasSendTabs, JSON.stringify(this.cart.id))
        .then(response=>{
          this.escolherFotosContinuar()
      })
    },
    clickTab (profile) {

      this.tab.profile = profile
      this.exibirFotosTab()
    },
    exibirFotosTab () {
      this.loading = true
      this.arrayFotosTab = []
      let user_id = this.tab.profile.user_id
      let fotos_perfil = this.arrayFotos[user_id]

      for (const key in fotos_perfil) {
        const foto = fotos_perfil[key];
        let objeto = this.fotoParaObjetoFoto(foto)
        this.arrayFotosTab.push(objeto)
      }
      
      this.loading = false
    },
    pegarUserIdPelaFoto(foto){
      let user_id = "";
      let path_partes = foto.split(this.DIRECTORY_SEPARATOR)
      user_id = path_partes[path_partes.length - 2]

      if(typeof user_id == 'undefined') {
        let path_partes = foto.split("/");
        user_id = path_partes[3]
      }

      return user_id
    },
    btnOnClickSelect(selectAction){
      this.actionSelect = selectAction;
      if(this.actionSelect == "create_new"){
        window.location.href = "/carts/create";
      }else if(this.actionSelect == "back_lists"){
        window.location.href = "/carts";
      }
    },
    updateForm: function (e) {
      this.loading = true
      axios.post(`/carts/update/`+ this.cart.id,{
          name: this.cart.name,
          profile_id: this.profile_id,
          client_id: this.cart.client_id,
          client_ids: this.client_ids,
          fotos: this.arrayFotosSelecionadasSendTabs,
          action: this.actionSelect

        }).then(response=>{
          this.loading = false
          this.actionSelect = "";
          this.messages = "";
          this.messages = JSON.stringify(response.data.message)
          this.status = JSON.stringify(response.data.status)
          $('#modal').modal('hide');
          $('#modal-messages-sucess').modal('show');
      }).catch(error=>{
          this.loading = false
          if(error.response.status === 422){
              this.actionSelect = "";
              this.errors = [];
              $('#modal').modal('hide');
              $('#modal-messages-error').modal('show');
              this.errors = error.response.data.errors;
          }
      });
      e.preventDefault();
    },
    addRemoveItemPhotoCart(fotos, cart_id){
      return new Promise((resolve, reject) => {
        axios.post(`/carts/updateItemPhoto/`+ cart_id,{
          fotos: fotos
        }).then(response => {
            resolve(response.data)
          })
          .catch(error => reject(error));
      })
    }
  },
}
</script>
<style>
.vue-select-image__thumbnail--selected {
  background: red;
}
</style>