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
                        <i class="fa fa-align-justify"></i> Categorías
                        <button type="button" class="btn btn-secondary" @click="openModal('category','register')">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" id="opcion" name="opcion">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="search" @keyup.enter="listCategory(1,search,criteria)" name="texto" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listCategory(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="category in arrayCategory" :key="category.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm"  @click="openModal('category','update',category)">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="category.status">
                                            <button type="button" class="btn btn-danger btn-sm" @click="disableCategory(category.id)">
                                                <i class="icon-trash"></i>                                         
                                            </button>
                                        </template>
                                         <template v-else>
                                            <button type="button" class="btn btn-danger btn-sm" @click="enableCategory(category.id)">
                                                <i class="icon-check"></i>                                         
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="category.name"></td>
                                    <td v-text="category.description"></td>
                                    <td>
                                        <div v-if="category.status">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Inactivo</span>
                                        </div>
                                    </td>
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
                                        <input type="text" v-model="name" name="nombre" class="form-control" placeholder="Nombre de categoría">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="description" name="descripcion" class="form-control" placeholder="Ingrese descripcion">
                                    </div>
                                </div>
                                 <div v-show="errorCategory" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsjCategory" :key="error" v-text="error">

                                        </div>

                                    </div>
                             

                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" v-if="typeAction==1" class="btn btn-primary" @click="registerCategory()" >Guardar</button>
                            <button type="button" v-if="typeAction==2" class="btn btn-primary" @click="updateCategory()">Actualizar</button>

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
                    category_id:0,
                    name:'',
                    description:'',
                    arrayCategory:[],
                    modal:0,
                    titleModal:'',
                    typeAction:0,
                    errorCategory:0,
                    errorShowMsjCategory:[],
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
            listCategory(page, search, criteria)
            {
               let me = this;
                    var url = '/categoria?page='+page+'&search='+search+'&criteria='+criteria;
                    axios.get(url).then(function (response) {
                        var answer = response.data;
                        me.arrayCategory=answer.categories.data
                        me.pagination=answer.pagination;
                        

                    })
                    .catch(function (error) {
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });  

            },
            registerCategory()
            {
                if(this.validateCategory())
                    {
                        return;
                    }
                let me = this;
                 axios.post('/categoria/registrar',{
                     'name':this.name,
                     'description':this.description

                 }).then(function (response) {
                      me.closeModal();
                      me.listCategory('1','','name');

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
                    me.listCategory(page, search, criteria);

                },
            updateCategory()
                {
                    if(this.validateCategory())
                        {
                        return;
                        }
                        let me=this;
                        axios.put('/categoria/actualizar',{
                            'name':this.name,
                            'description':this.description,
                            'id':this.category_id
                            }).then(function (response) {
                            me.closeModal();
                            me.listCategory('1','','name');
                            })
                            .catch(function (error) {
                            console.log(error);
                            })
                            .then(function () {
                            // always executed
                            });  
                    
                },
            validateCategory()
                {
                    this.errorCategory=0;
                    this.errorShowMsjCategory=[];
                    if(!this.name) this.errorShowMsjCategory.push("El nombre de la categoria no puede estar vacio");
                    if(this.errorShowMsjCategory.length) this.errorCategory=1;
                    return this.errorCategory;

                },
            openModal(model, action, data=[])
            {
                switch(model)
                {
                case "category":
                    {
                        switch(action)
                        {
                            case 'register':
                                {
                                    this.errorCategory=0;
                                    this.modal=1;
                                    this.name='';
                                    this.titleModal='Registrar Categoria';
                                    this.description='';
                                    this.typeAction=1;
                                    break;


                                }
                            case 'update':
                                {
                                    this.errorCategory=0;
                                    this.modal=1;
                                    this.titleModal='Actualizar Categoria';
                                    this.category_id=data['id'];
                                    this.name=data['name'];
                                    this.description=data['description'];
                                    this.typeAction=2;
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
                this.description='';
                this.titleModal='';
                                   

            },
            
            
        
        disableCategory(id)
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
                            me.listCategory('1','','name');
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

                enableCategory(id)
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
                            me.listPerson('1','','name');
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
            this.listCategory(1, this.search, this.criteria);
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
