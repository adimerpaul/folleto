<template>
  <q-card class="q-pa-xs" style="width: 900px;max-width: 900px">
    <q-card-section class="q-py-xs row items-center">
      <div class="text-h5 text-bold"> Pedido Nuevo </div>
      <q-space />
      <div class="text-right text-red text-h6">
        # {{empresa.id}} <span class="text-grey">{{empresa.nombre}}</span>
      </div>
      <q-btn flat dense icon="cancel" v-close-popup />
    </q-card-section>
    <q-card-section class="q-py-none">
      <q-form @submit="pedidoSubmit">
        <div class="row">
          <div class="col-12 col-md-6 q-pa-xs">
            <q-select dense outlined v-model="pedido.person" :options="empresa.person" option-value="id" option-label="nombre">
              <template v-slot:before>
                <span class="text-subtitle1">Quien lo pide</span>
              </template>
            </q-select>
          </div>
          <div class="col-12 col-md-6 q-pa-xs">
            <q-select dense outlined v-model="pedido.sucursal" :options="empresa.sucursals" option-value="id" option-label="nombre">
              <template v-slot:before>
                <span class="text-subtitle1">Sucursal</span>
              </template>
            </q-select>
          </div>
        </div>
        <div class="q-mt-md">
          <q-card>
            <q-tabs v-model="tab" dense class="text-grey" indicator-color="blue" align="justify" narrow-indicator>
              <q-tab name="pedido" no-caps class="bg-dark text-white">
                  <span class="text-bold">Pedido</span>
              </q-tab>
              <q-tab name="taller" no-caps class="bg-dark text-white">
                  <span class="text-bold">Diseño y Entrega</span>
              </q-tab>
              <q-tab name="pago" no-caps class="bg-dark text-white">
                  <span class="text-bold">Pago y Facturacion</span>
              </q-tab>
              <q-tab name="status" no-caps class="bg-dark text-white">
                  <span class="text-bold">Confimacion</span>
              </q-tab>
            </q-tabs>
            <q-separator />
            <q-tab-panels v-model="tab" animated class="bg-grey-2">
              <q-tab-panel style="height: 350px" name="pedido">
                <div class="row">
                  <div class="col-6 col-md-2 flex flex-center">Producto:</div>
                  <div class="col-6 col-md-4">
                    <q-select v-if="!editProduct" dense outlined v-model="pedido.producto" :options="productos" @update:modelValue="grSearch">
                      <template v-slot:after>
                        <q-btn flat dense icon="o_edit" :color="!editProduct ? 'orange' : 'green'"
                               rounded @click="editProduct = !editProduct"/>
                      </template>
                    </q-select>
                    <q-input dense outlined v-model="pedido.producto" v-else>
                      <template v-slot:after>
                        <q-btn flat dense icon="edit" :color="!editProduct ? 'orange' : 'green'"
                               rounded @click="editProduct = !editProduct"/>
                      </template>
                    </q-input>
                  </div>
                  <div class="col-6 col-md-2 flex flex-center">
<!--                    Otra:-->
                  </div>
                  <div class="col-6 col-md-4">
<!--                    <q-input dense outlined v-model="pedido.producto"/>-->
                  </div>
                  <div class="col-6 col-md-2 flex flex-center">Gr:</div>
                  <div class="col-6 col-md-4">
                    <q-select v-if="!editProduct" dense outlined v-model="pedido.gr" :options="grs" @update:modelValue="tamanoSearch">
                      <template v-slot:after>
                        <q-btn flat dense icon="o_edit" :color="!editProduct ? 'orange' : 'green'"
                               rounded @click="editProduct = !editProduct"/>
                      </template>
                    </q-select>
                    <q-input dense outlined v-model="pedido.gr" v-else>
                      <template v-slot:after>
                        <q-btn flat dense icon="edit" :color="!editProduct ? 'orange' : 'green'"
                               rounded @click="editProduct = !editProduct"/>
                      </template>
                    </q-input>
                  </div>
                  <div class="col-6 col-md-2 flex flex-center">
<!--                    Otra:-->
                  </div>
                  <div class="col-6 col-md-4">
