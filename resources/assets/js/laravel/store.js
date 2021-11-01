let store = {
  state: {
    cart: [],
    cartCount: 0,
    filters: {},
    profiles: [],
    auxEditProfiles: [],
    profileList: [],
    newCart: [],
  },

  mutations: {
    addToCart (state, item) {
      state.cart.push(item);
      state.cartCount++;
    },
    setProfiles (state, profiles) {
      state.profiles = state.profileList = profiles;
    },
    setFilters (state, filters) {
      state.filters = filters;
    },
    setAddInNewCart (state, profile) {
      //console.log("DEV_DEBUG-----------","adicionar setAddInNewCart>>>>>>" + profile.id);
      state.newCart.push(profile);
    },
    setAddInNewCartId (state, profile) {
      console.log("DEV_DEBUG-----------","adicionar setAddInNewCartId>>>>>> id" +  + profile);
      state.auxEditProfiles.push(profile);
      console.log("DEV_DEBUG","profiles ADICIONADOS" + JSON.stringify(state.auxEditProfiles));
    },
    setRemoveOfNewCartId (state, profile) {
      let index = state.auxEditProfiles.indexOf(profile)
      console.log("DEV_DEBUG-----------","remover>>>>>> id" + JSON.stringify(profile));
      state.auxEditProfiles.splice(index, 1);
      console.log("DEV_DEBUG","depois da remocao tenho>>>>> id" + JSON.stringify(state.auxEditProfiles));
    },
    setRemoveOfNewCart (state, profile) {
      let index = state.newCart.indexOf(profile)
      console.log("DEV_DEBUG-----------","remover" + JSON.stringify(profile.id));
      state.newCart.splice(index, 1);
      console.log("DEV_DEBUG","depois da remocao tenho>>>>>" + JSON.stringify(state.auxEditProfiles));
    },
  },
  getters: {
    new_cart: state => state.newCart,
    DIRECTORY_SEPARATOR: () => process.env.NODE_ENV == 'development' ? '\\' : '/'
  },
  actions: {
    getUserPhotos ({ commit, state }, ids) {
      return new Promise((resolve, reject) => {
        axios.get('/api/profiles/userPhotos', { params: { ids } })
          .then(response => {
            resolve(response.data)
          })
          .catch(error => reject(error));
      })
    },
    getUserPhotosCarts ({ commit, state }, id) {
      return new Promise((resolve, reject) => {
        axios.get('/api/profiles/userPhotosCarts', { params: { id } })
          .then(response => {
            resolve(response.data)
          })
          .catch(error => reject(error));
      })
    },
    getProfiles ({ commit, state }) {
      axios.get('/api/profiles', { params: state.filters }).then(response => {
        commit('setProfiles', response.data);
      });
    },

    setFilters ({ commit, dispatch }, filters) {
      commit('setFilters', filters)
    },

    toggleItemNewCart ({ state, commit }, profile) {
      if (state.newCart.includes(profile)) {
        commit('setRemoveOfNewCart', profile)
      }else{
        commit('setAddInNewCart', profile)
      }
    },
    toggleEditItemNewCart({ state, commit }, profile){
      if (state.auxEditProfiles.includes(profile.id)) {
        commit('setRemoveOfNewCartId', profile.id)
        commit('setRemoveOfNewCart', profile)
      }else{
        commit('setAddInNewCartId', profile.id)
        commit('setAddInNewCart', profile)
      }
    },
    addItemEditCart({ state, commit }, profile){
      commit('setAddInNewCartId', profile.id)
      commit('setAddInNewCart', profile)
    }
  }
};

export default store;
