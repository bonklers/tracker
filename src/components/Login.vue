<template>
	<div class="Login">
		<v-row
			align="center"
			justify="space-around"
		>
			<img alt="Vue logo" src="../assets/package.png">
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
						<v-text-field
							placeholder="Correo Electronico"
							v-model="email"
						></v-text-field>
					</v-row>
						
					<v-row class="mx-6 mt-6">
						<v-btn
							depressed
							width = "100%"
							color="primary"
							@click="login()"
						>Iniciar Sesión</v-btn>
					</v-row>
					<v-row class="red--text" v-if="showError" justify="center">Error de nombre de usuario.</v-row>
					<v-row class="white--text"> . </v-row>
				</v-card>
			</v-col>
		</v-row>
	</div>
</template>

<script>
import { EventBus } from "../event-bus.js";

export default {
	name: 'Login',
	props: {

	},
	data(){
		return {
			email: "",
			showError: false
		}
	},

	methods: {
		login () {
			const username = this.email.toLowerCase().trim()

			if (this.checkEmail(username) == false)
			{
				this.showError = true
			}
			else
			{
				console.log("pressed login send email:"+this.email+" and state=true")
			
				EventBus.$emit("eventSetUsername", { username:username , loginState:true})
			}
		},
		checkEmail(username){
			// TODO: error check username with other rules?
			
			if (username == "")
			{
				return false
			}
			return true
		}
	}

}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
