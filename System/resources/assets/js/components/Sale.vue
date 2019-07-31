<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ventas
                        <button type="button" @click="showDetail()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!-- Listado-->
                    <template v-if="list==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criteria">
                                      <option value="voucher_type">Tipo Comprobante</option>
                                      <option value="voucher_num">Número Comprobante</option>
                                      <option value="date">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="search" @keyup.enter="listSale(1,search,criteria)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listSale(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Cliente</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Serie Comprobante</th>
                                        <th>Número Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="sale in arraySale" :key="sale.id">
                                        <td>
                                            <button type="button" @click="watchSale(sale.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <template v-if="sale.status=='Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivateSale(sale.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="sale.user"></td>
                                        <td v-text="sale.name"></td>
                                        <td v-text="sale.voucher_type"></td>
                                        <td v-text="sale.voucher_serie"></td>
                                        <td v-text="sale.voucher_num"></td>
                                        <td v-text="sale.date"></td>
                                        <td v-text="sale.total"></td>
                                        <td v-text="sale.tax"></td>
                                        <td v-text="sale.status"></td>
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1,search,criteria)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="changePage(page,search,criteria)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1,search,criteria)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    </template>
                    <!--Fin Listado-->
                    <!-- Detalle-->
                    <template v-else-if="list==0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente(*)</label>
                                    <v-select
                                        :on-search="selectClient"
                                        label="nombre"
                                        :options="arrayClient"
                                        placeholder="Buscar Clientes..."
                                        :onChange="getDataClient"                                        
                                    >

                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto(*)</label>
                                <input type="text" class="form-control" v-model="tax">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante(*)</label>
                                    <select class="form-control" v-model="voucher_type">
                                        <option value="0">Seleccione</option>
                                        <option value="BOLETA">Boleta</option>
                                        <option value="FACTURA">Factura</option>
                                        <option value="TICKET">Ticket</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <input type="text" class="form-control" v-model="voucher_serie" placeholder="000x">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="voucher_num" placeholder="000xx">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorVenta" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsjSale" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Artículo <span style="color:red;" v-show="article_id==0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="code" @keyup.enter="searchArticle()" placeholder="Ingrese artículo">
                                        <button @click="openModal()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="article">
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio <span style="color:red;" v-show="price==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="price">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad <span style="color:red;" v-show="amount==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" class="form-control" v-model="amount">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Descuento</label>
                                    <input type="number" value="0" class="form-control" v-model="discount">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="addDetall()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetail.length">
                                        <tr v-for="(detail,index) in arrayDetail" :key="detail.id">
                                            <td>
                                                <button @click="deleteDetail(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detail.article">
                                            </td>
                                            <td>
                                                <input v-model="detail.price" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detail.amount>detail.stock">Stock: {{detail.stock}}</span>
                                                <input v-model="detail.amount" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detail.discount>(detail.price*detail.amount)">Descuento superior</span>
                                                <input v-model="detail.discount" type="number" class="form-control">
                                            </td>
                                            <td>
                                                {{detail.price*detail.amount-detail.discount}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{totalPartial=(total-totalTax).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{totalTax=((total*tax)/(1+tax)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{total=calculateTotal}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                NO hay artículos agregados
                                            </td>
                                        </tr>
                                    </tbody>                                    
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="hideDetail()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrerSale()">Registrar Venta</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- Fin Detalle-->
                    <!-- Ver ingreso -->
                    <template v-else-if="list==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente</label>
                                    <p v-text="client"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto</label>
                                <p v-text="tax"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p v-text="voucher_type"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <p v-text="voucher_serie"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante</label>
                                    <p v-text="voucher_num"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetail.length">
                                        <tr v-for="detail in arrayDetail" :key="detail.id">
                                            <td v-text="detail.article">
                                            </td>
                                            <td v-text="detail.price">
                                            </td>
                                            <td v-text="detail.amount">
                                            </td>
                                            <td v-text="detail.discount">
                                            </td>
                                            <td>
                                                {{detail.price*detail.amount-detail.discount}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{totalPartial=(total-totalTax).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{totalTax=((total*Tax)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{total}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                NO hay artículos agregados
                                            </td>
                                        </tr>
                                    </tbody>                                    
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="hideDetail()" class="btn btn-secondary">Cerrar</button>
                                
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- fin ver ingreso -->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="titleModal"></h4>
                            <button type="button" class="close" @click="closeModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criteriaA">
                                        <option value="name">Nombre</option>
                                        <option value="description">Descripción</option>
                                        <option value="code">Código</option>
                                        </select>
                                        <input type="text" v-model="searchA" @keyup.enter="listArticle(searchA,criteriaA)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listArticle(searchA,criteriaA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>Precio Venta</th>
                                            <th>Stock</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="article in arrayArticle" :key="article.id">
                                            <td>
                                                <button type="button" @click="addDetailModal(article)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td v-text="article.code"></td>
                                            <td v-text="article.name"></td>
                                            <td v-text="article.category_name"></td>
                                            <td v-text="article.sale_price"></td>
                                            <td v-text="article.stock"></td>
                                            <td>
                                                <div v-if="article.status">
                                                    <span class="badge badge-success">Activo</span>
                                                </div>
                                                <div v-else>
                                                    <span class="badge badge-danger">Desactivado</span>
                                                </div>
                                                
                                            </td>
                                        </tr>                                
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" v-if="typeAction==1" class="btn btn-primary" @click="registerPerson()">Guardar</button>
                            <button type="button" v-if="typeAction==2" class="btn btn-primary" @click="updatePerson()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    import vSelect from 'vue-select';
    export default {
        data (){
            return {
                sale_id: 0,
                client_id:0,
                client:'',
                voucher_type : 'BOLETA',
                voucher_serie : '',
                voucher_num : '',
                tax: 0.18,
                total:0.0,
                totalTax: 0.0,
                totalPartial: 0.0,
                arraySale : [],
                arrayClient: [],
                arrayDetail : [],
                list:1,
                modal : 0,
                titleModal : '',
                typeAction : 0,
                errorSale : 0,
                errorShowMsjSale : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criteria : 'voucher_num',
                search : '',
                criteriaA:'name',
                searchA: '',
                arrayArticle: [],
                article_id: 0,
                code: '',
                article: '',
                price: 0,
                amount:0,
                discount: 0,
                stock:0
            }
        },
        components: {
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            calculateTotal: function(){
                var answer=0.0;
                for(var i=0;i<this.arrayDetail.length;i++){
                    answer=answer+(this.arrayDetail[i].price*this.arrayDetail[i].amount-this.arrayDetail[i].discount)
                }
                return answer;
            }
        },
        methods : {
            listSale (page,search,criteria){
                let me=this;
                var url= '/ventas?page=' + page + '&search='+ search + '&criteria='+ criteria;
                axios.get(url).then(function (response) {
                    var answer= response.data;
                    me.arraySale = answer.sales.data;
                    me.pagination= answer.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectClient(search,loading){
                let me=this;
                loading(true)

                var url= '/cliente/selectCliente?filter='+search;
                axios.get(url).then(function (response) {
                    let answer = response.data;
                    q: search
                    me.arrayClient=answer.clients;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDataClient(val1){
                let me = this;
                me.loading = true;
                me.client_id = val1.id;
            },
            searchArticle(){
                let me=this;
                var url= '/articulo/buscarArticuloVenta?filter=' + me.code;

                axios.get(url).then(function (response) {
                    var answer= response.data;
                    me.arrayArticle = answer.articulos;

                    if (me.arrayArticle.length>0){
                        me.articulo=me.arrayArticle[0]['name'];
                        me.article_id=me.arrayArticle[0]['id'];
                        me.price=me.arrayArticle[0]['sale_price'];
                        me.stock=me.arrayArticle[0]['stock'];
                    }
                    else{
                        me.article='No existe artículo';
                        me.article_id=0;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            changePage(page,search,criteria){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listSale(page,search,criteria);
            },
            search(id){
                var sw=0;
                for(var i=0;i<this.arrayDetail.length;i++){
                    if(this.arrayDetail[i].article_id==id){
                        sw=true;
                    }
                }
                return sw;
            },
            deleteDetail(index){
                let me = this;
                me.arrayDetail.splice(index, 1);
            },
            addDetail(){
                let me=this;
                if(me.article_id==0 || me.amount==0 || me.price==0){
                }
                else{
                    if(me.search(me.article_id)){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese artículo ya se encuentra agregado!',
                            })
                    }
                    else{
                       if(me.amount>me.stock){
                           swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'NO hay stock disponible!',
                            })
                       } 
                       else{
                           me.arrayDetail.push({
                                article_id: me.article_id,
                                article: me.article,
                                amount: me.amount,
                                price: me.price,
                                discount: me.discount,
                                stock: me.stock
                            });
                            me.code="";
                            me.article_id=0;
                            me.article="";
                            me.amount=0;
                            me.price=0;
                            me.discount=0;
                            me.stock=0
                       }
                    }
                    
                }

                

            },
            addDetailModal(data =[]){
                let me=this;
                if(me.search(data['id'])){
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese artículo ya se encuentra agregado!',
                            })
                    }
                    else{
                       me.arrayDetail.push({
                            article_id: data['id'],
                            article: data['name'],
                            amount: 1,
                            price: data['sale_price'],
                            discount:0,
                            stock:data['stock']
                        }); 
                    }
            },
            listArticle (search,criteria){
                let me=this;
                var url= '/articulo/listarArticuloVenta?search='+ search + '&criteria='+ criteria;
                axios.get(url).then(function (response) {
                    var answer= response.data;
                    me.arrayArticle = answer.articles.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrerSale(){
                if (this.validateSale()){
                    return;
                }
                
                let me = this;

                axios.post('/venta/registrar',{
                    'client_id': this.client_id,
                    'voucher_type': this.voucher_type,
                    'voucher_serie' : this.voucher_serie,
                    'voucher_num' : this.voucher_num,
                    'tax' : this.tax,
                    'total' : this.total,
                    'data': this.arrayDetail

                }).then(function (response) {
                    me.list=1;
                    me.listSale(1,'','voucher_num');
                    me.client_id=0;
                    me.voucher_type='BOLETA';
                    me.voucher_serie='';
                    me.voucher_num='';
                    me.tax=0.18;
                    me.total=0.0;
                    me.article_id=0;
                    me.article='';
                    me.amount=0;
                    me.price=0;
                    me.stock=0;
                    me.code='';
                    me.discount=0;
                    me.arrayDetail=[];

                }).catch(function (error) {
                    console.log(error);
                });
            },
            validateSale(){
                let me=this;
                me.errorSale=0;
                me.errorShowMsjSale =[];
                var art;
                
                me.arrayDetail.map(function(x){
                    if (x.amount>x.stock){
                        art=x.article + " con stock insuficiente";
                        me.errorShowMsjSale.push(art);
                    }
                });

                if (me.client_id==0) me.errorShowMsjSale.push("Seleccione un Cliente");
                if (me.voucher_type==0) me.errorShowMsjSale.push("Seleccione el comprobante");
                if (!me.voucher_num) me.errorShowMsjSale.push("Ingrese el número de comprobante");
                if (!me.tax) me.errorShowMsjSale.push("Ingrese el impuesto de compra");
                if (me.arrayDetail.length<=0) me.errorShowMsjSale.push("Ingrese detalles");

                if (me.errorShowMsjSale.length) me.errorSale = 1;

                return me.errorSale;
            },
            showDetail(){
                let me=this;
                me.list=0;

                me.supplier_id=0;
                me.voucher_type='BOLETA';
                me.voucher_serie='';
                me.voucher_num='';
                me.tax=0.18;
                me.total=0.0;
                me.article_id=0;
                me.article='';
                me.amount=0;
                me.price=0;
                me.arrayDetail=[];
            },
            hideDetail(){
                this.list=1;
            },
            watchSale(id){
                let me=this;
                me.list=2;
                
                //Obtener los datos del ingreso
                var arraySaleT=[];
                var url= '/venta/obtenerCabecera?id=' + id;
                
                axios.get(url).then(function (response) {
                    var answer= response.data;
                    arraySaleT = answer.sale;

                    me.client = arraySaleT[0]['name'];
                    me.voucher_type=arraySaleT[0]['voucher_type'];
                    me.voucher_serie=arraySaleT[0]['voucher_serie'];
                    me.voucher_num=arraySaleT[0]['voucher_num'];
                    me.tax=arraySaleT[0]['tax'];
                    me.total=arraySaleT[0]['total'];
                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los datos de los detalles 
                var urld= '/venta/obtenerDetalles?id=' + id;
                
                axios.get(urld).then(function (response) {
                    console.log(response);
                    var answer= response.data;
                    me.arrayDetail = answer.details;
                })
                .catch(function (error) {
                    console.log(error);
                });               
            },
            closeModal(){
                this.modal=0;
                this.titleModal='';
            }, 
            openModal(){               
                this.arrayArticle=[];
                this.modal = 1;
                this.titleModal = 'Seleccione uno o varios artículos';
            },
            desactivateSale(id){
               swal({
                title: 'Esta seguro de anular esta venta?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/venta/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listSale(1,'','voucher_num');
                        swal(
                        'Anulado!',
                        'La venta ha sido anulada con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
        },
        mounted() {
            this.listSale(1,this.search,this.criteria);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }

</style>
