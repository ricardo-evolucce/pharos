<template>
  <div class="content content-full">
    <div class="block block-fx-pop">
      <div class="block-content">
        <form
          :action="action"
          method="POST"
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
            <div class="col-lg-8 col-xl-5">
              <div class="form-group">
                <label for="name">
                  Nome <span class="text-danger">*</span>
                </label>
                <input
                  type="text"
                  class="form-control"
                  id="name"
                  name="name"
                  placeholder="ex.: Meu carrinho"
                >
              </div>
              <div class="form-group row">
                <div class="col-lg-8">
                  <label for="client_id">
                    Para <span class="text-danger">*</span>
                  </label>
                  <select
                    class="custom-select"
                    id="client_id"
                    name="client_id"
                  >
                    <option value="">Selecione um destinatário</option>
                    <option
                      v-for="(client, key) in clients"
                      :value="client.id"
                      :key="key"
                    >{{client.contact}}</option>
                  </select>
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

                  {{arrayFotosSelecionadasTabs}}

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
                        <!-- {{arrayFotosSelecionadasTabs}} -->
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
                      value="create_send"
                    >
                      <i class="fa fa-check-circle mr-1"></i> Salvar e enviar
                    </button>
                    <button
                      type="submit"
                      name="action"
                      class="btn btn-success"
                      value="save"
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
          {{arrayFotosSelecionadasTabs}}
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
  </div>
</template>

<script>

import { mapGetters, mapMutations, mapActions } from 'vuex'

export default {
  props: {
    csrf_token: String,
    action: String,
    clients: Array
  },
  data () {
    return {
      loading: false,
      arrayFotos: {},
      arrayFotosTab: [],
      arrayFotosSelecionadasTabs: {},
      selectedImages: [],
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
    ...mapActions(['getUserPhotos']),
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

      let ids = []
      for (const key in this.new_cart) {
        const profile = this.new_cart[key];
        ids.push(profile.user_id)
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

          this.selectedImages = objeto_fotos
          this.montarArrayFotosSelecionadas(objeto_fotos)
          this.clickTab(this.new_cart[0])
        })
        .finally(() => {
          this.loading = false
        })
    },
    montarArrayFotosSelecionadas(fotos){    
      let aux = {}
      for (const i in fotos) {
        const foto = fotos[i];
        let user_id = this.pegarUserIdPelaFoto(foto.src)
        if(!aux.hasOwnProperty(user_id)){
          aux[user_id] = []
        }

        aux[user_id].push(foto)
      }
      this.arrayFotosSelecionadasTabs = aux
    },
    onselectmultipleimage (fotos) {
      this.arrayFotosSelecionadasTabs = {}
      this.montarArrayFotosSelecionadas(fotos)
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
      let path_partes = foto.split(this.DIRECTORY_SEPARATOR)
      let user_id = path_partes[path_partes.length - 2]
      return user_id
    }
  },
}
</script>
<style>
.vue-select-image__thumbnail--selected {
  background: red;
}
</style>
