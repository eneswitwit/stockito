import Vue from 'vue'
import fontawesome from '@fortawesome/fontawesome'
import '@fortawesome/fontawesome/styles.css'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import falight from '@fortawesome/fontawesome-free-solid';

Vue.component('fa', FontAwesomeIcon)

fontawesome.library.add(falight);
