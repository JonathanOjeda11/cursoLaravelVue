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
                        <i class="fa fa-align-justify"></i> Cliente
                        <button type="button" class="btn btn-secondary" @click="openModal('Person','register')">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criteria">
                                      <option value="name">Nombre</option>
                                      <option value="document_num">Documento</option>
                                      <option value="mail">Email</option>
                                      <option value="phone">Telefono</option>
                                      <option value="document_num">Documento</option>
                                    </select>
                                    <input type="text" v-model="search" @keyup.enter="listPerson(1,search,criteria)" name="texto" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listPerson(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Tipo Documento</th>
                                    <th>Numero</th>
                                    <th>Direccion</th>
                                    <th>Telefono</th>
                                    <th>Email</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="Person in arrayPerson" :key="Person.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm"  @click="openModal('Person','update',Person)">
                                          <i class="icon-pencil"></i>
                                        </button> 
                                    </td>
                                    <td v-text="Person.name"></td>
                                    <td v-text="Person.document_type"></td>
                                    <td v-text="Person.document_num"></td>
                                    <td v-text="Person.address"></td>
                                    <td v-text="Person.phone"></td>
                                    <td v-text="Person.mail"></td>
                                </tr>
                               
                            </tbody>
                        </table>
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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre de la persona">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Tipo Documento</label>
                                    <div class="col-md-9">
                                       <select v-model="document_type">
                                           <option value="DNI">DNI</option>
                                           <option value="RUC">RUC</option>
                                           <option value="PASS">PASS</option>
                                       </select>


                                    </div>
                                    </div>
                                    <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Numero de documento</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="document_num" class="form-control" placeholder="Numero de documento">
                                        <span class="help-block"></span>
                                    </div>
                                    </div>

                                     <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="address" class="form-control" placeholder="Direccion">
                                        <span class="help-block"></span>
                                    </div>
                                    </div>

                                     <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="phone" class="form-control" placeholder="Telefono">
                                        <span class="help-block"></span>
                                    </div>
                                    </div>

                                    <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="mail" class="form-control" placeholder="Email">
                                        <span class="help-block"></span>
                                    </div>
                                    </div>
                                
                                 <div v-show="errorPerson" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsjPerson" :key="error" v-text="error">

                                        </div>

                                    </div>
                             

                                </div>

                            </form>
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
    export default {
         data()
            {
                return {

                    person_id:0,
                    name:'',
                    document_type:'DNI',
                    document_num:'',
                    address:'',
                    phone:'',
                    mail:'',       
                    arrayPerson:[],
                    modal:0,
                    titleModal:'',
                    typeAction:0,
                    errorPerson:0,
                    errorShowMsjPerson:[],
                    pagination: {
                        'total':0,
                        'current_page':0, 
                        'per_page':0,
                        'last_page':0,
                        'from' :0,
                        'to':0,
                    },
                    offset:3,
                    criteria: 'name',
                    search: ''
                   
                }
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
            listPerson(page, search, criteria)
            {
               let me = this;
                    var url = '/cliente?page='+page+'&search='+search+'&criteria='+criteria;
                    axios.get(url).then(function (response) {
                        var answer = response.data;
                        me.arrayPerson=answer.persons.data
                        me.pagination=answer.pagination;
                        

                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });  

            },
            registerPerson()
            {
                if(this.validatePerson())
                    {
                        return;
                    }
                let me = this;
                 axios.post('/cliente/registrar',{
                     'name':this.name,
                     'document_type':this.document_type,
                     'document_num':this.document_num,
                     'address':this.address,
                     'phone':this.phone,
                     'mail':this.mail


                 }).then(function (response) {
                      me.closeModal();
                      me.listPerson('1','','name');

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
                    me.listPerson(page, search, criteria);

                },
            updatePerson()
                {
                    if(this.validatePerson())
                        {
                        return;
                        }
                        let me=this;
                        axios.put('/cliente/actualizar',{
                                'name':this.name,
                                'document_type':this.document_type,
                                'document_num':this.document_num,
                                'address':this.address,
                                'phone':this.phone,
                                'mail':this.mail,
                                'id':this.Person_id
                            }).then(function (response) {
                            me.closeModal();
                            me.listPerson('1','','name');
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
                    if(this.errorShowMsjPerson.length) this.errorPerson=1;
                    return this.errorPerson;

                },
            openModal(model, action, data=[])
            {
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
                                    this.titleModal='Registrar Cliente';
                                    this.description='';
                                    this.typeAction=1;
                                    this.document_type='DNI';
                                    this.document_num='';
                                    this.address='';
                                    this.phone='';
                                    this.mail='';
                                    break;


                                }
                            case 'update':
                                {
                                    this.errorPerson=0;
                                    this.modal=1;
                                    this.titleModal='Actualizar Cliente';
                                    this.Person_id=data['id'];
                                    this.name=data['name'];
                                    this.document_type=data['document_type'];
                                    this.document_num=data['document_num'];

                                    this.address=data['address'];
                                    this.phone=data['phone'];
                                    this.typeAction=2;
                                    this.mail=data['mail'];

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
                this.document_type='DNI';
                this.document_num='';
                this.address='';
                this.titleModal='';
                this.phone='';
                this.mail='';
                this.errorPerson=0;
                                   

            },
            
            
        
    
                },

        mounted() {
            this.listPerson(1, this.search, this.criteria);
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


        </style>
