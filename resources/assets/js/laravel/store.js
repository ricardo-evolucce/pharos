let store = {
  state: {
    cart: [],
    cartCount: 0,
    filters: {},
    profiles: [],
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
      state.newCart.push(profile);
    },
    setRemoveOfNewCart (state, profile) {
      let index = state.newCart.indexOf(profile)
      state.newCart.splice(index, 1);
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
      }
      else {
        commit('setAddInNewCart', profile)
      }

      // console.log(state.newCart);

    }
  }
};

export default store;