<!--                    <q-input dense outlined v-model="pedido.gr"/>-->
                  </div>
                  <div class="col-6 col-md-2 flex flex-center">Medida:</div>
                  <div class="col-6 col-md-4">
                    <q-select dense outlined v-model="pedido.medida" :options="medidas" @update:modelValue="cantidadSearch" v-if="!editProduct">
                      <template v-slot:after>
                        <q-btn flat dense icon="o_edit" :color="!editProduct ? 'orange' : 'green'"
                               rounded @click="editProduct = !editProduct"/>
                      </template>
                    </q-select>
                    <q-input dense outlined v-model="pedido.medida" v-else>
                      <template v-slot:after>
                        <q-btn flat dense icon="edit" :color="!editProduct ? 'orange' : 'green'"
                               rounded @click="editProduct = !editProduct"/>
                      </template>
                    </q-input>
                  </div>
                  <div class="col-6 col-md-2 flex flex-center">
<!--                    Otra:-->
                  </div>
                  <div class="col-6 col-md-4">
<!--                    <q-input dense outlined v-model="pedido.medida"/>-->
                  </div>
                  <div class="col-6 col-md-2 flex flex-center">
                    Cantidad:
                  </div>
                  <div class="col-6 col-md-2">
<!--                    <q-input dense outlined v-model="pedido.cantidad" />-->
                    <q-select dense outlined v-model="pedido.cantidad" :options="cantidades" @update:modelValue="precioSearch" v-if="!editProduct">
                      <template v-slot:after>
                        <q-btn flat dense icon="o_edit" :color="!editProduct ? 'orange' : 'green'"
                               rounded @click="editProduct = !editProduct"/>
                      </template>
                    </q-select>
                    <q-input dense outlined v-model="pedido.cantidad" v-else @update:modelValue="precioSearch" debounce="500">
                      <template v-slot:after>
                        <q-btn flat dense icon="edit" :color="!editProduct ? 'orange' : 'green'"
                               rounded @click="editProduct = !editProduct"/>
                      </template>
                    </q-input>
                  </div>
                  <div class="col-6 col-md-1 flex flex-center">
                    esp:
                  </div>
                  <div class="col-6 col-md-1">
                    <q-input dense outlined v-model="pedido.esp" />
                  </div>
                  <div class="col-6 col-md-2 flex flex-center">
                    <q-checkbox v-model="checkTerminacion"/>
                    Termin:
                  </div>
                  <div class="col-6 col-md-4">
                    <q-select dense outlined v-model="pedido.terminacion" :options="terminaciones" v-if="checkTerminacion">
<!--                      <template v-slot:after>-->
<!--                        <q-btn flat dense icon="add_circle_outline" color="green" @click="add('Terminacion')"/>-->
<!--                      </template>-->
                    </q-select>
                  </div>
                  <div class="col-6 col-md-2 flex flex-center text-red text-bold">F Entrega:</div>
                  <div class="col-6 col-md-4">
                    <q-select dense outlined v-model="pedido.fechaTexto" :options="['Una semana', 'Fecha Especial']" @update:model-value="calculateFechaEntrega"/>
<!--                    <q-input type="date" dense outlined v-model="pedido.fechaEntrega"/>-->
                  </div>
                  <div class="col-6 col-md-2 flex flex-center">
<!--                    Otra:-->
                  </div>
                  <div class="col-6 col-md-4">
<!--                    <q-input dense outlined v-model="pedido.terminacion" v-if="checkTerminacion"/>-->
                  </div>
                  <div class="col-6 col-md-2 flex flex-center">F Especial:</div>
                  <div class="col-6 col-md-4"><q-input type="date" dense outlined v-model="pedido.fechaEspecial" v-if="pedido.fechaTexto === 'Fecha Especial'"/></div>
                  <div class="col-6 col-md-2 flex flex-center">Descripcion:</div>
                  <div class="col-6 col-md-4"><q-input dense outlined v-model="pedido.descripcion"/></div>
                  <div class="col-6 col-md-2 flex flex-center text-green text-bold">Precio</div>
                  <div class="col-6 col-md-4"><q-input dense outlined v-model="pedido.precioProducto"/></div>
                  <div class="col-6 col-md-2 flex flex-center text-green text-bold">Precio:</div>
                  <div class="col-6 col-md-4"><q-input dense outlined v-model="pedido.precioEspecificaciones"/></div>
                </div>
              </q-tab-panel>
              <q-tab-panel style="height: 350px" name="taller">
                <div class="row">
                  <div class="col-6 col-md-2 flex flex-center">Diseño:</div>
                  <div class="col-6 col-md-4">
                    <q-select dense outlined v-model="pedido.diseno" :options="disenos">
