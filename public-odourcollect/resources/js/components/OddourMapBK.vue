<template>
    <div>
        <div id="map" style="height: 93vh; z-index: 0;">
            <div class="zone-msg" v-if="isZone">
                <p>{{$t('UI.MAP.SHOWING')}}<span>{{zone[0][0]}}</span></p>
                <div class="map-info" @click="info_map = true"><img :src="puntos_icon"></div>
            </div>
            <div class="info_button" @click="info = true"><img :src="info_icon"></div>
        </div>

        <oddour-bottom-nav v-if="show_nav" @clicked="selected_item" ></oddour-bottom-nav>

        <div v-if="visible">
            <oddour-detail v-if="visible" :oddour="oddour" :confirmed="confirmed" :author="author" @clicked="showComments"></oddour-detail>

            <div v-if="!singleM" class="close subheading font-weight-medium" @click="closeDetails"><img :src="close_icon"></div>

            <div  v-if="show_comments">
                <div class="back_toolbar">
                    <img @click="show_comments = false" :src="back_icon">
                    <p class="text-md-center">{{$t('DETAIL_ODOUR.COMMENTS')}}</p>
                </div>
                <oddour-comment class="comment_list" :author="author" :oddour="oddour.id"></oddour-comment>

            </div>
        </div>

        <div v-if="user_zones">
            <div class="back_toolbar">
                <img @click="user_zones = false" :src="back_icon">
                <p class="text-md-center">{{$t('MENU.USER_ZONES')}}</p>
            </div>
            <user-zones @clicked="selected_item" class="content"></user-zones>
        </div>

        <div class="dialogo">
            <v-layout row justify-center>
                <v-dialog v-model="info_map" transition="dialog-bottom-transition" scrollable>
                    <v-card flat>

                        <h2 color="primary" class="subheading font-weight-medium title">{{$t('UI.MAP.PRIVATE_MAP')}}
                            <div class="info-close" @click="info_map = false"><img :src="close_icon"></div>
                        </h2>

                        <div class="separator"></div>

                        <v-card-text style="padding-top: 60px;padding-bottom: 100px;">
                            <div class="apply-btn map">
                                <p class="map-btn" @click="info_map = false; selected_item('reset'); user_zones = true">{{$t('UI.MAP.SHOW_OTHER')}}</p>
                            </div>

                            <div class="apply-btn">
                                <div class="apply-btn map">
                                    <p class="map-btn" @click="info_map = false; selected_item('reset')">{{$t('UI.MAP.EXIT_MAP')}}</p>
                                </div>
                            </div>
                        </v-card-text>

                        <div class="apply-btn">
                            <v-divider style="margin-top: 0px;"></v-divider>
                            <h2 class="apply" @click="info_map = false">{{$t('UI.BOTTOM_BAR.BUTTONS.CANCEL')}}</h2>
                        </div>

                        <div style="flex: 1 1 auto;"></div>
                    </v-card>
                </v-dialog>
            </v-layout>
        </div>

        <div class="dialogo">
            <v-layout row justify-center>
                <v-dialog v-model="info" transition="dialog-bottom-transition" scrollable>
                    <v-card flat>

                        <h2 color="primary" class="subheading font-weight-medium title">{{$t('INFORMATION.MAP.TITLE')}}
                            <div class="info-close" @click="info = false"><img :src="close_icon"></div>
                        </h2>

                        <div class="separator"></div>

                        <v-card-text v-html="$t('INFORMATION.MAP.CONTENT')"></v-card-text>

                        <div class="apply-btn">
                            <v-divider style="margin-top: 0px;"></v-divider>
                            <h2 class="apply" @click="info = false"> {{$t('INFORMATION.MAP.ACTION')}}</h2>
                        </div>

                        <div style="flex: 1 1 auto;"></div>
                    </v-card>
                </v-dialog>
            </v-layout>
        </div>

        <v-layout row justify-center>
            <v-dialog v-model="visible_filters" transition="dialog-bottom-transition" scrollable>
                <v-card flat>
                    <h2 color="primary" class="subheading title">{{$t('UI.BOTTOM_SHEET.TITLE_FILTER')}}
                        <div class="info-close" @click="visible_filters = false"><img :src="close_icon"></div>
                        <div class="info-close" id="step_preload" style="left: 14px;right: auto; display: none;"><img v-on:click="steppreload" :src="back_icon"></div>
                        <div class="info-close" id="step1_back" style="left: 14px;right: auto; display: none;"><img v-on:click="step1back" :src="back_icon"></div>
                        <div class="info-close" id="step2_back" style="left: 14px;right: auto; display: none;"><img v-on:click="step2back" :src="back_icon"></div>
                        
                    </h2>
                    <div class="separator"></div>
                    <vue-scrollbar class="my-scrollbar preload" ref="Scrollbar">
                        <div class="scroll-me">
                            <h2 color="primary" class="subheading title">Filtros Sencillos</h2>
                                <div class="row">
                                    <div class="col-6 mt20"><a href="#" id="only_type" v-on:click="only_type" ><img class="filter" :src="types_filter">Solo Tipo</a></div>
                                    <div class="col-6 mt20"><a href="#" id="only_type" v-on:click="only_date" ><img class="filter" :src="date_icon">Solo Fechas</a></div>
                                </div>

                                <h2 color="primary" class="subheading title">Filtros Mixtos</h2>
                                <div class="row">
                                    <div class="col-6 mt20"><img class="filter" :src="types_filter">Tipo + Subtipo</div>
                                    <div class="col-6 mt20"><img class="filter" :src="date_icon">Tipo + Fecha</div>
                                    <div class="col-12 mt20"><a href="#" id="only_type" v-on:click="type_subtype_date" ><img class="filter" :src="date_icon">Tipo + Subtipo + Fecha</a></div>
                                </div>
                        </div>
                     </vue-scrollbar>
                     <vue-scrollbar class="my-scrollbar only_type result" ref="Scrollbar">
                        <div class="scroll-me">
                            <div id="step1_only_type">
                                <div class="radio-input" v-for="(it, index) in item">
                                    <input type="radio" color="#384658" v-model="selected" >
                                    <v-label :for="it.name">{{it.name_t}}</v-label>
                                    <div class="check"><div class="inside" style="background-image:url(../../../img/general/checked-white.svg);"></div></div>
                                </div>
                            </div>
                        </div>
                        <div class="apply-btn">
                            <v-divider style="margin-top: 0px;"></v-divider>
                            <h2 class="apply" @click="applyFilters">{{$t('UI.BOTTOM_SHEET.APPLY')}}</h2>
                        </div>
                    </vue-scrollbar>

                    <vue-scrollbar class="my-scrollbar only_date result" ref="Scrollbar">
                        <div class="scroll-me">
                            <div id="step1_only_date" style="height: 190px;">
                                 <div class="date_init">
                                <label class="label">Desde:</label>
                                <datepicker class="date" :value="date_init" v-model="date_init"></datepicker>
                                <img class="date_icon" :src="date_icon">
                                </div>

                                <div class="date_init">
                                <label class="label">Hasta:</label>
                                <datepicker class="date" :value="date_end" v-model="date_end"></datepicker>
                                <img class="date_icon" :src="date_icon">
                                </div>
                            </div>
                        </div>
                        <div class="apply-btn">
                            <v-divider style="margin-top: 0px;"></v-divider>
                            <h2 class="apply" @click="applyFilters">{{$t('UI.BOTTOM_SHEET.APPLY')}}</h2>
                        </div>
                    </vue-scrollbar>

                    <vue-scrollbar class="my-scrollbar type_subtype_date result" ref="Scrollbar">
                        <div class="scroll-me">
                            <div id="step1">
                                <div class="radio-input" v-for="(it, index) in item">
                                    <input type="radio" color="#384658" v-on:click="step1" v-model="selected" :data="it" :value="it.id" :label="it.name_t" :id="it.name" :key="it.id" >
                                    <v-label :for="it.name">{{it.name_t}}</v-label>
                                    <div class="check"><div class="inside" style="background-image:url(../../../img/general/checked-white.svg);"></div></div>
                                </div>
                            </div>

                            <div id="step2" style="display: none;">
                                <div v-for="(value, key, index) in oddourSubTypes">
                                    <div class="radio-input subtype" v-for="(values in value" :id="'subtype'+key" :class="'subtype'+key">
                                        <input type="radio" color="#384658" v-on:click="step2" v-model="selected2" :data="values" :value="values.id" :label="values.name" :id="values.name" :key="values.id" >
                                        <v-label :for="values.name">{{values.name}}</v-label>
                                        <div class="check"><div class="inside" style="background-image:url(../../../img/general/checked-white.svg);"></div></div>
                                    </div>
                                </div>
                            </div>

                            <div id="step3" style="display: none;">
                                <div class="date_init">
                                <label class="label">Desde:</label>
                                <datepicker class="date" :value="date_init" v-model="date_init"></datepicker>
                                <img class="date_icon" :src="date_icon">
                                </div>

                                <div class="date_init">
                                <label class="label">Hasta:</label>
                                <datepicker class="date" :value="date_end" v-model="date_end"></datepicker>
                                <img class="date_icon" :src="date_icon">
                                </div>
                            </div>
                        </div>
                        <div class="apply-btn">
                            <v-divider style="margin-top: 0px;"></v-divider>
                            <h2 class="apply" @click="applyFilters">{{$t('UI.BOTTOM_SHEET.APPLY')}}</h2>
                        </div>
                    </vue-scrollbar>

                    

                </v-card>
            </v-dialog>
        </v-layout>

    </div>

