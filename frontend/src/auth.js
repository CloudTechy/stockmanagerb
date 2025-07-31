import bearer from '@websanova/vue-auth/drivers/auth/bearer'
import axios from '@websanova/vue-auth/drivers/http/axios.1.x'
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'

/**
 * Authentication configuration, some of the options can be override in method calls
 */
const config = {
  auth: {
    request: function (req, token) {
      // Attach the token directly without Bearer prefix
      req.headers['Authorization'] = 'Bearer ' + token;
    },
    response: function (res) {
      // Extract the token from the response (e.g., res.data.token)
      return res.data.token;
    }
  },
  http: axios,
  router: router,
  tokenDefaultName: 'stockmanager',
  tokenStore: ['localStorage'],
  tokenType: 'Bearer',
  // tokenName: 'token',
  
  // API endpoints used in Vue Auth.
  registerData: {
    url: 'auth/register', 
    method: 'POST', 
     redirect: 'login'
  },
  loginData: {
    url: 'auth/login', 
    method: 'POST',  
    fetchUser: true,
    redirect: false
  },
  logoutData: {
    url: 'logout', 
    method: 'POST', 
    redirect: 'login', 
    makeRequest: true
  },
  fetchData: {
    url: 'user', 
    method: 'GET', 
    enabled: true
  },
  refreshData: {
    url: 'auth/refresh', 
    method: 'GET', 
    enabled: true, 
    interval: 30
  }
}
export default config