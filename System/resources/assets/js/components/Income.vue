<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
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
                    </template v-else>
                    <!--Fin Listado-->
                    <!--Detalle-->
                    <template >
                    <div class="card-body">
                        <div class="form-group row-border">
                            <div class="col-md-6">
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
                        </div>
                        <div class="form-group row-border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Articulo</label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="code" @keyup.enter="searchArticle()" placeholder="Ingrese el ID del articulo">
                                        <input type="text" readonly class="form-control"v-model="article">
                                        <button class="btn-primary btn">...</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input type="number" value="0" class="form-control" v-model="price" step="any">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" value="0" class="form-control" v-model="amount" step="any">
                                </div>
                            </div>



                            <div class="col-md-2">
                                <div class="form-group">
                                    <button class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
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
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    Articulo n
                                                </td>
                                                <td>
                                                    <input type="number" value="3" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="number" value="2" class="form-control">
                                                </td>
                                                <td>
                                                    $6.00
                                                </td>
                                                
                                            </tr>

                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    Articulo n
                                                </td>
                                                <td>
                                                    <input type="number" value="3" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="number" value="2" class="form-control">
                                                </td>
                                                <td>
                                                    $6.00
                                                </td>
                                                
                                            </tr>

                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                                <td>$5</td>  
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                                <td>$1</td>                                               
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                                <td>$5</td>
                                                
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
                    price:0,
                    article_id:0,
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
                    var url = '/supplier/selectSupplier?filter='+search;
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
                this.list=0;

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
                    'name':this.name,
                     'document_type':this.document_type,
                     'document_num':this.document_num,
                     'address':this.address,
                     'phone':this.phone,
                     'mail':this.mail,
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
                    this.errorPerson=0;
                    this.errorShowMsjPerson=[];
                    if(!this.name) this.errorShowMsjPerson.push("El nombre de la persona no puede estar vacio");
                    if(!this.user) this.errorShowMsjPerson.push("El nombre de usuario no puede estar vacio");
                    if(!this.password) this.errorShowMsjPerson.push("El password no puede estar vacio");
                    if(this.rol_id==0) this.errorShowMsjPerson.push("Debes seleccionar un rol");

                    if(this.errorShowMsjPerson.length) this.errorPerson=1;
                    return this.errorPerson;

                },
            openModal(model, action, data=[])
            {
                this.selectRol();
                switch(model)
                {
                case "Person":
                    {
                        switch(action)
                        {
                            case 'register':
                                {
                                    this.errorPerson=0;
                                    this.modal=1;
                                    this.name='';
                                    this.titleModal='Registrar Usuario';
                                    this.user='';
                                    this.password='';
                                    this.rol_id=0;
                                    this.description='';
                                    this.typeAction=1;
                                    this.document_type='DNI';
                                    this.contactPhone='';
                                    this.document_num='';
                                    this.address='';
                                    this.phone='';
                                    this.mail='';
                                    this.person_id;

                                    break;


                                }
                            case 'update':
                                {
                                    this.errorPerson=0;
                                    this.modal=1;
                                    this.titleModal='Actualizar Usuario';
                                    this.Person_id=data['id'];
                                    this.name=data['name'];
                                    this.document_type=data['document_type'];
                                    this.address=data['address'];
                                    this.phone=data['phone'];
                                    this.rol_id=data['rol_id'];
                                    this.user=data['user'];
                                    this.password=data['password']
                                    this.typeAction=2;
                                    this.mail=data['mail'];
                                    this.contactPhone=data['contact_phone'];
                                    this.contact=data['contact'];
                                    this.document_num=data['document_num'];


                                    break;

                                }
                                
                        }

                    }
                }

            },
            closeModal()
            {
                this.modal=0;
                this.name='';
                this.document_type='RUC';
                this.document_num='';
                this.address='';
                this.titleModal='';
                this.phone='';
                this.mail='';
                this.contact='';
                this.contactPhone='';
                this.errorPerson=0;
                this.user='';
                this.password='';
                this.rol_id=0;
                
                                   

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