</template>

<script>
    import OddourDetail from '../components/OddourDetail.vue';
    import Datepicker from 'vuejs-datepicker';
    
    import OddourComment from '../components/OddourComment.vue';
    import OddourBottomNav from '../components/OddourBttomNav.vue'
    import VueScrollbar from 'vue2-scrollbar';
    import store from '../store/store';
    import UserZones from '../views/User/UserZones.vue';


    export default {
        components: {OddourDetail, OddourBottomNav, VueScrollbar, OddourComment, UserZones, Datepicker},

        props: {
            singleMarker: { type: Boolean },
        },

        data(){
            return {
                date_init: new Date(),
                date_end: new Date(),
                selected_filter: 0,
                display_div: 'none',
                map: null,
                show_nav: true,
                markers: [],
                pointsInterest: [],
                zone: [],
                oddour: {
                    id: '',
                    name: '',
                    parent_type: '',
                    other: '',
                    parent_type_name: '',
                    type: '',
                    type_name: '',
                    intensity: 1,
                    annoy: 1,
                    loc: '',
                    date: '',
                    description: '',
                },
                confirmed:false,
                center: '',
                visible: false,
                visible_filters: false,
                back_filters: false,
                filters: '',
                mySubGroup: '',
                myInterestGroup: '',
                markers_layout: '',
                OdourIcon: '',
                redIcon: '',
                orangeIcon: '',
                greenIcon: '',
                InterestIcon: '',
                agricultureIcon: '',
                foodIcon: '',
                industryIcon: '',
                unknownIcon: '',
                urbanIcon: '',
                wasteIcon: '',
                waterIcon: '',
                niceIcon: '',
                singleM: this.singleMarker,
                token: '',
                user: '',
                author: false,
                current_pos: {
                    lat: 0,
                    lng: 0,
                },
                close_icon: '../../../img/general/close-mini.svg',
                info_icon: '../../../img/general/moreinfo-button.svg',
                puntos_icon: '../../../img/menu/menu-puntos.svg',
                back_icon:  '../../img/general/nav-back.svg',
                date_icon:  '../../img/general/calendar.svg',
                types_filter:  '../../svg/types-filter.svg',
                info: false,
                info_map: false,
                oddourTypes: [],
                oddourSubTypes: [],
                selected: [],
                selectedonly: [],
                selected2: [],
                options: {
                    activeColor: '#00b187'
                },
                isZone: false,
                item: [],
                show_comments: false,
                polygon: '',
                user_zones: false,
            }
        },

        computed: {
            //Takes from the store if we are in the home page or not, in order to change the toolbar
            isHome: function () {
                return this.$store.state.isHome;
            }
        },

        watch:{
            //Detects if the user wants to filter
            filters: function (val){
                //Obtener los olores segun el filtro
                if (this.singleM === false){
                    this.chargeFilteredMarkers();
                }
            },
        },

        mounted(){
            var vm = this;

            if( localStorage.getItem('auth-token') != null ) { this.isLoggedIn = true }
            if( this.isLoggedIn ){
                //If logged in save the user name in the data
                vm.user = JSON.parse( localStorage.getItem('user') );
                this.name = vm.user.name + ' ' + vm.user.surname;
                vm.token = localStorage.getItem('auth-token');
            }

            //Setea los principales valores del mapa (centro y zoom)
            //Maxzoom controla el zoom cuando se inicia la app
            vm.map = L.map( 'map', {
                center: [vm.current_pos.lat, vm.current_pos.lng],
                minZoom: 2,
                maxZoom: 18,
                zoom: 5,
                zoomControl:true
            });

            var options = {
                enableHighAccuracy: true,
                timeout: 60000,
                maximumAge: Infinity
            };

            function error(err) {
                console.warn(`ERROR(${err.code}): ${err.message}`);
            }

            navigator.geolocation.getCurrentPosition(function(location) {
                var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);
                vm.current_pos.lat = latlng.lat;
                vm.current_pos.lng = latlng.lng;
            }, error, options)

            this.$store.commit('lng', vm.current_pos.lng);
            this.$store.commit('lat', vm.current_pos.lat);

            //Copyright
            L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/rastertiles/voyager/{z}/{x}/{y}.png').addTo(vm.map);

            //disableClusteringAtZoom controla a partir de que zoom los elimina la agrupacion
            vm.markers_layout = L.markerClusterGroup({
                removeOutsideVisibleBounds: true,
                disableClusteringAtZoom: 19,
                maxClusterRadius: 30,
                showCoverageOnHover: false,
            });

            vm.mySubGroup = L.featureGroup.subGroup(vm.markers_layout ).on("click", this.showDetails);
            vm.myInterestGroup = L.featureGroup.subGroup(vm.markers_layout);

            console.log('GLOBAL');
            //Obtener de la api el listado de todos olores
            vm.getAllMarkers();

            //Obtain current location of the user
            if (store.state.lng === 0){
                navigator.geolocation.getCurrentPosition(function(location) {
                    var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);
                    vm.current_pos.lat = latlng.lat;
                    vm.current_pos.lng = latlng.lng;

                }, error, options)
            }
            this.$store.commit('lng', vm.current_pos.lng);
            this.$store.commit('lat', vm.current_pos.lat);

            //Start geolocalitation
            if (!store.state.lng || !store.state.lat){
                var lc = L.control.locate({
                    position: 'topright',
                    keepCurrentZoomLevel: false,
                    follow: true,
                    cacheLocation: true,
                    enableHighAccuracy: true,
                    removeOutsideVisibleBounds: true,
                    drawCircle: false,
                }).addTo(vm.map);

                lc.start();

            } else {
                (vm.map).setView(new L.LatLng(store.state.lat, store.state.lng), 8);
            }

            //Get list of odour types
            axios.get('api/odor/properties/type')
                .then(response => {
                    var points = response.data.content;

                    //Cargar la informacion
                    for (var i = 0; i < points.length; i++){
                        this.oddourSubTypes.push(points[i].childs);
                        points[i].name_t = this.$t('FILTER.TYPE.' + points[i].id);
                        this.item.push(points[i]);
                    }

                    this.oddourTypes.push(points[0].childs);

                }).catch(error => {
            });
            console.log(this.oddourSubTypes);
            //Add markers and cluster to the map
            vm.map.addLayer(vm.markers_layout );
            vm.map.addLayer(vm.mySubGroup);
            vm.map.addLayer(vm.myInterestGroup);


            //Icono personalizado (imagen principal + sombra)
            vm.OdourIcon = L.Icon.extend({
                options: {
                    iconSize:     [33, 39],
                    iconAnchor:   [16, 40],
                    popupAnchor:  [-3, -76]
                }
            });
            //Variancias del mismo icono
            vm.blackIcon = new vm.OdourIcon({iconUrl: '../../img/spot0.png'});
            vm.greenIcon = new vm.OdourIcon({iconUrl: '../../img/spot1.png'});
            vm.lightGreenIcon = new vm.OdourIcon({iconUrl: '../../img/spot2.png'});
            vm.yellowIcon = new vm.OdourIcon({iconUrl: '../../img/spot3.png'});
            vm.orangeIcon = new vm.OdourIcon({iconUrl: '../../img/spot4.png'});
            vm.lightPinkIcon = new vm.OdourIcon({iconUrl: '../../img/spot5.png'});
            vm.pinkIcon = new vm.OdourIcon({iconUrl: '../../img/spot6.png'});
            vm.purpleIcon = new vm.OdourIcon({iconUrl: '../../img/spot7.png'});

            vm.InterestIcon = L.Icon.extend({
                options: {
                    iconSize:     [42, 42],
                    iconAnchor:   [21, 42],
                    popupAnchor:  [-3, -76]
                }
            });

            vm.agricultureIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/agriculture-spot.png'});
            vm.foodIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/food-spot.png'});
            vm.industryIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/industry-spot.png'});
            vm.unknownIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/unknown-spot.png'});
            vm.urbanIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/urban-spot.png'});
            vm.wasteIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/waste-spot.png'});
            vm.waterIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/water-spot.png'});
            vm.niceIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/nice-spot.png'});

        },

        methods:{
            only_type: function(event){
                $(".preload").css('display', 'none');
                $(".only_type").css('display', 'block');
                $('#step_preload').css('display', 'block');
            },
            only_date: function(event){
                $(".preload").css('display', 'none');
                $(".only_date").css('display', 'block');
                $('#step_preload').css('display', 'block');
            },
            type_subtype_date : function(event){
                $(".preload").css('display', 'none');
                $(".type_subtype_date").css('display', 'block');
                 $(".only_date").css('display', 'none');
                 $(".only_type").css('display', 'none');
                $('#step_preload').css('display', 'block');
            },
             step1: function (event) {
              if (event) {
                console.log(this.oddourSubTypes[event.target.value]);
                if(this.oddourSubTypes[event.target.value] == undefined){
                   $("#step3").css('display', 'block');

                   $('#step1').css('display', 'none');
                   $('#step1_back').css('display', 'block');

                   $(".subtype").css('display', 'none');

                   $('#step2').css('display', 'none');
                   $('#step2_back').css('display', 'none');

                   $(".subtype"+event.target.value).css('display', 'block');
                } else {

                    $('#step1').css('display', 'none');
                    $('#step1_back').css('display', 'block');
                    
                    $('#step2').css('display', 'block');
                    $(".subtype").css('display', 'none');
                    $("#step3").css('display', 'none');
                    $(".subtype"+event.target.value).css('display', 'block');
                }
                
              }
            },
            step2: function (event) {
              if (event) {
                $('#step1').css('display', 'none');
                $('#step2').css('display', 'none');
                $('#step2_back').css('display', 'block');
                $('#step3').css('display', 'block');
              }
            },
            steppreload: function (event){
                if (event) {
                 $('.preload').css('display', 'block');
                 $('.only_type').css('display', 'none');
                 $('.only_date').css('display', 'none');
                 $('.type_subtype_date').css('display', 'none');
                 $('#step_preload').css('display', 'none');
                }
            },
            step1back: function (event){
                if (event) {
                 $('#step1').css('display', 'block');
                 $('#step1_back').css('display', 'none');
                 $('#step2').css('display', 'none');
                 $("#step3").css('display', 'none');
                }
            },
            step2back: function (event){
                if (event) {
                 $('#step2').css('display', 'block');
                 $('#step2_back').css('display', 'none');
                 $('#step3').css('display', 'none');
                }
            },
            //Decide an action accordign to the value it recovers from the menu
            selected_item(value){
                this.closeDetails();
                var vm = this;

                vm.markers = [];
                vm.mySubGroup.clearLayers();
                vm.pointsInterest = [];
                vm.myInterestGroup.clearLayers();
                console.log('SELECTED');
                 if(value == true){
                     vm.getAllMarkers();
                 }
                //
                this.selected = [];

                if (value.length === 2){
                    //Show the map centered in one marker
                    if (value[0] === 'odour'){
                        this.isZone = false;
                        this.getOneMarker(this.token, value[1], false);
                    //Show the map centered in the new created odour
                    } else if (value[0] === 'new') {
                        this.isZone = false;
                        this.getOneMarker(this.token, value[1], true);
                    //Show the zone selected
                    }else{
                        if (vm.polygon){
                            console.log('1');
                            vm.map.removeLayer(vm.polygon);
                            
                        }
                        this.user_zones = false;
                        this.getOneZone(this.token, value[1]);
                        this.getMapPointsOfInterest(value[1]);
                        this.isZone = true;
                        this.show_nav = true;
                    }
                } else {
                    
                    //Close all opened layers
                    if (value === 'reset'){
                        this.isZone = false;
                        this.closeDetails();
                        if (vm.polygon){
                            vm.map.removeLayer(vm.polygon);
                        }
                        (vm.map).setView(new L.LatLng(this.current_pos.lat, this.current_pos.lng), 18);
                    //Close the odour details layers
                    } else if (value === 'close_details') {
                        
                        this.visible_filters = false;
                        this.visible = false;
                        this.closeDetails();
                    } else if (value === 'login') {
                        this.visible_filters = false;
                        this.visible = false;
                        this.closeDetails();

                        if (navigator.geolocation){
                            var options = {
                                enableHighAccuracy: true,
                                timeout: 60000,
                                maximumAge: Infinity
                            };

                            function error(err) {
                                console.warn(`ERROR(${err.code}): ${err.message}`);
                            }

                            navigator.geolocation.getCurrentPosition(function(location) {
                                vm.current_pos = new L.LatLng(location.coords.latitude, location.coords.longitude);
                            }, error, options)
                        }
                        (vm.map).setView(new L.LatLng(this.current_pos.lat, this.current_pos.lng), 18);


                        //Show filters layer
                    } else if (value === 'goHome') {
                        this.isZone = false;
                        this.closeDetails();
                        if (vm.polygon){
                            vm.map.removeLayer(vm.polygon);
                        }
                        if (navigator.geolocation){
                            var options = {
                                enableHighAccuracy: true,
                                timeout: 60000,
                                maximumAge: Infinity
                            };

                            function error(err) {
                                console.warn(`ERROR(${err.code}): ${err.message}`);
                            }

                            navigator.geolocation.getCurrentPosition(function(location) {
                                vm.current_pos = new L.LatLng(location.coords.latitude, location.coords.longitude);
                            }, error, options)
                        }
                        (vm.map).setView(new L.LatLng(this.current_pos.lat, this.current_pos.lng), 18);
                    } else {
                        this.visible_filters = value;
                    }
                }
            },

            //Show and charge one odour detail information
            showDetails(event) {
                console.log('showDetails');
                var vm = this;

                vm.display_div = 'visible';
                vm.visible = true;
                vm.show_nav = true;
                vm.author = false;
                vm.confirmed = false;

                var oddour_id = event.layer.id;
                axios.get('api/odor/' + oddour_id, {
                    token: vm.token,
                }).then(response => {

                    var data = response.data.object;

                    vm.oddour.id = data.id;
                    vm.oddour.name = data.name;
                    vm.oddour.parent_type = data.id_odor_parent_type;
                    vm.oddour.other = data.other;
                    vm.oddour.type = data.id_odor_type;
                    vm.oddour.type_name = this.$t('FILTER.SUBTYPE.' + data.id_odor_type);
                    vm.oddour.parent_type_name = this.$t('FILTER.TYPE.' + data.id_odor_parent_type);
                    vm.oddour.intensity = data.id_odor_intensity;
                    vm.oddour.annoy = data.id_odor_annoy;
                    vm.oddour.loc = data.location.place;

                    var now = new Date(data.created_at.replace(/ /g,"T"));
                    var userAgent = window.navigator.userAgent;

                    if (userAgent.match(/iPad/i) || userAgent.match(/iPhone/i)) {
                        var nowUtc = new Date( now.getTime() + (-(0) * 60000));
                    }
                    else {
                        var nowUtc = new Date( now.getTime() + (-(now.getTimezoneOffset()) * 60000));
                    }

                    vm.oddour.date = nowUtc.toLocaleString("en-GB");
                    vm.oddour.description = data.description;
                    vm.oddour.confirmations = data.confirmations;
                    vm.oddour.comments = data.comments.length;
                    vm.oddour.user = this.$t('MENU.USER') + data.user.id;


                    if( localStorage.getItem('auth-token') != null ) { this.isLoggedIn = true }
                    if( this.isLoggedIn ){
                        //If logged in save the user name in the data
                        vm.user = JSON.parse( localStorage.getItem('user') );
                        vm.token = localStorage.getItem('auth-token');

                        axios.post('/api/odor/' + data.id + '/is-confirmed/' + vm.user.id, {
                            token: vm.token,
                        }).then(response => {

                            if(vm.user.id == data.id_user){
                                vm.author = true;
                            }
                            vm.confirmed = response.data.confirmed;

                        }).catch(error => {
                        });
                    }


                }).catch(error => {
                });

            },

            //Get all point of interest
            // getPointsOfInterest(){
            //     var vm = this;
            //
            //     vm.pointsInterest = [];
            //     vm.myInterestGroup.clearLayers();
            //
            //     vm.agricultureIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/agriculture-spot.png'});
            //     vm.foodIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/food-spot.png'});
            //     vm.industryIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/industry-spot.png'});
            //     vm.unknownIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/unknown-spot.png'});
            //     vm.urbanIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/urban-spot.png'});
            //     vm.wasteIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/waste-spot.png'});
            //     vm.waterIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/water-spot.png'});
            //
            //     axios.get('api/points-of-interest/list')
            //         .then(response => {
            //
            //             var points = response.data.content;
            //             var marker;
            //
            //             //Cargar la informacion
            //             points.forEach(function (point){
            //                 vm.pointsInterest.push(point);
            //             });
            //
            //             vm.pointsInterest.forEach(function(m){
            //                 switch (m.icon){
            //                     case 'agriculture-spot.png':
            //                         marker = L.marker([m.latitude, m.longitude], {icon: vm.agricultureIcon}).addTo(vm.myInterestGroup).bindPopup(m.name).openPopup();
            //                         marker.id = m.id;
            //                         break;
            //                     case 'food-spot.png':
            //                         marker = L.marker([m.latitude, m.longitude], {icon: vm.foodIcon}).addTo(vm.myInterestGroup).bindPopup(m.name).openPopup();
            //                         marker.id = m.id;
            //                         break;
            //                     case 'industry-spot.png':
            //                         marker = L.marker([m.latitude, m.longitude], {icon: vm.industryIcon}).addTo(vm.myInterestGroup).bindPopup(m.name).openPopup();
            //                         marker.id = m.id;
            //                         break;
            //                     case 'unknown-spot.png':
            //                         marker = L.marker([m.latitude, m.longitude], {icon: vm.unknownIcon}).addTo(vm.myInterestGroup).bindPopup(m.name).openPopup();
            //                         marker.id = m.id;
            //                         break;
            //                     case 'urban-spot.png':
            //                         marker = L.marker([m.latitude, m.longitude], {icon: vm.urbanIcon}).addTo(vm.myInterestGroup).bindPopup(m.name).openPopup();
            //                         marker.id = m.id;
            //                         break;
            //                     case 'waste-spot.png':
            //                         marker = L.marker([m.latitude, m.longitude], {icon: vm.wasteIcon}).addTo(vm.myInterestGroup).bindPopup(m.name).openPopup();
            //                         marker.id = m.id;
            //                         break;
            //                     case 'water-spot.png':
            //                         marker = L.marker([m.latitude, m.longitude], {icon: vm.waterIcon}).addTo(vm.myInterestGroup).bindPopup(m.name).openPopup();
            //                         marker.id = m.id;
            //                         break;
            //                     default:
            //                         marker = L.marker([m.latitude, m.longitude], {icon: vm.waterIcon}).addTo(vm.myInterestGroup).bindPopup(m.name).openPopup();
            //                         marker.id = m.id;
            //                 }
            //             })
            //
            //         }).catch(error => {
            //         });
            //
            // },

            getMapPointsOfInterest(zone){
                console.log('getMapPointsOfInterest');
                var vm = this;

                vm.pointsInterest = [];
                vm.myInterestGroup.clearLayers();

                vm.agricultureIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/agriculture-spot.png'});
                vm.foodIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/food-spot.png'});
                vm.industryIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/industry-spot.png'});
                vm.unknownIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/unknown-spot.png'});
                vm.urbanIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/urban-spot.png'});
                vm.wasteIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/waste-spot.png'});
                vm.waterIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/water-spot.png'});
                vm.niceIcon= new vm.InterestIcon({iconUrl: '../../img/odour-origin/nice-spot.png'});


                axios.post('api/points-of-interest/list',{
                    zone: zone,
               }).then(response => {
                   var points = response.data.content;
                   var marker;

                   //Cargar la informacion
                   points.forEach(function (point){
                       vm.pointsInterest.push(point);
                   });

                   vm.pointsInterest.forEach(function(m){
                       switch (m.icon){
                           case 'agriculture-spot.png':
                               marker = L.marker([m.latitude, m.longitude], {icon: vm.agricultureIcon}).addTo(vm.myInterestGroup).bindPopup(m.name);
                               marker.id = m.id;
                               break;
                           case 'food-spot.png':
                               marker = L.marker([m.latitude, m.longitude], {icon: vm.foodIcon}).addTo(vm.myInterestGroup).bindPopup(m.name);
                               marker.id = m.id;
                               break;
                           case 'industry-spot.png':
                               marker = L.marker([m.latitude, m.longitude], {icon: vm.industryIcon}).addTo(vm.myInterestGroup).bindPopup(m.name);
                               marker.id = m.id;
                               break;
                           case 'unknown-spot.png':
                               marker = L.marker([m.latitude, m.longitude], {icon: vm.unknownIcon}).addTo(vm.myInterestGroup).bindPopup(m.name);
                               marker.id = m.id;
                               break;
                           case 'urban-spot.png':
                               marker = L.marker([m.latitude, m.longitude], {icon: vm.urbanIcon}).addTo(vm.myInterestGroup).bindPopup(m.name);
                               marker.id = m.id;
                               break;
                           case 'waste-spot.png':
                               marker = L.marker([m.latitude, m.longitude], {icon: vm.wasteIcon}).addTo(vm.myInterestGroup).bindPopup(m.name);
                               marker.id = m.id;
                               break;
                           case 'water-spot.png':
                               marker = L.marker([m.latitude, m.longitude], {icon: vm.waterIcon}).addTo(vm.myInterestGroup).bindPopup(m.name);
                               marker.id = m.id;
                               break;
                           case 'nice-spot.png':
                               marker = L.marker([m.latitude, m.longitude], {icon: vm.niceIcon}).addTo(vm.myInterestGroup).bindPopup(m.name);
                               marker.id = m.id;
                           default:
                               marker = L.marker([m.latitude, m.longitude], {icon: vm.waterIcon}).addTo(vm.myInterestGroup).bindPopup(m.name);
                               marker.id = m.id;
                       }
                   })

               }).catch(error => {

               });

            },

            //Display the zone polygon
            displayPolygon(){
                 console.log('displayPolygon');
                var vm = this;

                vm.polygon = L.polygon(vm.zone[1]).bindPopup(vm.zone[0][0]).addTo(vm.map);
                vm.map.fitBounds(vm.polygon.getBounds());
            },

            //Close odour detail information layer
            closeDetails(){
                var vm = this;
                vm.visible = false;
                vm.show_nav = true;
                vm.author = false;
                vm.confirmed = false;
                vm.oddour.name = '';
                vm.oddour.type = '';
                vm.oddour.type_name = '';
                vm.oddour.other = '';
                vm.oddour.intensity = 1;
                vm.oddour.annoy = 1;
                vm.oddour.loc = '';
                vm.oddour.date = '';
                vm.oddour.description = '';
                vm.oddour.confirmations = '';
                vm.oddour.comments = '';
                vm.oddour.user ='';
            },

            //Show odour comments layer
            showComments(value){
                if (value === 'comments'){
                    this.show_comments = true;
                } else if (value === 'delete'){
                    this.closeDetails();
                    var vm = this;

                    vm.markers = [];
                    vm.mySubGroup.clearLayers();
                    vm.pointsInterest = [];
                    vm.myInterestGroup.clearLayers();
                     console.log('SHOW COMMENT');
                    vm.getAllMarkers();
                    this.selected = [];
                }
            },

            //Apply odour types filters
            applyFilters(value){
                this.filters = this.selected;
                this.visible_filters = false
            },

            //Get zone information
            getOneZone(token, zone) {
                console.log('getOneZone');
                var vm = this;
                vm.zone = [];

                axios.post('api/zone/' + zone, {
                    token: token,
                    user: vm.user.id
                }).then(response => {
                    var name = [];
                    name.push(response.data.object.name);
                    var data = response.data.object.points;

                    var point = [];

                    data.forEach(function(points){
                        var coord = [];
                        coord.push(parseFloat(points.latitude));
                        coord.push(parseFloat(points.longitude));

                        point.push(coord);
                    });

                    vm.zone.push(name);
                    vm.zone.push(point);

                    this.getAllMarkers(zone);

                    
                    this.displayPolygon();

                }).catch(error => {
                });

            },

           getZoneMarkers(id){

            console.log('getZoneMarkers');
               var vm = this;

               vm.markers = [];
               

               axios.post('api/odor/list', {
                   zone: id
                   }).then(response => {

                   var points = response.data.content;

                   vm.markers = [];
                   vm.mySubGroup.clearLayers();
                   //Cargar la informacion
                   points.forEach(function (point){
                       if (point.location) {
                           vm.markers.push(point);
                       }
                   });
                   console.log(vm.markers);
                   //Añadir al mapa el array de localizaciones
                   vm.markers.forEach(function(m){
                   
                       switch (m.color){

                           case 1:
                               marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.greenIcon}).addTo(vm.mySubGroup);
                               marker.id = m.id;
                               break;
                           case 2:
                               marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.lightGreenIcon}).addTo(vm.mySubGroup);
                               marker.id = m.id;
                               break;
                           case 3:
                               marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.yellowIcon}).addTo(vm.mySubGroup);
                               marker.id = m.id;
                               break;
                           case 4:
                               marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.orangeIcon}).addTo(vm.mySubGroup);
                               marker.id = m.id;
                               break;
                           case 5:
                               marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.lightPinkIcon}).addTo(vm.mySubGroup);
                               marker.id = m.id;
                               break;
                           case 6:
                               marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.pinkIcon}).addTo(vm.mySubGroup);
                               marker.id = m.id;
                               break;
                           case 7:
                               marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.purpleIcon}).addTo(vm.mySubGroup);
                               marker.id = m.id;
                               break;
                            case 8:
                               marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.purpleIcon}).addTo(vm.mySubGroup);
                               marker.id = m.id;
                               break;
                            case 9:
                               marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.blackIcon}).addTo(vm.mySubGroup);
                               marker.id = m.id;
                               break;
                           default:
                               marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.greenIcon}).addTo(vm.mySubGroup);
                               marker.id = m.id;
                       }
                   });

                   }).catch(error => {

               });
           },

            //Get and center one marker and if, is the new created, update the map
            getOneMarker(token, mark, add){
                var vm = this;

                vm.display_div = 'visible';
                vm.visible = true;
                vm.show_nav = true;
                vm.author = false;
                vm.confirmed = false;

//                if (add === true){
//                    vm.mySubGroup.clearLayers();
//                    vm.getAllMarkers();
//                }

                axios.get('../../api/odor/' + mark, {
                    token: token,
                    }).then(response => {
                        var data = response.data.object;

                        (vm.map).setView([Number(data.location.latitude) - 0.0005, data.location.longitude], 18);

                        vm.oddour.id = data.id;
                        vm.oddour.name = data.name;
                        vm.oddour.parent_type = data.id_odor_parent_type;
                        vm.oddour.type = data.id_odor_type;
                        vm.oddour.type_name = this.$t('FILTER.SUBTYPE.' + data.id_odor_type);
                        vm.oddour.other = data.other;
                        vm.oddour.parent_type_name = this.$t('FILTER.TYPE.' + data.id_odor_parent_type);
                        vm.oddour.intensity = data.id_odor_intensity;
                        vm.oddour.annoy = data.id_odor_annoy;
                        vm.oddour.loc = data.location.place;

                        var now = new Date(data.created_at.replace(/ /g,"T"));
                        var userAgent = window.navigator.userAgent;

                        if (userAgent.match(/iPad/i) || userAgent.match(/iPhone/i)) {
                            var nowUtc = new Date( now.getTime() + (-(0) * 60000));
                        }
                        else {
                            var nowUtc = new Date( now.getTime() + (-(now.getTimezoneOffset()) * 60000));
                        }

                        vm.oddour.date = nowUtc.toLocaleString("en-GB");
                        vm.oddour.description = data.description;
                        vm.oddour.confirmations = data.confirmations;
                        vm.oddour.comments =  data.comments.length;
                        vm.oddour.user = this.$t('MENU.USER') + data.user.id;

                        axios.post('/api/odor/' + data.id + '/is-confirmed/' + vm.user.id, {
                            token: vm.token,
                        }).then(response => {
                            if(vm.user.id == data.id_user){
                                vm.author = true;
                            }
                            vm.confirmed = response.data.confirmed;
                        }).catch(error => {
                        });

                }).catch(error => {
                });
            },

            //Get all the markers and display on the map
            getAllMarkers(id){
                
                var vm = this;
                var marker;
                if(id != ''){
                  axios.post('api/odor/list', {
                   zone: id
                   }).then(response => {

                        var points = response.data.content;

                        vm.markers = [];
                        vm.mySubGroup.clearLayers();
                        //Cargar la informacion
                        points.forEach(function (point){
                            if (point.location){
                                vm.markers.push(point);
                            }
                        });

                        //Añadir al mapa el array de localizaciones
                        vm.markers.forEach(function(m){
                            
                            if(m.id_odor_type == 9){
                                marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.blackIcon}).addTo(vm.mySubGroup);
                                   marker.id = m.id;
                            } else {

                            switch (m.color){

                                case 1:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.greenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 2:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.lightGreenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 3:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.yellowIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 4:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.orangeIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 5:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.lightPinkIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 6:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.pinkIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 7:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.purpleIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 8:
                                   marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.purpleIcon}).addTo(vm.mySubGroup);
                                   marker.id = m.id;
                                   break;
                                case 9:
                                   marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.blackIcon}).addTo(vm.mySubGroup);
                                   marker.id = m.id;
                                   break;
                                default:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.greenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                }
                            }
                        })
                    }).catch(error => {
                    });  
                } else {
                    axios.post('api/odor/list').then(response => {

                        var points = response.data.content;

                        vm.markers = [];
                        vm.mySubGroup.clearLayers();
                        //Cargar la informacion
                        points.forEach(function (point){
                            if (point.location){
                                vm.markers.push(point);
                            }
                        });

                        //Añadir al mapa el array de localizaciones
                        vm.markers.forEach(function(m){
                            
                            if(m.id_odor_type == 9){
                                marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.blackIcon}).addTo(vm.mySubGroup);
                                   marker.id = m.id;
                            } else {

                            switch (m.color){

                                case 1:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.greenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 2:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.lightGreenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 3:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.yellowIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 4:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.orangeIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 5:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.lightPinkIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 6:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.pinkIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 7:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.purpleIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 8:
                                   marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.purpleIcon}).addTo(vm.mySubGroup);
                                   marker.id = m.id;
                                   break;
                                case 9:
                                   marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.blackIcon}).addTo(vm.mySubGroup);
                                   marker.id = m.id;
                                   break;
                                default:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.greenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                }
                            }
                        })
                    }).catch(error => {
                    });
                }
                

            },

            //Get odour list according odour type
            getFilteredMarkers(id){
                console.log('getFilteredMarkers');
                var vm = this;
                var marker;

                axios.post('api/odor/list', {
                    type: id
                    }).then(response => {
                        var points = response.data.content;

                        //Cargar la informacion
                        points.forEach(function (point){
                            if (point.location) {
                                vm.markers.push(point);
                            }
                        });

                        vm.greenIcon = new vm.OdourIcon({iconUrl: '../../img/spot1.png'});
                        vm.lightGreenIcon = new vm.OdourIcon({iconUrl: '../../img/spot2.png'});
                        vm.yellowIcon = new vm.OdourIcon({iconUrl: '../../img/spot3.png'});
                        vm.orangeIcon = new vm.OdourIcon({iconUrl: '../../img/spot4.png'});
                        vm.lightPinkIcon = new vm.OdourIcon({iconUrl: '../../img/spot5.png'});
                        vm.pinkIcon = new vm.OdourIcon({iconUrl: '../../img/spot6.png'});
                        vm.purpleIcon = new vm.OdourIcon({iconUrl: '../../img/spot7.png'});

                        //Añadir al mapa el array de localizaciones
                        vm.markers.forEach(function(m){

                            switch (m.color){
                                case 1:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.greenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 2:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.lightGreenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 3:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.yellowIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 4:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.orangeIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 5:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.lightPinkIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 6:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.pinkIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 7:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.purpleIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                default:
                                    marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.greenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                            }
                            stop();

                        });

                }).catch(error => {
                    });
            },

            //Clear filter layer and charge the new one
            chargeFilteredMarkers() {

                var vm = this;

                vm.markers = [];
                vm.mySubGroup.clearLayers();

                if (vm.filters.length === 0 && vm.filters !== null){
                     console.log('CHANGE FILTER');
                    vm.getAllMarkers();

                } else {
                    vm.getFilteredMarkers(vm.filters);
                }

                vm.map.addLayer(vm.markers_layout );
                vm.map.addLayer(vm.mySubGroup);
            }
        },
    }
