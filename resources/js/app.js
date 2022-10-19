
require('./bootstrap');

window.Vue = require('vue');
import VueGoodTablePlugin from 'vue-good-table';
import VueWait from 'vue-wait';

import VueNativeNotification from 'vue-native-notification'

Vue.use(VueNativeNotification, {
    requestOnNotify: true
})

//Remove the close menu button
$("#menu-toggle-close").hide();

if(navigator.userAgent == "iOSApp"){
    $("#fixed-header").hide();
    $("#sidebar-wrapper").hide();
    $('#app').css('margin-top', '0px');
}

// import the styles
Vue.use(VueGoodTablePlugin);
Vue.use(require('vue-moment'));

Vue.component('chat-messages', require('./components/ChatMessages.vue').default);
Vue.component('chat-form', require('./components/ChatForm.vue').default);
Vue.component('issues-table', require('./components/IssuesTable.vue').default);
Vue.component('properties-table', require('./components/PropertiesTable.vue').default);
Vue.component('property-wizard', require('./components/PropertyWizard.vue').default);
Vue.component('search', require('./components/Search.vue').default);
Vue.component('stream-menu', require('./components/StreamMenu.vue').default);
Vue.component('document-menu', require('./components/DocumentMenu.vue').default);
Vue.component('document-wizard', require('./components/DocumentWizard.vue').default);
Vue.component('document-upload', require('./components/DocumentUpload.vue').default);
Vue.component('document-table', require('./components/DocumentTable.vue').default);
Vue.component('document-folder', require('./components/DocumentFolder.vue').default);
Vue.component('document-count', require('./components/DocumentCount.vue').default);
Vue.component('direct-messages', require('./components/DirectMessages.vue').default);
Vue.component('dm-page', require('./components/DMPage.vue').default);
Vue.component('direct-chatform', require('./components/DirectMessageForm.vue').default);
Vue.component('colleague-menu', require('./components/ColleagueMenu.vue').default);
Vue.component('unread-messages', require('./components/UnreadMessages.vue').default);
Vue.component('landlords-dm', require('./components/LandlordsDM.vue').default);
Vue.component('tenants-dm', require('./components/TenantsDM.vue').default);
Vue.component('tenant-count', require('./components/TenantCount.vue').default);
Vue.component('user-activities', require('./components/UserActivities.vue').default);
Vue.component('message-all', require('./components/MessageAllProperties.vue').default);
Vue.component('message-property', require('./components/MessageAProperty.vue').default);
Vue.component('agent-invite', require('./components/AgentInvite.vue').default);
Vue.component('reminders', require('./components/Reminders.vue').default);
Vue.component('quick-reminder', require('./components/QuickReminder.vue').default);
Vue.component('reminder-wizard', require('./components/ReminderWizard.vue').default);
Vue.component('stream-wizard', require('./components/StreamWizard.vue').default);
Vue.component('advert', require('./components/Advert.vue').default);
Vue.component('select-search', require('./components/SelectSearch.vue').default);
Vue.use(VueWait); // add VueWait as Vue plugin

var vm = new Vue({
    el: '#app',
    wait: new VueWait({
        // Defaults values are following:
        useVuex: false, // Uses Vuex to manage wait state
        vuexModuleName: 'wait', // Vuex module name

        registerComponent: true, // Registers `v-wait` component
        componentName: 'v-wait', // <v-wait> component name, you can set `my-loader` etc.

        registerDirective: true, // Registers `v-wait` directive
        directiveName: 'wait', // <span v-wait /> directive name, you can set `my-loader` etc.

    }),
    data: {
        messages: [],
        liveMessages: [],
        streams: [],
        unread: [],
        unreadStreams: [],
        userId: '',
        streamId: '',
    },
    created() {
        var that = this;
        axios.get('/userid/').then(function (response){
            if (typeof response.data.agencyId != 'undefined'){
                Echo.private('stream.' + response.data.agencyId)
                    .listen('StreamUpdated', (e) => {
                        that.updateActivities(e);
                        that.fetchMessages();
                        that.setNotificationBell(e.user.sub);
                    }
                );
            }
            that.userId = response.data.sub;
            Echo.private('users.' + that.userId).
                notification((notification) => {
                    document.getElementById('notifications').classList.add('text-danger');
                    if (typeof that.$refs.directMessage !== 'undefined' ){
                        that.$refs.directMessage.receivedMessage(notification);
                    }
                    if (typeof that.$refs.activities !== 'undefined' ){
                        that.$refs.activities.receivedMessage(notification);
                        that.getUnreadMessages();
                    }
            });
        });
            
        axios.get('/notificationsData/').then(function (response) {
            response.data.map(item => {
                //Use Pusher Channels to get messages for all properties on the account
                Echo.private('stream.' + item.streamid)
                    .listen('StreamUpdated', (e) => {
                        that.updateActivities(e);
                        that.fetchMessages();
                        that.setNotificationBell(e.user.sub);
                    });
                if (typeof(item.private_stream_id) != "undefined"){
                    Echo.private('stream.' + item.private_stream_id)
                        .listen('StreamUpdated', (e) => {
                            that.updateActivities(e);
                            that.fetchMessages();
                            that.setNotificationBell(e.user.sub);
                        });
                }
            })
        });

    },
    async mounted() {
        var that = this;
        await axios.get('/streams/data').then(function (response) {
            that.streams = response.data.data;
        });

        if (this.userId == ''){
            axios.get('/userid/').then(function (response){
                that.userId = response.data;
            });
        }
        this.getUnreadMessages();
    },
    methods: {
        async fetchMessages() {
            //Gets all messages and repopulates stream
            if (this.streamId != '' ) {
                await axios.get('/messages/' + this.streamId).then(response => {
                    this.messages = response.data;
                });
                $('#chatContainer').scrollTop($('#chatContainer')[0].scrollHeight);
            };
        },
        async addMessage(message) {
            $("#btn-chat").html("Sending").prop('disabled', true);
            if (this.streamId != '') {
                await axios.post('/messages/' + this.streamId, {
                    message: message
                }).then(response => {
                    this.fetchMessages();
                });
            };
            $("#btn-chat").html("Send").prop('disabled', false);
            //this.fetchMessages();
        },
        async getUnreadMessages(){
            var that = this;
            this.$wait.start('getUnread');
            await axios.get('/unreadMessages').then(function (response) {
                that.unread = response.data;
            });
            await axios.get('/getLatestStreams').then(function (response) {
                that.unreadStreams = response.data;
            });
            this.$wait.end('getUnread');
        },
        async updateActivities(e){
            if (typeof this.$refs.activities !== 'undefined'){
                this.$refs.activities.streamUpdated(e);
            }
        },
        async setNotificationBell(fromUser){
            if (fromUser != this.userId) {
                document.getElementById('notifications').classList.add('text-danger');
            }
        },
    }
});

/*
 * Stop a form from submitting multiple times
 * add the class submit-once to the form, and the class disable-on-submit to the button
 */
(function () {
    var canSubmit = true;
    $('.submit-once').submit(function () {
        if (canSubmit){
            canSubmit = false;
            return true;
        }
        else{
            return false;
        }
        $(this).find('.disable-on-submit').enabled(false);
    })
})();
