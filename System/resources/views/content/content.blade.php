    @extends('main')
    @section('content')
    <template v-if="menu==0">
        <h1>Contenido del menu numero 0</h1>
    </template>
    <template v-if="menu==1">
        <Category></Category>
    </template>
    <template v-if="menu==2">
        <Articlee></Articlee>
       
    </template>
    <template v-if="menu==3">
        <h1>Contenido del menu numero 3</h1>
    </template>
    <template v-if="menu==4">
        <Supplier></Supplier>
    </template>
    <template v-if="menu==5">
        <h1>Contenido del menu numero 5</h1>
    </template>
    <template v-if="menu==6">
        <Client></Client>
    </template>
    <template v-if="menu==7">
        <User></User>
    </template>
    <template v-if="menu==8">
        <Rol></Rol>
    </template>
    <template v-if="menu==9">
        <h1>Contenido del menu numero 9</h1>
    </template>
    <template v-if="menu==10">
        <h1>Contenido del menu numero 10</h1>
    </template>
    <template v-if="menu==11">
        <h1>Contenido del menu numero 11</h1>
    </template>
    <template v-if="menu==12">
        <h1>Contenido del menu numero 12</h1>
    </template>

     

    @endsection