<!--                      <template v-slot:after>-->
<!--                        <q-btn flat dense icon="add_circle_outline" color="green" @click="add('Diseno')"/>-->
<!--                      </template>-->
                    </q-select>
                  </div>
                  <div class="col-6 col-md-2 flex flex-center">
                    <q-checkbox v-model="checkEnvio"/>
                    Envio:
                  </div>
                  <div class="col-6 col-md-4">
                    <q-select dense outlined v-model="pedido.envio" :options="['Moto', 'Expreso', 'Interior', 'Mercado Envios']"
                              v-if="checkEnvio"/>
                  </div>
                  <div class="col-6 col-md-2 flex flex-center">Lados:</div>
                  <div class="col-6 col-md-4">
                    <q-select dense outlined v-model="pedido.lado" :options="lados" @update:modelValue="precioDiseno">
<!--                      <template v-slot:after>-->
<!--                        <q-btn flat dense icon="add_circle_outline" color="green" @click="add('Lado')"/>-->
<!--                      </template>-->
                    </q-select>
                  </div>
                  <div class="col-6 col-md-2 flex flex-center">Direccion:</div>
                  <div class="col-6 col-md-4"><q-select  v-if="checkEnvio" dense outlined v-model="pedido.direccion" :options="empresa.direccion" option-value="id" option-label="direccion"/></div>
                  <div class="col-6 col-md-2 flex flex-center">Especificaciones:</div>
                  <div class="col-6 col-md-4"><q-input type="textarea" dense outlined v-model="pedido.especificaciones"/></div>
                  <div class="col-6">
                    <div class="row"  v-if="checkEnvio">
                      <div class="col-6 col-md-4 flex flex-center">Localidad:</div>
                      <div class="col-6 col-md-8"><q-input dense outlined v-model="pedido.direccion.localidad" readonly v-if="pedido.direccion"/></div>
                      <div class="col-6 col-md-4 flex flex-center">CodigoPostal:</div>
                      <div class="col-6 col-md-8"><q-input dense outlined v-model="pedido.direccion.codigoPostal" readonly v-if="pedido.direccion"/></div>
                      <div class="col-6 col-md-4 flex flex-center">Descripcion:</div>
                      <div class="col-6 col-md-8"><q-input dense outlined v-model="pedido.descripcionEnvio"/></div>
                    </div>
                  </div>
                  <div class="col-6 col-md-2 flex flex-center text-green text-bold">Precio Diseño:</div>
                  <div class="col-6 col-md-4"><q-input dense outlined v-model="pedido.precioDiseno" /></div>
                  <div class="col-6 col-md-2 flex flex-center text-green text-bold">Envio:</div>
                  <div class="col-6 col-md-4"><q-input dense outlined v-model="pedido.precioEnvio"/></div>
                </div>
              </q-tab-panel>
              <q-tab-panel style="height: 350px" name="pago">
                <div class="row">
                  <div class="col-6 col-md-2 flex flex-center">Seña:</div>
                  <div class="col-6 col-md-3"><q-input dense outlined v-model="pedido.pago" /></div>
                  <div class="col-6 col-md-1 flex flex-center">Iva:</div>
                  <div class="col-6 col-md-3">
<!--                    <q-input dense outlined v-model="pedido.iva" />-->
                    <q-select dense outlined v-model="pedido.facturaA" :options="['Factura ninguna', 'Factura pedido', 'Factura seña']"
                              :hint="`Iva: ${pedido.iva}%`"/>
                  </div>
                  <div class="col-6 col-md-2 flex flex-center">Se Facturo:</div>
                  <div class="col-6 col-md-1">
