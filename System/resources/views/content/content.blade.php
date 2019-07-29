    @extends('main')
    @section('content')

    

                 <template v-if="menu==0">
                    <h1>Escritorio</h1>
                </template>
                <template v-if="menu==1">
                    <Category></Category>
                </template>
                <template v-if="menu==2">
                    <Articlee></Articlee>
                   
                </template>
                <template v-if="menu==3">
                    <Incomes></Incomes>
                </template>
                <template v-if="menu==4">
                    <Supplier></Supplier>
                </template>
                <template v-if="menu==5">
                    <h1>Ventas</h1>
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
                    <h1>Reporte ingresos</h1>
                </template>
                <template v-if="menu==10">
                    <h1>Reporte ventas</h1>
                </template>
                <template v-if="menu==11">
                    <h1>Ayuda</h1>
                </template>
                <template v-if="menu==12">
                    <h1>Acerca de</h1>
                </template>

                @if(Auth::check())
            @if(Auth::user()->rol_id==1)
                 
     
            @elseif(Auth::user()->rol_id==2)
                <template v-if="menu==5">
                    <h1>Ventas</h1>
                </template>
                <template v-if="menu==6">
                    <Client></Client>
                </template>
                 <template v-if="menu==10">
                    <h1>Reporte ventas</h1>
                </template>
                <template v-if="menu==11">
                    <h1>Ayuda</h1>
                </template>
                <template v-if="menu==12">
                    <h1>Acerca de</h1>
                </template>
                
            @elseif(Auth::user()->rol_id==3)
                <template v-if="menu==1">
                    <Category></Category>
                </template>
                <template v-if="menu==2">
                    <Articlee></Articlee>
                   
                </template>
                <template v-if="menu==3">
                    <Incomes></Incomes>
                </template>
                <template v-if="menu==4">
                    <Supplier></Supplier>
                </template>
                <template v-if="menu==9">
                    <h1>Reporte ingresos</h1>
                </template>
                <template v-if="menu==11">
                    <h1>Ayuda</h1>
                </template>
                <template v-if="menu==12">
                    <h1>Acerca de</h1>
                </template>
                
            @else

            @endif



        @endif



    @endsection