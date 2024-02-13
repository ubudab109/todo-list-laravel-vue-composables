import axios from 'axios';
import store from '../stores';

var method = axios.create({
  headers: {
    "Authorization" : `Bearer ${store.getters['Token']}`
  }
});

export default method;