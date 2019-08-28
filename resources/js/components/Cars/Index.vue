<template>
    <div class="q-pa-md" >
        <div class="row">
            <div class="col-12" v-if="cars">
                <multiselect v-model="cars_selected" :multiple="true" :options="cars" placeholder="Select cars " label="model" track-by="_id"></multiselect>
            </div>
            <div class="col-12" v-for="car in cars_selected" :key="car._id">
                <car :id="car._id"></car>
            </div>
        </div>
   </div>
</template>

<script>
    import car from './Single';
    import { Multiselect } from 'vue-multiselect';

    export default {
        name: "Cars",
        components: {
            car,
            Multiselect
        },
        data () {
            return {
                cars: [],
                cars_selected: [],
            }
        },
        mounted() {
            this.getCars();
        },
        methods:{
            getCars(){
                let _this = this ;
                axios.get( 'api/cars', {
                        params: {
                            'cars_select' : _this.cars_select,
                            'start' : _this.start,
                            'end' : _this.end
                        }
                    }).then( function( response ) {
                        _this.cars = response.data.cars;
                    })
                    .catch( error =>
                        Swal({
                            title: "Error",
                            text: error.response.data.message,
                            type: "error",
                        })
                    );
            },
        }
    }
</script>

