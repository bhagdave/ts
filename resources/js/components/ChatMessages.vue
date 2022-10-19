<template>
    <div>
        <div v-if="!messages.length">
            <div class="text-center">
                 <h5 class="font-weight-light pt-2">Loading Stream History</h5>
            </div>
        </div>
        <div class="chat">

            <div class="row pt-3 media-body pb-2 mb-0" v-if="messages.length > 100">
                <div class="col-12">
                    <small class="text-center">This stream is longer than 100 messages. We display the last 100 here.</small>
                </div>
            </div>
        
            <div v-for="message in messages">
                <div class="pt-3 w-100 media-body pb-2 mb-0 " v-if="message.properties.messageType ==='Event'">
                    <p class="text-center text-muted">
                        {{message.description}}
                    </p> 
                    <div v-if="new Date(message.created_at) < latest ">
                        <span class="text-success oi oi-check"></span>
                    </div>
                </div>

                <div class="row pt-3 w-100 media-body pb-2 mb-0" v-else-if="message.properties.messageType==='ImageUpload'">
                     <div v-if="message.properties.profileImage" class="col-auto pr-0">
                         <img :src="message.properties.profileImage" class="rounded-circle border-0" width="40" height="40">
                     </div>
                     <div v-else class="col-auto p-0">
                         <img src="/images/default.png" class="rounded-circle border-0" width="40" height="40">
                     </div>
                     <div class="col">
                         <strong>{{ message.properties.firstName }} {{message.properties.lastName }}</strong> 
                         <span class="text-muted pl-1"> {{message.updated_at | moment("MMMM Do YYYY, h:mm ") }} </span>
                         <span class="badge badge-pill badge-light">{{message.properties.userType}}</span>
                         <p>
                             <div><img :src="message.properties.filePath" class="w-50 img-thumbnail rounded"></div>
                         </p>
                     </div>   
                     <div v-if="new Date(message.created_at) < latest ">
                         <span class="text-success oi oi-check"></span>
                     </div>
                </div>

                <div class="row pt-3 w-100 media-body pb-2 mb-0" v-else-if="message.properties.messageType==='AudioUpload'">
                     <div v-if="message.properties.profileImage" class="col-auto pr-0">
                         <img :src="message.properties.profileImage" class="rounded-circle border-0" width="40" height="40">
                     </div>
                     <div v-else class="col-auto p-0">
                         <img src="/images/default.png" class="rounded-circle border-0" width="40" height="40">
                     </div>
                     <div class="col">
                         <strong>{{ message.properties.firstName }} {{message.properties.lastName }}</strong> 
                         <span class="text-muted pl-1"> {{message.updated_at | moment("MMMM Do YYYY, h:mm ") }} </span>
                         <span class="badge badge-pill badge-light">{{message.properties.userType}}</span>
                         <p>
                             <div><audio controls :src="message.properties.filePath" class=""></audio></div>
                         </p>
                     </div>   
                     <div v-if="new Date(message.created_at) < latest ">
                         <span class="text-success oi oi-check"></span>
                     </div>
                </div>

                <div class="row pt-3 w-100 media-body pb-2 mb-0" v-else-if="message.properties.messageType==='VideoUpload'">
                     <div v-if="message.properties.profileImage" class="col-auto pr-0">
                         <img :src="message.properties.profileImage" class="rounded-circle border-0" width="40" height="40">
                     </div>
                     <div v-else class="col-auto p-0">
                         <img src="/images/default.png" class="rounded-circle border-0" width="40" height="40">
                     </div>
                     <div class="col">
                         <strong>{{ message.properties.firstName }} {{message.properties.lastName }}</strong> 
                         <span class="text-muted pl-1"> {{message.updated_at | moment("MMMM Do YYYY, h:mm ") }} </span>
                         <span class="badge badge-pill badge-light">{{message.properties.userType}}</span>
                         <p>
                             <div>
                                 <video controls width="250":src="message.properties.filePath" class="w-50 img-thumbnail rounded">
                                     <source :src="message.properties.filePath">
                                 </video>
                             </div>
                         </p>
                     </div>   
                     <div v-if="new Date(message.created_at) < latest ">
                         <span class="text-success oi oi-check"></span>
                     </div>
                </div>

                <div class="row pt-3 w-100 media-body pb-2 mb-0" v-else-if="message.properties.messageType==='TextUpload'">
                     <div v-if="message.properties.profileImage" class="col-auto pr-0">
                         <img :src="message.properties.profileImage" class="rounded-circle border-0" width="40" height="40">
                     </div>
                     <div v-else class="col-auto p-0">
                         <img src="/images/default.png" class="rounded-circle border-0" width="40" height="40">
                     </div>
                     <div class="col">
                         <strong>{{ message.properties.firstName }} {{message.properties.lastName }}</strong> 
                         <span class="text-muted pl-1"> {{message.updated_at | moment("MMMM Do YYYY, h:mm ") }} </span>
                         <span class="badge badge-pill badge-light">{{message.properties.userType}}</span>
                         <p>
                             <div>
                                 <a :href="message.properties.filePath" target="_blank" class="w-50 img-thumbnail rounded">
                                     {{ message.properties.fileName }}
                                 </a>
                             </div>
                         </p>
                     </div>   
                     <div v-if="new Date(message.created_at) < latest ">
                         <span class="text-success oi oi-check"></span>
                     </div>
                </div>
     
                <div class="row pt-3 w-100 media-body pb-2" v-else-if="message.properties.messageType === 'PropertyIssue'">
                     <p class="text-center text-muted w-100">
                      An issue at this property was updated by {{ message.properties.firstName }} {{message.properties.lastName }} - <a :href="message.properties.issueLink">See Details</a>
                     </p> 
                     <div v-if="new Date(message.created_at) < latest ">
                         <span class="text-success oi oi-check"></span>
                     </div>
                </div>
      
                <div class="row pt-3 w-100 media-body pb-2" v-else-if="message.properties.messageType === 'NewPropertyIssue'">
                     <p class="text-center text-muted w-100">
                      A new issue at this property was posted by {{ message.properties.firstName }} {{message.properties.lastName }} - <a :href="message.properties.issueLink">See Details</a>
                     </p> 
                     <div v-if="new Date(message.created_at) < latest ">
                         <span class="text-success oi oi-check"></span>
                     </div>
                </div>
      
                <div class="pt-3 w-100 media-body pb-2 mb-0 " v-else-if="message.properties.messageType === 'PropertyIssue' && message.properties.issueStatus === 'Closed' ">
                         <p class="text-center text-muted w-100">
                      An issue at this property was closed by {{ message.properties.firstName }} {{message.properties.lastName }} - <a :href="message.properties.issueLink">See Details</a>
                     </p> 
                     <div v-if="new Date(message.created_at) < latest ">
                         <span class="text-success oi oi-check"></span>
                     </div>
                </div>
                          
                <div class="row w-100 pt-3 media-body pb-2 mb-0 pl-3" v-else>    
                    <div v-if="message.properties.profileImage" class="col-auto p-0">
                        <img :src="message.properties.profileImage" class="rounded-circle border-0" width="40" height="40">
                    </div>
                    <div v-else class="col-auto p-0">
                        <img src="/images/default.png" class="rounded-circle border-0" width="40" height="40">
                    </div>
                    <div class="col">
                        <strong>{{ message.properties.firstName }} {{message.properties.lastName }}</strong> 
                        <span class="text-muted pl-1"> {{message.updated_at | moment("MMMM Do YYYY, h:mm ")}} </span>
                        <span class="badge badge-pill badge-light">{{message.properties.userType}}</span>
                        <p>
                            {{message.description}}
                        </p>
                    </div>
      
                    <div v-if="new Date(message.created_at) < latest ">
                        <span class="text-success oi oi-check"></span>
                    </div>
                </div>    
            </div>
        </div>  
    </div>
</template>

<script>
  export default {
      props: ['messages','waiting', 'stream_id'],
      data() {
          return {
              latest: ''
          }
      },
      async mounted() {
          this.$root.streamId = this.stream_id;
          this.$root.fetchMessages();
          this.$wait.start('getLatestVisit');
          await axios.get('/stream/getLatestVisit/' + this.stream_id).then(response => {
              if (response.data !== 'Err') {
                  this.latest = new Date(response.data) ;
              }
          });
          this.$wait.end('getLatestVisit');
          $('#chatContainer').scrollTop($('#chatContainer')[0].scrollHeight);
      },
  };
</script>
