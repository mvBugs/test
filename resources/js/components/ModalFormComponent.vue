<template>
    <div>
        <div v-if="show">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Добавити місце</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" @click="closeModal">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="inputPassword" class="col-sm-2 col-form-label">Назва</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputPassword" placeholder="Введіть назву ..." v-model="point.title">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Довгота</label>
                                        <div class="col-sm-5">
                                            <input type="text" readonly class="form-control-plaintext" v-model="point.lng">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Широта</label>
                                        <div class="col-sm-5">
                                            <input type="text" readonly class="form-control-plaintext" v-model="point.lat">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2">Опис</label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" rows="4" v-model="point.description"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Виберіть фото</label>
                                        <input type="file" multiple="multiple" class="form-control-file" name="file" ref="file">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" @click="closeModal">Назад</button>
                                    <button type="button" class="btn btn-primary" @click="submit">Зберегти</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
        <button @click="showModal = true">Click</button>
    </div>
</template>

<script>
    import {bus} from "../app";
    import {HTTP} from '../axios';

    export default {
        name: "ModalFormComponent",
        data () {
            return {
                point: {
                    lat: '',
                    lng: '',
                    title: '',
                    description: '',
                },
                files: [],
                show: false,
                lat: '',
                lng: ''
            }
        },
        mounted () {
            bus.$on('addPoint', (lat, lng) => {
                this.point.lat = lat;
                this.point.lng = lng;
                this.show = true;
            });
        },
        methods: {
            closeModal () {
                this.show = false;
                this.point.lat = '';
                this.point.lng = '';
                this.point.title = '';
                this.point.description = '';
                bus.$emit("closeModal");
            },
            submit () {
                let vm = this;
                let point = vm.toFormData(vm.point);
                HTTP.post('points', point, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(function (responce) {
                    vm.closeModal()
                    bus.$emit('sendForm')
                })

            },
            toFormData: function(obj) {
                let formData = new FormData();
                for(let key in obj) {
                    formData.append(key, obj[key]);
                }
                for( var i = 0; i < this.$refs.file.files.length; i++ ){
                    let file = this.$refs.file.files[i];
                    formData.append('images[' + i + ']', file);
                }
                return formData;
            },
        }
    }
</script>

<style scoped>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }
</style>
