<template>
    <div class="q-pa-md" >
        <div class="row" v-if="car">
            <div class="col-2">
                <v-card class="col-12">
                    <v-card-title  v-if="car.model" > Model :  {{ car.model }} </v-card-title>
                    <v-card-text v-if="car.distance" > Distance :  {{ car.distance }} </v-card-text>
                    <v-card-text  v-if="car.manufacturer"> Manufacturer : {{ car.manufacturer }} </v-card-text>
                    <v-card-text v-if="car.max_speed" > Max speed : {{ car.max_speed }} </v-card-text>
                    <v-card-text  v-if="car.odometer" > Odometer : {{ car.odometer }} </v-card-text>
                </v-card>
            </div>
            <div class="col-6">
                <v-card class="col-12">
                    <GmapMap
                        v-if="markers[0]"
                        :center="markers[0].position"
                        :zoom="15"
                        map-type-id="terrain"
                        style="width: 100% ; height: 800px">
                           <GmapMarker
                                   :key="index"
                                   v-for="(m, index) in markers"
                                   v-if="m"
                                   :position="m.position"
                                   :clickable="true"
                                   :draggable="true"
                                   @click="center= m.position"
                           />
                    </GmapMap>
                    <div class="title" v-else> No markers </div>
                </v-card>
            </div>
            <div class="col-4">
                <v-row justify="center">
                    <v-card class="col-11">
                        <v-card-title>
                            {{ start }}
                            <v-btn v-if="start" class="mx-2" fab dark small color="red"  @click.stop="start = null">
                                <v-icon dark>remove</v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-card-text>Start Date</v-card-text>
                        <v-date-picker v-model="start"
                                       :max="end"
                                       full-width
                        ></v-date-picker>
                    </v-card>
                </v-row>
                <v-divider></v-divider>
                <v-row justify="center">
                    <v-card class="col-11">
                        <v-card-title>
                            {{ end }}
                            <v-btn v-if="end" class="mx-2" fab dark small color="red"  @click.stop="end = null">
                                <v-icon dark>remove</v-icon>
                            </v-btn>
                        </v-card-title>

                        <v-card-text>End Date</v-card-text>
                        <v-date-picker v-model="end"
                                       :min="start"
                                       full-width
                        ></v-date-picker>
                    </v-card>
                </v-row>
            </div>
        </div>
        <div class="row justify-center" v-else><h1> Car not selected </h1></div>
   </div>
</template>

<script>
    export default {
        name: "Car",
        props: ['id'],
        data () {
            return {
                car: null,
                markers: [],
                start: null,
                end: null
            }
        },
        mounted() {
            this.getCar();
        },
        watch: {
            start() {
                this.getCar();
            },
            end() {
                this.getCar();
            },
            $route() {
                this.getCar();
            },
        },
        methods:{
            getCar(){
                let id;

                if( this.id ) id =  this.id;
                if( this.$route.params.carId ) id =  this.$route.params.carId;

                let _this = this ;
                axios.get( 'api/cars/' + id, {
                        params: {
                            'start' : _this.start,
                            'end' : _this.end
                        }
                    }).then( function( response ) {
                        _this.car = response.data.car;
                        _this.markers = response.data.markers;
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

