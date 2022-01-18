<template>
    <div>
        <v-stepper id="stepper" v-model="step" style="background: transparent !important; height: 100%">

            <v-stepper-header>
                <v-stepper-step step="1" :complete="step > 1"></v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step step="2" :complete="step > 2"></v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step step="3" :complete="step > 3"></v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step step="4" :complete="step > 4"></v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step step="5"></v-stepper-step>
            </v-stepper-header>

            <v-stepper-items style="z-index: 0;">
                <v-stepper-content step="1">
                    <img class="info-btn" @click="info1 = true" :src="info_icon">
                    <h2 class="step-title">{{$t('ADD_ODOUR.Q_ODOUR_TYPE')}}</h2>
                    <!-- Type of odour -->


                    <vue-scrollbar  classes="my-scrollbar" ref="Scrollbar">
                        <div id="container1" class="scroll-me">
                            <div  class="radio-input" v-for="(oddourType, index) in odourType[0].slice().reverse()">
                                <input type="radio" color="#384658" v-model="selected_parent" :data="oddourType" :id="oddourType.id" :value="oddourType.id" :label="oddourType.name" :key="oddourType.id" >
                                <label :for="oddourType.id">{{oddourType.name_t}}</label><!-- Aqui -->
                                <div class="check"><div class="inside" style="background-image:url(../../../img/general/checked-white.svg);"></div></div>
                            </div>
                        </div>
                    </vue-scrollbar>

                    <div class="align-center bottom" style="">
                        <v-btn color="secondary" id="step2" class="large-button body-2 font-weight-regular" @click.native="step = 2; scrolltop();">{{$t('ADD_ODOUR.NEXT')}}</v-btn>

                    </div>

                    <v-layout row justify-center>
                        <v-dialog v-model="info1" transition="dialog-bottom-transition" scrollable>
                            <v-card flat>

                                <h2 color="primary" class="subheading font-weight-medium title">{{$t('INFORMATION.ODOUR_TYPE.TITLE')}}
                                    <div class="info-close" @click="info1 = false"><img :src="close_icon"></div>
                                </h2>

                                <div class="separator"></div>

                                <v-card-text v-html="$t('INFORMATION.ODOUR_TYPE.CONTENT')"></v-card-text>

                                <div class="apply-btn">
                                    <v-divider style="margin-top: 0px;"></v-divider>
                                    <h2 class="apply" @click="info1 = false">{{$t('INFORMATION.ODOUR_TYPE.ACTION')}}</h2>
                                </div>

                                <div style="flex: 1 1 auto;"></div>
                            </v-card>
                        </v-dialog>
                    </v-layout>

                    <v-layout row justify-center>
                        <v-dialog v-model="no_geolocation" transition="dialog-bottom-transition" scrollable>
                            <v-card flat>

                                <h2 color="primary" class="subheading font-weight-medium title">{{$t('UI.INFO.ERROR')}}
                                    <div class="info-close" @click="no_geolocation = false"><img :src="close_icon"></div>
                                </h2>

                                <div class="separator"></div>

                                <v-card-text v-html="$t('ADD_ODOUR.ERROR_GEOLOCATION')"></v-card-text>

                                <div class="apply-btn">
                                    <v-divider style="margin-top: 0px;"></v-divider>
                                    <h2 class="apply" @click="closeCreate">{{$t('UI.INFO.AGREE')}}</h2>
                                </div>

                                <div style="flex: 1 1 auto;"></div>
                            </v-card>
                        </v-dialog>
                    </v-layout>
                </v-stepper-content>


                <v-stepper-content step="2">
                    <img class="info-btn" @click="info2 = true" :src="info_icon">
                    <h2 class="step-title" v-if="selected_parent != 8 && selected_parent != 9">{{$t('ADD_ODOUR.Q_ODOUR_SUBTYPE')}}</h2>
                    <h2 class="step-title" v-if="selected_parent == 8">{{$t('ADD_ODOUR.Q_ODOUR_SUBTYPE_DES')}}</h2>
                   
                    <!-- Type of odour -->

                    <vue-scrollbar classes="my-scrollbar" ref="Scrollbar">
                        <div class="scroll-me" v-if="selected_parent == 9">

                            <p color="primary" class="pform subheading font-weight-medium">{{$t('ADD_ODOUR.INPUT_FORM.Q_COMMENT')}}</p>
                            <textarea v-model="comment" placeholder="" :rows="3" :max-rows="3"></textarea>
                        </div>
                        <div id="container" class="scroll-me">
                            <textarea v-model="other" placeholder="" :rows="2" :max-rows="2" v-if="selected_parent == 8"></textarea>
                            <div  class="radio-input" v-for="(oddourType, index) in odourType[0][selected_parent - 1].childs" v-if="selected_parent != 9">
                                <input type="radio" color="#384658" v-model="selected_child" :data="oddourType" :id="oddourType.id+1000" :value="oddourType.id+1000" :label="oddourType.name" :key="oddourType.id+1000" >
                                <label :for="oddourType.id+1000" id="label" style="">{{oddourType.name_t}}</label>
                                <div class="check"><div class="inside" style="background-image:url(../../../img/general/checked-white.svg);"></div></div>
                            </div>
                        </div>
                        
                    </vue-scrollbar>
                 
                    <div id="arrow" class="align-center arrow-container">
                            <a href="#" >
                                <div class="arrow"></div>
                            </a>
                    </div>
                     <!-- NEXT STEP FORM -->
                    <div class="align-center bottom" v-if="selected_parent == 9">
                        <img v-if="publishing" :src="load_icon">
                        <v-btn v-if="!publishing && selected_parent != 9" class="back-btn" flat @click.native="step = 4; scrolltop();"><img :src="arrow_icon"></v-btn>
                        <v-btn v-if="!publishing && selected_parent == 9" class="back-btn" flat @click.native="step = 1; scrolltop();"><img :src="arrow_icon"></v-btn>
                        <v-btn v-if="!publishing" color="secondary" class="large-button body-2 font-weight-regular" @click.native="submit">{{$t('ADD_ODOUR.SAVE')}}</v-btn>
                    </div>
                    <div id="button" class="align-center bottom" style="" v-if="selected_parent != 9">
                        <!--<p class="step-subtitle">{{$t('ADD_ODOUR.Q_SELECT_ODOUR_TYPE')}}</p>-->
                        <v-btn class="back-btn" flat @click.native="step = 1; scrolltop();"><img :src="arrow_icon"></v-btn>
                        <v-btn color="secondary" class="large-button body-2 font-weight-regular" @click.native="step = 3; scrolltop();">{{$t('ADD_ODOUR.NEXT')}}</v-btn>
                    </div>

                    <v-layout row justify-center>
                        <v-dialog v-model="info2" transition="dialog-bottom-transition" scrollable>
                            <v-card flat>

                                <h2 color="primary" class="subheading font-weight-medium title">{{$t('INFORMATION.ODOUR_SUBTYPE.TITLE')}}
                                    <div class="info-close" @click="info2 = false"><img :src="close_icon"></div>
                                </h2>

                                <div class="separator"></div>

                                <v-card-text v-html="$t('INFORMATION.ODOUR_SUBTYPE.CONTENT')"> </v-card-text>

                                <div class="apply-btn">
                                    <v-divider style="margin-top: 0px;"></v-divider>
                                    <h2 class="apply" @click="info2 = false">{{$t('INFORMATION.ODOUR_SUBTYPE.ACTION')}}</h2>
                                </div>

                                <div style="flex: 1 1 auto;"></div>
                            </v-card>
                        </v-dialog>
                    </v-layout>
                </v-stepper-content>


                <v-stepper-content step="3">
                    <img class="info-btn" @click="info3 = true" :src="info_icon">
                    <h2 class="step-title">{{$t('ADD_ODOUR.Q_ODOUR_INTENSITY')}}</h2><br>
                    <div class="centrar-v">
                        <v-container fluid grid-list-lg>
                            <div class="text-xs-center">
                                <div class="font-weight-medium body-2 three-columns chart">
                                    <v-progress-circular :rotate="90" :size="130" :color="intensity_fill" :width="16" :value="(intensity_slider*16.666666667)">{{(intensity_slider)}}</v-progress-circular>
                                </div>
                            </div>

                            <v-layout row wrap class="text-xs-center">

                                <v-flex>
                                    <p class="step-type">{{intensity_info[intensity_slider - 1]}}</p>
                                    <p class="step-type-description">{{intensity_description[intensity_slider - 1]}}</p>
                                    <v-slider :color="intensity_fill" v-model="intensity_slider" ticks="always" :tick-labels="intensity_labels" :max="6":min="1"></v-slider>
                                </v-flex>
                            </v-layout>

                            <div class="align-center">
                                <p class="step-subtitle">{{$t('ADD_ODOUR.INFO_INTENSITY')}}</p>
                            </div>

                            <div class="align-center bottom" style="">
                                <p v-if="error"></p>
                                <v-btn class="back-btn" flat @click.native="step = 2; scrolltop();"><img :src="arrow_icon"></v-btn>
                                <v-btn color="secondary" class="large-button body-2 font-weight-regular" @click.native="checkIntensity();">{{$t('ADD_ODOUR.NEXT')}}</v-btn>

                            </div>

                            <v-layout row justify-center>
                                <v-dialog v-model="error" transition="dialog-bottom-transition" scrollable>
                                    <v-card tile>
                                        <h2 color="primary" class="subheading font-weight-medium title">{{$t('UI.INFO.ERROR')}}
                                            <div class="info-close" @click="error = false"><img :src="close_icon"></div>
                                        </h2>

                                        <div class="separator"></div>

                                        <v-card-text>
                                            {{$t('ADD_ODOUR.ERROR_ADDING_PLACEMARK')}}
                                        </v-card-text>

                                        <div class="apply-btn">
                                            <v-divider style="margin-top: 0px;"></v-divider>
                                            <h2 class="apply" @click="error = false">{{$t('UI.INFO.AGREE')}}</h2>
                                        </div>
                                        <div style="flex: 1 1 auto;"></div>
                                    </v-card>
                                </v-dialog>
                            </v-layout>

                            <v-layout row justify-center>
                                <v-dialog v-model="info3" transition="dialog-bottom-transition" scrollable>
                                    <v-card flat>

                                        <h2 color="primary" class="subheading font-weight-medium title">{{$t('INFORMATION.INTENSITY.TITLE')}}
                                            <div class="info-close" @click="info3 = false"><img :src="close_icon"></div>
                                        </h2>

                                        <div class="separator"></div>

                                        <v-card-text v-html="$t('INFORMATION.INTENSITY.CONTENT')"></v-card-text>

                                        <div class="apply-btn">
                                            <v-divider style="margin-top: 0px;"></v-divider>
                                            <h2 class="apply" @click="info3 = false">{{$t('INFORMATION.INTENSITY.ACTION')}}</h2>
                                        </div>

                                        <div style="flex: 1 1 auto;"></div>
                                    </v-card>
                                </v-dialog>
                            </v-layout>

                        </v-container>
                    </div>
                </v-stepper-content>


                <v-stepper-content step="4">
                    <img class="info-btn" @click="info4 = true" :src="info_icon">
                    <h2 class="step-title">{{$t('ADD_ODOUR.Q_ODOUR_ANNOYANCE')}}</h2><br>
                    <div class="centrar-v">
                        <v-container fluid grid-list-lg>
                            <div class="text-xs-center">
                                <div class="font-weight-medium body-2 three-columns chart">
                                    <v-progress-circular :rotate="90" :size="130" :color="annoy_fill" :width="16" :value="(annoy_slider+4)*12.5">{{(annoy_slider)}}</v-progress-circular>
                                </div>
                            </div>

                            <v-layout row wrap class="text-xs-center">
                                <v-flex>
                                    <p class="step-type">{{annoy_info[annoy_slider + 4]}}</p>
                                    <p class="step-type-description">{{annoy_description[annoy_slider + 4]}}</p>
                                    <v-slider :color="annoy_fill" v-model="annoy_slider" ticks="always" :tick-labels="annoy_labels" :min="-4" :max="4"></v-slider>
                                </v-flex>
                            </v-layout>

                            <div class="align-center">
                                <p class="step-subtitle">{{$t('ADD_ODOUR.INFO_ANNOYANCE')}}</p>
                            </div>

                            <div class="align-center bottom" style="">    
                                <v-btn class="back-btn" flat @click.native="step = 3 ; scrolltop();"><img :src="arrow_icon"></v-btn>
                                <v-btn color="secondary" class="large-button body-2 font-weight-regular" @click.native="step = 5; scrolltop();">{{$t('ADD_ODOUR.NEXT')}}</v-btn>
                                
                            </div>

                            <v-layout row justify-center>
                                <v-dialog v-model="info4" transition="dialog-bottom-transition" scrollable>
                                    <v-card flat>

                                        <h2 color="primary" class="subheading font-weight-medium title">{{$t('INFORMATION.ANNOY.TITLE')}}
                                            <div class="info-close" @click="info4 = false"><img :src="close_icon"></div>
                                        </h2>

                                        <div class="separator"></div>

                                        <v-card-text v-html="$t('INFORMATION.ANNOY.CONTENT')"></v-card-text>

                                        <div class="apply-btn">
                                            <v-divider style="margin-top: 0px;"></v-divider>
                                            <h2 class="apply" @click="info4 = false">{{$t('INFORMATION.ANNOY.ACTION')}}</h2>
                                        </div>

                                        <div style="flex: 1 1 auto;"></div>
                                    </v-card>
                                </v-dialog>
                            </v-layout>

                        </v-container>
                    </div>
                </v-stepper-content>


                <v-stepper-content step="5">
                    <vue-scrollbar classes="my-scrollbar final-step" ref="Scrollbar">
                        <div class="scroll-me">

                            <p color="primary" class="pform subheading font-weight-medium">{{$t('ADD_ODOUR.INPUT_FORM.Q_ODOUR_DURATION')}}</p>
                            <v-select v-model="duration_selected" :items="duration" item-value="id" item-text="name_t" :placeholder="placeholder"></v-select>

                            <p color="primary" class="pform subheading font-weight-medium">{{$t('ADD_ODOUR.INPUT_FORM.Q_ODOUR_COMES_FROM')}}</p>
                            <textarea v-model="origen" placeholder="" :rows="2" :max-rows="2"></textarea>

                            <p color="primary" class="pform subheading font-weight-medium">{{$t('ADD_ODOUR.INPUT_FORM.Q_COMMENT')}}</p>
                            <textarea v-model="comment" placeholder="" :rows="3" :max-rows="3"></textarea>
                        </div>
                    </vue-scrollbar>

                    <!-- NEXT STEP FORM -->
                    <div class="align-center bottom" style="">
                        <img v-if="publishing" :src="load_icon">
                        <v-btn v-if="!publishing" class="back-btn" flat @click.native="step = 4; scrolltop();"><img :src="arrow_icon"></v-btn>
                        <v-btn v-if="!publishing" color="secondary" class="large-button body-2 font-weight-regular" @click.native="submit">{{$t('ADD_ODOUR.SAVE')}}</v-btn>
                    </div>

                    <v-layout row justify-center>
                        <v-dialog v-model="state.status" transition="dialog-bottom-transition" scrollable>
                            <v-card flat>

                                <h2 color="primary" class="subheading font-weight-medium title">{{state.title}}
                                    <div class="info-close" @click="state.status = false"><img :src="close_icon"></div>
                                </h2>

                                <div class="separator"></div>

                                <v-card-text>
                                    <p>{{state.msg}}</p>

                                </v-card-text>

                                <div class="apply-btn">
                                    <v-divider style="margin-top: 0px;"></v-divider>
                                    <h2 class="apply" @click="state.status = false">{{$t('UI.INFO.AGREE')}}</h2>
                                </div>

                                <div style="flex: 1 1 auto;"></div>
                            </v-card>
                        </v-dialog>
                    </v-layout>


                    <v-layout row justify-center>
                        <v-dialog v-model="stateError.status" transition="dialog-bottom-transition" scrollable>
                            <v-card flat>

                                <h2 color="primary" class="subheading font-weight-medium title">{{stateError.title}}
                                    <div class="info-close" @click="closeError()"><img :src="close_icon"></div>
                                </h2>

                                <div class="separator"></div>

                                <v-card-text>
                                    <p>{{stateError.msg}}</p>

                                </v-card-text>

                                <div class="apply-btn">
                                    <v-divider style="margin-top: 0px;"></v-divider>
                                    <h2 class="apply" @click="closeError()">{{$t('UI.INFO.AGREE')}}</h2>
                                </div>

                                <div style="flex: 1 1 auto;"></div>
                            </v-card>
                        </v-dialog>
                    </v-layout>
                </v-stepper-content>
            </v-stepper-items>

        </v-stepper>
    </div>
