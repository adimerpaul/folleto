<template>
  <q-page class="q-pa-xs bg-grey-3">
    <div class="row">
      <div class="col-12 text-uppercase luckiest text-h2 text-center">
        Usuarios
      </div>
    </div>
    <div class="col-12">
      <q-btn :loading="loading" :color="tab=='activos'?'white':'grey'" label="ACTIVOS" no-caps :class="`text-bold ${tab=='activos'?'text-blue':'text-white bg-grey'} w-150`" size="15px" @click="tab = 'activos'"/>
      <q-btn :loading="loading" :color="tab=='inactivos'?'white':'grey'" label="INACTIVOS" no-caps :class="`text-bold ${tab=='inactivos'?'text-blue':'text-white bg-grey'} w-150`" size="15px" @click="tab = 'inactivos'"/>
      <q-card>
        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="activos">
            <q-table dense :rows="userActive"
                     :loading="loading" :wrap-cells="true" flat bordered
                     :columns="usersColumn" :filter="userFilterActive">
              <template v-slot:top-right>
                <q-btn color="blue" @click="userAdd" label="Nuevo usuario" no-caps :loading="loading"/>
                <q-btn flat round icon="refresh" @click="usersGet" :loading="loading">
                  <q-tooltip>Actualizar</q-tooltip>
                </q-btn>
                <q-input outlined dense v-model="userFilterActive" debounce="500" placeholder="Buscar" class="bg-white" clearable name="search" id="search">
                  <template v-slot:append>
                    <q-icon name="search" class="cursor-pointer">
                      <q-tooltip>Buscar</q-tooltip>
                    </q-icon>
                  </template>
                </q-input>
              </template>
              <template v-slot:body-cell-permissions="props">
                <q-td :props="props">
                    <template v-for="permission in props.row.permissions" :key="permission">
                      {{permission.name}}
                      <span v-if="props.row.permissions.length > 1 && props.row.permissions[props.row.permissions.length-1].id != permission.id">
                        ,
                      </span>
                    </template>
                </q-td>
              </template>
              <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                  <q-btn-group flat>
                    <q-btn flat round dense icon="edit" @click="userEdit(props.row)" color="grey" :loading="loading">
                      <q-tooltip>Editar</q-tooltip>
                    </q-btn>
                    <q-btn flat round dense icon="lock_open" @click="userPassword(props.row)" color="grey" :loading="loading">
                      <q-tooltip>Contraseña</q-tooltip>
                    </q-btn>
                    <q-btn flat round dense icon="o_delete" @click="userDelete(props.row)" color="grey" :loading="loading">
                      <q-tooltip>Eliminar</q-tooltip>
                    </q-btn>
                  </q-btn-group>
                </q-td>
              </template>
              <template v-slot:body-cell-active="props">
                <q-td :props="props">
                  <q-toggle v-model="props.row.active" @update:model-value="userUpdate(props.row)"
                            keep-color dense
                            false-value="No" true-value="Si" color="green" :label="props.row.active"/>
                </q-td>
              </template>
              <template v-slot:body-cell-role="props">
                <q-td :props="props">
                  <template v-if="props.row.roles.length > 0">
                    {{props.row.roles[0].name}}
                  </template>
                </q-td>
              </template>
            </q-table>
          </q-tab-panel>
          <q-tab-panel name="inactivos" class="">
            <q-table dense :rows="userInactive"
                     :loading="loading" :wrap-cells="true" flat bordered
                     :columns="usersColumn" :filter="userFilterInactive">
              <template v-slot:top-right>
                <q-btn flat round icon="refresh" @click="usersGet" :loading="loading">
                  <q-tooltip>Actualizar</q-tooltip>
                </q-btn>
                <q-input outlined dense v-model="userFilterInactive" debounce="500" placeholder="Buscar" class="bg-white" clearable>
                  <template v-slot:append>
                    <q-icon name="search" class="cursor-pointer">
                      <q-tooltip>Buscar</q-tooltip>
                    </q-icon>
                  </template>
                </q-input>
              </template>
              <template v-slot:body-cell-permissions="props">
                <q-td :props="props">
                  <template v-for="permission in props.row.permissions" :key="permission">
                    {{permission.name}}
                    <span v-if="props.row.permissions.length > 1 && props.row.permissions[props.row.permissions.length-1].id != permission.id">
                        ,
                      </span>
                  </template>
                </q-td>
              </template>
              <template v-slot:body-cell-role="props">
                <q-td :props="props">
                  <template v-if="props.row.roles.length > 0">
                    {{props.row.roles[0].name}}
                  </template>
                </q-td>
              </template>
              <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                  <q-btn-group flat>
                    <q-btn flat round dense icon="edit" @click="userEdit(props.row)" color="grey" :loading="loading">
                      <q-tooltip>Editar</q-tooltip>
                    </q-btn>
                    <q-btn flat round dense icon="lock_open" @click="userPassword(props.row)" color="grey" :loading="loading">
                      <q-tooltip>Contraseña</q-tooltip>
                    </q-btn>
                  </q-btn-group>
                </q-td>
              </template>
              <template v-slot:body-cell-active="props">
                <q-td :props="props">
                  <q-toggle v-model="props.row.active" @update:model-value="userUpdate(props.row)"
                            keep-color dense
                            false-value="No" true-value="Si" color="red" :label="props.row.active"/>
                </q-td>
              </template>
            </q-table>
          </q-tab-panel>
        </q-tab-panels>
      </q-card>
    </div>
    <q-dialog v-model="userDialog" prevent-close>
      <q-card style="width: 950px">
        <q-card-section class="row items-center q-py-none">
          <q-icon name="account_circle" size="25px"/>
          <div class="text-h6 text-bold q-pl-xs">{{userOption==='add'?'Nuevo':'Editar'}} usuario</div>
          <q-space/>
          <q-btn flat round icon="close" @click="userDialog = false" color="grey" :loading="loading">
            <q-tooltip>Cerrar</q-tooltip>
          </q-btn>
        </q-card-section>
        <q-card-section class="q-py-none">
          <q-form @submit="userSubmit(user)">
            <q-input dense outlined v-model="user.name" label="Nombre" :loading="loading" required/>
            <q-input dense outlined v-model="user.email" label="Correo" :loading="loading" required name="correo" id="correo"
                      :rules="[val => /.+@.+\..+/.test(val) || 'Correo inválido']"/>
            <q-input dense outlined v-model="user.password" label="Contraseña"
                     :loading="loading" require v-if="userOption=='add'" type="password"/>
            <q-input dense outlined v-model="user.password_confirmation" label="Confirmar contraseña"
                     :loading="loading" require v-if="userOption=='add'" type="password"
                     :rules="[val => val === user.password || 'Las contraseñas no coinciden']"
            />
            <q-select dense outlined required v-model="user.roles[0].id" label="Rol" :options="roles" emit-value map-options
                      option-value="id" option-label="name" :loading="loading" @update:model-value="changeRol">
              <template v-slot:after>
                <q-icon name="add_circle_outline" class="cursor-pointer" color="green" @click="roleCreate" :loading="loading">
                  <q-tooltip>Agregar rol</q-tooltip>
                </q-icon>
                <q-icon name="edit" class="cursor-pointer" color="orange" @click="editarPermiso" :loading="loading">
                  <q-tooltip>Editar rol</q-tooltip>
                </q-icon>
              </template>
            </q-select>
            <div class="row">
              <div v-for="permission in permissions" :key="permission.id" class="q-pl-xs col-3">
                  <q-checkbox v-model="permission.checked"
                              class="text-caption"
                              :label="permission.name.includes('Crear')?'Crear':permission.name.includes('Editar')?'Editar':permission.name.includes('Eliminar')?'Eliminar':permission.name.includes('Ver')?'Ver':permission.name"
                              dense />
              </div>
            </div>
            <q-card-actions align="right">
              <q-btn label="Cerrar" color="grey" no-caps @click="userDialog = false"/>
              <q-btn :label="`${userOption=='edit'?'Actualizar':'Crear'} usuario`"
                     :color="userOption=='edit'?'orange':'blue'"
                     type="submit" :loading="loading" no-caps/>
            </q-card-actions>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>
