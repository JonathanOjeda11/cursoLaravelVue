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
                        <i class="fa fa-align-justify"></i> Articulos
                        <button type="button" class="btn btn-secondary" @click="openModal('article','register')">
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
                                    <input type="text" v-model="search" @keyup.enter="listArticle(1,search,criteria)" name="texto" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listArticle(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Categoria</th>
                                    <th>Precio_Venta</th>
                                    <th>Stock</th>
                                    <th>Descripcion</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="article in arrayArticle" :key="article.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm"  @click="openModal('article','update',article)">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="article.status">
                                            <button type="button" class="btn btn-danger btn-sm" @click="disableArticle(article.id)">
                                                <i class="icon-trash"></i>                                         
                                            </button>
                                        </template>
                                         <template v-else>
                                            <button type="button" class="btn btn-danger btn-sm" @click="enableArticle(article.id)">
                                                <i class="icon-check"></i>                                         
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="article.code"></td>
                                    <td v-text="article.name"></td>
                                    <td v-text="article.category_name"></td>
                                    <td v-text="article.sale_price"></td>}
                                    <td v-text="article.stock"></td>
                                    <td v-text="article.description"></td>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="categoryid">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="category in arrayCategory" :key="category.id" :value="category.id" v-text="category.name"></option>
                                        </select>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="code" class="form-control" placeholder="Código de barras">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre de artículo">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio Venta</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="sale_price" class="form-control" placeholder="">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="stock" class="form-control" placeholder="">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="description" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div v-show="errorArticle" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsjArticle" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" v-if="typeAction==1" class="btn btn-primary" @click="registerArticle()" >Guardar</button>
                            <button type="button" v-if="typeAction==2" class="btn btn-primary" @click="updateArticle()">Actualizar</button>

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
                            <h4 class="modal-title">Eliminar Articulo</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Estas seguro de eliminar el articulo?</p>
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
                    
                    article_id:0,
                    categoryid:0,
                    categoryName:'',
                    code:'',
                    name:'',
                    stock:0,
                    description:'',
                    sale_price:0,
                    arrayArticle:[],
                    modal:0,
                    titleModal:'',
                    typeAction:0,
                    errorArticle:0,
                    errorShowMsjArticle:[],
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
                    search: '',
                    arrayCategory:[]
                   
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
                listArticle(page, search, criteria)
                {
                let me = this;
                        var url = '/articulo?page='+page+'&search='+search+'&criteria='+criteria;
                        axios.get(url).then(function (response) {
                            var answer = response.data;
                            me.arrayArticle=answer.articles.data
                            me.pagination=answer.pagination;
                            

                        })
                        .catch(function (error) {
                            console.log(error);
                        })
                        .then(function () {
                            // always executed
                        });  

                },
                selectCategory(){
                let me=this;
                var url= '/categoria/selectCategoria';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var answer= response.data;
                    me.arrayCategoria = answer.categories;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
                registerArticle()
                {
                    if(this.validateArticle())
                        {
                            return;
                        }
                    let me = this;
                    axios.post('/articulos/registrar',{
                        

                    }).then(function (response) {
                        me.closeModal();
                        me.listArticle('1','','name');

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
                        me.listArticle(page, search, criteria);

                },
                updateArticle()
                {
                        if(this.validateArticle())
                            {
                            return;
                            }
                            let me=this;
                            axios.put('/articulo/actualizar',{
                                'name':this.name,
                                'description':this.description,
                                'id':this.category_id
                                }).then(function (response) {
                                me.closeModal();
                                me.listArticle('1','','name');
                                })
                                .catch(function (error) {
                                console.log(error);
                                })
                                .then(function () {
                                // always executed
                                });  
                        
                },
                validateArticle()
                {
                        this.errorArticle=0;
                        this.errorShowMsjArticle=[];
                        if(!this.name) this.errorShowMsjArticle.push("El nombre del articulo no puede estar vacio");
                        if(this.errorShowMsjArticle.length) this.errorArticle=1;
                        return this.errorArticle;

                },
                openModal(model, action, data=[])
                {
                    switch(model)
                    {
                    case "article":
                        {
                            switch(action)
                            {
                                case 'register':
                                    {
                                        this.errorArticle=0;
                                        this.modal=1;
                                        this.name='';
                                        this.titleModal='Registrar Articulo';
                                        this.description='';
                                        this.typeAction=1;
                                        break;


                                    }
                                case 'update':
                                    {
                                        this.errorArticle=0;
                                        this.modal=1;
                                        this.titleModal='Actualizar Articulo';
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
                
                disableArticle(id)
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
                        axios.put('/articulo/desactivar',{
                            'id':id
                            }).then(function (response) {
                                swalWithBootstrapButtons.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                                )
                            me.listArticulo('1','','name');
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

                enableArticle(id)
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
                        axios.put('/article/activar',{
                            'id':id
                            }).then(function (response) {
                                swalWithBootstrapButtons.fire(
                                'Added!',
                                'Your file has been added.',
                                'success'
                                )
                            me.listArticle('1','','name');
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
                }},

        mounted() {
            this.listArticle(1, this.search, this.criteria);
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