<!--                    <q-select dense outlined v-model="pedido.seFacturo" :options="['Si','No']" />-->
                    <q-toggle v-model="pedido.seFacturo" color="primary" false-value="No" true-value="Si" :label="pedido.seFacturo"/>
                  </div>
                  <div class="col-12 col-md-6">
                    <div class="row">
                      <div class="col-6 col-md-4 flex flex-center">Metodo de $:</div>
                      <div class="col-6 col-md-8"><q-select dense outlined v-model="pedido.metodoPago" :options="['Efectivo', 'Tarjeta', 'Transferencia']"/></div>
                      <div class="col-6 col-md-4 flex flex-center">CUIT/DNI:</div>
                      <div class="col-6 col-md-8"><q-select dense outlined v-model="pedido.facturacion" :options="empresa.facturacion" option-value="id" option-label="cuit"/></div>
                      <div class="col-6 col-md-4 flex flex-center">Condicional:</div>
                      <div class="col-6 col-md-8"><q-input dense outlined v-model="pedido.facturacion.condicional" readonly v-if="pedido.facturacion"/></div>
                      <div class="col-6 col-md-4 flex flex-center">Razon social:</div>
                      <div class="col-6 col-md-8"><q-input dense outlined v-model="pedido.facturacion.razonSocial" readonly v-if="pedido.facturacion"/></div>
                      <div class="col-6 col-md-4 flex flex-center">Comentario:</div>
                      <div class="col-6 col-md-8"><q-input dense outlined v-model="pedido.comentarioPago"/></div>
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <q-card>
                      <q-card-section class="row q-pa-lg">
                        <div class="col-6 col-md-6 flex flex-center">Subtotal:</div>
                        <div class="col-6 col-md-6"><div class="text-bold text-right">${{precioTotal}}</div></div>
                        <div class="col-6 col-md-6 flex flex-center">Iva:</div>
                        <div class="col-6 col-md-6"><div class="text-bold text-right">${{precioIva}}</div></div>
                        <div class="col-6 col-md-6 bg-dark text-white flex flex-center">Total:</div>
                        <div class="col-6 col-md-6 bg-dark text-white"><div class="text-bold text-right">${{precioTotal + precioIva}}</div></div>
                        <div class="col-6 col-md-6 flex flex-center">Seña:</div>
                        <div class="col-6 col-md-6"><div class="text-bold text-right">${{pedido.pago}}</div></div>
                        <div class="col-6 col-md-6 flex flex-center">Deuda:</div>
                        <div class="col-6 col-md-6"><div class="text-bold text-right text-red">${{deuda}}</div></div>
                      </q-card-section>
                    </q-card>
                  </div>
                </div>
              </q-tab-panel>
              <q-tab-panel style="height: 350px" name="status">
                <div class="row">
                  <div class="col-12 col-md-12">
                    <q-markup-table wrapCells separator="horizontal" dense>
                      <thead>
                      <tr class="bg-black text-white">
                        <th class="text-left">Cant.</th>
                        <th class="text-left">Descripcion</th>
                        <th class="text-left">Gr</th>
                        <th class="text-left">Tamaño</th>
                        <th class="text-left">Entrega</th>
                        <th class="text-right">Precio</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr>
                        <td class="text-left">{{pedido.cantidad}}</td>
                        <td class="text-left">{{pedido.producto}}</td>
                        <td class="text-left">{{pedido.gr}}</td>
                        <td class="text-left">{{pedido.medida}}</td>
                        <td class="text-left">{{pedido.fechaEntrega}}</td>
                        <td class="text-right">${{pedido.precioProducto}}</td>
                      </tr>
                      <tr>
                        <td class="text-left">1</td>
                        <td class="text-left">{{pedido.diseno}}</td>
                        <td class="text-left"></td>
                        <td class="text-left">{{pedido.lado}}</td>
                        <td class="text-left"></td>
                        <td class="text-right">${{pedido.precioDiseno}}</td>
                      </tr>
                      <tr>
                        <td class="text-left">1</td>
                        <td class="text-left">Envio {{pedido.envio}}</td>
                        <td class="text-left">
                          <template v-if="pedido.envio=='Si'">
                            {{pedido.direccion.localidad}}
                          </template>
                        </td>
                        <td class="text-left"></td>
                        <td class="text-left"></td>
                        <td class="text-right">${{pedido.precioEnvio}}</td>
                      </tr>
                      <tr>
                        <td class="text-left">1</td>
                        <td class="text-left">Terminacion: {{pedido.terminacion}}</td>
                        <td class="text-left"></td>
                        <td class="text-left"></td>
                        <td class="text-left"></td>
                        <td class="text-right">${{pedido.precioEspecificaciones}}</td>
                      </tr>
