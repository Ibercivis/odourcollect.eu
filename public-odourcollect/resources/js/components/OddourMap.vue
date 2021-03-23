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
                <v-card flat style="height: 100%;">
                    <h2 color="primary" class="subheading title">{{$t('UI.BOTTOM_SHEET.TITLE_FILTER')}}
                        <div class="info-close" @click="visible_filters = false; verifymap()"><img :src="close_icon"></div>
                    </h2>
                    <div class="separator"></div>

                    <vue-scrollbar class="my-scrollbar" ref="Scrollbar">
                        <div class="scroll-me sub-container">
                            <div class="preload">
                               <div class="row">								

                                                                  <div class="col-12 pb-4 sub-container">
                                        <div class="row">
																						<div class="col-4">
																							<div class="map-btn pb-4">Date range</div>
																						</div>
                                            <div class="col-4 pl-5 pr-5">
                                                <datepicker class="date" :value="date_init" v-model="date_init"></datepicker>
                                            </div>
                                            <div class="col-4 pl-5 pr-5">
                                                    <datepicker class="date" :value="date_end" v-model="date_end"></datepicker>
                                            </div>
                                        </div>
                                    </div>							


									<div class="col-12">
                                        <div class="row">
                                            <div class="col-4 pb-4">
                                                <div class="map-btn pb-4">Type & Subtype:</div>
                                            </div>
                                            <div class="col-4 pr-5 pl-5">
                                                <select title="Select an item"  class="myselect" v-model="typeSelected" style="border: 1px solid #555;" @change="typeSelectedChange()">										
                                                    <option v-for="it in item" v-bind:value="it.id" :selected="typeSelected === it.id">
                                                        {{ it.name_t }}
                                                    </option>
                                                </select>								
                                            </div>
                                            <div class="col-4 pl-5 pr-5">
                                                <select title="Select an item" class="myselect" v-model="subtypeSelected" style="border: 1px solid #555;" v-if="typeSelected">										
                                                    <option v-for="values in getEligibleSubTypes()" v-bind:value="values.id" :selected="subtypeSelected === values.id">
                                                        {{ values.name }}
                                                    </option>
                                                </select>				
                                            </div>
										</div>			
									</div>

                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="map-btn pb-5">Intensity & Hedonic tone:</div><br /><br/><br />
                                            </div>
                                            <div class="col-4 pl-5 pr-5">
                                                <vue-slider class="pl-3 pr-3" ref="slider" v-model="intensity"  v-bind="intensitySliderOptions"></vue-slider>
                                            </div>                                        
                                            <div class="col-4 pl-5 pr-5">
                                                 <vue-slider class="pl-3 pr-3" ref="annoySlider" v-model="annoy"  v-bind="annoySliderOptions" />
                                             </div>                                        
                                        </div>
                                    </div>
			<div class="col-12">
				<div class="row">
                                        <div class="apply-btn apply" @click="clearFilters()" id="clearButton">Clear Filters X</div>
</div>
                                    </div>



                               </div>
                            </div>
                        </div>
                    </vue-scrollbar>
       	
                    <div class="apply-btn">
                        <v-divider style="margin-top: 0px;"></v-divider>
                        <h2 class="apply" @click="applyFilters">{{$t('UI.BOTTOM_SHEET.APPLY')}}</h2>
                    </div>

                </v-card>
            </v-dialog>
        </v-layout>

    </div>

</template>

