<template>
    <div>
        <nav id="sidebar" v-if="show" >

            <div class="container">
                <div class="sidebar-header">
                    <h3>{{ point.title }}</h3>
                </div>
                <hr v-if="point.description">
                <p class="text">
                    {{ point.description }}
                </p>
                <hr v-if="images.count">
                <div class="row">
                    <div class="col-6" v-for="img in images"><img :src="img" alt="" class="img-thumbnail"></div>
                </div>
                <a :href="'/admin/points/' + point.id + '/edit'" v-if="user.id == point.user_id">Edit</a>
            </div>
            <hr>
            <div class="pb-cmnt-container">
                <div class="card card-comments mb-3 wow fadeIn" v-if="point.comments.length">
                    <div class="card-header font-weight-bold">{{ point.comments.length }} comments</div>
                    <div class="card-body">
                        <div  v-for="comment in point.comments">
                            <div class="media d-block d-md-flex mt-3 comment">
                                <div class="media-body text-center text-md-left ml-md-3 ml-0">
                                    <div>
                                        <h5 class="mt-0 font-weight-bold">{{ comment.name }}
                                        </h5>
                                        <span class="pull-right">
                                        {{ comment.created_at }}
                                    </span>
                                    </div>
                                    {{ comment.text }}
                                </div>
                            </div>
                            <hr>
                        </div>

                    </div>
                </div>

                <div class="form-group row">
                    <label for="input" class="col-sm-1 col-form-label">Ім'я</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="input" placeholder="Ім'я ..." v-model="comment.name">
                    </div>
                </div>
                <textarea placeholder="Залиште свій відгук" class="pb-cmnt-textarea" v-model="comment.text"></textarea>
                <div class="btn-group">
                    <button class="btn btn-primary pull-right" type="button" @click="submit">Відправити</button>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
    import {bus} from "../app";
    import {HTTP} from '../axios';

    export default {
        name: "SidebarComponent",
        props: {
            user: '',
        },
        data () {
            return {
                comment: {
                    name: '',
                    text: '',
                    point_id: '',
                },
                point: null,
                show: false,
                images: [],
            }
        },
        mounted () {
            bus.$on('clickMarker', (point) => {
                this.point = point;
                this.comment.point_id = point.id;
                this.images = point.images;
                this.show = true;
                if (this.user) {
                    this.comment.name = this.user.name;
                }
                this.comment.text = '';
            });
            bus.$on('closeWindow', () => {
                this.point = null;
                this.show = false;
            });
        },
        computed: {

        },
        methods: {
            submit () {
                let vm = this;
                let comment = vm.toFormData(vm.comment);
                HTTP.post('comment/store', comment, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(function (responce) {
                    HTTP.get('points/' + vm.point.id)
                        .then((response) => {
                            vm.point = response.data.data
                            vm.comment.text = '';
                        });
                })

            },
            toFormData: function(obj) {
                let formData = new FormData();
                for(let key in obj) {
                    formData.append(key, obj[key]);
                }

                return formData;
            },
        }
    }
</script>

<style scoped>
    #sidebarCollapse {
        width: 40px;
        height: 40px;
        background: #f5f5f5;
    }

    #sidebarCollapse span {
        width: 80%;
        height: 2px;
        margin: 0 auto;
        display: block;
        background: #ffffff;
        transition: all 0.8s cubic-bezier(0.810, -0.330, 0.345, 1.375);
    }

    #sidebarCollapse span:first-of-type {
        /* rotate first one */
        transform: rotate(45deg) translate(2px, 2px);
    }
    #sidebarCollapse span:nth-of-type(2) {
        /* second one is not visible */
        opacity: 0;
    }
    #sidebarCollapse span:last-of-type {
        /* rotate third one */
        transform: rotate(-45deg) translate(1px, -1px);
    }

    #sidebarCollapse.active span {
        /* no rotation */
        transform: none;
        /* all bars are visible */
        opacity: 1;
        margin: 5px auto;
    }

    #sidebar {
        padding: 10px;
        min-width: 500px;
        max-width: 500px;
        position: relative;
        height: 100%;
        z-index: 1020;
        background: #fff;
        color: #7386D5;
        transition: all 0.6s cubic-bezier(0.945, 0.020, 0.270, 0.665);
        transform-origin: center left; /* Set the transformed position of sidebar to center left side. */
        padding-bottom: 100px;
        overflow: auto;
    }

    .text{
        text-align: justify;
    }

    #sidebar.active {
        margin-left: -250px;
        transform: rotateY(100deg); /* Rotate sidebar vertically by 100 degrees. */
    }

    @media (max-width: 768px) {
        /* Reversing the behavior of the sidebar:
           it'll be rotated vertically and off canvas by default,
           collapsing in on toggle button click with removal of
           the vertical rotation.   */
        #sidebar {
            margin-left: -250px;
            transform: rotateY(100deg);
        }
        #sidebar.active {
            margin-left: 0;
            transform: none;
        }

        /* Reversing the behavior of the bars:
           Removing the rotation from the first,
           last bars and reappear the second bar on default state,
           and giving them a vertical margin */
        #sidebarCollapse span:first-of-type,
        #sidebarCollapse span:nth-of-type(2),
        #sidebarCollapse span:last-of-type {
            transform: none;
            opacity: 1;
            margin: 5px auto;
        }

        /* Removing the vertical margin and make the first and last bars rotate again when the sidebar is open, hiding the second bar */
        #sidebarCollapse.active span {
            margin: 0 auto;
        }
        #sidebarCollapse.active span:first-of-type {
            transform: rotate(45deg) translate(2px, 2px);
        }
        #sidebarCollapse.active span:nth-of-type(2) {
            opacity: 0;
        }
        #sidebarCollapse.active span:last-of-type {
            transform: rotate(-45deg) translate(1px, -1px);
        }
    }

    /*comments*/
      .pb-cmnt-container {
          font-family: Lato;
          margin-top: 20px;
      }

    .pb-cmnt-textarea {
        resize: none;
        padding: 20px;
        height: 130px;
        width: 100%;
        border: 1px solid #F2F2F2;
    }
</style>