<script>
export default {
  name: 'UsuariosPage',
  data () {
    return {
      userDialog: false,
      user: {},
      userOption: '',
      tab: 'activos',
      users: [],
      userFilterActive: '',
      userFilterInactive: '',
      usersColumn: [
        { name: 'actions', label: 'Acciones', field: 'actions', align: 'left', sortable: false },
        { name: 'name', label: 'Nombre', field: 'name', align: 'left', sortable: true },
        { name: 'email', label: 'Correo', field: 'email', align: 'left', sortable: true },
        { name: 'permissions', label: 'Permisos', field: 'permissions', align: 'left', sortable: true, wrapCells: true },
        { name: 'role', label: 'Rol', field: 'role', align: 'left', sortable: true },
        { name: 'active', label: 'Activo', field: 'active', align: 'left', sortable: true }
      ],
      loading: false,
      permissions: [],
      roles: []
    }
  },
  mounted () {
    this.usersGet()
    this.rolesGet()
    this.permissionsGet()
  },
  methods: {
    changeRol (rol) {
      console.log(rol)
      const permisosRol = this.roles.filter(role => role.id === rol)
      console.log(permisosRol)
      this.permissions.forEach(permission => {
        permission.checked = false
        permisosRol[0].permissions.forEach(permisoRol => {
          if (permission.id === permisoRol.id) {
            permission.checked = true
          }
        })
      })
    },
    rolesGet () {
      this.$axios.get('roles').then(res => {
        this.roles = res.data
      })
    },
    userSubmit () {
      if (this.user.roles[0].id === '') {
        this.$alert.error('Debe seleccionar un rol')
        return
      }
      this.loading = true
      if (this.userOption === 'add') {
        this.$axios.post('users',
          { ...this.user, permissions: this.permissions.filter(permission => permission.checked) }
        ).then(() => {
          this.userDialog = false
          this.usersGet()
          this.rolesGet()
        }).catch((err) => {
          this.$alert.error(err.response.data.message)
          this.loading = false
        })
      } else {
        this.$axios.put(`users/${this.user.id}`,
          { ...this.user, permissions: this.permissions.filter(permission => permission.checked) }
        ).then(() => {
          this.userDialog = false
          this.usersGet()
          this.rolesGet()
        }).catch((err) => {
          this.$alert.error(err.response.data.message)
        }).finally(() => {
          this.loading = false
        })
      }
    },
    editarPermiso () {
      if (this.user.roles[0].id === '') {
        this.$alert.error('Debe seleccionar un rol')
      } else {
        this.$q.dialog({
          title: 'Editar rol',
          message: 'Ingrese el nombre del rol',
          prompt: {
            model: this.user.roles[0].name,
            type: 'text'
          },
          cancel: true
        }).onOk(data => {
          this.loading = true
          this.$axios.put(`roles/${this.user.roles[0].id}`, { name: data }).then(() => {
            this.$alert.success('Rol actualizado')
            this.rolesGet()
          }).catch((err) => {
            this.$alert.error(err.response.data.message)
          }).finally(() => {
            this.loading = false
          })
        })
      }
    },
    roleCreate () {
      this.$q.dialog({
        title: 'Nuevo rol',
        message: 'Ingrese el nombre del rol',
        prompt: {
          model: '',
          type: 'text'
        },
        cancel: true
      }).onOk(data => {
        this.loading = true
        this.$axios.post('roles', { name: data }).then(() => {
          this.$alert.success('Rol creado')
          this.rolesGet()
        }).catch((err) => {
          this.$alert.error(err.response.data.message)
        }).finally(() => {
          this.loading = false
        })
      })
    },
    permissionsGet () {
      this.$axios.get('permissions').then(res => {
        this.permissions = res.data
      })
    },
    userEdit (user) {
      this.permissions.forEach(permission => {
        permission.checked = false
        user.permissions.forEach(userPermission => {
          if (permission.id === userPermission.id) {
            permission.checked = true
          }
        })
      })
      if (user.roles.length > 0) {
        // user.roles[0].id = user.roles[0].id.toString()
      } else {
        user.roles = [{ id: '' }]
      }
      this.user = user
      this.userDialog = true
      this.userOption = 'edit'
    },
    userPassword (user) {
      this.$q.dialog({
        title: 'Cambiar contraseña',
        message: 'Ingrese la nueva contraseña',
        prompt: {
          model: '',
          type: 'text',
          attrs: {
            maxlength: 20
          },
          outlined: true,
          noParentEvent: true
        },
        cancel: true
      }).onOk(data => {
        this.$axios.put(`updatePassword/${user.id}`, { password: data }).then(() => {
          this.$alert.success('Contraseña actualizada')
        }).catch((err) => {
          this.$alert.error(err.response.data.message)
        })
      })
    },
    userDelete (user) {
      this.$q.dialog({
        title: 'Eliminar usuario',
        message: '¿Está seguro de eliminar este usuario?',
        cancel: true
      }).onOk(() => {
        this.loading = true
        this.$axios.delete(`users/${user.id}`).then(() => {
          this.$alert.success('Usuario eliminado')
          this.usersGet()
        }).catch((err) => {
          this.$alert.error(err.response.data.message)
        })
      })
    },
    userUpdate (user) {
      this.$q.dialog({
        title: 'Cambiar estado',
        message: '¿Está seguro de cambiar el estado de este usuario?',
        cancel: true
      }).onOk(() => {
        this.$axios.get(`updateActive/${user.id}`, user).then(() => {
        }).catch((err) => {
          this.$alert.error(err.response.data.message)
        })
      }).onCancel(() => {
        user.active = user.active === 'Si' ? 'No' : 'Si'
      })
    },
    userAdd () {
      this.user = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        roles: [{ id: '' }]
      }
      this.userDialog = true
      this.userOption = 'add'
      this.permissions.forEach(permission => {
        permission.checked = false
      })
    },
    usersGet () {
      this.loading = true
      this.$axios.get('users').then(res => {
        this.users = res.data
      }).finally(() => {
        this.loading = false
      })
    }
  },
  computed: {
    userActive () {
      return this.users.filter(user => user.active === 'Si')
    },
    userInactive () {
      return this.users.filter(user => user.active === 'No')
    }
  }
}
</script>
