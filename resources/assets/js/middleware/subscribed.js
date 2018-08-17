import store from '~/store'

export default (to, from, next) => {
  if (store.getters['auth/user'].creative || store.getters['auth/user'].brand && store.getters['auth/user'].subscribed) {
    next()
  } else {
    next({ name: 'select-plan'})
  }
}
