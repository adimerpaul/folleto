import LoginPage from 'pages/LoginPage.vue'
import MainLayout from 'layouts/MainLayout.vue'
import IndexPage from 'pages/IndexPage.vue'
import UsuariosPage from 'pages/usuarios/UsuariosPage.vue'
import EstadisticasPage from 'pages/EstadisticasPage.vue'

const routes = [
  {
    path: '/',
    component: MainLayout,
    children: [
      { path: '', component: IndexPage, meta: { requiresAuth: true } },
      { path: 'usuarios', component: UsuariosPage, meta: { requiresAuth: true } },
      { path: 'estadisticas', component: EstadisticasPage, meta: { requiresAuth: true } }
    ]
  },
  {
    path: '/login',
    component: LoginPage
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