<!--                      <tr>-->
<!--                        <td class="text-left">1</td>-->
<!--                        <td class="text-left">Iva {{pedido.iva}}%</td>-->
<!--                        <td class="text-left"></td>-->
<!--                        <td class="text-left"></td>-->
<!--                        <td class="text-left"></td>-->
<!--                        <td class="text-right">${{precioIva}}</td>-->
<!--                      </tr>-->
                      <tr>
                        <td colspan="5" class="text-right text-bold">Total</td>
                        <td class="text-right bg-black text-white text-bold">${{precioTotal + precioIva}}</td>
                      </tr>
                      <tr>
                        <td colspan="5" class="text-right text-bold ">Seña</td>
                        <td class="text-right text-bold ">${{pedido.pago}}</td>
                      </tr>
                      <tr>
                        <td colspan="5" class="text-right text-bold text-red">Deuda</td>
                        <td class="text-right text-bold text-red">${{deuda}}</td>
                      </tr>
                      </tbody>
                    </q-markup-table>
                  </div>
                </div>
              </q-tab-panel>
            </q-tab-panels>
          </q-card>
        </div>
        <div class="row q-mt-md">
          <div class="col-12 col-md-8"/>
          <div class="col-4 col-md-2 text-center q-pa-xs">
            <q-btn no-caps label="Anterior" color="grey" class="w-100" @click="anterior"/>
          </div>
          <div class="col-4 col-md-2 text-center q-pa-xs">
            <q-btn no-caps label="Siguiente" color="blue" class="w-100" @click="siguiente" v-if="tab!='status'"/>
            <q-btn no-caps label="Confirmar" :loading="loading" color="green" class="w-100" @click="confimar" v-if="tab=='status'"/>
          </div>
        </div>
<!--        <pre>{{pedido}}</pre>-->
<!--        <pre>{{empresa}}</pre>-->
      </q-form>
    </q-card-section>
  </q-card>
</template>
<script>