<script>


    import OddourDetail from '../components/OddourDetail.vue';
    import OddourComment from '../components/OddourComment.vue';
    import OddourBottomNav from '../components/OddourBttomNav.vue'
    import VueScrollbar from 'vue2-scrollbar';
    import store from '../store/store';
    import UserZones from '../views/User/UserZones.vue';
    import Datepicker from 'vuejs-datepicker';
    import VueSlider from 'vue-slider-component';
    import 'vue-slider-component/theme/default.css';



    
    export default {
        components: {OddourDetail, OddourBottomNav, VueScrollbar, OddourComment, UserZones, Datepicker, VueSlider},

        props: {
            singleMarker: { type: Boolean },
        },

        data(){
            return {
                date_init: "",
                date_end: "",
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
                check_icon:  '../../img/general/check.png',
                list_icon:  '../../img/general/list.svg',
                arrow_icon: '../../svg/next.svg',
                info: false,
                info_map: false,
                oddourTypes: [],
                oddourSubTypes: [],
                selected: [],
				typeSelected: 0,
				subtypeSelected: 0,
                selected_new: [],
                selected2: [],
                options: {
                    activeColor: '#00b187'
                },
                isZone: false,
                item: [],
                show_comments: false,
                polygon: '',
                user_zones: false,
                intensity:[0,6],
                annoy:[-4,4],
                intensitySliderOptions:{
                    dotSize: 14,
                    width: 'auto',
                    height: 1,
                    contained: false,
                    direction: 'ltr',
                    data: null,
                    min: 0,
                    max: 6,
                    interval: 1,
                    disabled: false,
                    clickable: true,
                    duration: 0.5,
                    adsorb: false,
                    lazy: false,
                    tooltip: 'active',
                    tooltipPlacement: 'top',
                    tooltipFormatter: void 0,
                    useKeyboard: false,
                    keydownHook: null,
                    dragOnClick: false,
                    enableCross: true,
                    fixed: false,
                    minRange: void 0,
                    maxRange: void 0,
                    order: true,
                    marks: true,
                    dotOptions: void 0,
                    process: true,
                    dotStyle: void 0,
                    railStyle: void 0,
                    processStyle: void 0,
                    tooltipStyle: void 0,
                    stepStyle: void 0,
                    stepActiveStyle: void 0,
                    labelStyle: void 0,
                    labelActiveStyle: void 0,
                },
                 annoySliderOptions:{
                    dotSize: 14,
                    width: 'auto',
                    height: 1,
                    contained: false,
                    direction: 'ltr',
                    data: null,
                    min: -4,
                    max: 4,
                    interval: 1,
                    disabled: false,
                    clickable: true,
                    duration: 0.5,
                    adsorb: false,
                    lazy: false,
                    tooltip: 'active',
                    tooltipPlacement: 'top',
                    tooltipFormatter: void 0,
                    useKeyboard: false,
                    keydownHook: null,
                    dragOnClick: false,
                    enableCross: true,
                    fixed: false,
                    minRange: void 0,
                    maxRange: void 0,
                    order: true,
                    marks: true,
                    dotOptions: void 0,
                    process: true,
                    dotStyle: void 0,
                    railStyle: void 0,
                    processStyle: void 0,
                    tooltipStyle: void 0,
                    stepStyle: void 0,
                    stepActiveStyle: void 0,
                    labelStyle: void 0,
                    labelActiveStyle: void 0,
                },
            }
        },

        computed: {
            //Takes from the store if we are in the home page or not, in order to change the toolbar
            isHome: function () {
                return this.$store.state.isHome;
            },
	
		},
        watch:{

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
            verifymap(){
               var vm = this;
                 /*if(vm.markers == ''){
                     vm.getAllMarkers();
                 }*/
            },
            //Only type filter
            only_type: function(event){
                $(".preload").css('display', 'none');
                $(".only_date").css('display', 'none');
                $(".only_type").css('display', 'block');
                $('#step_preload').css('display', 'block');
            },
            steppreload: function (event){
                if (event) {
                 $('.preload').css('display', 'block');
                 $('.only_type').css('display', 'none');
                 $('.type_subtype').css('display', 'none');
                 $(".only_date").css('display', 'none');
                 $('#step_preload').css('display', 'none');
                }
            },
            //Type Subtype//
            type_subtype: function(event){
                $(".preload").css('display', 'none');
                $(".only_date").css('display', 'none');
                 $(".only_type").css('display', 'none');
                $(".type_subtype").css('display', 'block');
                $('#step_preload').css('display', 'block');
            },
             
            ///DATE
            only_date: function(event){
                $(".preload").css('display', 'none');
                $(".only_type").css('display', 'none');
                $(".type_subtype").css('display', 'none');
                $(".only_date").css('display', 'block');
                $('#step_preload').css('display', 'block');
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
                this.selected = [];
                this.selected2 = [];

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

            step1(value) {
               setTimeout(() => {
                   if(this.selected.length == 1){
                        $(".subtype").css('display', 'none');
                        $(".subtype"+value).css('display', 'block');
                        console.log('si');
                       
                   } else {
                       this.selected2 = [];
                       $(".subtype").css('display', 'none');
                         console.log('no');
                   }
            }, 1000)
               
            },
            //Show and charge one odour detail information
            showDetails(event) {
                console.log('showDetails3');
                var vm = this;
		console.log(event);

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

                    var now = new Date(data.published_at.replace(/ /g,"T"));
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
			
			getEligibleSubTypes() {
				if(this.typeSelected){
					return this.oddourSubTypes[this.typeSelected-1];
                }else
					return [];
  
			},

            typeSelectedChange(){
                this.subtypeSelected = 0;
            },

            clearFilters(){
                this.typeSelected = 0;
                this.subtypeSelected = 0;
                this.intensity=[0,6];
                this.annoy=[-4,4];
                this.date_init = null;
                this.date_end = null;
                this.getAllMarkers();
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
                    this.selected2 = [];
                }
            },

            //Apply odour types filters
            applyFilters(value){
			    console.log("applyfilters");
                //this.filters = this.typeSelected;

                console.log(this.filters);
                this.visible_filters = false
                //Obtener los olores segun el filtro
                if (this.singleM === false){
                    this.chargeFilteredMarkers();
                }

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
                               marker = L.marker([m.latitude, m.longitude], {icon: vm.yellowIcon}).addTo(vm.mySubGroup);
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

                        var now = new Date(data.published_at.replace(/ /g,"T"));
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
                            if (point.latitude){
                                vm.markers.push(point);
                            }
                        });
				//Añadir al mapa el array de localizaciones
                        vm.markers.forEach(function(m){
                           
                           // if(m.id_odor_type == 9){
                           //     marker = L.marker([m.location.latitude, m.location.longitude], {icon: vm.blackIcon}).addTo(vm.mySubGroup);
                           //        marker.id = m.id;
                           // } else {
                            switch (m.color){

                                case 1:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.greenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 2:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.lightGreenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 3:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.yellowIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 4:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.orangeIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 5:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.lightPinkIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 6:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.pinkIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 7:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.purpleIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 8:
                                   marker = L.marker([m.latitude, m.longitude], {icon: vm.purpleIcon}).addTo(vm.mySubGroup);
                                   marker.id = m.id;
                                   break;
                                case 9:
                                   marker = L.marker([m.latitude, m.longitude], {icon: vm.blackIcon}).addTo(vm.mySubGroup);
                                   marker.id = m.id;
                                   break;
                                default:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.greenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                }
                            //}
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
      			    console.log('latitude');
                            console.log(m.latitude);
                            console.log(m.id);
                       
                            
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
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.yellowIcon}).addTo(vm.mySubGroup);
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
                $(".preload").css('display', 'block');
                $(".only_date").css('display', 'none');
                $(".only_type").css('display', 'none');
                $(".type_subtype").css('display', 'none');
      
                var type = this.typeSelected;
                var subtype = this.subtypeSelected;
                var annoy = this.annoy;
                var intensity = this.intensity;
                                
                var minAnnoy = annoy[0];
                var maxAnnoy = annoy[1];
                var minIntensity = intensity[0];
                var maxIntensity = intensity[1];

                var temp_date_init=this.date_init;
                if(!temp_date_init){
                    temp_date_init = "2000-01-01 00:00:01";
                }
               
                var temp_date_end = this.date_end;
                if(!temp_date_end){
                    temp_date_end= "3000-01-01 00:00:01";
                }

                axios.post('api/odor/list', {
                    type: type,
                    subtype: subtype,
                    minAnnoy: minAnnoy,
                    maxAnnoy: maxAnnoy,
                    minIntensity: minIntensity,
                    maxIntensity: maxIntensity,
                    date_init: moment(temp_date_init).format('YYYY-MM-DD H:mm:ss'),
                    date_end: moment(temp_date_end).format('YYYY-MM-DD H:mm:ss')
                    }).then(response => {
                        var points = response.data.content;
						
                        //Cargar la informacion
                       
                        points.forEach(function (point){
                                if (point.latitude) {
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
                        console.log(vm.markers);
                        //Añadir al mapa el array de localizaciones
                        vm.markers.forEach(function(m){

                            switch (m.color){
                                case 1:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.greenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 2:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.lightGreenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 3:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.yellowIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 4:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.orangeIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 5:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.lightPinkIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 6:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.pinkIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                case 7:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.purpleIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                                    break;
                                default:
                                    marker = L.marker([m.latitude, m.longitude], {icon: vm.greenIcon}).addTo(vm.mySubGroup);
                                    marker.id = m.id;
                            }
                            stop();

                        });

                }).catch(error => {
                    });
             
                
               
            },

            //Clear filter layer and charge the new one
            chargeFilteredMarkers() {
                console.log("chargeFilteredMarkers");
                var vm = this;

                vm.markers = [];
                vm.mySubGroup.clearLayers();
			/*	console.log(vm.filters);
                if (vm.filters.length === 0 && vm.filters !== null && this.date_init === "" && this.date_end === ""){
                    vm.getAllMarkers();
                } else {
                    vm.getFilteredMarkers(vm.filters);
                }
            */
                vm.getFilteredMarkers(vm.typeSelected);
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
    .radio-input input[type=checkbox]{
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
    input[type=checkbox]:checked ~ .check .inside{
        opacity: 1;
    }
    
    input[type=radio]:checked ~ .check .inside{
        opacity: 1;
    }

    
/*NEw Style */
.result{
  display: none;
}
.icon{
    width: 30px;
    position: absolute;
    top: 0;
    bottom: 0;
    margin: auto;
}
.icon2{
    top: -11px;
    right: 0px;
}
.circle{
    border: 1px solid;
    border-radius: 50%;
    width: 70%;
    height: 90%;
    position: relative;
    margin: 0 auto;
    top: -3px;
    right: 0px;
    margin-right: 0px;
}
.square{
    padding: 0px;
    text-align: center;
    margin-bottom: 10px;
    font-weight: bold;
    font-size: 21px;
}
a{
    color: #000;
}
input[type=radio]:checked ~ .check .inside{
        opacity: 1;
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
button, input, optgroup, select, textarea {
    box-sizing: border-box;
    width: 100% !important;
}
.date_icon{
        float: right;
        position: absolute;
        right: 0px;
        bottom: -1px;
    }

/*# sourceMappingURL=default.css.map */

</style>
