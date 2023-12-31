<template>
    <div class="content-wrapper">
        <div class="user-profile mb-primary">
            <div class="card card-with-shadow py-5 border-0">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-5">
                        <div class="media border-right px-5 pr-xl-5 pl-xl-0 user-header-media">
                            <div class="profile-pic-wrapper">
                                <div class="custom-image-upload-wrapper circle mx-xl-auto">
                                    <div class="image-area d-flex">
                                        <img id="imageResult"
                                             :src="profile_picture_link"
                                             alt=""
                                             class="img-fluid mx-auto my-auto">
                                    </div>
                                    <div class="input-area">
                                        <label id="upload-label" for="upload">{{ $t('change') }}</label>
                                        <input id="upload" type="file" onchange="readURL(this)"
                                               class="form-control d-none">
                                    </div>
                                </div>
                            </div>
                            <div class="media-body user-info-header">
                                <h4>
                                    {{ loggedInUser.full_name }}
                                </h4>
                                <span class="badge badge-pill badge-success user-status">{{
                                        $optional(loggedInUser, 'status', 'translated_name')
                                    }}</span>
                                <p class="text-muted mt-2 mb-0">{{ loggedInUser.email }}</p>
                                <p class="text-primary">
                                    {{
                                        loggedInUser.roles ? collection(loggedInUser.roles).pluck('name').join(', ') : ''
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-7">
                        <div
                            class="user-details px-5 px-sm-5 px-md-5 px-lg-0 px-xl-0 mt-5 mt-sm-5 mt-md-0 mt-lg-0 mt-xl-0">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                    <div class="border-right custom">
                                        <div class="media mb-4 mb-xl-5">
                                            <div class="align-self-center mr-3">
                                                <app-icon name="phone"/>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-muted mb-0">{{ $t('contact') }}:</p>
                                                <p class="mb-0"
                                                   v-if="loggedInUser.profile && loggedInUser.profile.contact">
                                                    {{ loggedInUser.profile.contact }}</p>
                                                <p class="mb-0" v-else>{{ $t('not_added_yet') }}</p>
                                            </div>
                                        </div>
                                        <div class="media mb-4 mb-xl-0">
                                            <div class="align-self-center mr-3">
                                                <app-icon name="map-pin"/>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-muted mb-0">{{ $t('address') }}:</p>
                                                <p class="mb-0"
                                                   v-if="loggedInUser.profile && loggedInUser.profile.address">
                                                    {{ loggedInUser.profile.address }}</p>
                                                <p class="mb-0" v-else>{{ $t('not_added_yet') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                    <div class="media mb-4 mb-xl-5">
                                        <div class="align-self-center mr-3">
                                            <app-icon :name="'calendar'"/>
                                        </div>
                                        <div class="media-body">
                                            <p class="text-muted mb-0">{{ $t('created') }}:</p>
                                            <p class="mb-0" v-if="loggedInUser.created_at">
                                                {{ formatDateToLocal(loggedInUser.created_at) }}</p>
                                            <p class="mb-0" v-else>{{ $t('not_added_yet') }}</p>
                                        </div>
                                    </div>
                                    <div class="media mb-0 mb-xl-0">
                                        <div class="align-self-center mr-3">
                                            <app-icon name="gift"/>
                                        </div>
                                        <div class="media-body">
                                            <p class="text-muted mb-0">{{ $t('date_of_birth') }}:</p>
                                            <p class="mb-0"
                                               v-if="loggedInUser.profile && loggedInUser.profile.date_of_birth">
                                                {{ formatDateToLocal(loggedInUser.profile.date_of_birth) }}</p>
                                            <p class="mb-0" v-else>{{ $t('not_added_yet') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <app-tab :tabs="tabs" :icon="tabIcon"/>
    </div>
</template>
<script>

import {FormMixin} from "../../../core/mixins/form/FormMixin";
import {formatDateToLocal} from "../../Helper/Support/DateTimeHelper";

export default {
    name: "Profile",
    mixins: [FormMixin],
    data() {
        return {

            loggedInUser: {},
            userImage: '',
            profile_picture: '',
            tabIcon: 'user',
            tabs: [
                {
                    'name': this.$t('personal_info'),
                    'title': this.$t('personal_info'),
                    'component': 'app-profile-personal-info',
                    'permission': ''
                },
                {
                    'name': this.$fieldTitle('password', 'change', true),
                    'title': this.$fieldTitle('password', 'change', true),
                    'component': 'app-password-change',
                    'permission': ''
                },
                {
                    'name': this.$t('activity'),
                    'title': this.$t('activity'),
                    'component': 'app-activity',
                    'permission': ''
                },

            ],
            formatDateToLocal
        }
    },

    computed: {
        userInfo() {
            return this.$store.getters.getUserInformation
        },
        profile_picture_link() {
            return window.user.profile_picture ? window.user.profile_picture.full_url : '/images/avatar.png'
        }
    },
    mounted() {
        let instance = this;
        instance.$store.dispatch('getUserInformation');

        // SHOW UPLOADED IMAGE
        let input = document.getElementById('upload');

        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#imageResult').attr('src', e.target.result);
                };
                let image = reader.readAsDataURL(input.files[0]);

                let formData = new FormData;
                formData.append('profile_picture', input.files[0]);
                instance.axiosPost({
                    url: `admin/auth/users/profile-picture`,
                    data: formData
                }).then(response => {
                    location.reload();
                });

            }
        }

        $(function () {
            $('#upload').on('change', function () {
                readURL(input);
            });
        });

    },

    watch: {
        userInfo: {
            handler: function (user) {
                this.loggedInUser = user;
            },
            deep: true
        },
    },

    methods: {}
}
</script>

<style scoped>
.text-primary:hover {

}
</style>