export default {
  name: 'PedidoNewComponent',
  props: {
    empresa: {
      type: Object,
      required: true
    },
    pedidoDato: {
      type: Object,
      required: true
    }
  },
  data () {
    return {
      editProduct: false,
      checkTerminacion: false,
      checkEnvio: false,
      tab: 'pedido',
      pedido: {},
      loading: false,
      productos: [],
      grs: [],
      medidas: [],
      cantidades: [],
      lados: ['1 Lado', '2 Lado', '2 lados distintos'],
      disenos: [],
      terminaciones: []
    }
  },
  async mounted () {
    this.getTextProduct()
    // this.getTextGr()
    // this.getTextMedida()
    // this.getLados()
    this.getDisenos()
    this.getTerminaciones()
    this.pedido = this.pedidoDato
  },
  methods: {
    precioDiseno (lado) {
      this.$axios.get('precioDiseno/' + this.pedido.diseno + '/' + lado)
        .then(response => {
          this.pedido.precioDiseno = response.data
        })
        .catch(error => {
          this.$alert.error(error.response.data.message)
        })
    },
    precioSearch (cantidad) {
      let cantidadEspaciado = 0
      if (cantidad % 1000 === 0) {
        cantidadEspaciado = parseInt(cantidad)
      } else {
        cantidadEspaciado = parseInt(cantidad) + 1000
      }
      // console.log(cantidadEspaciado)
      const divisionEspaciado = cantidadEspaciado / 1000
      // console.log(divisionEspaciado)
      this.pedido.esp = parseInt(divisionEspaciado)
      this.$axios.get('precio/' + this.pedido.producto + '/' + this.pedido.gr + '/' + this.pedido.medida + '/' + cantidad)
        .then(response => {
          this.pedido.precioProducto = response.data
        })
        .catch(error => {
          this.$alert.error(error.response.data.message)
        })
    },
    cantidadSearch (medida) {
      this.pedido.cantidad = ''
      this.$axios.get('cantidad/' + this.pedido.producto + '/' + this.pedido.gr + '/' + medida)
        .then(response => {
          this.cantidades = response.data
        })
        .catch(error => {
          this.$alert.error(error.response.data.message)
        })
    },
    tamanoSearch (gr) {
      this.pedido.medida = ''
      this.$axios.get('tamano/' + this.pedido.producto + '/' + gr)
        .then(response => {
          this.medidas = response.data
        })
        .catch(error => {
          this.$alert.error(error.response.data.message)
        })
    },
    grSearch (producto) {
      this.pedido.gr = ''
      this.$axios.get('gr/' + producto)
        .then(response => {
          this.grs = response.data
        })
        .catch(error => {
          this.$alert.error(error.response.data.message)
        })
    },
    calculateFechaEntrega () {
      if (this.pedido.fechaTexto === 'Una semana') {
        const fecha = new Date()
        fecha.setDate(fecha.getDate() + 7)
        this.pedido.fechaEntrega = fecha.toISOString().substr(0, 10)
        this.pedido.fechaEspecial = fecha.toISOString().substr(0, 10)
      } else if (this.pedido.fechaTexto === 'Un mes') {
        const fecha = new Date()
        fecha.setDate(fecha.getDate() + 30)
        this.pedido.fechaEntrega = fecha.toISOString().substr(0, 10)
        this.pedido.fechaEspecial = fecha.toISOString().substr(0, 10)
      }
    },
    async getLados () {
      this.lados = await this.$axios.get('textLado').then(response => response.data)
    },
    async getDisenos () {
      // this.disenos = await this.$axios.get('textDiseno').then(response => response.data)
      this.disenos = await this.$axios.get('disenoNombre').then(response => response.data)
      // console.log(this.disenos)
    },
    async getTerminaciones () {
      // this.terminaciones = await this.$axios.get('textTerminacion').then(response => response.data)
      this.terminaciones = await this.$axios.get('terminacionNombre').then(response => response.data)
    },
    async getTextProduct () {
      this.productos = await this.$axios.get('productos').then(response => response.data)
      // this.pedido.producto = this.productos[0]
    },
    async getTextGr () {
      this.grs = await this.$axios.get('textGr').then(response => response.data)
    },
    async getTextMedida () {
      this.medidas = await this.$axios.get('textMedida').then(response => response.data)
    },
    // add (type) {
    //   this.$q.dialog({
    //     title: 'Agregar ' + type,
    //     message: 'Ingrese el nombre del ' + type,
    //     prompt: {
    //       model: '',
    //       type: 'text'
    //     },
    //     cancel: true,
    //     persistent: true
    //   }).onOk(data => {
    //     this.$axios.post('text' + type, { name: data })
    //       .then(response => {
    //         this.$alert.success(type + ' agregado')
    //         if (type === 'Producto') {
    //           this.getTextProduct()
    //         } else if (type === 'Gr') {
    //           this.getTextGr()
    //         } else if (type === 'Medida') {
    //           this.getTextMedida()
    //         } else if (type === 'Lado') {
    //           this.getLados()
    //         } else if (type === 'Diseno') {
    //           this.getDisenos()
    //         } else if (type === 'Terminacion') {
    //           this.getTerminaciones()
    //         }
    //       })
    //       .catch(error => {
    //         this.$alert.error(error.response.data.message)
    //       })
    //   })
    // },
    anterior () {
      if (this.tab === 'status') {
        this.tab = 'pago'
      } else if (this.tab === 'pago') {
        this.tab = 'taller'
      } else if (this.tab === 'taller') {
        this.tab = 'pedido'
      }
    },
    siguiente () {
      if (this.tab === 'pedido') {
        this.tab = 'taller'
      } else if (this.tab === 'taller') {
        this.tab = 'pago'
      } else if (this.tab === 'pago') {
        this.tab = 'status'
      }
    },
    pedidoSubmit () {
      // this.$emit('pedidoSubmit', this.pedido)
    },
    confimar () {
      if (this.precioTotal <= 0) {
        this.$alert.error('El precio total no puede ser menor o igual a 0')
        return false
      }
      this.$q.dialog({
        title: 'Confirmar Pedido',
        message: 'Esta seguro que desea confirmar el pedido?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        if (this.empresa.id === 0) {
          this.loading = true
          this.$axios.post('createEmpresaTotal', {
            empresa: this.empresa,
            pedido: this.pedido
          })
            .then(response => {
              this.loading = false
              this.$q.notify({
                message: 'Pedido confirmado #' + response.data.pedido.id,
                color: 'green',
                closeBtn: 'x',
                icon: 'check_circle',
                position: 'top',
                timeout: 200000
              })
              this.pedido = this.pedidoDato
              this.$emit('empresaSearch', response.data.empresa)
              this.$emit('closeDialog')
              this.$router.push('/central-files')
            })
            .catch(error => {
              this.loading = false
              this.$alert.error(error.response.data.message)
            })
        } else {
          this.loading = true
          this.$axios.post('pedidos', this.pedido)
            .then(response => {
              this.loading = false
              // this.$alert.success('Pedido confirmado #' + response.data.id)
              this.$q.notify({
                message: 'Pedido confirmado #' + response.data.id,
                color: 'green',
                closeBtn: 'x',
                icon: 'check_circle',
                position: 'top',
                timeout: 200000
              })
              this.pedido = this.pedidoDato
              this.$emit('empresaSearch', this.empresa)
              this.$emit('closeDialog')
            })
            .catch(error => {
              this.loading = false
              this.$alert.error(error.response.data.message)
            })
        }
      })
    }
  },
  computed: {
    precioTotal () {
      const precio = parseFloat(this.pedido.precioProducto) + parseFloat(this.pedido.precioDiseno) + parseFloat(this.pedido.precioEnvio) + parseFloat(this.pedido.precioEspecificaciones)
      return Math.round(precio * 100) / 100
    },
    precioIva () {
      // const precio = parseFloat(this.pedido.precioProducto) + parseFloat(this.pedido.precioDiseno) + parseFloat(this.pedido.precioEnvio) + parseFloat(this.pedido.precioEspecificaciones)
      // return Math.round(precio * parseFloat(this.pedido.iva) / 100 * 100) / 100
      if (this.pedido.facturaA === 'Factura ninguna') {
        return 0
      } else if (this.pedido.facturaA === 'Factura pedido') {
        return Math.round(this.precioTotal * parseFloat(this.pedido.iva) / 100 * 100) / 100
      } else if (this.pedido.facturaA === 'Factura seña') {
        return Math.round(this.pedido.pago * parseFloat(this.pedido.iva) / 100 * 100) / 100
      }
      return 0
    },
    deuda () {
      const precio = parseFloat(this.pedido.precioProducto) + parseFloat(this.pedido.precioDiseno) + parseFloat(this.pedido.precioEnvio) + parseFloat(this.pedido.precioEspecificaciones)
      // const iva = precio * parseFloat(this.pedido.iva / 100)
      let iva = 0
      const pago = this.pedido.pago === '' ? 0 : this.pedido.pago
      if (this.pedido.facturaA === 'Factura ninguna') {
        iva = 0
      } else if (this.pedido.facturaA === 'Factura pedido') {
        iva = Math.round(this.precioTotal * parseFloat(this.pedido.iva) / 100 * 100) / 100
      } else if (this.pedido.facturaA === 'Factura seña') {
        iva = Math.round(pago * parseFloat(this.pedido.iva) / 100 * 100) / 100
      }
      const deuda = parseFloat(precio) + parseFloat(iva) - pago
      return Math.round(deuda * 100) / 100
    }
  }
}
</script>