</script>

<style lang="scss">
    .v-dialog {
        border-radius:20px !important;
    }
    ul.points, ul.spots {
        padding-left:0;
    }
    ul.points li {
        margin: 0;
        padding: 2px 0 5px 30px;
        list-style: none;
        background-repeat: no-repeat;
        background-position: left top;
        background-size: 20px;
    }
    ul.spots li {
        margin: 0;
        padding: 2px 0 5px 50px;
        list-style: none;
        background-repeat: no-repeat;
        background-position: left top;
        background-size: 40px;
        height:40px;
    }
    .leaflet-top {
        top: 46px!important;
    }
    .leaflet-popup {
        margin-bottom: -5px!important;
        margin-left: 3px!important;
    }
    .container {
        max-width: 100%!important;
    }
    .marker-cluster div {
        font: none;
        font-size: 15px;
        color: white;
        font-weight: bold;
    }
    .marker-cluster-small div, .marker-cluster-medium div, .marker-cluster-large div {
        background-color: rgba(28, 173, 137, 0.8);
    }
    .marker-cluster-small, .marker-cluster-medium, .marker-cluster-large {
        background-color: rgba(28, 173, 137, 0.3);
    }
</style>
<style scoped lang="scss">
    @import './../../sass/app.scss';
    .date{
        margin-top:0px;
    }
    .date[data-v-49c1db04] div{
          box-sizing: border-box;
        width: 100%;
        height: 30px;
    }
    .date[data-v-49c1db04] > div{
          box-sizing: border-box;
        width: 100%;
        height: 30px;
    }

   .date > div{
        box-sizing: border-box;
        width: 100%;
        height: 30px;
    }
    #step3{
        height: 500px;
    }
    .date_init{
        width:100%;
        position:relative;
        float:left;
        margin-top:20px;
    }
    .label{
        display: inline-block;
        margin-bottom: .5rem;
        font-weight: bold;
        font-size: 16px;
    }
    .close{
        position: fixed;
        bottom: 313px;
        right: 13px;
        z-index: 1;
        padding: 10px;
    }
    .info_button{
        position: absolute;
        top: 100px;
        right: 9px;
        z-index: 401;
        cursor: pointer;
    }
    .v-dialog__content {
        align-items: flex-end;
        display: flex;
        height: 100%;
        justify-content: center;
        left: 0;
        pointer-events: none;
        position: fixed;
        transition: .2s cubic-bezier(.25,.8,.25,1);
        width: 100%;
        z-index: 6;
        outline: none;
        top: 0;
    }
    .apply{
        color: $primary-font-color;
        text-transform: uppercase;
        text-align: center;
        padding-bottom: 10px;
        font-size: 15px!important;
        font-weight: 600;
        cursor: pointer;
    }
    .apply-btn{
        text-align: center;
        bottom: -20px;
        width: 100%;
        left: 0;
        background-color: white;
    }
    .apply-btn.map{
        margin-bottom: 20px;
    }
    .map-btn{
        color: $primary-font-color;
        text-transform: uppercase;
        text-align: center;
        font-size: 15px!important;
        font-weight: 600;
    }
    .v-dialog:not(.v-dialog--fullscreen) {
        max-height: 400px;
    }
    .separator {
        border-bottom: 1px solid #dadada;
        margin-top: 5px;
    }
    .title{
        text-align: center;
        font-size: 14px!important;
        margin-top: 16px;
        font-weight: 600!important;
    }
    .info-close{
        position: absolute;
        right: 14px;
        top: 4px;
        padding: 10px;
        cursor: pointer;
    }
    .my-scrollbar{
        padding: 15px;
    }
    .v-input--selection-controls:not(.v-input--hide-details) .v-input__slot {
        margin-bottom: 0px;
    }
    .v-input__control {
        height: 14px!important;
    }
    .v-input{
        height: 20px!important;
    }
    .zone-msg{
        z-index: 401;
        position: absolute;
        width: 100%;
        background-color: #f7f7f7;
        padding: 10px;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.21);
        height: 42px;
        margin-bottom: 0px!important;
    }
    .zone-msg p{
        font-size: 10px;
        color: #6b7c93;
        width: 80%;
        padding: 0;
        margin: 0;
        margin-top: 5px;
    }
    .zone-msg span{
        font-size: 12px;
        font-weight: bold;
        color: black;
        margin-left: 10px;
    }
    .map-info{
        float: right;
        margin-top: -31px;
        padding: 10px 20px;
    }
    .back_toolbar{
        height: 64px;
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.15);
        position: fixed;
        top: 0px;
        width: 100%;
        right: 0;
        z-index: 4;
        background: white;
        cursor: pointer;
    }
    .back_toolbar img{
        padding: 19px 0 0 23px;
    }
    .text-md-center{
        width: 100%;
        text-align: center;
        margin: 0;
        line-height: 95%;
        font-size: 20px;
        font-weight: 600;
        margin-top: -19px;
    }
    .comment_list{
        padding-top: 60px;
    }
    .content {
        position: absolute;
        top: 0;
        background: white;
        width: 100%;
        height: 100%;
    }


    .radio-input {
        position: relative;
        height: 45px;
    }
    .radio-input input[type=radio] {
        position: absolute;
        visibility: hidden;
    }
    .radio-input label{
        display: block;
        position: relative;
        font-size: 16px;
        padding: 10px 35px 10px 5px;
        z-index: 9;
        cursor: pointer;
        -webkit-transition: all 0.25s linear;
    }
    .radio-input .check{
        display: block;
        position: absolute;
        border: 2px solid #BAC5D4;
        border-radius: 100%;
        height: 25px;
        width: 25px;
        top: 10px;
        right: 5px;
        z-index: 5;
        transition: border .25s linear;
        -webkit-transition: border .25s linear;
    }
    .radio-input .check .inside {
        display: block;
        position: absolute;
        border-radius: 100%;
        height: 21px;
        width: 21px;
        top: 0;
        left: 0;
        background-color:#384659;
        //background-image: url(../../../../img/general/user-mini.svg);
        background-size:cover;
        margin: auto;
        opacity:0;
        transition: opacity 0.15s linear;
        -webkit-transition: opacity 0.15s linear;
    }
    input[type=radio]:checked ~ .check .inside{
        opacity: 1;
    }
    .date_icon{
        float: right;
        position: absolute;
        right: 0px;
        bottom: -9px;
    }
    
.result{
  display: none;
}
.filter{
    position: relative;
    max-height: 40px;
    margin-right: 7px;
    margin-top: 3px;
    margin: 0 auto;
    display: block;
    margin-bottom:10px;
}
.mt20{
    margin-top: 20px;
    text-align:center;
}
a{
    color: #000;
}
.step1_date{
    position: relative;
    display: block;
    height: 180px;
}

</style>
