<template>
    <v-app>
        <v-navigation-drawer app v-model="drawer">
            <v-list-item>
                <v-list-item-content>
                    <v-list-item-title class="title">
                        Application
                    </v-list-item-title>
                    <v-list-item-subtitle>
                        Cars list
                    </v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>
            <v-list dense nav v-if="cars">
                <v-list-item v-for="car in cars" :key="car._id" v-if="car._id" link>
                    <router-link :to="{ name: 'car', params: { carId: car._id }}">
                    <v-list-item-content>
                        <v-list-item-title v-if="car.manufacturer">{{ car.manufacturer }}</v-list-item-title>
                        <v-list-item-subtitle v-if="car.model">{{ car.model }}</v-list-item-subtitle>
                    </v-list-item-content>
                    </router-link>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar app>
            <v-row align="center" justify="end">
                <v-btn class="mx-2" fab dark color="teal"  @click.stop="drawer = !drawer">
                    <v-icon dark>directions_car</v-icon>
                </v-btn>
            </v-row>
        </v-app-bar>
        <v-content>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        name: "Dashboard",
        data () {
            return {
                cars: [],
                drawer: true,
            }
        },
        mounted() {
            this.getCars();
        },
        methods:{
            getCars(){
                let _this = this ;
                axios.get('api/cars/')
                    .then(function(response){
                        _this.cars = response.data.cars;
                    })
                    .catch(error =>
                        Swal({
                            title: "Error",
                            text: error.response.data.message,
                            type: "error",
                        }));
            }
        },
    }
</script>