</template>
<script>
    import VueScrollbar from 'vue2-scrollbar';
    import { required } from 'vuelidate/lib/validators';

    export default {

        components: { VueScrollbar },
        validations: {
            registration: {
                odourReportDate: { required },
                odourReportTime: { required }
            },
        },
        data() {
            return {
                odourType: [],
                isLoggedIn: null,
                name: null,
                token: null,
                step: 1,
                registration: {
                    odourReportDate: '',
                    odourReportTime: '',
                },
                slider: 0,
                sliderLabel: 0,
                sliderProgress: 50,
                sliderProgressa: 50,
                progressColor: 'teal',
                item: [],
                selected_parent: 1,
                selected_child: 1,
                options: {
                    activeColor: '#00b187'
                },
                intensity_fill : '#58B5C7',
                annoy_fill: '#8C86D3',
                annoy_slider: 0,
                intensity_slider: 1,
                comment: '',
                origen: '',
                other : '',
                current_pos: '',
                error: false,
                arrow_icon: '../../../img/general/nav-back.svg',
                info_icon: '../../../img/general/moreinfo-button-create.svg',
                close_icon: '../../../img/general/close-mini.svg',
                load_icon:'../../../img/general/ajax-loader.gif',
                info1: false,
                info2: false,
                info3: false,
                info4: false,
                intensity_labels: [
                    '1',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '6'
                ],
                annoy_labels: [
                    '-4',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '4'
                ],
                intensity_info: [this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.VERY_WEAK.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.WEAK.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.DISTINCT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.STRONG.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.VERY_STRONG.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.EXTREMELY_STRONG.DEFINITION')],
                intensity_description: [this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.VERY_WEAK.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.WEAK.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.DISTINCT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.STRONG.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.VERY_STRONG.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.EXTREMELY_STRONG.DESCRIPTION')],
                annoy_info: [this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.EXTREMELY_UNPLEASANT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.VERY_UNPLEASANT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.UNPLEASANT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.SLIGHTLY_UNPLEASANT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.NEUTRAL.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.SLIGHTLY_PLEASANT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.PLEASANT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.VERY_PLEASANT.DEFINITION'),this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.EXTREMELY_PLEASANT.DEFINITION')],
                annoy_description: [this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.EXTREMELY_UNPLEASANT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.VERY_UNPLEASANT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.UNPLEASANT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.SLIGHTLY_UNPLEASANT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.NEUTRAL.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.SLIGHTLY_PLEASANT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.PLEASANT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.VERY_PLEASANT.DESCRIPTION'),this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.EXTREMELY_PLEASANT.DESCRIPTION')],
                state : {
                    status : false,
                    msg: '',
                    title: ''
                },
                stateError : {
                    status : false,
                    msg: '',
                    title: ''
                },
                duration: [],
                duration_selected: '',
                placeholder: this.$t('ADD_ODOUR.INPUT_FORM.DURATION'),
                publishing: false,
                no_geolocation: false,
                subtypeScrollObserver: null
            }
        },
        computed: {
          errorsDate(){
              const errors = []
              if (!this.$v.registration.odourReportDate.$dirty) return errors
              !this.$v.registration.odourReportDate.required && errors.push('Date is required.')
              return errors
          },

          errorsTime(){
              const errors = []
              if (!this.$v.registration.odourReportTime.$dirty) return errors
              !this.$v.registration.odourReportTime.required && errors.push('Time is required.')
              return errors
          }
        },
        methods: {


            closeError(){
                this.stateError.status = false
                this.$emit('clicked', 'go_login');
            },

            closeCreate(){
                this.no_geolocation = false
                this.$emit('clicked', 'reset')
            },
            //Publish the new odour
            submit (){

                var vm = this;

                vm.publishing = true;
                var selected_child_var = vm.selected_child;
                
                if(selected_child_var == 8){
                    var selected_child_var = vm.selected_child;
                } else if(selected_child_var == 9){
                    var selected_child_var = vm.selected_child;
                } else {
                    var selected_child_var =  vm.selected_child - 1000;
                }

                if(selected_child_var == 9){
                    axios.post('../api/odor/store', {
                            token: vm.token,
                            id_odor_type: '88',
                            id_user: vm.name.id,
                            name: 'test',
                            description: vm.comment,
                            origin: vm.origen,
                            other: vm.other,
                            id_odor_intensity: vm.intensity_slider + 1,
                            id_odor_duration: vm.duration_selected,
                            id_odor_annoy: vm.annoy_slider + 5,
                            latitude: (vm.current_pos.lat).toFixed(4),
                            longitude: (vm.current_pos.lng).toFixed(4)
                        }).then(response => {
                            this.$emit('clicked', ['new', response.data.object.id])

                        }).catch(error => {
                            vm.publishing = false;
                            vm.stateError.status = true;
                            vm.stateError.msg = this.$t('ADD_ODOUR.ERROR_LOGIN');
                            vm.stateError.title = this.$t('UPDATE_PROFILE.KO_TITLE')
                        });
                } else {
                
                    if (vm.duration_selected){
                        axios.post('../api/odor/store', {
                            token: vm.token,
                            id_odor_type: selected_child_var,
                            id_user: vm.name.id,
                            name: 'test',
                            description: vm.comment,
                            origin: vm.origen,
                            other: vm.other,
                            id_odor_intensity: vm.intensity_slider + 1,
                            id_odor_duration: vm.duration_selected,
                            id_odor_annoy: vm.annoy_slider + 5,
                            latitude: (vm.current_pos.lat).toFixed(4),
                            longitude: (vm.current_pos.lng).toFixed(4)
                        }).then(response => {
                            this.$emit('clicked', ['new', response.data.object.id])

                        }).catch(error => {
                            vm.publishing = false;
                            vm.stateError.status = true;
                            vm.stateError.msg = this.$t('ADD_ODOUR.ERROR_LOGIN');
                            vm.stateError.title = this.$t('UPDATE_PROFILE.KO_TITLE')
                        });
                    } else {
                        vm.publishing = false;
                        vm.state.status = true;
                        vm.state.msg = this.$t('ADD_ODOUR.ERROR_DURATION')
                        vm.state.title = this.$t('UPDATE_PROFILE.KO_TITLE')
                    }
                }
            },

            //Scroll to top when the step changes
            scrolltop(){
                var element = document.getElementById("stepper");
                var top = element.offsetTop;
                window.scrollTo(0, top);
            },

            //Check is the intensity > 0 or show an error
            checkIntensity(){
                var vm = this;
                
                if (vm.intensity_slider === 0){
                    vm.error = true
                } else {
                    vm.error = false
                    vm.step = 4;
                    this.scrolltop();
                }
            },
            goHome(){
                this.$router.push({ name: 'home' });
            }
        },
        watch:{
            step() {
                if(this.step == 2){
                    document.getElementById("arrow").style.display = "block";
                    function callback(entries,observer){
                        if(entries[0].isIntersecting){
                            document.getElementById("arrow").style.display = "none";
                        }else{
                            document.getElementById("arrow").style.display = "block";
                        }
            
                    }
                    var labels = document.querySelectorAll('label');
                    const element = labels[labels.length- 1];
                    this.subtypeScrollObserver = new IntersectionObserver(callback, {});
                    this.subtypeScrollObserver.observe(element); 
                }
                if(this.step == 1 || this.step == 3){
                    this.subtypeScrollObserver.disconnect();
                }
                
            },

            //Update the odour child type according to the parent type
            selected_parent(){
                if(this.odourType[0][this.selected_parent - 1].id == 8){
                    this.selected_child = this.odourType[0][this.selected_parent - 1].id;
                } else  if(this.odourType[0][this.selected_parent - 1].id == 9){
                     this.selected_child = this.odourType[0][this.selected_parent - 1].id;
            
                } else {
                    this.selected_child = this.odourType[0][this.selected_parent - 1].childs[0].id;
                    this.selected_child += 1000;
                }
                
            }

        },

        mounted() {

            if( localStorage.getItem('auth-token') != null ) { this.isLoggedIn = true }
            if( this.isLoggedIn ){
                //If logged in save the user name in the data
                this.name = JSON.parse(localStorage.getItem('user'));
                this.token = localStorage.getItem('auth-token');
            }

            var vm = this;

            //Get the user location
            if (navigator.geolocation){
                var options = {
                    enableHighAccuracy: true,
                    timeout: 60000,
                    maximumAge: Infinity
                };

                function error(err) {
                    vm.no_geolocation = true;
                }

                // vm.no_geolocation = false;

                navigator.geolocation.getCurrentPosition(function(location) {
                    vm.current_pos = new L.LatLng(location.coords.latitude, location.coords.longitude);
                }, error, options)
            }

            vm.intensity_info = [this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.VERY_WEAK.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.WEAK.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.DISTINCT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.STRONG.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.VERY_STRONG.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.EXTREMELY_STRONG.DEFINITION')],
            vm.intensity_description = [this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.VERY_WEAK.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.WEAK.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.DISTINCT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.STRONG.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.VERY_STRONG.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_INTENSITY.EXTREMELY_STRONG.DESCRIPTION')],
            vm.annoy_info = [this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.EXTREMELY_UNPLEASANT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.VERY_UNPLEASANT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.UNPLEASANT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.SLIGHTLY_UNPLEASANT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.NEUTRAL.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.SLIGHTLY_PLEASANT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.PLEASANT.DEFINITION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.VERY_PLEASANT.DEFINITION'),this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.EXTREMELY_PLEASANT.DEFINITION')],
            vm.annoy_description = [this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.EXTREMELY_UNPLEASANT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.VERY_UNPLEASANT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.UNPLEASANT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.SLIGHTLY_UNPLEASANT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.NEUTRAL.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.SLIGHTLY_PLEASANT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.PLEASANT.DESCRIPTION'), this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.VERY_PLEASANT.DESCRIPTION'),this.$t('ODOUR.CLASSIFICATION_BY_ANNOYANCE.EXTREMELY_PLEASANT.DESCRIPTION')],

            //Get types and subtypes of the odour
            axios.get('../api/odor/properties/type')
            .then(response => {
                var points = response.data.content;

                //Cargar la informacion
                for (var i = 0; i < points.length; i++){
                    for (var e = 0; e < points[i].childs.length; e++){
                        points[i].childs[e].name_t = this.$t('FILTER.SUBTYPE.' + points[i].childs[e].id);
                    }
                    points[i].name_t = this.$t('FILTER.TYPE.' + points[i].id);
                    this.item.push(points[i]);
                }
                this.odourType.push(this.item);

                this.selected_child = this.odourType[0][this.selected_parent - 1].childs[0].id
                this.selected_child += 1000;
            }).catch(error => {

            });

            //Get the 3 types of duration
            axios.get('../api/odor/properties/duration')
                .then(response => {
                    var points = response.data.content;

                    //Cargar la informacion
                    for (var i = 0; i < points.length; i++){
                        points[i].name_t = this.$t('DURATION.' + points[i].id);
                        this.duration.push(points[i]);
                    }

                }).catch(error => {
            });
        },
        beforeRouteEnter(to, from, next) {
            if (!localStorage.getItem('auth-token') || localStorage.getItem('auth-token') == null) {
                return next('login')
            }
            next()
        }
      
    }

