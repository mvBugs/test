<template>
    <div>
        <div id="map"></div>
        <div class="menu" id="menu">
            <button v-on:click="addPoint(newPointLat, newPointLng)" class="btn btn-success menu-item">Add point</button>
        </div>
    </div>

</template>

<script>
    import GoogleMapsLoader from 'google-maps'
    import {bus} from "../app";
    import {HTTP} from '../axios';

    GoogleMapsLoader.KEY = 'AIzaSyD9Qv3PhtLRXw8_cP707YTs8NwHukEnf9k';
    GoogleMapsLoader.VERSION = '3.38';

    export default {
        name: 'MapComponent',
        props: {
            user: '',
        },
        data() {
            return {
                newPointLat: null,
                newPointLng: null,
                points: '',
            }
        },
        methods: {
            addPoint (lat, lng) {
                bus.$emit("addPoint", lat, lng);
            },
            getPoints() {
                let vm = this;
                HTTP.get('points')
                    .then((response) => {
                        this.points = response.data.data
                        vm.$emit('updateMarkers')
                    });
            },
            markersInit(map) {
                let vm = this;

                for (let point in vm.points) {
                    let marker = new google.maps.Marker({
                        position: new google.maps.LatLng(vm.points[point].lat, vm.points[point].lng),
                        map:map
                    });


                    //ініціалізація інфоменю
                    let infowindow = new google.maps.InfoWindow({
                        content: vm.points[point].description,
                        title: vm.points[point].title,
                    });

                    //інфо по кліку по маркеру
                    var activeInfoWindow;
                    google.maps.event.addListener(marker, 'mouseover', function() {
                        if (activeInfoWindow) {
                            activeInfoWindow.close();
                        }
                        // map.setCenter(new google.maps.LatLng(vm.points[point].lat, vm.points[point].lng));

                        infowindow.open(map, marker);
                        // bus.$emit("clickMarker", vm.points[point]);
                        activeInfoWindow = infowindow;
                    });

                    google.maps.event.addListener(marker, 'click', function() {
                        map.setCenter(new google.maps.LatLng(vm.points[point].lat, vm.points[point].lng));
                        bus.$emit("clickMarker", vm.points[point]);
                    })


                    //закрити всі інфо-блоки по кліку
                    google.maps.event.addListener(map, "mouseover", function (e) {
                        if (activeInfoWindow) {
                            activeInfoWindow.close();
                        }
                        bus.$emit("closeWindow");
                    });
                }
            },
            closeContextMenu (map, menuDisplayed, menuBox) {
                if (menuDisplayed == true) {
                    menuBox.style.display = "none";
                    map.setOptions({
                        draggable: true,
                        zoomControl: true,
                        scrollwheel: true,
                        disableDoubleClickZoom: false
                    });
                }
            },
            zoomDisable(map) { //Відключити зум і прокрутку
                map.setOptions({
                    draggable: false,
                    zoomControl: false,
                    scrollwheel: false,
                    disableDoubleClickZoom: true
                });
            },
        },
        computed: {

        },
        mounted() {
            let vm = this;

            GoogleMapsLoader.load(function(google) {
                var latlng = new google.maps.LatLng(50.747441, 25.325092);
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    center: latlng
                });

                vm.getPoints();
                vm.$on('updateMarkers', function () {
                    vm.markersInit(map)
                })

                //show modal if rightclick

                var menuDisplayed = false;
                var menuBox = null;
                var mapCanvas = document.getElementById("map");

                if (vm.user) {
                    google.maps.event.addListener(map, "rightclick", function(event) {
                        vm.newPointLat = event.latLng.lat();
                        vm.newPointLng = event.latLng.lng();

                        vm.zoomDisable(map)

                        for (var prop in event) {
                            if (event[prop] instanceof MouseEvent) {
                                let mouseEvt = event[prop];
                                let left = mouseEvt.clientX;
                                let top = mouseEvt.clientY;

                                menuBox = document.getElementById("menu");
                                menuBox.style.left = left + "px";
                                menuBox.style.top = top + "px";
                                menuBox.style.display = "block";

                                mouseEvt.preventDefault();

                                menuDisplayed = true;
                            }
                        }
                    });
                }

                map.addListener("click", function() {
                    vm.closeContextMenu(map, menuDisplayed, menuBox);
                    vm.getPoints(map);
                });


                bus.$on("closeModal", () => {
                    vm.closeContextMenu(map, menuDisplayed, menuBox);
                });

            })

            // vm.getPoints();
            bus.$on('sendForm', function () {
                vm.getPoints();
            })

        }

    }
</script>
<style scoped>
    .menu {
        position: fixed;
        display: none;
    }

    .menu-item:hover {
        background-color: #6CB5FF;
        cursor: pointer;
    }
</style>
