
<template>
	<div class="Add">
		<v-row
			align="center"
			justify="space-around"
		>
			<img alt="Vue logo" src="../assets/add.png">
		</v-row>
		<v-row justify="center">
			<v-col
				cols="12"
				sm="6"
				md="6"
				lg="6"
			>
				<v-card>
                    <v-row class="mx-6"> 
                        <v-text-field class="mx-6 tracking"
                            placeholder="Descripcion"
                            v-model="description"
                        ></v-text-field>
					</v-row>
					<v-row class="mx-6"> 
                        <v-text-field class="mx-6 tracking"
                            placeholder="Numero Tracking"
                            v-model="newTrackingNumber"
                        ></v-text-field>
					</v-row>
						
					<v-row class="mx-6 my-6">
						<v-btn
							depressed
							width = "100%"
							color="success"
							@click="add()"
						>Agregar Numero Tracking</v-btn>
					</v-row>
                    <v-row class="mx-6 my-6">
						<v-btn
							depressed
							width = "100%"
							color="error"
							@click="cancel()"
						>Cancelar</v-btn>
					</v-row>
					<v-row class="white--text"> . </v-row>
				</v-card>
			</v-col>
		</v-row>
	</div>
</template>

<script>
import apiInterface from '../apiInterface.js';
import { EventBus } from "../event-bus.js";

export default {
	name: 'Add',
	props: {
        username: {
            type: String,
            default: ""
        }
	},
	data(){
		return {
			description: "",
            newTrackingNumber: ""
		}
	},

	methods: {
		add: function () {
            apiInterface.addTrackingNumber(this.description, this.newTrackingNumber, this.username)
		},
        cancel: function () {
            EventBus.$emit("addTrackingNumber", {addStatus:200})
        }
	}

}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>