</script>
 

<style lang="scss">
    .v-stepper__wrapper{
        height: 100%;
        position: relative;
    }
    .v-stepper__step__step {
        font-size:0;
        height: 7px !important;
        min-width: 7px !important;
        width: 7px !important;
    }
    .v-stepper__step__step .v-icon {
        font-size:0;
    }
    .v-slider__thumb {
        width: 34px;
        height: 34px;
        left: -17px;
        border:5px solid;
        background-color: white !important;
    }
    .v-slider__ticks>span {
        top:15px;
    }
    .v-progress-circular {
        border: 5px solid white;
        border-radius: 50%;
        background-color: white;
    }
    .v-progress-circular__underlay {
        stroke:white;
    }
    .v-progress-circular__info {
        font-size:35px;
        color: #535353;
        text-shadow: 0px 0px 8px rgba(0,0,0,0.05);
    }
    .v-dialog {
        border-radius:20px;
    }
</style>
<style scoped lang="scss">
    @import '../../../sass/app.scss';

    *{
        color: $font-color-dark;
    }
    .container{
        padding: 0;
    }
    .centrar-v {
        height: calc(100% - 120px);
        display: table;
        width: 100%;
    }
    .centrar-v .container {
        display:table-cell;
    }
    .display-1{
        font-size: 22px!important;
        margin-bottom:0;
        line-height: 33px!important;
    }
    h2{
        text-align: center;
    }
    .align-center{
        text-align: center;
    }
    .v-stepper,
    .v-stepper__header {
        box-shadow: none !important;
    }
    .v-stepper__header {
        margin: 0 auto;
        max-width:150px;
        height: 50px;
    }
    .v-stepper__step {
        padding:15px 20px;
    }
    .v-stepper__header .v-divider {
        opacity:0;
    }
    .message{
        margin-top: 20px
    }
    .v-stepper__items{
        background-color: $secondary-bg;
        height: calc(100% - 50px);
    }
    .v-stepper__content{
        padding: 0px 20px 20px 20px;
        height: 100%;
        position: relative;
    }

    .ly-tabbar{
        background-color: $secondary-bg!important;
    }
    .ly-tab-list{
        background-color: $secondary-bg!important;
    }
    .spacer {
        flex-grow: 0.3 !important;
    }
    .row {
        margin-right: 0px !important;
        margin-left: 0px !important;
    }
    .container.grid-list-lg .layout .flex {
        padding:8px 11px !important;
    }
    .bottom{
        position: absolute;
        left: 0;
        width: 100%;
        bottom: 0;
    }
    .v-btn{
        text-transform: uppercase;
        border-radius: 30px;
        margin-top: 20px;
        height: 50px;
        margin-left: 20px;
        font-size: 15px;
        font-weight: 600 !important;
        box-shadow: none !important;
    }
    .back-btn{
        background-color: white;
        width: 35px;
        border-radius: 96px;
        padding: 0;
        margin: 0;
        height: 35px;
        min-width: 35px;
        margin-top: 20px;
    }
    .bottom .back-btn {
        position: absolute;
        left: 0;
        top:0;
        margin-top:3px;
    }
    .my-scrollbar{
        width: 35%;
        min-width: 100%;
        height: calc(100vh - 240px);
        overflow: hidden;
    }
    .my-scrollbar.final-step{
        height: calc(100vh - 190px);
    }
    .scroll-me{
        padding-bottom: 10px;
    }

    .boderScroll{
        border: $font-color-dark 2px solid !important;
        border-radius: 10px;
    }
    textarea{
        width: 100%;
        background-color: white;
        border-radius: 20px;
        padding: 10px;
        font-size: 16px;
    }
    .v-input__slot{
        background-color: white;
    }
    .large-button{
        width: 180px;
        height: 40px;
        margin-top:0;
        margin-left:0;
    }
    .info-btn{
        position:absolute;
        top: 0;
        right:0;
        cursor: pointer;
    }
    .apply{
        color: $primary-font-color;
        text-transform: uppercase;
        text-align: center;
        padding-bottom: 10px;
        font-size: 15px;
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
    .v-dialog:not(.v-dialog--fullscreen) {
        max-height: 400px;
    }
    .separator {
        border-bottom: 1px solid #dadada;
        margin-top: 5px;
    }
    .title{
        text-align: center;
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
    .v-dialog {
        margin:0 !important;
        border-radius: 30px 30px 0 0;
    }
    .step-title{
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
        padding: 0 25px;
    }
    .step-subtitle{
        font-size: 13px;
        font-weight: normal;
    }
    .step-type{
        font-size: 16px;
        font-weight: 600;
        margin-top: 5px;
        height: 24px;
        margin-bottom:25px;
    }
 
    .step-type-description{
        height: 30px;
        margin-bottom: 0;
    }
    .filters-nav{
        position: fixed;
        bottom: 0px;
        width: 100%;
        right: 0;
        height: 430px;
        padding: 20px;
        border-radius: 50px 50px 0px 0px;
    }
    .radio-input {
      position: relative;
      height: 45px;
      border-bottom: 1px solid #D6DCE5;
    }
    .radio-input input[type=radio]{
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
    p.pform{
        margin-top:10px;
        margin-bottom:5px;
    }

    div#container1{
        padding-bottom: -7px !important;
    }



$total-arrows: 3;
$arrow-line-length: 20px;
$arrow-line-width: 4px;

// arrow animtion + choose direction
@mixin arrow-transitions($rot: 0deg) {
  transform: translate(-50%, -50%) rotateZ($rot);
}

// base

.arrow {
  left: 50%;
  margin-top: -10px;
  transition: all .4s ease;
  &:before,
  &:after {
    transition: all .4s ease;
    content: '';
    position: absolute;
    transform-origin: bottom right;
    background: #00b187;
    width: $arrow-line-width;
    height: $arrow-line-length;
    border-radius: 10px;
    transform: translate(-50%, -50%) rotateZ(-45deg);
  }
  &:after {
    transform-origin: bottom left;
    transform: translate(-50%, -50%) rotateZ(45deg);
  }
  @for $i from 1 through $total-arrows {
    &:nth-child(#{$i}) {
      top: 15 + (100% * $i/5);
    }
  }

  .arrow-container{
      margin-top: 1px !important;
      height: 15px;
  }
}
</style>
