<template>
  <div class="content content-full">
    <div class="block block-fx-pop">
      <div class="block-content">
        <form
          :action="action"
          method="POST"
          @submit="checkForm"
        >
          <input
            type="hidden"
            name="_token"
            :value="csrf_token"
          >
          <!-- {{clients}} -->
          <!-- @csrf -->
          <!-- Vital Info -->
          <h2 class="content-heading pt-0">Informações</h2>
          <div class="row push">
            <div class="col-lg-4">
              <p class="text-muted">
                Algumas informações importantes sobre seu novo carrinho.
              </p>
            </div>
            <div class="col-lg-12 col-xl-12">
              <div class="form-group">
                <label for="name">
                  Nome <span class="text-danger">*</span>
                </label>
                <input
                  type="text"
                  class="form-control"
                  id="name"
                  name="name"
                  v-model="name"
                  placeholder="ex.: Meu carrinho"
                >
              </div>
              <div class="form-group row">
                <div class="col-lg-12">
                  <!-- <label for="client_id">-->
                  <label>
                    Para <span class="text-danger">*</span>
                  </label>
<!--                  <select-->
<!--                    class="custom-select"-->
<!--                    id="client_id"-->
<!--                    name="client_id"-->
<!--                  >-->
<!--                    <option value="">Selecione um destinatário</option>-->
<!--                    <option-->
<!--                      v-for="(client, key) in clients"-->
<!--                      :value="client.id"-->
<!--                      :key="key"-->
<!--                    >{{client.contact}}</option>-->
<!--                  </select>-->
                  <br>
                  <div v-for="(client, key, index) in clients" :key="index">
                    <input type="checkbox"
                       :id="key"
                       :name="'client_ids[]'"
                       :value="client.id"
                       v-model="client_ids">
                       <label
                           :for="key">
                           {{ client.contact }}
                       </label>
                  </div>
                  <br>
                </div>
              </div>
            </div>
          </div>
          <!-- END Vital Info -->

          <!-- People -->
          <h2 class="content-heading pt-0">Agenciados</h2>
          <div class="row push">
            <div class="col-lg-12">
              <profile-list ref="profile-list"></profile-list>
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
                        {{selectedImages}}
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
                      value="create_send"
                      v-on:click="btnOnClickSelect('create_send')"
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
                  @click="escolherFotosContinuar(true)"
                >
                  <i class="fa fa-arrow-right mr-1"></i> Escolher fotos e continuar
                </button>
              </div>
            </div>
          </div>
          <!-- END Submit -->
          <!-- {{ arrayFotosSelecionadasTabs}} -->

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
import { mapGetters, mapMutations, mapActions } from 'vuex'

export default {
  props: {
    csrf_token: String,
    action: String,
    clients: Array,
  },
  data () {
    return {
      loading: false,
      arrayFotos: {},
      arrayFotosTab: [],
      arrayFotosSelecionadasTabs: {},
      arrayFotosSelecionadasSendTabs: {},
      selectedImages: [],
      selectedAuxImages: [],
      errors: [],
      messages: '',
      status: 0,
      actionSelect: '',
      name: '',
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

  },
  methods: {
    ...mapActions(['getUserPhotos','getUserPhotosCarts',]),
    fotoParaObjetoFoto(foto){
      return {
        id: `${foto}`,
        src: `${foto}`,
      }
    },
    escolherFotosContinuar (isStart) {
      this.loading = true
      this.arrayFotosTab = []
    //  this.selectedImages = []

      let ids = []
      this.profile_id = []

      if(this.cart_id){
        this.selectedImages = []
        this.getUserPhotosCarts(this.cart_id)
          .then(cart_select_photos => {
              for (const i in cart_select_photos) {
                  const fotos = cart_select_photos[i];
                  for (const j in fotos) {
                    const foto = fotos[j];
                    this.selectedImages.push(this.fotoParaObjetoFoto(foto))
                    console.log("this>>>>>" + JSON.stringify(this.selectedImages))
                  }
              }
          })
      }

      
      for (const key in this.new_cart) {
        const profile = this.new_cart[key];
        ids.push(profile.user_id)
        this.profile_id.push(profile.id)
      }

      this.arrayFotos = {}
      this.getUserPhotos(ids)
          .then(perfis_fotos => {
            this.arrayFotos = perfis_fotos
            let objeto_fotos = []
            for (const i in perfis_fotos) {
              const fotos = perfis_fotos[i];
              for (const j in fotos) {
                const foto = fotos[j];
                objeto_fotos.push(this.fotoParaObjetoFoto(foto))
              }
            }

          if(this.cart_id){
              this.montarArrayFotosSelecionadas(this.selectedImages)
            }else{
              this.selectedImages = objeto_fotos
              this.montarArrayFotosSelecionadas(objeto_fotos)
          }
        if(isStart){
          this.clickTab(this.new_cart[0])
        } else{
          this.clickTab(this.tab.profile)
        }
      }).finally(() => {
        this.loading = false
      })
    },

    montarArrayFotosSelecionadas(fotos){
      this.arrayFotosSelecionadasSendTabs = {}

      for (const y in fotos) {
          let user_id_2 = this.pegarUserIdPelaFoto(JSON.stringify(fotos[y].src).replace(/"/g, ""))
          
          if (!this.arrayFotosSelecionadasSendTabs.hasOwnProperty(user_id_2)) {
            this.arrayFotosSelecionadasSendTabs[user_id_2] = []
          }
          // alimento o objeto com as fotos selecionadas
          this.arrayFotosSelecionadasSendTabs[user_id_2].push(fotos[y])
          this.arrayFotosSelecionadasTabs = this.arrayFotosSelecionadasSendTabs
      }
    },
    onselectmultipleimage (fotos) {

      this.arrayFotosSelecionadasTabs = {}
      this.montarArrayFotosSelecionadas(fotos)

      if(this.cart_id){
        this.loading = true

        this.addRemoveItemPhotoCart(this.arrayFotosSelecionadasSendTabs, this.cart_id)
          .then(response=>{
            this.escolherFotosContinuar(false)

        }).catch(error=>{
            console.log("Error ao processar" + error)
        });
      }else{

        this.saveCart(this.arrayFotosSelecionadasSendTabs)
          .then(response=>{
            console.log("reposta ao salvar" + JSON.stringify(response))

        }).catch(error=>{
            console.log("Error ao salvar" + error)
        });
      } 
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
    checkForm: function (e) {
      this.loading = true
      axios.post('/carts', {
          name: this.name,
          profile_id: this.profile_id,
          client_ids: this.client_ids,
          cart_id: Object,
          fotos: this.arrayFotosSelecionadasSendTabs,
          action: this.actionSelect
      }).then(response =>{
        this.loading = false
        this.actionSelect = "";
        this.messages = "";
        this.messages = JSON.stringify(response.data.message)
        this.status = JSON.stringify(response.data.status)
        $('#modal').modal('hide');
        $('#modal-messages-sucess').modal('show');
      }).catch((error) => {
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
          fotos: fotos,
          profile_id: this.profile_id
        }).then(response => {
            resolve(response.data)
          })
          .catch(error => reject(error));
      })
    },
    saveCart(fotos){
      return new Promise((resolve, reject) => {
        axios.post(`/carts/storeCartDraft`,{
          name: this.name,
          profile_id: this.profile_id,
          fotos: fotos
        }).then(response => {
            this.cart_id = response.data.id
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
