import Vue from 'vue'
import {axiosGet} from "../../../common/Helper/AxiosHelper";
import {NOTIFICATIONS} from '../../../common/Config/apiUrl'
import {calenderTime, onlyTime} from '../../../common/Helper/Support/DateTimeHelper'
import optional from '../../../common/Helper/Support/Optional'

const state = {
    notifications: {}
};

const actions = {
    getNotifications({commit, state}, nextPage = NOTIFICATIONS) {
        axiosGet(`${nextPage}?per_page=10&unread=1`).then(({data}) => {
            commit('SET_NOTIFICATIONS', data);
        });
    }

};

const mutations = {
    SET_NOTIFICATIONS(state, data){
        if (!Object.keys(state.notifications).length){
            state.notifications = data;
        }else {
            state.notifications.next_page_url = data.next_page_url;
            state.notifications.data = state.notifications.data.concat(data.data);
        }
    }
};

const getters = {
    getFormattedNotifications: state => {
        let notifications = {
            ...state.notifications
        };
        if (state.notifications.data) {
            notifications.data = state.notifications.data.map(notification => {
                return {
                    title: notification.data.message,
                    url: notification.data.url,
                    name: optional(notification, 'notifier', 'full_name'),
                    img: optional(notification, 'notifier', 'profile_picture', 'full_url'),
                    date: calenderTime(notification.created_at, true),
                    time: onlyTime(notification.created_at),
                    id: notification.id,
                    status: notification.read_at == null ? Vue.prototype.$t('new') : Vue.prototype.$t('old'),
                    description: ''
                }
            });
            notifications.total_unread = state.notifications.data.filter(notification => {
                return notification.read_at == null;
            }).length;

            return notifications;
        }
        return {
            data: [],
            total_unread: 0
        };
    }

};


export default {
    state,
    getters,
    actions,
    mutations
}
