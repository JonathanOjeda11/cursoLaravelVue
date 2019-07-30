<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ingresos
                        <button type="button" class="btn btn-secondary" @click="showDetail()">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!--Listado-->
                    <template v-if="list">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criteria">
                                      <option value="voucher_type">Tipo de comprobante</option>
                                      <option value="voucher_num">Numero de comprobante</option>
                                      <option value="date">Fecha-Hora</option> 
                                    </select>
                                    <input type="text" v-model="search" @keyup.enter="listIncome(1,search,criteria)" name="texto" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listIncome(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            
                        
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Usuario</th>
                                    <th>Proveedor</th>
                                    <th>Tipo Comprobante</th>
                                    <th>Serie Comprobante</th>
                                    <th>Numero Comprobante</th>
                                    <th>Fecha-Hora</th>
                                    <th>Total</th>
                                    <th>Impuesto</th>
                                    <th>Estado</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="income in arrayIncome" :key="income.id">
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm"  @click="openModal('income','update',income)">
                                          <i class="icon-eye"></i>
                                        </button>
                                        <template v-if="income.status == 'Registrado'">
                                            <button type="button" class="btn btn-danger btn-sm" @click="disableIncome(income.id)">
                                                <i class="icon-trash"></i>                                         
                                            </button>
                                        </template>
                                        
                                    </td>
                                    <td v-text="income.user"></td>
                                    <td v-text="income.name"></td>
                                    <td v-text="income.voucher_type"></td>
                                    <td v-text="income.voucher_serie"></td>
                                    <td v-text="income.voucher_num"></td>
                                    <td v-text="income.date"></td>
                                    <td v-text="income.total"></td>
                                    <td v-text="income.tax"></td>
                                    <td v-text="income.status"></td>
                                </tr>
                               
                            </tbody>
                        </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page>1">
                                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page-1,search,criteria)">Ant</a>
                                </li>
                                <li class="page-item active" v-for="page in pagesNumber" :key="page" :class="[page==isActived ?  'active': '']">
                                    <a class="page-link" href="#" @click.prevent="changePage(page,search,criteria)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page+1,search,criteria)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    </template>
                    <!--Fin Listado-->
                    <!--Detalle-->
                    <template >
                    <div class="card-body">
                        <div class="form-group row-border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor</label>
                                    <v-select
                                        :on-search="selectSupplier"
                                        label="name"
                                        :options="arraySupplier"
                                        placeholder="Buscando Proveedores"
                                        :onchange="getDataSupplier"

                                    >

                                    </v-select>
                                </div>
                                
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto</label>
                                <input type="text" class="form-control" v-model="tax">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
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
                                    <input type="text" class="form-control" v-model="voucher_serie" placeholder="Ingrese la serie del comprobante">

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Numero de comprobante</label>
                                    <input type="text" class="form-control" v-model="voucher_num" placeholder="Ingrese el numero de comprobante">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div v-show="errorIncome" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsjIncome" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row-border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Articulo <span style="color:red;" v-show="article_id==0">(*)Seleccione</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="code" @keyup.enter="searchArticle()" placeholder="Ingrese el ID del articulo">
                                        <input type="text" readonly class="form-control"v-model="article">
                                        <button class="btn-primary btn" @click="openModal()">...</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio<span style="color:red;" v-show="price==0">(*)Ingrese</span></label>
                                    <input type="number" value="0" class="form-control" v-model="price" step="any">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad<span style="color:red;" v-show="amount==0">(*)Ingrese</span></label>
                                    <input type="number" value="0" class="form-control" v-model="amount" step="any">
                                </div>
                            </div>



                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="addDetail()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                            </div>

                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Articulos</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetail.length">
                                            <tr v-for = "(detail,index) in arrayDetail" :key="detail.id">
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close" @click="deleteDetail(index)"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detail.article">
                                                   
                                                </td>
                                                <td>
                                                    <input type="number" v-model="detail.price" value="3" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="number" v-model="detail.amount" value="2" class="form-control">
                                                </td>
                                                <td>
                                                    {{detail.price*detail.amount}}
                                                </td>
                                                
                                            </tr>


                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                                <td>$ {{totalPartial=(total-totalTax).toFixed(2)}}</td>  
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                                <td>$ {{totalTax=((total*tax)/(1+tax)).toFixed(2)}}</td>                                               
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                                <td>$ {{total=totalCalculate}}</td>
                                                
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="5">
                                                    No hay articulos
                                                </td>
                                            </tr>
                                        </tbody>


                                    </table>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="hideDetail()">Cerrar</button>
                                    <button type="button" class="btn btn-primary" @click="registerIncome()">Registrar compra</button>
                                    

                                </div> 
                            </div>
                        </div>
                    
                    </template>
                    <!--Fin Detalle-->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal}"  role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                        <select class="form-control col-md-3" id="opcion" name="opcion" v-model="criteriaA">
                                          <option value="nombre">Nombre</option>
                                          <option value="descripcion">Descripción</option>
                                          <option value="code">Codigo</option>
                                        </select>
                                        <input type="text" v-model="searchA" @keyup.enter="listArticle(searchA,criteriaA)" name="texto" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listArticle(searchA,criteriaA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                 <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Categoria</th>
                                            <th>Precio_Venta</th>
                                            <th>Stock</th>
                                            
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                            <tbody>
                                <tr v-for="article in arrayArticle" :key="article.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm"  @click="addDetailModal(article)">
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
                                            <span class="badge badge-danger">Inactivo</span>
                                        </div>
                                    </td>
                                </tr>
                               
                            </tbody>
                        </table>



                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" v-if="typeAction==1" class="btn btn-primary" @click="registerPerson()" >Guardar</button>
                            <button type="button" v-if="typeAction==2" class="btn btn-primary" @click="updatePerson()">Actualizar</button>

                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            <!-- Inicio del modal Eliminar -->
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-danger" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Eliminar Categoría</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Estas seguro de eliminar la categoría?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-danger">Eliminar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- Fin del modal Eliminar -->
        </main>
</template>

<script>

import vSelect from 'vue-select';
    export default {
         data()
            {
                return {

                    income_id:0,
                    supplier_id:0,
                    name:'',
                    voucher_type:'BOLETA',
                    voucher_num:'',
                    voucher_serie:'',
                    tax:0.18,
                    total:0.0,
                    totalTax:0.0,
                    totalPartial:0.0,
                    price:0,
                    article_id:0,
                    searchA:'',
                    criteriaA:'',
                    list:1,
                    arrayIncome:[],
                    arrayDetail:[],
                    arraySupplier:[],
                    modal:0,
                    titleModal:'',
                    typeAction:0,
                    errorIncome:0,
                    amount:0,
                    contact:'',
                    contactPhone:'',
                    errorShowMsjIncome:[],
                    pagination: {
                        'total':0,
                        'current_page':0, 
                        'per_page':0,
                        'last_page':0,
                        'from' :0,
                        'to':0,
                    },
                    offset:3,
                    criteria: 'voucher_num',
                    search: '',
                    arrayArticle:[],
                    article_id:0,
                    code:'',
                    article:'',
                    price:0,
                    amount:0

                   
                }
            },
            components:{
                vSelect

            },
            computed:
            {
                isActived: function()
                {
                    return this.pagination.current_page;
                },
                pagesNumber:  function()
                {
                    if(!this.pagination.to)
                    {
                        return [];
                    }
                    var from = this.pagination.current_page - this.offset;
                    if(from < 1)
                    {
                        from = 1;
                    }

                    var to = from+(this.offset*2);
                    if(to>=this.pagination.last_page)
                    {
                        to = this.pagination.last_page;
                    }
                    var pagesArray=[];
                    while(from<=to)
                    {
                    pagesArray.push(from);
                    from++;
                    }
                    return pagesArray;


                },
                totalCalculate: function()
                {
                    var result = 0.0;
                    for(var i=0;i<this.arrayDetail.length;i++)
                    {
                        result=result+(this.arrayDetail[i].price*this.arrayDetail[i].amount);

                    }
                    return result;
                }
            },
        methods: {
            listIncome(page, search, criteria)
            {
               let me = this;
                    var url = '/ingreso?page='+page+'&search='+search+'&criteria='+criteria;
                    axios.get(url).then(function (response) {
                        var answer = response.data;
                        me.arrayIncome=answer.income.data
                        me.pagination=answer.pagination;
                        

                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });  

            },
            selectSupplier(search, loading)
            {
                    let me = this;
                    loading(true)
                    var url = '/proveedor/selectProveedor?filter='+search;
                    axios.get(url).then(function (response) {
                        let answer = response.data;
                        q: search
                        me.arraySupplier=answer.supplier;
                        loading(false)
                        

                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });  

            },

            getDataSupplier(val1)
            {
                let me = this;
                me.loading = true;
                me.supplier_id = val1.id;


            },
            listArticle(search, criteria)
                {
                let me = this;
                        var url = '/articulo/listar?search='+ buscar + '&criteria='+ criterio;
                        axios.get(url).then(function (response) {
                            var answer = response.data;
                            me.arrayArticle=answer.articles.data;
                            
                            

                        })
                        .catch(function (error) {
                            console.log(error);
                        })
                        .then(function () {
                            // always executed
                        });  

                },

            addDetailModal(data=[])
            {
                let me = this;
                if(me.findArticle(data['id']))
                    {
                        swal({
                            type:'error',
                            title:'error',
                            text:'El articulo ya esta registrado',
                        })
                    }else
                    {
                        me.arrayDetail.push({
                        article_id: data['id'],
                        article: data['name'],
                        amount: 1,
                        price: 1
                        });
                        


                    }
                    

            },

            findArticle(id)
            {
                var sw=0;
                for(var i=0;i<this.arrayDetail.length;i++)
                {
                    if (this.arrayDetail[i].article_id==this.id) 
                    {
                        sw=true;
                    }
                }
                return sw;

            },
            deleteDetail(index)
            {
                let me = this;
                me.arrayDetail.splice(index, 1);

            },

            addDetail()
            {
                let me = this;
                if(me.article_id==0 || me.amount==0 || me.price==0)
                {

                }else{
                    if(me.findArticle(me.article_id))
                    {
                        swal({
                            type:'error',
                            title:'error',
                            text:'El articulo ya esta registrado',
                        })
                    }else
                    {
                        me.arrayDetail.push({
                        article_id: me.article_id,
                        article:me.article,
                        amount:me.amount,
                        price:me.price
                        });
                        me.code="",
                        me.article_id=0;
                        me.article="";
                        me.amount=0;
                        me.price=0;


                    }
                    

                }





               

            },
            searchArticle()
            {
                let me = this;
                var url = '/articulo/buscarArticulo?filter='+me.code;

                axios.get(url).then(function (response) {
                        var answer = response.data;
                        me.arrayArticle = answer.articles;

                        if(me.arrayArticle.length>0)
                        {
                            me.article = me.arrayArticle[0]['name'];
                            me.article_id = me.arrayArticle[0]['id'];

                        }else{
                            me.article = 'no existe articulo';
                            me.article_id=0;
                        }

                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });  


            },
            showDetail()
            {
                let me = this;
                list=0;
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
            hideDetail()
            {
                this.list=1;

            },
            registerIncome()
            {
                if(this.validatePerson())
                    {
                        return;
                    }
                let me = this;
                 axios.post('/ingreso/registrar',{
                    'supplier_id':'7',
                    'voucher_type':this.voucher_type,
                    'voucher_serie': this.voucher_serie,
                    'voucher_num':this.voucher_num,
                    'tax':this.tax,
                    'total':this.total,
                    'data':this.arrayDetail



                 }).then(function (response) {
                    me.list=1;
                    me.listIncome(1,'','voucher_num');
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



                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });  

            
            },
            changePage(page, search, criteria)
                {
                    let me = this;
                    //Actualiza la pagina actual
                    me.pagination.current_page=page;
                    //Envia la peticion para mandar la pagina
                    me.listIncome(page, search, criteria);

                },
            updatePerson()
                {
                    if(this.validatePerson())
                        {
                        return;
                        }
                        let me=this;
                        axios.put('/user/actualizar',{
                                'name':this.name,
                                'document_type':this.document_type,
                                'document_num':this.document_num,
                                'address':this.address,
                                'phone':this.phone,
                                'mail':this.mail,
                                'id':this.Person_id,
                                'user':this.user,
                                'password':this.password,
                                'rol_id':this.rol_id

                            }).then(function (response) {
                            me.closeModal();
                            me.listIncome('1','','name');
                            })
                            .catch(function (error) {
                            console.log(error);
                            })
                            .then(function () {
                            // always executed
                            });  
                    
                },






            validatePerson()
                {
                    this.errorIncome=0;
                    this.errorShowMsjIncome=[];
                   if(this.supplier_id==0) this.errorShowMsjIncome.push("Selecciona un Proveedor");
                   if (this.voucher_type==0) this.errorShowMsjIncome.push("Selecciona el comprobante");
                   if (!this.voucher_num==0) this.errorShowMsjIncome.push("Ingresa el numero del comprobante");
                   if (!this.tax==0) this.errorShowMsjIncome.push("Ingresa el impuesto de compra");
                   if (this.arrayDetail.length<=0) this.errorShowMsjIncome.push("Ingrese detalles");

                    if(this.errorShowMsjIncome.length) this.errorPerson=1;
                    return this.errorIncome;

                },
            openModal()
            {            
                this.arrayArticle=[];
                this.modal=1;
                this.titleModal='Selecciona uno o varios articulos';  

            },
            closeModal()
            {
                this.modal=0;
                this.titleModal='';
            },


               disableUser(id)
                {
                    const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false,
                    })

                    swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me=this;
                        axios.put('/user/desactivar',{
                            'id':id
                            }).then(function (response) {
                                swalWithBootstrapButtons.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                                )
                            me.listIncome('1','','name');
                            })
                            .catch(function (error) {
                            console.log(error);
                            })
                            .then(function () {
                            // always executed
                            });  
                    

                    
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                        )
                    }
                    })
                },

                enableUser(id)
                {
                    const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false,
                    })

                    swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, activate it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me=this;
                        axios.put('/user/activar',{
                            'id':id
                            }).then(function (response) {
                                swalWithBootstrapButtons.fire(
                                'Added!',
                                'Your file has been added.',
                                'success'
                                )
                            me.listIncome('1','','name');
                            })
                            .catch(function (error) {
                            console.log(error);
                            })
                            .then(function () {
                            // always executed
                            });  
                    

                    
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                        )
                    }
                    })
                },

        },
   

     

        mounted() {
            this.listIncome(1, this.search, this.criteria);
        }
    
        }

        </script>
        <style>
        .mostrar
        {
            display: list-item !important;
            opacity: 1 !important;
            position: absolute !important;
            background-color: #3c29297a !important;
        }
        .modal-content
        {
            width: 100% !important;
            position: absolute;
        }
        .div-error
        {
            display: flex;
            justify-content: center;

        }
        .text-error
        {
            color: red !important;
            font-weight: bold;
        }
        @media (min-width:600px)
        {
            .btnagregar
            {
                margin-top:2rem;
            }
        }


        </style>
