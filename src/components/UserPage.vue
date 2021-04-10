<template>
    <div class="UserPage">
        <v-container v-if="addStatus==200">
            <v-app-bar
            app
            color="success"
            dark
            >
                <div class="d-flex align-center">
                    <v-img
                    alt="Vuetify Logo"
                    class="shrink mr-2"
                    contain
                    src="https://cdn.vuetifyjs.com/images/logos/vuetify-logo-dark.png"
                    transition="scale-transition"
                    width="40"
                    />
                    <div>
                        Bienvenido, {{ username }}
                    </div>
                </div>

                <v-spacer></v-spacer>

                <v-btn v-if="enableEdit==false"
                    depressed
                    color="primary"
                    
                    @click="add()"
                >Agregar</v-btn>
            </v-app-bar>
            <v-row class="white--text"> . </v-row>
            <v-data-table
                :headers="enableEdit ? adminHeaders : headers"
                :items="userData"
                :items-per-page="5"
                class="elevation-1"
                @click:row="handleClick"
            ></v-data-table>
            <!-- TODO: for admin SHOW username in table -->
            
            <div class="overlay">
                <v-overlay
                    :value="showPic"
                    opacity=0.8
                >
                    <v-card light>
                        <v-row class="white--text" justify="center"> . </v-row>
                        <v-row class="mx-6 mt-6"><a :href="'../tracker-be/'+selectedItem.imageLocation" target="_blank">Agrandar foto</a></v-row>
                        <v-row class="mx-6 mt-6"> 
                            <v-img :src="'../tracker-be/'+selectedItem.imageLocation" max-width="400" max-height="400"/>
                        </v-row>
                            
                        <v-row class="mx-6 mt-6">
                            <v-btn
                                depressed
                                width = "100%"
                                color="primary"
                                @click="showPic=false"
                            >Cerrar</v-btn>
                        </v-row>
                        <v-row class="white--text" justify="center"> . </v-row>
                    </v-card>
                </v-overlay>
            </div>

            <div class="overlay">
                <v-overlay
                    :value="showUpload"
                    opacity=0.8
                >
                    <v-card light> 
                        <v-row class="white--text" justify="center"> . </v-row>
                        <input type="file" accept="image/*" name="fileToUpload" @change="uploadImage($event, username)" id="file-input" class="uploadButton mt-6 ml-6"> 
                        <v-row class="mx-6 my-6">
                            <v-btn
                                depressed
                                width = "100%"
                                color="error"
                                @click="showUpload=false"
                            >Cancelar</v-btn>
                        </v-row>
                        <v-row class="white--text" justify="center"> . </v-row>
                    </v-card>
                </v-overlay>
            </div>

        </v-container>
        <div v-else>
            <Add :username="username"/>
        </div>
    </div>
</template>

<script>
import Add from './Add';
import apiInterface from "../apiInterface";
import { EventBus } from "../event-bus.js";

export default {
    trackingNumber: 'UserPage',
    props: {
        username: {
            type: String,
            default: ""
        }
    },
    components: {
		Add
	},

	data(){
		return {
            selectedItem: {},
            showPic: false,
            showUpload: false,
			addStatus: 200,
            enableEdit: false,
            newTrackingNumber: "",
            adminHeaders: [
                {
                    text: 'Usuario',
                    align: 'start',
                    value: 'username',
                },
                { text: 'Descripcion', value: 'description' },
                { text: 'Numero Tracking', value: 'trackingNumber' },
                { text: 'Fecha Ingresada', value: 'sentDate' },
                { text: 'Status', value: 'receivedDate' },
            ],
            headers: [
                {
                    text: 'Descripcion',
                    align: 'start',
                    value: 'description',
                },
                { text: 'Numero Tracking', value: 'trackingNumber' },
                { text: 'Fecha Ingresada', value: 'sentDate' },
                { text: 'Status', value: 'receivedDate' },
            ],
            userData: [],
		}
	},

    methods:{
        add(){
            this.addStatus = 0
        },
        handleClick(value) {
            this.selectedItem = value
            if(value.receivedDate == "No Recibido")
            {
                if(this.enableEdit == true)
                {
                    this.showUpload = true
                }
            }
            else
            {
                this.showPic = true
            }
        },
        uploadImage(image, username){
            apiInterface.uploadImage(this.selectedItem, image, username);
        }
    },

	created() 
	{
		console.log("UserPage created")
		EventBus.$on("addTrackingNumber", busPackage => {
			console.log("App handle addTrackingNumber")
			this.addStatus = busPackage.addStatus;
		});

        console.log("request user data");
        apiInterface.getUserData(this.username);

        EventBus.$on("getUserData", busPackage => {
			console.log("App handle getUserData")
            this.selectedItem = {}
            this.showPic = false
            this.showUpload = false
            this.addStatus = 200
            if(busPackage.status == 200)
            {
                this.userData = busPackage.data;
                this.enableEdit = false;
                console.log("set new data size:"+busPackage.data.length)
            }
            else if(busPackage.status == 500)
            {
                this.userData = busPackage.data;
                this.enableEdit = true;
                console.log("set new data size:"+busPackage.data.length)  
            }
            else
            {
                console.log("Error getting data: "+busPackage.status)
            }
		});
	}
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.tracking{
    max-width: 400px;
}

input[type=text] {
    padding:5px; 
    border:2px solid #ccc; 
    -webkit-border-radius: 5px;
    border-radius: 5px;
}

input[type=text]:focus {
    border-color:#333;
}

input[type=submit] {
    padding:5px 15px; 
    background:#4caf50 !important; 
    border:0 none;
    cursor:pointer;
    -webkit-border-radius: 5px;
    border-radius: 5px; 
}
</style>
