<template>
	<v-app>
		<v-main>
			<div v-if="loginState==false" >
				<Login/>
			</div>
			<div v-else>
				<UserPage :username="username"/>
			</div>
		</v-main>
	</v-app>
</template>

<script>
import Login from './components/Login';
import UserPage from './components/UserPage';
import { EventBus } from "./event-bus.js";

export default {
	name: 'App',

	components: {
		Login,
		UserPage
	},

	data(){
		return {
			loginState: false,
			username: ""
		}
	},

	created() 
	{
		console.log("App created")
		EventBus.$on("eventSetUsername", busPackage => {
			console.log("App handle setUsername")
			this.loginState = busPackage.loginState;
			this.username = busPackage.username;
		});
	}
};
</script>
